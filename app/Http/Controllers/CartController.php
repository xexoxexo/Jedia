<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ElectricTransactionDetail;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use RuntimeException;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $variant_id = $request->variant_id;
        $product_id = $request->product_id;
        $quantity = $request->product_quantity;

        $validated = $request->validate([
            'variant_id' => 'required',
            'product_id' => 'required',
            'product_quantity' => 'required',
        ]);

        $cart = Cart::where('user_id', $user_id)->where('variant_id', $variant_id)->where('product_id', $product_id)->first();

        if ($cart == null) {
            Cart::create([
                'user_id' => $user_id,
                'variant_id' => $variant_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        } else {
            Cart::where('user_id', $user_id)->where('variant_id', $variant_id)->where('product_id', $product_id)->update(['quantity' => $quantity]);
        }

        return redirect()->route('cart.index');
    }

    public function index(Request $request)
    {
        $products = Product::paginate(6);
        $recommendations = Product::all()->random(5);

        if ($request->ajax()) {
            $view = view('product.load', compact('products'))->render();
            return Response::json(['view' => $view, 'nextPageUrl' => $products->nextPageUrl()]);
        }

        return view('cart.index', [
            'recommendations' => $recommendations,
            'products' => $products,
        ]);
    }

    public function checkout_index()
    {
        if (Auth::user()->carts->count() == 0) {
            return redirect()->route('cart.index');
        } else if (Auth::user()->locations->count() == 0) {
            return redirect()->route('locations.index');
        }

        $recommendations = Product::all()->random(5);
        $shipments = Shipment::all();

        return view('cart.checkout', [
            'recommendations' => $recommendations,
            'shipments' => $shipments,
        ]);
    }

    public function checkout_store(Request $request, MidtransService $midtrans)
    {
        $validated = $request->validate([
            'user-location-id' => 'required|exists:locations,id',
            'transaction_details' => 'required|string',
            'payment_channel' => 'nullable|string|in:all,bank_transfer,credit_card,gopay_qris,store',
        ]);

        $locationId = $request->input('user-location-id');
        $paymentChannel = (string) $request->input('payment_channel', 'all');
        $enabledPayments = $this->resolveEnabledPayments($paymentChannel);
        $rawTransactionDetails = json_decode($request->transaction_details, true);

        if (! is_array($rawTransactionDetails)) {
            return redirect()->back()->withErrors([
                'transaction_details' => 'Invalid transaction payload.',
            ]);
        }

        $user = Auth::user();

        if (! $user->locations()->where('id', $locationId)->exists()) {
            return redirect()->back()->withErrors([
                'user-location-id' => 'Selected location is invalid.',
            ]);
        }

        $carts = $user->carts()->with(['product.merchant.location', 'variant'])->get();

        if ($carts->count() === 0) {
            return redirect()->route('cart.index');
        }

        $transactionRows = [];
        $itemDetails = [];
        $grossAmount = 0;
        $validShipmentIds = Shipment::pluck('id')->all();

        foreach ($carts as $cart) {
            $productId = (string) $cart->product_id;
            $variantId = (string) $cart->variant_id;

            if (
                ! isset($rawTransactionDetails[$productId]) ||
                ! isset($rawTransactionDetails[$productId][$variantId]) ||
                ! isset($rawTransactionDetails[$productId][$variantId]['shipment_id']) ||
                ! isset($rawTransactionDetails[$productId][$variantId]['total_paid'])
            ) {
                return redirect()->back()->withErrors([
                    'transaction_details' => 'Shipment data for one or more products is missing.',
                ]);
            }

            $shipmentId = (string) $rawTransactionDetails[$productId][$variantId]['shipment_id'];
            $totalPaid = (int) round((float) $rawTransactionDetails[$productId][$variantId]['total_paid']);

            if (! in_array($shipmentId, $validShipmentIds, true)) {
                return redirect()->back()->withErrors([
                    'transaction_details' => 'Invalid shipment option selected.',
                ]);
            }

            if ($totalPaid <= 0) {
                return redirect()->back()->withErrors([
                    'transaction_details' => 'Invalid total payment value detected.',
                ]);
            }

            $discount = $cart->product->lowestDiscount();
            $promo_name = null;

            if ($discount != 0) {
                if ($cart->product->flash_sale != null) {
                    $promo_name = 'Flash Sale';
                } else {
                    $promo_name = $cart->product->lowestPromo()->promo->promo_name;
                }
            }

            $transactionRows[] = [
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'quantity'=> $cart->quantity,
                'price' => $cart->variant->price,
                'shipment_id' => $shipmentId,
                'status' => 'Awaiting Payment',
                'promo_name' => $promo_name,
                'discount' => $discount,
                'total_paid' => $totalPaid,
            ];

            $itemName = $cart->product->name.' - '.$cart->variant->name;
            if (strlen($itemName) > 50) {
                $itemName = substr($itemName, 0, 50);
            }

            $itemDetails[] = [
                'id' => substr('PR-'.$cart->product_id.'-VA-'.$cart->variant_id, 0, 50),
                'price' => $totalPaid,
                'quantity' => 1,
                'name' => $itemName,
            ];

            $grossAmount += $totalPaid;
        }

        if ($grossAmount <= 0) {
            return redirect()->back()->withErrors([
                'transaction_details' => 'Unable to calculate payment amount.',
            ]);
        }

        $transactionHeader = DB::transaction(function () use ($user, $locationId, $grossAmount, $transactionRows) {
            $header = TransactionHeader::create([
                'user_id' => $user->id,
                'location_id' => $locationId,
                'date' => now(),
                'payment_gateway' => 'midtrans',
                'payment_status' => 'pending',
                'payment_gross_amount' => $grossAmount,
            ]);

            $header->update([
                'payment_order_id' => $header->id,
            ]);

            foreach ($transactionRows as $row) {
                TransactionDetail::create([
                    'transaction_id' => $header->id,
                    'product_id' => $row['product_id'],
                    'variant_id' => $row['variant_id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'shipment_id' => $row['shipment_id'],
                    'status' => $row['status'],
                    'promo_name' => $row['promo_name'],
                    'discount' => $row['discount'],
                    'total_paid' => $row['total_paid'],
                ]);
            }

            return $header;
        });

        try {
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $transactionHeader->payment_order_id,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->username ?? 'Customer',
                    'email' => $user->email ?? null,
                    'phone' => $user->phone ?? null,
                ],
                'callbacks' => [
                    'finish' => route('payments.midtrans.finish'),
                ],
            ];

            if (count($enabledPayments) > 0) {
                $snapPayload['enabled_payments'] = $enabledPayments;
            }

            if ($paymentChannel === 'credit_card') {
                $snapPayload['credit_card'] = [
                    'secure' => true,
                ];
            }

            $snapResponse = $midtrans->createSnapTransaction($snapPayload);
            $snapToken = $snapResponse['token'] ?? null;
            $redirectUrl = $snapResponse['redirect_url'] ?? null;

            if (! $redirectUrl) {
                throw new RuntimeException('Midtrans did not return redirect_url.');
            }

            $transactionHeader->update([
                'payment_redirect_url' => $redirectUrl,
            ]);

            if (! is_string($snapToken) || $snapToken === '') {
                return redirect()->away($redirectUrl);
            }

            $transactionHeader->load([
                'details.product.images',
                'details.product.merchant',
                'details.variant',
            ]);

            return view('payment.checkout', [
                'transaction' => $transactionHeader,
                'snapToken' => $snapToken,
                'clientKey' => (string) config('services.midtrans.client_key'),
                'snapScriptUrl' => $midtrans->snapJsUrl(),
                'autoOpenSnap' => true,
            ]);
        } catch (RuntimeException $exception) {
            Log::error('Failed to start Midtrans payment.', [
                'transaction_id' => $transactionHeader->id,
                'error' => $exception->getMessage(),
            ]);

            $friendlyMessage = 'Transaction created, but payment gateway is unavailable. Please try again from your history page.';

            if (str_contains($exception->getMessage(), 'Access denied due to unauthorized transaction')) {
                $friendlyMessage = 'Midtrans configuration is invalid (server/client key or environment mismatch). Please contact admin.';
            }

            return redirect()->route('history-transaction.index')->withErrors([
                'payment' => $friendlyMessage,
            ]);
        }
    }

    public function bill_store(Request $request)
    {
        $validated = $request->validate([
            'subscription_number' => ['required', 'numeric', 'digits_between:11,12'],
            'nominal' => ['required', 'numeric', 'min:10000', 'max:50000'],
        ]);

        $th = TransactionHeader::create([
            'user_id' => Auth::user()->id,
            'location_id' => null,
            'date' => now(),
        ]);

        ElectricTransactionDetail::create([
            'transaction_id' => $th->id,
            'electric_token' => Str::uuid(),
            'subscription_number' => $request->subscription_number,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('history-transaction.index');
    }

    private function resolveEnabledPayments(string $paymentChannel): array
    {
        return match ($paymentChannel) {
            'bank_transfer' => ['bank_transfer'],
            'credit_card' => ['credit_card'],
            'gopay_qris' => ['gopay', 'qris'],
            'store' => ['store'],
            default => [],
        };
    }
}

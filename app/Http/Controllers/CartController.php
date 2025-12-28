<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ElectricTransactionDetail;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

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

    public function checkout_store(Request $request)
    {
        $validated = $request->validate([
            'user-location-id' => 'required',
            'transaction_details' => 'required',
        ]);

        $location_id = $request->input('user-location-id');
        $date = now();

        $th = TransactionHeader::create([
            'user_id' => Auth::user()->id,
            'location_id' => $location_id,
            'date' => $date,
        ]);

        $td = json_decode($request->transaction_details, true);

        foreach (Auth::user()->carts as $cart) {
            $discount = $cart->product->lowestDiscount();
            $promo_name = null;

            if ($discount != 0) {
                if ($cart->product->flash_sale != null) {
                    $promo_name = 'Flash Sale';
                } else {
                    $promo_name = $cart->product->lowestPromo()->promo->promo_name;
                }
            }

            TransactionDetail::create([
                'transaction_id' => $th->id,
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'quantity'=> $cart->quantity,
                'price' => $cart->variant->price,
                'shipment_id' => $td[$cart->product_id][$cart->variant_id]['shipment_id'],
                'status' => 'Pending',
                'promo_name' => $promo_name,
                'discount' => $discount,
                'total_paid' => $td[$cart->product_id][$cart->variant_id]['total_paid'],
            ]);
        }

        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect()->route('cart.index');
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
}

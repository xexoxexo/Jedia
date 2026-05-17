<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentController extends Controller
{
    public function finish(Request $request, MidtransService $midtrans)
    {
        $orderId = (string) $request->query('order_id', '');

        if ($orderId !== '') {
            try {
                $statusPayload = $midtrans->getTransactionStatus($orderId);
                $this->syncPaymentToDatabase($statusPayload);
            } catch (RuntimeException $exception) {
                Log::warning('Unable to sync Midtrans status from finish callback.', [
                    'order_id' => $orderId,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if (Auth::check()) {
            return redirect()->route('history-transaction.index');
        }

        return redirect()->route('home.index');
    }

    public function notification(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (! $midtrans->verifySignature($payload)) {
            Log::warning('Invalid Midtrans notification signature.', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = (string) ($payload['order_id'] ?? '');

        if ($orderId === '') {
            return response()->json(['message' => 'Missing order_id'], 422);
        }

        try {
            // Always reconcile with Midtrans API for latest status.
            $statusPayload = $midtrans->getTransactionStatus($orderId);
            $this->syncPaymentToDatabase($statusPayload);
        } catch (RuntimeException $exception) {
            Log::error('Failed to reconcile Midtrans notification.', [
                'order_id' => $orderId,
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['message' => 'Unable to process notification'], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    private function syncPaymentToDatabase(array $statusPayload): void
    {
        $orderId = (string) ($statusPayload['order_id'] ?? '');

        if ($orderId === '') {
            return;
        }

        $transactionHeader = TransactionHeader::where('payment_order_id', $orderId)
            ->orWhere('id', $orderId)
            ->first();

        if (! $transactionHeader) {
            return;
        }

        $paymentStatus = strtolower((string) ($statusPayload['transaction_status'] ?? 'pending'));
        $isPaid = in_array($paymentStatus, ['settlement', 'capture'], true);
        $isFailed = in_array($paymentStatus, ['deny', 'cancel', 'expire', 'failure'], true);
        $grossAmount = (int) round((float) ($statusPayload['gross_amount'] ?? 0));

        DB::transaction(function () use ($transactionHeader, $statusPayload, $paymentStatus, $isPaid, $isFailed, $grossAmount): void {
            $transactionHeader->update([
                'payment_status' => $paymentStatus,
                'payment_type' => $statusPayload['payment_type'] ?? $transactionHeader->payment_type,
                'payment_method' => $this->resolvePaymentMethod($statusPayload),
                'payment_gross_amount' => $grossAmount > 0 ? $grossAmount : $transactionHeader->payment_gross_amount,
                'paid_at' => $isPaid ? now() : null,
            ]);

            if ($isPaid) {
                TransactionDetail::where('transaction_id', $transactionHeader->id)
                    ->where('status', 'Awaiting Payment')
                    ->update(['status' => 'Pending']);

                $details = TransactionDetail::where('transaction_id', $transactionHeader->id)
                    ->get(['product_id', 'variant_id']);

                foreach ($details as $detail) {
                    Cart::where('user_id', $transactionHeader->user_id)
                        ->where('product_id', $detail->product_id)
                        ->where('variant_id', $detail->variant_id)
                        ->delete();
                }
            } elseif ($isFailed) {
                TransactionDetail::where('transaction_id', $transactionHeader->id)
                    ->whereIn('status', ['Awaiting Payment', 'Pending'])
                    ->update(['status' => 'Rejected']);
            }
        });
    }

    private function resolvePaymentMethod(array $statusPayload): ?string
    {
        if (isset($statusPayload['va_numbers'][0]['bank'])) {
            return strtoupper((string) $statusPayload['va_numbers'][0]['bank']).' VA';
        }

        if (isset($statusPayload['permata_va_number'])) {
            return 'Permata VA';
        }

        if (isset($statusPayload['bill_key'])) {
            return 'Mandiri Bill';
        }

        if (isset($statusPayload['store'])) {
            return ucfirst((string) $statusPayload['store']);
        }

        if (isset($statusPayload['payment_type'])) {
            return ucwords(str_replace('_', ' ', (string) $statusPayload['payment_type']));
        }

        return null;
    }
}

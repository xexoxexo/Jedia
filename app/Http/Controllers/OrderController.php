<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function update($th_id, $pr_id, $va_id, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Rejected,Shipping,Completed'],
        ]);

        $detail = TransactionDetail::where('transaction_id', $th_id)
            ->where('product_id', $pr_id)
            ->where('variant_id', $va_id)
            ->firstOrFail();

        if ($detail->header->payment_gateway === 'midtrans' && ! $detail->header->isPaymentSuccessful()) {
            return redirect()->back()->withErrors([
                'status' => 'Order cannot be processed because payment is not completed yet.',
            ]);
        }

        $detail->update(['status' => $request->status]);

        return redirect()->back();
    }
}

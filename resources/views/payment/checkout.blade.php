@extends('layouts.dashboard')

@section('title', 'Pembayaran - tokoNJedia')

@push('head-scripts')
<script src="{{ $snapScriptUrl }}" data-client-key="{{ $clientKey }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('content')
<div class="payment-container">
    {{-- Progress Steps --}}
    <div class="progress-steps animate-in">
        <div class="step">
            <div class="step-number done">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
            </div>
            <span class="step-label active">Keranjang</span>
        </div>
        <div class="step-line done"></div>
        <div class="step">
            <div class="step-number done">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
            </div>
            <span class="step-label active">Checkout</span>
        </div>
        <div class="step-line done"></div>
        <div class="step">
            <div class="step-number active">3</div>
            <span class="step-label active">Pembayaran</span>
        </div>
    </div>

    {{-- Payment Card --}}
    <div class="payment-card animate-in animate-delay-1">
        <div class="payment-card-header">
            <h2>Ringkasan Pembayaran</h2>
            <p>Order #{{ substr($transaction->id, 0, 8) }}...{{ substr($transaction->id, -4) }}</p>
        </div>

        <div class="payment-card-body">
            <p style="font-weight:700;font-size:0.9rem;color:#212121;margin-bottom:0.5rem">Detail Pesanan</p>

            @foreach($transaction->details as $detail)
            <div class="order-item">
                @if($detail->product && $detail->product->images && $detail->product->images->count() > 0)
                <img src="{{ $detail->product->images[0]->image }}" alt="{{ $detail->product->name ?? 'Produk' }}" class="order-item-img">
                @else
                <div class="order-item-img" style="background:#f0f2f5;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#8492a6" style="width:24px;height:24px"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                </div>
                @endif
                <div class="order-item-info">
                    <div class="order-item-name">{{ $detail->product->name ?? 'Produk' }}</div>
                    @if($detail->variant)
                    <div class="order-item-variant">{{ $detail->variant->name }} &times; {{ $detail->quantity }}</div>
                    @endif
                    @if($detail->product && $detail->product->merchant)
                    <div class="merchant-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:12px;height:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" /></svg>
                        {{ $detail->product->merchant->name }}
                    </div>
                    @endif
                </div>
                <div class="order-item-price">Rp {{ number_format($detail->total_paid, 0, ',', '.') }}</div>
            </div>
            @endforeach

            {{-- Summary --}}
            <div class="summary-section">
                @php
                    $subtotal = $transaction->details->sum(function ($d) { return $d->price * $d->quantity; });
                    $shipping = $transaction->payment_gross_amount - $subtotal;
                    if ($shipping < 0) $shipping = 0;
                    $totalDiscount = $transaction->details->sum('discount');
                @endphp
                <div class="summary-row">
                    <span>Subtotal ({{ $transaction->details->count() }} produk)</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if($shipping > 0)
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($totalDiscount > 0)
                <div class="summary-row" style="color:#00aa5b">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total Pembayaran</span>
                    <span class="amount">Rp {{ number_format($transaction->payment_gross_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Pay Button --}}
        <div class="pay-btn-wrapper animate-in animate-delay-2">
            <button type="button" id="pay-button" class="pay-btn" onclick="bayarSekarang()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                <span id="pay-btn-text">Bayar Online &mdash; Rp {{ number_format($transaction->payment_gross_amount, 0, ',', '.') }}</span>
                <div id="pay-btn-spinner" class="btn-spinner" style="display:none"></div>
            </button>
        </div>

        <p class="terms-text" style="padding-bottom:1.5rem">
            Dengan melakukan pembayaran, saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> tokoNJedia.
        </p>

        <div style="padding:0 2.5rem 2rem">
            <div class="secure-badge">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;color:#00aa5b"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                Pembayaran diproses secara aman oleh Midtrans &mdash; PCI-DSS Certified
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var isProcessing = false;
var shouldAutoOpenSnap = @json($autoOpenSnap ?? false);
var fallbackPaymentUrl = @json($transaction->payment_redirect_url);
var payButtonDefaultText = 'Bayar Online - Rp {{ number_format($transaction->payment_gross_amount, 0, ",", ".") }}';
function bayarSekarang() {
    if (isProcessing) return;

    if (typeof window.snap === 'undefined') {
        if (fallbackPaymentUrl) {
            window.location.href = fallbackPaymentUrl;
            return;
        }

        alert('Payment gateway belum siap. Silakan muat ulang halaman dan coba lagi.');
        return;
    }

    isProcessing = true;
    var btnText = document.getElementById('pay-btn-text');
    var btnSpinner = document.getElementById('pay-btn-spinner');
    var payBtn = document.getElementById('pay-button');
    btnText.textContent = 'Memproses...';
    btnSpinner.style.display = 'inline-block';
    payBtn.disabled = true;

    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = '{{ route("payments.midtrans.finish") }}?order_id={{ $transaction->payment_order_id }}&status=success';
        },
        onPending: function(result) {
            window.location.href = '{{ route("payments.midtrans.finish") }}?order_id={{ $transaction->payment_order_id }}&status=pending';
        },
        onError: function(result) {
            window.location.href = '{{ route("payments.midtrans.finish") }}?order_id={{ $transaction->payment_order_id }}&status=error';
        },
        onClose: function() {
            isProcessing = false;
            btnText.textContent = payButtonDefaultText;
            btnSpinner.style.display = 'none';
            payBtn.disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if (shouldAutoOpenSnap) {
        bayarSekarang();
    }
});
</script>
@endpush

@extends('layouts.dashboard')

@section('title', 'Hasil Pembayaran - tokoNJedia')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('content')
<div class="payment-container" style="padding-top:2rem;padding-bottom:2rem">
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
            <div class="step-number done">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
            </div>
            <span class="step-label active">Selesai</span>
        </div>
    </div>

    {{-- Result Card --}}
    <div class="result-card animate-in animate-delay-1">
        @if($status === 'success')
            <div class="result-icon-wrapper success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="result-icon success"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
            </div>
            <h2 class="result-title">Pembayaran Berhasil! 🎉</h2>
            <p class="result-subtitle">Terima kasih! Pembayaran Anda telah diterima dan pesanan sedang diproses oleh penjual.</p>
        @elseif($status === 'pending')
            <div class="result-icon-wrapper pending">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="result-icon pending"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <h2 class="result-title">Menunggu Pembayaran</h2>
            <p class="result-subtitle">Silakan selesaikan pembayaran Anda sesuai instruksi yang diberikan. Status akan diperbarui otomatis.</p>
        @else
            <div class="result-icon-wrapper error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="result-icon error"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
            </div>
            <h2 class="result-title">Pembayaran Gagal</h2>
            <p class="result-subtitle">Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.</p>
        @endif

        @if($transaction)
        <div style="text-align:left;margin-top:1rem;margin-bottom:0.5rem">
            <div class="result-detail-row">
                <span class="result-detail-label">Order ID</span>
                <span class="result-detail-value" style="font-size:0.8rem">{{ substr($transaction->id, 0, 8) }}...{{ substr($transaction->id, -4) }}</span>
            </div>
            <div class="result-detail-row">
                <span class="result-detail-label">Total</span>
                <span class="result-detail-value" style="color:#00aa5b">Rp {{ number_format($transaction->payment_gross_amount, 0, ',', '.') }}</span>
            </div>
            @if($transaction->payment_type)
            <div class="result-detail-row">
                <span class="result-detail-label">Metode</span>
                <span class="result-detail-value">{{ $transaction->payment_method ?? ucwords(str_replace('_', ' ', $transaction->payment_type)) }}</span>
            </div>
            @endif
            <div class="result-detail-row">
                <span class="result-detail-label">Status</span>
                <span class="result-detail-value">
                    @if($transaction->isPaymentSuccessful())
                        <span style="color:#4caf50;font-weight:700">✓ Lunas</span>
                    @elseif($transaction->payment_status === 'pending')
                        <span style="color:#ff9800;font-weight:700">⏳ Pending</span>
                    @else
                        <span style="color:#f44336;font-weight:700">✗ {{ ucfirst($transaction->payment_status ?? 'Gagal') }}</span>
                    @endif
                </span>
            </div>
        </div>
        @endif

        <div class="result-actions animate-in animate-delay-2">
            <a href="{{ route('history-transaction.index') }}" class="result-btn primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                Lihat Riwayat Transaksi
            </a>
            <a href="{{ route('home.index') }}" class="result-btn secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

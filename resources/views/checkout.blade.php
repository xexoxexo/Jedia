<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran - tokoNJedia</title>

    <!-- Midtrans Snap.js (Sandbox) -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
</head>
<body>

<!-- Ringkasan Belanja -->
<div class="ringkasan">
    <h3>Ringkasan Belanja</h3>
    <p>Harga Total: Rp {{ number_format($order->harga_total) }}</p>
    <p>Pengiriman:  Rp {{ number_format($order->ongkir) }}</p>
    <hr>
    <h4>Total: Rp {{ number_format($order->total_harga) }}</h4>

    <p>
        Dengan membeli produk dari tokoNJedia, saya menyetujui
        <a href="#">syarat dan ketentuan</a>.
        Anda akan diarahkan ke halaman pembayaran online yang aman.
    </p>

    <!-- Tombol Bayar -->
    <button id="pay-button"
            onclick="bayarSekarang()"
            style="background:#00b14f; color:white; padding:12px 24px; border:none; border-radius:8px; cursor:pointer; width:100%">
        Bayar Online
    </button>
</div>

<script>
function bayarSekarang() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('Sukses:', result);
            window.location.href = '{{ route("pembayaran.sukses") }}';
        },
        onPending: function(result) {
            console.log('Pending:', result);
            window.location.href = '{{ route("pembayaran.pending") }}';
        },
        onError: function(result) {
            console.error('Error:', result);
            alert('Pembayaran gagal, silakan coba lagi.');
        },
        onClose: function() {
            alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
        }
    });
}
</script>

</body>
</html>

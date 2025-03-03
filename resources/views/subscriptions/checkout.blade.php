<!DOCTYPE html>
<html>
<head>
    <title>Proses Pembayaran Berlangganan</title>
</head>
<body>
    <h1>Proses Pembayaran Berlangganan</h1>
    <p>Silakan selesaikan pembayaran sebesar Rp1 untuk mengaktifkan berlangganan Anda.</p>
    <button id="pay-button">Bayar dengan Midtrans</button>
    <pre><div id="result-json">Hasil transaksi akan muncul di sini:<br></div></pre>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>    <script type="text/javascript">

        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    // Midtrans akan secara otomatis mengirim data ke callback URL via POST
                    alert('Pembayaran berhasil! Silakan tunggu konfirmasi.');
                    window.location.href = "{{ route('subscriptions.success') }}?order_id=" + result.order_id;
                },
                onPending: function(result) {
                    alert('Pembayaran sedang diproses. Silakan cek status nanti.');
                },
                onError: function(result) {
                    alert('Pembayaran gagal: ' + JSON.stringify(result));
                    window.location.href = "{{ route('subscriptions.failed') }}";
                }
            });
        };
    </script>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

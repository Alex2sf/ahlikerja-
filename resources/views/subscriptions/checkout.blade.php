<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran Berlangganan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }
        #pay-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #pay-button:hover {
            background-color: #45a049;
        }
        #result-json {
            margin-top: 2rem;
            padding: 1rem;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            color: #333;
            text-align: left;
        }
        a {
            margin-top: 1rem;
            color: #4CAF50;
            text-decoration: none;
            font-size: 0.9rem;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Proses Pembayaran Berlangganan</h1>
        <p>Silakan selesaikan pembayaran sebesar <strong>Rp1</strong> untuk mengaktifkan berlangganan Anda.</p>
        <button id="pay-button">Bayar dengan Midtrans</button>
        <pre><div id="result-json">Hasil transaksi akan muncul di sini:<br></div></pre>
        <a href="{{ route('home') }}">Kembali ke Home</a>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
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
</body>
</html>

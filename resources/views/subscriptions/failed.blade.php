<!DOCTYPE html>
<html>
<head>
    <title>Berlangganan Gagal</title>
</head>
<body>
    <h1>Berlangganan Gagal</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    <p>Pembayaran gagal. Silakan coba lagi atau hubungi dukungan.</p>
    <a href="{{ route('subscriptions.create') }}">Coba Berlangganan Lagi</a>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

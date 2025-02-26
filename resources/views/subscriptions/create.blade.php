<!DOCTYPE html>
<html>
<head>
    <title>Berlangganan untuk Melihat Postingan Tugas</title>
</head>
<body>
    <h1>Berlangganan untuk Melihat Semua Postingan Tugas</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    <p>Dengan berlangganan sebesar Rp1 per bulan, Anda dapat melihat semua postingan tugas selama 1 bulan.</p>
    <form action="{{ route('subscriptions.store') }}" method="POST">
        @csrf
        <button type="submit">Berlangganan Sekarang (Rp1/Bulan)</button>
    </form>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

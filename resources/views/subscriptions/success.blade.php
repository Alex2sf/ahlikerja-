<!DOCTYPE html>
<html>
<head>
    <title>Berlangganan Berhasil</title>
</head>
<body>
    <h1>Berlangganan Berhasil</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <p>Anda sekarang dapat melihat semua postingan tugas selama 1 bulan. Silakan kunjungi <a href="{{ route('posts.all') }}">Semua Postingan</a>.</p>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

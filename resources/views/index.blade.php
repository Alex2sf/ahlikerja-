<!DOCTYPE html>
<html>
<head>
    <title>Daftar Postingan Saya</title>
</head>
<body>
    <h1>Daftar Postingan Saya</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($posts->isEmpty())
        <p>Tidak ada postingan.</p>
    @else
        @foreach ($posts as $post)
            <div>
                <h2>{{ $post->judul }}</h2>
                <p>{{ $post->deskripsi }}</p>
                @if ($post->gambar && count($post->gambar) > 0)
                    <h3>Gambar:</h3>
                    <div>
                        @foreach ($post->gambar as $gambar)
                            <img src="{{ Storage::url('posts/' . $gambar) }}" width="150" alt="Gambar Postingan">
                        @endforeach
                    </div>
                @endif
                <p>Lokasi: {{ $post->lokasi }}</p>
                <p>Estimasi Anggaran: Rp {{ number_format($post->estimasi_anggaran, 2, ',', '.') }}</p>
                <p>Durasi: {{ $post->durasi }}</p>
                <p>Dibuat pada: {{ $post->created_at->format('d F Y') }}</p>
            </div>
            <hr>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
    <a href="{{ route('posts.create') }}">
        <button>Buat Postingan Baru</button>
    </a>
</body>
</html>

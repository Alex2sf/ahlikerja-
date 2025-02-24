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
                <!-- Like -->
                <p>Jumlah Like: {{ $post->likes->count() }}</p>
                <form action="{{ route('posts.like', $post->id) }}" method="POST">
                    @csrf
                    @if ($post->likes()->where('user_id', Auth::id())->exists())
                        <button type="submit">Unlike</button>
                    @else
                        <button type="submit">Like</button>
                    @endif
                </form>

                <!-- Comments -->
                <h3>Komentar</h3>
                @if ($post->comments->isEmpty())
                    <p>Tidak ada komentar.</p>
                @else
                    @foreach ($post->comments as $comment)
                        <div>
                            <p><strong>{{ $comment->user->name }}</strong>
                                @if ($comment->user->nama_panggilan)
                                    ({{ $comment->user->nama_panggilan }})
                                @endif
                            </p>
                            <p>{{ $comment->content }}</p>
                            <p>Dibuat pada: {{ $comment->created_at->format('d F Y H:i') }}</p>
                        </div>
                    @endforeach
                @endif

                <!-- Form Comment -->
                <form action="{{ route('posts.comment', $post->id) }}" method="POST">
                    @csrf
                    <textarea name="content" placeholder="Tulis komentar..." required></textarea>
                    <button type="submit">Kirim Komentar</button>
                </form>

                <a href="{{ route('posts.edit', $post->id) }}">
                    <button>Edit</button>
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                </form>
                <a href="{{ route('posts.edit', $post->id) }}">
                    <button>Edit</button>
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                </form>
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

<!DOCTYPE html>
<html>
<head>
    <title>Semua Postingan Tugas</title>
</head>
<body>
    <h1>Semua Postingan Tugas</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($posts->isEmpty())
        <p>Tidak ada postingan.</p>
    @else
        @foreach ($posts as $post)
            <div>
                <h2>{{ $post->judul }}</h2>
                <p>Deskripsi: {{ $post->deskripsi }}</p>
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
                <p> Diposting oleh:
                    @if ($post->user)
                        <a href="{{ $post->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $post->user->id) : route('user.profile.show', $post->user->id) }}">
                            {{ $post->user->name }}
                            @if ($post->user->nama_panggilan)
                                ({{ $post->user->nama_panggilan }})
                            @endif
                        </a>
                    @else
                        Pengguna tidak ditemukan
                    @endif
                </p>
                @if ($post->user && $post->user->foto_profile)
                    <a href="{{ $post->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $post->user->id) : route('user.profile.show', $post->user->id) }}">
                        <img src="{{ Storage::url('profiles/' . $post->user->foto_profile) }}" width="50" alt="Foto Profil"
                             @if ($post->user->role === 'kontraktor')
                                 style="display: none;"
                             @endif
                        >
                        @if ($post->user->role === 'kontraktor' && $post->user->foto_profile)
                            <img src="{{ Storage::url('contractors/' . $post->user->foto_profile) }}" width="50" alt="Foto Profil">
                        @endif
                    </a>
                @endif
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
                            <p><strong>
                                <a href="{{ $comment->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $comment->user->id) : route('user.profile.show', $comment->user->id) }}">
                                    {{ $comment->user->name }}
                                    @if ($comment->user->nama_panggilan)
                                        ({{ $comment->user->nama_panggilan }})
                                    @endif
                                </a>
                            </strong></p>
                            <p>{{ $comment->content }}</p>
                            @if ($comment->user && $comment->user->foto_profile)
                                <a href="{{ $comment->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $comment->user->id) : route('user.profile.show', $comment->user->id) }}">
                                    <img src="{{ Storage::url('profiles/' . $comment->user->foto_profile) }}" width="50" alt="Foto Profil"
                                         @if ($comment->user->role === 'kontraktor')
                                             style="display: none;"
                                         @endif
                                    >
                                    @if ($comment->user->role === 'kontraktor' && $comment->user->foto_profile)
                                        <img src="{{ Storage::url('contractors/' . $comment->user->foto_profile) }}" width="50" alt="Foto Profil">
                                    @endif
                                </a>
                            @endif
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

                @if (Auth::check() && Auth::user()->id === $post->user_id)
                    <a href="{{ route('posts.edit', $post->id) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                    </form>
                @endif
            </div>
            <hr>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

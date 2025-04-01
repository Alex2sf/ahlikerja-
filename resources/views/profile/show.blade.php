@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="containers">
        <!-- Notifikasi -->
        @if (session('info'))
            <div class="notification alert-info">{{ session('info') }}</div>
        @endif

        <div class="profile-section">
            <!-- Bagian Informasi Profil -->
            <div class="profile-info">
                <img src="{{ $profile && $profile->foto_profile ? asset('storage/' . $profile->foto_profile) : asset('images/default-profile.png') }}"
                     alt="Foto Profil {{ $profile->nama_lengkap ?? 'User' }}">
                <h1>{{ $profile->nama_lengkap }}</h1>
                <p>{{ $profile->nama_panggilan ?? 'Tidak diisi' }}</p>
                <p>{{ $profile->bio ?? 'Tidak ada bio.' }}</p>
            </div>

            <!-- Bagian Detail Profil -->
            <div class="profile-details">
                <div>
                    <h2>Jenis Kelamin</h2>
                    <p>{{ $profile->jenis_kelamin ?? 'Tidak diisi' }}</p>
                </div>
                <div>
                    <h2>Tanggal Lahir</h2>
                    <p>{{ $profile->tanggal_lahir ? $profile->tanggal_lahir->format('d F Y') : 'Tidak diisi' }}</p>
                </div>
                <div>
                    <h2>Tempat Lahir</h2>
                    <p>{{ $profile->tempat_lahir ?? 'Tidak diisi' }}</p>
                </div>
                <div>
                    <h2>Alamat Lengkap</h2>
                    <p>{{ $profile->alamat_lengkap ?? 'Tidak diisi' }}</p>
                </div>
                <div>
                    <h2>Nomor Telepon</h2>
                    <p>{{ $profile->nomor_telepon ?? 'Tidak diisi' }}</p>
                </div>
                <div>
                    <h2>Email</h2>
                    <p>{{ $profile->email }}</p>
                </div>
                <div>
                    <h2>Media Sosial</h2>
                    @if ($profile->media_sosial && count($profile->media_sosial) > 0)
                        <ul>
                            @foreach ($profile->media_sosial as $media)
                                <li>{{ $media ?? 'Tidak diisi' }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada media sosial yang diisi.</p>
                    @endif
                </div>
            </div>

            <!-- Tombol Edit dan Kembali -->
            <div class="button-group">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>

        <!-- Posts Section (Right Column) -->
        <div class="posts-section">
            <h1>Daftar Postingan Saya</h1>
            @if (session('success'))
                <div class="notification alert-success">{{ session('success') }}</div>
            @endif
            @if ($posts->isEmpty())
                <p class="text-center text-muted">Tidak ada postingan.</p>
            @else
                @foreach ($posts as $post)
                    <div class="post-card">
                        <h2>{{ $post->judul }}</h2>
                        <p>{{ $post->deskripsi }}</p>
                        <!-- Tampilkan Gambar -->
                        @if ($post->gambar && count($post->gambar) > 0)
                        <h3>Gambar:</h3>
                        <div class="post-images">
                            @foreach ($post->gambar as $gambar)
                                <img src="{{ Storage::url($gambar) }}" alt="Gambar Postingan" data-full-image="{{ Storage::url($gambar) }}" class="post-image">
                            @endforeach
                        </div>
                        @else
                        <p>Tidak ada gambar.</p>
                        @endif
                        <p><strong>Lokasi:</strong> {{ $post->lokasi }}</p>
                        <p><strong>Estimasi Anggaran:</strong> Rp {{ number_format($post->estimasi_anggaran, 2, ',', '.') }}</p>
                        <p><strong>Durasi:</strong> {{ $post->durasi }}</p>
                        <p><small>Dibuat pada: {{ $post->created_at->format('d F Y') }}</small></p>

                        <!-- Like Section -->
                        <div class="like-section">
                            <p>Jumlah Like: {{ $post->likes->count() }}</p>
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'btn-outline-primary' : 'btn-primary' }}">
                                    {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'Unlike' : 'Like' }}
                                </button>
                            </form>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section mt-3">
                            <h3>Komentar</h3>
                            @if ($post->comments->isEmpty())
                                <p>Tidak ada komentar.</p>
                            @else
                                @foreach ($post->comments as $comment)
                                    <div class="comment">
                                        <p><strong>{{ $comment->user->name }}</strong>
                                            @if ($comment->user->nama_panggilan)
                                                ({{ $comment->user->nama_panggilan }})
                                            @endif
                                        </p>
                                        <p>{{ $comment->content }}</p>
                                        <small class="text-muted">Dibuat pada: {{ $comment->created_at->format('d F Y H:i') }}</small>
                                    </div>
                                @endforeach
                            @endif
                            <!-- Form Comment -->
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="comment-form mt-3">
                                @csrf
                                <textarea name="content" placeholder="Tulis komentar..." required class="form-control"></textarea>
                                <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
                            </form>
                        </div>

                        <!-- Tombol Edit dan Hapus -->
                        <div class="button-group">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
            <a href="{{ route('posts.create') }}" class="btn btn-secondary">Buat Postingan Baru</a>
        </div>
    </div>

    <style>
        /* General Styles */
        .containers {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
            display: flex;
            gap: 50px;
        }

        /* Profile Section (Left Column) */
        .profile-section {
            width: 400px; /* Lebar tetap */
            background-color: #fff;
            padding: 20px; /* Padding lebih kecil */
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            height: fit-content; /* Tinggi menyesuaikan isi */
            display: flex;
            flex-direction: column;
            gap: 20px; /* Jarak antara dua bagian */
            position: sticky;
            top: 100px; /* Jarak dari atas, disesuaikan dengan tinggi header */
        }

        /* Bagian Informasi Profil */
        .profile-info {
            text-align: center;
            border-bottom: 1px solid #e0d8c9; /* Garis pemisah */
            padding-bottom: 20px; /* Jarak dari garis pemisah */
        }

        .profile-info img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }


        .post-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .post-images img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .profile-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .profile-info p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        /* Bagian Detail Profil */
        .profile-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px; /* Jarak antara detail */
        }

        .profile-details h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 5px;
        }

        .profile-details p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .profile-details ul {
            padding-left: 20px;
            margin: 0;
        }

        .profile-details ul li {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        /* Posts Section (Right Column) */
        .posts-section {
            flex: 2;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9; /* Border natural vintage */
        }

        .posts-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36; /* Cokelat tua elegan */
            text-align: center;
            margin-bottom: 20px;
        }

        .post-card {
            background-color: #fdfaf6; /* Latar krem */
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
        }

        .post-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .post-card p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .post-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .like-section, .comments-section {
            margin-top: 15px;
        }

        .btn {
            background-color: #a8c3b8; /* Hijau sage */
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #8ba89a; /* Hijau lebih gelap */
        }

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5; /* Beige */
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        .btn-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-danger:hover {
            background-color: #f5c6cb;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .notification.alert-info {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .notification.alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                flex-direction: column;
            }

            .profile-section, .posts-section {
                width: 100%;
            }

            .profile-section {
                position: static; /* Hilangkan sticky di mobile */
            }
        }
    </style>
@endsection

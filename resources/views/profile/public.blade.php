@extends('layouts.app')

@section('title', 'Profil Publik - ' . $user->name)

@section('content')
    <div class="container">
        <!-- Notifikasi -->
        @if (session('error'))
            <div class="notification error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="notification success">{{ session('success') }}</div>
        @endif

        <!-- Layout Dua Kolom -->
        <div class="row">
            <!-- Profil (Kiri) -->
            <div class="profile-section">
                <h1>Profil Publik - {{ $user->name }}</h1>
                <div class="profile-content">
                    <div class="profile-photo">
                        @if ($profile->foto_profile)
                            <img src="{{ Storage::url($profile->foto_profile) }}" alt="Foto Profil" class="profile-preview">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" alt="Foto Profil Default">
                        @endif
                    </div>
                    <div class="info-item">
                        <h2>Nama Lengkap</h2>
                        <p>{{ $profile->nama_lengkap }}</p>
                    </div>
                    <div class="info-item">
                        <h2>Nama Panggilan</h2>
                        <p>{{ $profile->nama_panggilan ?? 'Tidak diisi' }}</p>
                    </div>
                    <div class="info-item">
                        <h2>Bio</h2>
                        <p>{{ $profile->bio }}</p>
                    </div>
                    <div class="info-item">
                        <h2>Email</h2>
                        <p>{{ $profile->email }}</p>
                    </div>
                    <div class="info-item">
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

                    <!-- Tombol Chat untuk Kontraktor -->
                    @if (Auth::check() && Auth::user()->role === 'kontraktor')
                        <div class="button-group">
                            <a href="{{ route('chats.index', $user->id) }}" class="btn btn-primary">Chat</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Postingan (Kanan) -->
            <div class="posts-section">
                <h1>Daftar Postingan {{ $user->name }}</h1>
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
                            <div class="button-group">
                                <p>Jumlah Like: {{ $post->likes->count() }}</p>
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'btn-outline-primary' : 'btn-primary' }}">
                                        {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'Unlike' : 'Like' }}
                                    </button>
                                </form>
                            </div>

                            <!-- Comments Section -->
                            <div class="comments-section">
                                <h3>Komentar</h3>
                                @if ($post->comments->isEmpty())
                                    <p>Tidak ada komentar.</p>
                                @else
                                    @foreach ($post->comments as $comment)
                                        <div class="comment">
                                            <p><strong>
                                                <a href="{{ $comment->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $comment->user->id) : route('user.profile.show', $comment->user->id) }}">
                                                    {{ $comment->user->name }}
                                                    @if ($comment->user->nama_panggilan)
                                                        ({{ $comment->user->nama_panggilan }})
                                                    @endif
                                                </a>
                                            </strong></p>
                                            <p>{{ $comment->content }}</p>
                                            <small class="text-muted">Dibuat pada: {{ $comment->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    @endforeach
                                @endif
                                <!-- Form Comment -->
                                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="comment-form">
                                    @csrf
                                    <textarea name="content" placeholder="Tulis komentar..." required></textarea>
                                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                                </form>
                            </div>

                            <!-- Tombol Edit dan Hapus (Hanya untuk Pemilik Postingan) -->
                            @if (Auth::id() === $post->user_id)
                                <div class="button-group">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
                @if (Auth::id() === $user->id)
                    <div class="create-post-btn">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Postingan Baru</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div>
    </div>

    <style>
        /* Row Layout */
        .row {
            display: flex;
            gap: 30px;
        }

        /* Profile Section (Kiri) */
        .profile-section {
            width: 350px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .profile-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .profile-photo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
            margin: 0 auto;
            display: block;
        }

        .info-item h2 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 8px;
        }

        .info-item p, .info-item ul {
            font-size: 14px;
            color: #555;
        }

        .info-item ul {
            padding-left: 20px;
            margin: 0;
        }

        .profile-section .button-group {
            margin-top: 20px;
            text-align: center;
        }

        /* Posts Section (Kanan) */
        .posts-section {
            flex: 1;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            width: 800px;
            margin-top: 20px;
        }

        .posts-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Post Card */
        .post-card {
            background-color: #fdfaf6;
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

        .post-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 10px;
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

        /* Like Section */
        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        /* Comments Section */
        .comments-section {
            margin-top: 20px;
        }

        .comments-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 15px;
        }

        .comment {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #e0d8c9;
        }

        .comment p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .comment a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .comment a:hover {
            text-decoration: underline;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            resize: vertical;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Create Post Button */
        .create-post-btn {
            text-align: center;
            margin-top: 20px;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8; /* Hijau sage */
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 1px solid #a8c3b8;
            color: #a8c3b8;
        }

        .btn-outline-primary:hover {
            background-color: #a8c3b8;
            color: #fff;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .profile-section {
                width: 100%;
                position: static;
            }

            .posts-section {
                width: 100%;
            }

            .post-images img {
                width: 100px;
                height: 100px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

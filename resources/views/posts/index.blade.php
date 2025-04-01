@extends('layouts.app')

@section('title', 'Daftar Postingan Saya')

@section('content')
    <div class="container">
        <div class="posts-section">
            <h1>Daftar Postingan Saya</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
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

            <div class="action-buttons">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Postingan Baru</a>
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>
    </div>

    <style>
        /* Posts Section */
        .posts-section {
            width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .posts-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
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
        .notification.success {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .posts-section {
                padding: 20px;
                margin: 20px;
            }

            .posts-section h1 {
                font-size: 28px;
            }

            .post-images img {
                width: 100px;
                height: 100px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

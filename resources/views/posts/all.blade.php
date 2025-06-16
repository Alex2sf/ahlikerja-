@extends('layouts.app')

@section('title', 'Semua Postingan Tugas')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter (Kiri) -->
            <div class="sidebar">
                <h2>Cari dan Filter</h2>
                <form method="GET" action="{{ route('posts.all') }}">
                    <div class="form-group">
                        <label for="search">Cari (Judul/Deskripsi):</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                    </div>
                    <div class="form-group">
                        <label for="lokasi_filter">Lokasi:</label>
                        <input type="text" id="lokasi_filter" name="lokasi" value="{{ request('lokasi') }}" placeholder="Masukkan lokasi...">
                    </div>
                    <div class="form-group">
                        <label>Estimasi Anggaran:</label>
                        <input type="number" name="anggaran_min" value="{{ request('anggaran_min') }}" placeholder="Min (Rp)" step="0.01" min="0">
                        <input type="number" name="anggaran_max" value="{{ request('anggaran_max') }}" placeholder="Max (Rp)" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="durasi_filter">Durasi:</label>
                        <input type="text" id="durasi_filter" name="durasi" value="{{ request('durasi') }}" placeholder="Contoh: 3 hari, 1 minggu, 2 bulan">
                    </div>
                    <button type="submit"
                        class="btn"
                        style="background-color: #A0522D; color: white; border: none;">
                        Terapkan
                    </button>
                    <a href="{{ route('posts.all') }}" class="btn btn-secondary">Reset Filter</a>
                </form>
            </div>

            <!-- Main Content (Kanan) -->
            <div class="main-content">
                <h1>Semua Tender Proyek</h1>
                @if (session('success'))
                    <div class="notification success">{{ session('success') }}</div>
                @endif

                <!-- Daftar Semua Postingan -->
                @if ($posts->isEmpty())
                    <p class="text-center text-muted">Tidak ada postingan.</p>
                @else
                    @foreach ($posts as $post)
                        <div class="post-card">
                            <div class="post-card-grid">
                                <!-- Kolom Kiri: Judul, Deskripsi, Gambar -->
                                <div class="post-card-left">
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
                                </div>
                                <!-- Kolom Kanan: Detail, Dokumen, Status, User Info, Tombol -->
                                <div class="post-card-right">
                                    <!-- Tampilkan Dokumen -->
                                    @if ($post->dokumen)
                                        <p><strong>Dokumen:</strong> <a href="{{ Storage::url($post->dokumen) }}" target="_blank">Lihat Dokumen</a></p>
                                    @else
                                        <p>Tidak ada dokumen.</p>
                                    @endif
                                    <p><strong>Lokasi:</strong> {{ $post->lokasi }}</p>
                                    <p><strong>Estimasi Anggaran:</strong> Rp {{ number_format($post->estimasi_anggaran, 2, ',', '.') }}</p>
                                    <p><strong>Durasi:</strong> {{ $post->durasi }}</p>
                                    <p><strong>Status:</strong>
                                        @if ($post->status === 'open')
                                            <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px;">
                                                Open
                                            </span>
                                        @else
                                            <span style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px;">
                                                Closed
                                            </span>
                                        @endif
                                    </p>
                                    <div class="user-info">
                                        <p>Diposting oleh:
                                            <a href="{{ route('user.profile.show', $post->user->id) }}">
                                                {{ $post->user->name }}
                                                @if ($post->user->nama_panggilan)
                                                    ({{ $post->user->nama_panggilan }})
                                                @endif
                                            </a>
                                        </p>
                                    </div>
                                    <p><small>Dibuat pada: {{ $post->created_at->format('d F Y') }}</small></p>

                                    <!-- Tombol "Berikan Penawaran" dan "Chat" untuk Kontraktor -->
                                    @if (Auth::check() && Auth::user()->role === 'kontraktor' && Auth::user()->id !== $post->user_id && $post->status === 'open')
                                        <div class="button-group">
                                            @php
                                                $isApproved = Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved;
                                            @endphp
                                            <form action="{{ route('offers.store', $post->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn offer-btn"
                                                    @if(!$isApproved) disabled @endif
                                                    style="background-color: #D2691E; /* oranye-coklat */
                                                        color: white;
                                                        font-weight: 600;
                                                        padding: 6px 14px;
                                                        font-size: 0.95rem;
                                                        border: none;
                                                        border-radius: 5px;
                                                        box-shadow: 0 3px 5px rgba(0,0,0,0.1);
                                                        cursor: pointer;">
                                                    Berikan Penawaran
                                                </button>
                                            </form>
                                            <a href="{{ route('chats.index', ['receiverId' => $post->user->id, 'postTitle' => $post->judul]) }}"
                                               class="btn chat-btn"
                                               @if(!$isApproved) disabled @endif
                                               style="background-color: #8B4513; /* coklat tua */
                                                      color: white;
                                                      font-weight: 600;
                                                      padding: 6px 14px;
                                                      font-size: 0.95rem;
                                                      border: none;
                                                      border-radius: 5px;
                                                      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
                                                      cursor: pointer;">
                                                Chat
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Like (Hanya ditampilkan jika bukan admin) -->
                                    @if (Auth::check() && Auth::user()->role !== 'admin')
                                        <div class="button-group">
                                            <p>Jumlah Like: {{ $post->likes->count() }}</p>
                                            @php
                                                $isApproved = Auth::check() && Auth::user()->role === 'kontraktor' ? (Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved) : true;
                                            @endphp
                                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm like-btn"
                                                        style="background-color: {{ $post->likes()->where('user_id', Auth::id())->exists() ? '#ffffff' : '#D2691E' }}; color: {{ $post->likes()->where('user_id', Auth::id())->exists() ? '#D2691E' : '#ffffff' }}; border: 1px solid #D2691E;"
                                                        @if(!$isApproved) disabled @endif>
                                                    {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'Unlike' : 'Like' }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="comments-section">
                            <h3>Komentar</h3>
                            @if ($post->comments->isEmpty())
                                <p>Tidak ada komentar.</p>
                            @else
                                <!-- Show first two comments -->
                                @foreach ($post->comments->take(2) as $comment)
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
                                <!-- Show additional comments in a hidden container -->
                                @if ($post->comments->count() > 2)
                                    <div class="more-comments" id="more-comments-{{ $post->id }}" style="display: none;">
                                        @foreach ($post->comments->slice(2) as $comment)
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
                                    </div>
                                    <a href="javascript:void(0);" class="view-more-comments" data-post-id="{{ $post->id }}">
                                        Lihat semua {{ $post->comments->count() }} komentar
                                    </a>
                                @endif
                            @endif

                            <!-- Form Comment (Hanya ditampilkan jika bukan admin) -->
                            @if (Auth::check() && Auth::user()->role !== 'admin')
                                @php
                                    $isApproved = Auth::check() && Auth::user()->role === 'kontraktor' ? (Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved) : true;
                                @endphp
                                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="comment-form" @if(!$isApproved) onsubmit="return false;" @endif>
                                    @csrf
                                    <textarea name="content" placeholder="Tulis komentar..." required @if(!$isApproved) readonly @endif></textarea>
                                    <button type="submit"
                                            class="btn comment-btn"
                                            style="background-color: #D2691E; color: white; border: 1px solid #D2691E;"
                                            @if(!$isApproved) disabled @endif>
                                        Kirim Komentar
                                    </button>
                                </form>
                            @endif
                        </div>

                            <!-- Tombol Edit, Hapus, dan Lihat Penawaran -->
                            <div class="button-group">
                                <!-- Tombol untuk Admin -->
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit (Admin)</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus (Admin)</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pesan untuk Kontraktor yang Belum Berlangganan -->
                    @if (Auth::check() && Auth::user()->role === 'kontraktor')
                        @php
                            $subscription = App\Models\Subscription::where('contractor_id', Auth::id())
                                ->where('is_active', true)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now())
                                ->first();
                        @endphp
                        @if (!$subscription && $totalPosts > $limit)
                            <div class="subscription-prompt">
                                <p>Anda hanya dapat melihat {{ $limit }} postingan teratas. Terdapat {{ $totalPosts - $limit }} postingan lainnya yang dapat Anda lihat dengan berlangganan.</p>
                                <a href="{{ route('subscriptions.create') }}"
                                   class="btn"
                                   style="background-color: #A0522D; /* sienna - coklat elegan */
                                          color: white;
                                          font-weight: 600;
                                          padding: 6px 16px;
                                          font-size: 0.95rem;
                                          border: none;
                                          border-radius: 5px;
                                          box-shadow: 0 3px 5px rgba(0,0,0,0.1);">
                                    Berlangganan Sekarang
                                </a>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <!-- Modal untuk Gambar Besar -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close">×</span>
                <img id="modalImage" src="" alt="Gambar Besar">
            </div>
        </div>

        <!-- Modal untuk Peringatan Belum Disetujui -->
        <div id="notApprovedModal" class="modal">
            <div class="modal-content">
                <span class="close-not-approved">×</span>
                <p>Anda harus disetujui oleh admin terlebih dahulu untuk melakukan tindakan ini.</p>
                <button class="btn btn-secondary" onclick="closeNotApprovedModal()">Tutup</button>
            </div>
        </div>
    </div>

    <style>
        /* Row Layout */
        .row {
            display: flex;
            gap: 30px;
        }

        /* Sidebar (Filter) */
        .sidebar {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .sidebar .form-group {
            margin-bottom: 15px;
        }

        .sidebar .form-group label {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 8px;
        }

        .sidebar .form-group input {
            width: 95%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
            transition: border-color 0.3s ease;
        }

        .sidebar .form-group input:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            margin-top: 20px;
        }

        .main-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
            width: 800px;
        }

        /* Post Card */
        .post-card {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            border: 1px solid #e0d8c9;
        }

        .post-card:hover {
            transform: translateY(-5px);
        }

        .post-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }

        .post-card-left,
        .post-card-right {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .post-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .post-card p {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
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

        .post-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .post-image:hover {
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
        }

        #modalImage {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 5px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #5a3e36;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .close:hover {
            color: #a8c3b8;
        }

        /* Modal untuk Peringatan Belum Disetujui */
        #notApprovedModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        #notApprovedModal .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        #notApprovedModal .modal-content p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        #notApprovedModal .close-not-approved {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #5a3e36;
            cursor: pointer;
        }

        #notApprovedModal .close-not-approved:hover {
            color: #a8c3b8;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .user-info p {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            margin: 0;
        }

        .user-info a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .user-info a:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        /* Like Section */
        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        /* Comments Section */
        .comments-section {
            margin-top: 20px;
            grid-column: 1 / -1; /* Membuat komentar memanjang di kedua kolom */
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
            border: 1px solid #e0d8e9;
        }

        .comment p {
            font-family: 'Roboto', sans-serif;
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
            color: #a8c3b8;
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

        /* View More Comments */
        .view-more-comments {
            display: block;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #5a3e36;
            text-decoration: none;
            margin-top: 10px;
            cursor: pointer;
        }

        .view-more-comments:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        /* Subscription Prompt */
        .subscription-prompt {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            border: 1px solid #e0d8c9;
        }

        .subscription-prompt p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            margin-bottom: 15px;
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

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5;
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

        button[disabled], a[disabled] {
            background-color: #d4c8b5 !important;
            color: #6b5848 !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
            }

            .main-content {
                width: 100%;
            }

            .post-card-grid {
                grid-template-columns: 1fr; /* Satu kolom di layar kecil */
            }

            .post-images img {
                width: 100px;
                height: 100px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .modal-content {
                width: 90%;
                padding: 10px;
            }

            #modalImage {
                max-height: 70vh;
            }

            .subscription-prompt p {
                font-size: 14px;
            }
        }
    </style>

    <script>
        // Modal untuk gambar besar
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementsByClassName('close')[0];

        document.querySelectorAll('.post-image').forEach(image => {
            image.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalImage.src = image.getAttribute('data-full-image');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Modal untuk peringatan belum disetujui
        const notApprovedModal = document.getElementById('notApprovedModal');
        const closeNotApproved = document.getElementsByClassName('close-not-approved')[0];

        function openNotApprovedModal() {
            notApprovedModal.style.display = 'flex';
        }

        function closeNotApprovedModal() {
            notApprovedModal.style.display = 'none';
        }

        document.querySelectorAll('.offer-btn[disabled], .chat-btn[disabled], .like-btn[disabled], .comment-btn[disabled]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                openNotApprovedModal();
            });
        });

        closeNotApproved.addEventListener('click', closeNotApprovedModal);

        notApprovedModal.addEventListener('click', (e) => {
            if (e.target === notApprovedModal) {
                closeNotApprovedModal();
            }
        });

        // Toggle more comments
        document.querySelectorAll('.view-more-comments').forEach(link => {
            link.addEventListener('click', () => {
                const postId = link.getAttribute('data-post-id');
                const moreComments = document.getElementById(`more-comments-${postId}`);
                if (moreComments.style.display === 'none' || moreComments.style.display === '') {
                    moreComments.style.display = 'block';
                    link.textContent = 'Sembunyikan komentar';
                } else {
                    moreComments.style.display = 'none';
                    link.textContent = `Lihat semua ${link.textContent.match(/\d+/)[0]} komentar`;
                }
            });
        });
    </script>
@endsection

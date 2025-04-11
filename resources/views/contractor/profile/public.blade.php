@extends('layouts.app')

@section('title', 'Profil Publik Kontraktor - ' . $user->name)

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="profile-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Profil Publik Kontraktor - {{ $user->name }}</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('error'))
            <div class="notification error">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="notification info">{{ session('info') }}</div>
        @endif
        @if (session('success'))
            <div class="notification success">{{ session('success') }}</div>
        @endif

        <!-- Profil Card -->
        <div class="profile-card">
            <!-- Foto Profil -->
            <div class="profile-image-container">
                @if ($profile->foto_profile)
                <img src="{{ Storage::url($profile->foto_profile) }}" alt="Foto Profil" class="profile-preview">
                @else
                    <div class="no-image">Tidak ada foto profil</div>
                @endif
            </div>

            <!-- Informasi Profil -->
            <div class="profile-details">
                <p><strong>Perusahaan:</strong> {{ $profile->perusahaan ?? 'Tidak diisi' }}</p>

                <p><strong>Bio:</strong> {{ $profile->bio ?? 'Tidak diisi' }}</p>

                <!-- Bidang Usaha -->
                @if ($profile->bidang_usaha && count($profile->bidang_usaha) > 0)
                    <p><strong>Bidang Usaha:</strong></p>
                    <ul class="detail-list">
                        @foreach ($profile->bidang_usaha as $bidang)
                            <li>{{ $bidang }}</li>
                        @endforeach
                    </ul>
                @else
                    <p><strong>Bidang Usaha:</strong> Tidak diisi</p>
                @endif
            </div>

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-button active" onclick="openTab('documents')">Dokumen Pendukung</button>
                <button class="tab-button" onclick="openTab('portfolios')">Portofolio</button>
                <button class="tab-button" onclick="openTab('ratings')">Rating</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Dokumen Pendukung -->
                <div id="documents" class="tab-pane active">
                    @if ($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
                        <h3>Dokumen Pendukung</h3>
                        <ul class="item-list">
                            @foreach ($profile->dokumen_pendukung as $doc)
                                <li>
                                    <a href="{{ Storage::url($doc) }}" target="_blank" class="item-link">
                                        {{ basename($doc) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada dokumen pendukung.</p>
                    @endif
                </div>

                <!-- Portofolio -->
                <div id="portfolios" class="tab-pane">
                    @if ($profile->portofolio && count($profile->portofolio) > 0)
                        <h3>Portofolio</h3>
                        <ul class="portfolio-grid">
                            @foreach ($profile->portofolio as $port)
                                <li>
                                    <a href="{{ Storage::url($port) }}" target="_blank">
                                        <img src="{{ Storage::url($port) }}" alt="Portofolio" class="item-image">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada portofolio.</p>
                    @endif
                </div>

                <!-- Rating -->
                <div id="ratings" class="tab-pane">
                    <h3>Rating dan Ulasan</h3>
                    @php
                        $reviews = $profile->user->reviews;
                        $averageRating = $reviews->avg('rating');
                    @endphp
                    @if ($reviews->isEmpty())
                        <p>Belum ada ulasan.</p>
                    @else
                        <p>Rating Rata-rata: {{ number_format($averageRating, 1) }}/5 ({{ $reviews->count() }} ulasan)</p>
                        <ul class="reviews-list">
                            @foreach ($reviews as $review)
                                <li class="review-card">
                                    <p>Rating: {{ $review->rating }}/5</p>
                                    <p>Ulasan: {{ $review->review ?? 'Tidak ada ulasan' }}</p>
                                    <p>Oleh: <a href="{{ route('user.profile.show', $review->user->id) }}">{{ $review->user->name }}</a></p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="button-group">
                @if (Auth::check() && Auth::user()->role === 'user') <!-- Hanya untuk role 'user' -->
                    <a href="{{ route('chats.index', $user->id) }}" class="btn btn-primary">Chat</a>
                @endif
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>

        <!-- Modal untuk Dokumen/Portofolio Besar -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close">×</span>
                <img id="modalImage" src="" alt="Dokumen/Portofolio Besar">
            </div>
        </div>
    </div>

    <style>
        /* General Container */
        .containers {
            width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Profile Header */
        .profile-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #e0d8c9;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        p, li {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #555;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .notification.info {
            background-color: #cce5ff;
            color: #004085;
            border-color: #b8daff;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* Profile Card */
        .profile-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            margin-bottom: 20px;
        }

        .profile-image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #a8c3b8;
            transition: transform 0.3s ease;
        }

        .profile-preview:hover {
            transform: scale(1.05);
        }

        .no-image {
            width: 150px;
            height: 150px;
            background-color: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b5848;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
        }

        .profile-details {
            margin-bottom: 20px;
        }

        .detail-list {
            list-style: none;
            padding: 0;
        }

        .detail-list li {
            margin-bottom: 5px;
        }

        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0d8c9;
        }

        .tab-button {
            background-color: #d4c8b5;
            border: none;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            transition: background-color 0.3s ease;
        }

        .tab-button.active {
            background-color: #a8c3b8;
            color: #fff;
        }

        .tab-button:hover {
            background-color: #c7b9a1;
        }

        /* Tab Content */
        .tab-content {
            padding: 20px;
            background-color: #fdfaf6;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0d8c9;
            border-top: none;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .item-list {
            list-style: none;
            padding: 0;
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .item-link {
            color: #a8c3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .item-link:hover {
            color: #8ba89a;
            text-decoration: underline;
        }

        .item-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin: 5px 0;
            transition: transform 0.3s ease;
        }

        .item-image:hover {
            transform: scale(1.05);
        }

        .reviews-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 kolom */
            gap: 20px; /* Jarak antar ulasan */
        }

        .review-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e0d8c9;
            transition: transform 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
        }

        .review-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .review-card a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .review-card a:hover {
            text-decoration: underline;
        }

        /* Button Group */
        .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background-color: #a8c3b8;
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
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
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
        }

        .close:hover {
            color: #a8c3b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-preview {
                width: 120px;
                height: 120px;
            }

            .tab-navigation {
                flex-direction: column;
            }

            .tab-button {
                width: 100%;
                border-radius: 5px;
            }

            .portfolio-grid {
                grid-template-columns: repeat(2, 1fr); /* 2 kolom di layar kecil */
            }

            .reviews-list {
                grid-template-columns: 1fr; /* 1 kolom di layar kecil */
            }

            .item-image {
                height: 120px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>

    <script>
        function openTab(tabName) {
            const tabPanes = document.getElementsByClassName('tab-pane');
            for (let i = 0; i < tabPanes.length; i++) {
                tabPanes[i].style.display = 'none';
            }

            const tabButtons = document.getElementsByClassName('tab-button');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }

            document.getElementById(tabName).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        window.onload = function() {
            openTab('documents');
        };

        // Modal untuk dokumen/portofolio besar
        // const modal = document.getElementById('imageModal');
        // const modalImage = document.getElementById('modalImage');
        // const closeModal = document.getElementsByClassName('close')[0];

        // document.querySelectorAll('.item-link, .item-image').forEach(item => {
        //     item.addEventListener('click', (e) => {
        //         e.preventDefault(); // Mencegah link terbuka di tab baru
        //         modal.style.display = 'flex';
        //         modalImage.src = item.getAttribute('data-full-image') || item.querySelector('img').getAttribute('data-full-image');
        //     });
        // });

        // closeModal.addEventListener('click', () => {
        //     modal.style.display = 'none';
        // });

        // modal.addEventListener('click', (e) => {
        //     if (e.target === modal) {
        //         modal.style.display = 'none';
        //     }
        // });
    </script>
@endsection

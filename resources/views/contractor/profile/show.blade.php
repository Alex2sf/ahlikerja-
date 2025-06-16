@extends('layouts.app')

@section('title', 'Profil Kontraktor')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="profile-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Profil Kontraktor</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('info'))
            <div class="notification info">
                {{ session('info') }}
            </div>
        @endif
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="notification error">
                {{ session('error') }}
            </div>
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
                <p><strong>Perusahaan:</strong> {{ $profile->perusahaan }}</p>

                <p><strong>Bio:</strong> {{ $profile->bio ?: 'Tidak ada bio' }}</p>

                @if ($profile->nomor_telepon)
                    <p><strong>Nomor Telepon:</strong> {{ $profile->nomor_telepon }}</p>
                @endif

                @if ($profile->alamat)
                    <p><strong>Alamat:</strong> {{ $profile->alamat }}</p>
                @endif

                <p><strong>Nomor NPWP:</strong> {{ $profile->nomor_npwp }}</p>

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
                <button class="tab-button active" onclick="openTab('dokumen')">Dokumen Pendukung</button>
                <button class="tab-button" onclick="openTab('portofolio')">Portofolio</button>
                <button class="tab-button" onclick="openTab('legalitas')">Dokumen Legalitas</button>
                <button class="tab-button" onclick="openTab('ratings')">Rating dan Ulasan</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Dokumen Pendukung -->
                <div id="dokumen" class="tab-pane active">
                    @if ($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
                        <h3>Dokumen Pendukung</h3>
                        <ul class="item-list">
                            @foreach ($profile->dokumen_pendukung as $index => $dokumen)
                                <li>
                                    <a href="{{ Storage::url($dokumen) }}" target="_blank" class="item-link">
                                        {{ basename($dokumen) }}
                                    </a>
                                    <form action="{{ route('contractor.profile.delete', ['type' => 'dokumen', 'index' => $index]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada dokumen pendukung.</p>
                    @endif
                </div>

                <!-- Portofolio -->
                <div id="portofolio" class="tab-pane">
                    @if ($profile->portofolio && count($profile->portofolio) > 0)
                        <h3>Portofolio</h3>
                        <ul class="portfolio-grid">
                            @foreach ($profile->portofolio as $index => $port)
                                <li>
                                    <a href="{{ Storage::url($port) }}" target="_blank">
                                        <img src="{{ Storage::url($port) }}" alt="Portofolio" class="item-image">
                                    </a>
                                    <form action="{{ route('contractor.profile.delete', ['type' => 'portofolio', 'index' => $index]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus portofolio ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada portofolio.</p>
                    @endif
                </div>



                <!-- Dokumen Legalitas -->
                <div id="legalitas" class="tab-pane">
                    @if ($profile->legalitas && count($profile->legalitas) > 0)
                        <h3>Dokumen Legalitas</h3>
                        <ul class="item-list">
                            @foreach ($profile->legalitas as $index => $doc)
                                <li>
                                    <a href="{{ Storage::url($doc) }}" target="_blank" class="item-link">
                                        {{ basename($doc) }}
                                    </a>
                                    <form action="{{ route('contractor.profile.delete', ['type' => 'legalitas', 'index' => $index]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus dokumen legalitas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada dokumen legalitas.</p>
                    @endif
                </div>

                <!-- Rating dan Ulasan -->
                <div id="ratings" class="tab-pane">
                    <h3>Rating dan Ulasan</h3>
                    @php
                        $reviews = $profile->user->reviews ?? collect();
                        $averageRating = $reviews->avg('rating') ?? 0;
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
<a href="{{ route('contractor.profile.edit') }}" class="btn"
   style="background-color: #008080; color: white; border: none; padding: 10px 20px; border-radius: 5px;">
   Edit Profil
</a>


<a href="{{ route('home') }}" class="btn"
   style="background-color: #a0522d; color: white; border: none; padding: 10px 20px; border-radius: 5px;">
   Kembali ke Home
</a>
            </div>
        </div>
    </div>

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
            openTab('dokumen');
        };
    </script>

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

        .item-list, .portfolio-grid, .image-list {
            list-style: none;
            padding: 0;
        }

        .item-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .portfolio-grid, .image-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .portfolio-grid li, .image-list li {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
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

        /* Review Styles */
        .reviews-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Membagi menjadi 2 kolom */
            gap: 15px;
        }

        .review-card {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e0d8c9;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Button Styles */
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

        .button-group {
            text-align: center;
            margin-top: 20px;
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

            .portfolio-grid, .image-list {
                grid-template-columns: repeat(2, 1fr); /* 2 kolom di layar kecil */
            }

            .item-image {
                height: 120px;
            }

            .reviews-list {
                grid-template-columns: 1fr; /* 1 kolom di layar kecil */
            }

            .review-card {
                padding: 10px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
@endsection

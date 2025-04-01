@extends('layouts.app')

@section('title', 'Daftar Semua Kontraktor')

@section('content')
    <div class="container">
        <div class="contractors-section">
            <h1>Daftar Semua Kontraktor</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif

            <!-- Form Pencarian dan Filter -->
            <form method="GET" action="{{ route('contractors.index') }}" class="search-filter-form">
                <div class="form-group">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari (Nama/Perusahaan)...">
                </div>
                <div class="form-group">
                    <input type="text" name="lokasi" id="lokasi" value="{{ request('lokasi') }}" placeholder="Lokasi...">
                </div>
                <div class="form-group">
                    <select name="bidang_usaha" id="bidang_usaha">
                        <option value="">Semua Bidang Usaha</option>
                        @foreach ($bidangUsahaOptions as $bidang)
                            <option value="{{ $bidang }}" {{ request('bidang_usaha') === $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cari & Filter</button>
                <a href="{{ route('contractors.index') }}" class="btn btn-reset">Reset</a>
            </form>

            <!-- Daftar Kontraktor -->
            @if ($contractors->isEmpty())
                <p class="text-center text-muted">Tidak ada kontraktor yang ditemukan.</p>
            @else
                <div class="contractor-grid">
                    @foreach ($contractors as $contractor)
                        <div class="contractor-card">
                            <div class="contractor-header">
                                @if ($contractor->contractorProfile && $contractor->contractorProfile->foto_profile)
                                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}">
                                        <img src="{{ Storage::url($contractor->contractorProfile->foto_profile) }}" alt="Foto Profile" class="item-image">
                                    </a>
                                @else
                                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}">
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Foto Default" class="profile-photo">
                                    </a>
                                @endif
                            </div>
                            <h2>
                                <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}" class="contractor-link">
                                    {{ $contractor->name }}
                                    @if ($contractor->contractorProfile && $contractor->contractorProfile->nama_panggilan)
                                        ({{ $contractor->contractorProfile->nama_panggilan }})
                                    @endif
                                </a>
                            </h2>
                            <p class="rating">
                                @php
                                    $reviews = $contractor->reviews;
                                    $averageRating = $reviews->avg('rating');
                                @endphp
                                @if ($reviews->isEmpty())
                                    Belum ada ulasan.
                                @else
                                    {{ number_format($averageRating, 1) }}/5 ({{ $reviews->count() }} ulasan)
                                @endif
                            </p>
                            <p><strong>Perusahaan:</strong> {{ $contractor->contractorProfile->perusahaan ?? 'Tidak diisi' }}</p>
                            <p><strong>Lokasi:</strong> {{ $contractor->contractorProfile->alamat ?? 'Tidak diisi' }}</p>
                            <p><strong>Bidang Usaha:</strong>
                                @if ($contractor->contractorProfile && $contractor->contractorProfile->bidang_usaha && count($contractor->contractorProfile->bidang_usaha) > 0)
                                    {{ implode(', ', $contractor->contractorProfile->bidang_usaha) }}
                                @else
                                    Tidak ada bidang usaha yang diisi.
                                @endif
                            </p>

                            @if (Auth::check() && Auth::user()->role === 'user')
                                <div class="button-group">
                                    <a href="{{ route('bookings.create', $contractor->id) }}" class="btn btn-primary">Pesan</a>
                                    <a href="{{ route('chats.index', $contractor->id) }}" class="btn btn-secondary">Chat</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="back-link">
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>
    </div>

    <style>
        /* Contractors Section */
        .contractors-section {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .contractors-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Search and Filter Form */
        .search-filter-form {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        /* Contractor Grid */
        .contractor-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Contractor Card */
        .contractor-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .contractor-card:hover {
            transform: translateY(-5px);
        }

        .contractor-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .profile-photo,
        .item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
            transition: transform 0.3s ease;
        }

        .profile-photo:hover,
        .item-image:hover {
            transform: scale(1.05);
        }

        .contractor-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            text-align: center;
            margin-bottom: 10px;
        }

        .contractor-link {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .contractor-link:hover {
            text-decoration: underline;
        }

        .contractor-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .rating {
            font-weight: 500;
            color: #6b5848;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
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

        .btn-reset {
            background-color: transparent;
            border: 1px solid #d4c8b5;
            color: #6b5848;
        }

        .btn-reset:hover {
            background-color: #f8f1e9;
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

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .contractor-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .contractors-section {
                padding: 20px;
                margin: 20px;
            }

            .contractors-section h1 {
                font-size: 28px;
            }

            .contractor-grid {
                grid-template-columns: 1fr;
            }

            .search-filter-form {
                flex-direction: column;
                gap: 10px;
            }

            .form-group {
                min-width: 100%;
            }

            .profile-photo,
            .item-image {
                width: 80px;
                height: 80px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

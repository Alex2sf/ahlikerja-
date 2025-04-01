@extends('layouts.app')

@section('title', 'Pesanan untuk Saya (Kontraktor)')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="booking-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Pesanan untuk Saya (Kontraktor)</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Booking Content -->
        <div class="booking-content">
            @if ($bookings->isEmpty())
                <p class="empty-message">Tidak ada pesanan untuk Anda.</p>
            @else
                <div class="booking-grid">
                    @foreach ($bookings as $booking)
                        <div class="booking-card">
                            <h2 class="booking-title">{{ $booking->judul }}</h2>
                            <p class="booking-description">Deskripsi: {{ $booking->deskripsi }}</p>
                            @if ($booking->gambar && count($booking->gambar) > 0)
                                <h3 class="image-title">Gambar:</h3>
                                <div class="image-grid">
                                    @foreach ($booking->gambar as $gambar)
                                        <a href="{{ Storage::url($gambar) }}" target="_blank">
                                            <img src="{{ Storage::url($gambar) }}" alt="Portofolio" class="booking-image">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <p class="booking-detail"><strong>Lokasi:</strong> {{ $booking->lokasi }}</p>
                            <p class="booking-detail"><strong>Estimasi Anggaran:</strong> Rp {{ number_format($booking->estimasi_anggaran, 2, ',', '.') }}</p>
                            <p class="booking-detail"><strong>Durasi:</strong> {{ $booking->durasi }}</p>
                            <div class="user-info">
                                @if ($booking->user->profile && $booking->user->profile->foto_profile)
                                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                                        <img src="{{ Storage::url($booking->user->profile->foto_profile) }}" alt="Foto Profile" class="user-image">
                                    </a>
                                @else
                                    <div class="no-image">Tidak ada foto</div>
                                @endif
                                <p class="user-name">
                                    User:
                                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                                        {{ $booking->user->name }}
                                        @if ($booking->user->profile && $booking->user->profile->nama_panggilan)
                                            ({{ $booking->user->profile->nama_panggilan }})
                                        @endif
                                    </a>
                                </p>
                            </div>
                            <p class="booking-detail"><strong>Status:</strong>
                                <span class="status {{ $booking->status }}">
                                    {{ $booking->status }}
                                </span>
                            </p>
                            @if ($booking->status === 'pending')
                                <div class="status-buttons">
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-accept">Terima</button>
                                    </form>
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit" class="btn btn-decline" onclick="return confirm('Yakin ingin menolak pesanan ini?')">Tolak</button>
                                    </form>
                                </div>
                            @endif
                            <p class="booking-detail"><strong>Dibuat pada:</strong> {{ $booking->created_at->format('d F Y') }}</p>
                            <div class="decoration-line"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Back Link -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div>
    </div>

    <style>
        /* General Container */
        .containers {
            width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Booking Header */
        .booking-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #e0d8c9;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
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

        /* Booking Content */
        .booking-content {
            margin-bottom: 20px;
        }

        .empty-message {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #6b5848;
            text-align: center;
            padding: 20px;
            background-color: #fdfaf6;
            border-radius: 8px;
            border: 1px solid #e0d8c9;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .booking-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            margin-bottom: 0; /* Hapus margin bottom untuk grid */
        }

        .booking-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #5a3e36;
            margin-bottom: 15px;
        }

        .image-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        .booking-description {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .booking-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .booking-image:hover {
            transform: scale(1.05);
        }

        .booking-detail {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .user-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #a8c3b8;
            transition: transform 0.3s ease;
        }

        .user-image:hover {
            transform: scale(1.05);
        }

        .no-image {
            width: 80px;
            height: 80px;
            background-color: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b5848;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
        }

        .user-name {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status.pending {
            color: #fff;
            background-color: #ffc107;
        }

        .status.accepted {
            color: #fff;
            background-color: #28a745;
        }

        .status.declined {
            color: #fff;
            background-color: #dc3545;
        }

        .status-buttons {
            margin: 15px 0;
            display: flex;
            gap: 10px;
        }

        /* Decoration Line */
        .decoration-line {
            height: 1px;
            background-color: #d4c8b5;
            margin-top: 15px;
            opacity: 0.7;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-accept {
            background-color: #28a745;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-decline {
            background-color: #dc3545;
        }

        .btn-decline:hover {
            background-color: #c82333;
        }

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
            }

            .booking-grid {
                grid-template-columns: 1fr; /* Satu kolom di layar kecil */
            }

            .booking-card {
                padding: 15px;
            }

            .booking-title {
                font-size: 18px;
            }

            .image-title {
                font-size: 16px;
            }

            .image-grid {
                grid-template-columns: 1fr; /* Satu kolom di layar kecil */
            }

            .booking-image {
                height: 150px;
            }

            .booking-description {
                font-size: 13px;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .user-image,
            .no-image {
                width: 60px;
                height: 60px;
            }

            .no-image {
                font-size: 12px;
            }

            .booking-detail {
                font-size: 13px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .status-buttons {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
@endsection

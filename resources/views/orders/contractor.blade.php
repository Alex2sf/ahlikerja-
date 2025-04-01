@extends('layouts.app')

@section('title', 'Keranjang Pemesanan Saya (Kontraktor)')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="order-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Keranjang Pemesanan Saya (Kontraktor)</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Order Content -->
        <div class="order-content">
            @if ($orders->isEmpty())
                <p class="empty-message">Tidak ada pemesanan untuk Anda.</p>
            @else
                <div class="order-grid">
                    @foreach ($orders as $order)
                        <div class="order-card">
                            <h2 class="order-title">{{ $order->post->judul }}</h2>
                            <div class="user-info">
                                @if ($order->user->profile && $order->user->profile->foto_profile)
                                    <a href="{{ route('user.profile.show', $order->user->id) }}">
                                        <img src="{{ Storage::url($order->user->profile->foto_profile) }}" alt="Foto Profile" class="user-image">
                                    </a>
                                @else
                                    <div class="no-image">Tidak ada foto</div>
                                @endif
                                <p class="user-name">
                                    User:
                                    <a href="{{ route('user.profile.show', $order->user->id) }}">
                                        {{ $order->user->name }}
                                        @if ($order->user->profile && $order->user->profile->nama_panggilan)
                                            ({{ $order->user->profile->nama_panggilan }})
                                        @endif
                                    </a>
                                </p>
                            </div>
                            <p class="order-detail"><strong>Lokasi:</strong> {{ $order->post->lokasi }}</p>
                            <p class="order-detail"><strong>Durasi:</strong> {{ $order->post->durasi }}</p>
                            <p class="order-detail"><strong>Dibuat pada:</strong> {{ $order->created_at->format('d F Y') }}</p>
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

        /* Order Header */
        .order-header {
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

        /* Order Content */
        .order-content {
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

        .order-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .order-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            margin-bottom: 0; /* Hapus margin bottom untuk grid */
        }

        .order-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #5a3e36;
            margin-bottom: 15px;
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

        .order-detail {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #5a3e36;
            margin-bottom: 10px;
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
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
            }

            .order-grid {
                grid-template-columns: 1fr; /* Satu kolom di layar kecil */
            }

            .order-card {
                padding: 15px;
            }

            .order-title {
                font-size: 18px;
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

            .order-detail {
                font-size: 13px;
            }

            .btn {
                padding: 8px 15px;
                font-size: 12px;
            }
        }
    </style>
@endsection

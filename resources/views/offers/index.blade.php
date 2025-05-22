@extends('layouts.app')

@section('title', 'Daftar Penawaran untuk Postingan')

@section('content')
    <div class="container">
        <div class="offers-section">
            <h1>Daftar Penawaran untuk Postingan: {{ $post->judul }}</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="notification error">{{ session('error') }}</div>
            @endif

            @if ($offers->isEmpty())
                <p class="text-center text-muted">Tidak ada penawaran untuk postingan ini.</p>
            @else
                @foreach ($offers as $offer)
                    <div class="offer-card">
                        <div class="offer-header">
                            @if ($offer->contractor->contractorProfile && $offer->contractor->contractorProfile->foto_profile)
                                <img src="{{ Storage::url($offer->contractor->contractorProfile->foto_profile) }}" alt="Foto Profil" class="profile-image">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto Profil Default" class="profile-image">
                            @endif

                            <div class="offer-info">
                                <h2>Kontraktor:
                                    <a href="{{ route('contractor.profile.showPublic', $offer->contractor->id) }}">
                                        {{ $offer->contractor->name }}
                                        @if ($offer->contractor->contractorProfile && $offer->contractor->contractorProfile->nama_panggilan)
                                            ({{ $offer->contractor->contractorProfile->nama_panggilan }})
                                        @endif
                                    </a>
                                </h2>
                                <p>Status:
                                    @if ($offer->status === 'accepted')
                                        <span class="status accepted">Diterima</span>
                                    @elseif ($offer->status === 'not_selected')
                                        <span class="status not-selected">Tidak Terpilih</span>
                                    @else
                                        <span class="status pending">Menunggu Persetujuan</span>
                                    @endif
                                </p>
                                <small>Ditawarkan pada: {{ $offer->created_at->format('d F Y H:i') }}</small>
                            </div>
                        </div>
                        @if (!$acceptedOffer && Auth::user()->id === $post->user_id)
                            <div class="button-group mt-3">
                                <form action="{{ route('offers.accept', $offer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Terima Penawaran</button>
                                </form>
                            </div>
                        @elseif ($offer->status === 'accepted')
                            <p class="text-muted mt-2">Penawaran ini telah diterima dan ditambahkan ke keranjang pemesanan.</p>
                        @endif
                    </div>
                @endforeach
            @endif

            <div class="back-link">
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>
    </div>

    <style>
        /* Offers Section */
        .offers-section {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .offers-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 25px;
        }

        /* Offer Card */
        .offer-card {
            background-color: #fdfaf6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .offer-card:hover {
            transform: translateY(-4px);
        }

        /* Offer Header */
        .offer-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
            transition: transform 0.3s ease;
        }

        .profile-image:hover {
            transform: scale(1.05);
        }

        .offer-info h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #5a3e36;
            margin-bottom: 5px;
        }

        .offer-info p {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .offer-info a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .offer-info a:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        .status {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.accepted {
            background-color: #d4edda;
            color: #155724;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.not-selected {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-success {
            background-color: #a8c3b8;
        }

        .btn-success:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Notification */
        .notification {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 13px;
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

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .offers-section {
                padding: 15px;
                margin: 15px;
            }

            .offers-section h1 {
                font-size: 24px;
            }

            .offer-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .profile-image {
                width: 60px;
                height: 60px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .btn {
                width: 100%;
                text-align: center;
                padding: 5px 10px;
                font-size: 12px;
            }
        }
    </style>
@endsection

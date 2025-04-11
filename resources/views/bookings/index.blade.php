@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="container">
        <div class="bookings-section">
            <h1>Pesanan Saya</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif

            @if ($bookings->isEmpty())
                <p class="text-center text-muted">Tidak ada pesanan.</p>
            @else
                <div class="bookings-list">
                    @foreach ($bookings as $booking)
                        <div class="booking-card">
                            <h2>{{ $booking->judul }}</h2>
                            <p>{{ Str::limit($booking->deskripsi, 150) }}</p>
                            @if ($booking->gambar && count($booking->gambar) > 0)
                                <h3>Gambar:</h3>
                                <div class="booking-images">
                                    @foreach ($booking->gambar as $gambar)
                                        <img src="{{ Storage::url($gambar) }}" alt="Gambar Pesanan" class="booking-image">
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Tidak ada gambar.</p>
                            @endif
                            @if ($booking->dokumen)
                                <p><strong>Dokumen dari Anda:</strong> <a href="{{ Storage::url($booking->dokumen) }}" target="_blank">Lihat Dokumen</a></p>
                            @else
                                <p class="text-muted">Tidak ada dokumen dari Anda.</p>
                            @endif
                            @if ($booking->response_file)
                            <p><strong>File Balasan:</strong> <a href="{{ Storage::url($booking->response_file) }}" target="_blank">Lihat File Balasan</a></p>
                            @else
                                <p class="text-muted">Tidak ada file balasan.</p>
                            @endif

                            <p><strong>Lokasi:</strong> {{ $booking->lokasi }}</p>
                            <p><strong>Estimasi Anggaran:</strong> Rp {{ number_format($booking->estimasi_anggaran, 2, ',', '.') }}</p>
                            <p><strong>Durasi:</strong> {{ $booking->durasi }}</p>
                            <div class="contractor-info">
                                @if ($booking->contractor->contractorProfile && $booking->contractor->contractorProfile->foto_profile)
                                    <a href="{{ route('contractor.profile.showPublic', $booking->contractor->id) }}">
                                        <img src="{{ Storage::url($booking->contractor->contractorProfile->foto_profile) }}" alt="Foto Profil" class="profile-photo">
                                    </a>
                                @else
                                    <a href="{{ route('contractor.profile.showPublic', $booking->contractor->id) }}">
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Foto Profil Default" class="profile-photo">
                                    </a>
                                @endif
                                <p>Kontraktor:
                                    <a href="{{ route('contractor.profile.showPublic', $booking->contractor->id) }}" class="contractor-link">
                                        {{ $booking->contractor->contractorProfile ? $booking->contractor->contractorProfile->perusahaan : 'Perusahaan belum diatur' }}
                                        @if ($booking->contractor->contractorProfile && $booking->contractor->contractorProfile->nama_panggilan)
                                            ({{ $booking->contractor->contractorProfile->nama_panggilan }})
                                        @endif
                                    </a>
                                </p>
                            </div>
                            <p>Status: <span class="status {{ $booking->status }}">{{ $booking->status }}</span></p>
                            @if ($booking->status === 'declined' && $booking->decline_reason)
                                <p class="decline-reason"><strong>Alasan Penolakan:</strong> {{ $booking->decline_reason }}</p>
                            @endif
                            <p><small>Dibuat pada: {{ $booking->created_at->format('d F Y') }}</small></p>
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
        /* Bookings Section */
        .bookings-section {
            max-width: 1200px; /* Perluas untuk 2 kolom */
            margin: 30px auto;
            background-color: #fff;
            padding: 20px; /* Kurangi dari 25px */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .bookings-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 20px; /* Kurangi dari 25px */
        }

        /* Bookings List */
        .bookings-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 kolom */
            gap: 20px; /* Jarak antar kartu */
        }

        /* Booking Card */
        .booking-card {
            background-color: #fdfaf6;
            padding: 12px; /* Kurangi dari 15px */
            border-radius: 8px;
            margin-bottom: 0; /* Hapus margin bawah */
            transition: transform 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-4px);
        }

        .booking-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #5a3e36;
            margin-bottom: 8px;
        }

        .booking-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .booking-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 8px;
        }

        .booking-images {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .booking-image {
            width: 130px; /* Kurangi dari 140px */
            height: 130px; /* Kurangi dari 140px */
            object-fit: cover;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        .booking-image:hover {
            transform: scale(1.03);
        }

        /* Contractor Info */
        .contractor-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .profile-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
            transition: transform 0.3s ease;
        }

        .profile-photo:hover {
            transform: scale(1.05);
        }

        .contractor-link {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .contractor-link:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        /* Status */
        .status {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.accepted {
            background-color: #d4edda;
            color: #155724;
        }

        .status.declined {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Decline Reason */
        .decline-reason {
            font-size: 14px;
            color: #721c24;
            margin-bottom: 8px;
            background-color: #f8d7da;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }

        /* Notification */
        .notification.success {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 13px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Link Dokumen */
        a[href$=".pdf"], a[href$=".doc"], a[href$=".docx"] {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        a[href$=".pdf"]:hover, a[href$=".doc"]:hover, a[href$=".docx"]:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .bookings-section {
                padding: 15px;
                margin: 15px;
            }

            .bookings-section h1 {
                font-size: 24px;
            }

            .bookings-list {
                grid-template-columns: 1fr; /* 1 kolom di layar kecil */
            }

            .booking-card h2 {
                font-size: 18px;
            }

            .booking-image {
                width: 100px;
                height: 100px;
            }

            .contractor-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .profile-photo {
                width: 60px;
                height: 60px;
            }

            .btn {
                width: 100%;
                text-align: center;
                padding: 5px 10px;
                font-size: 12px;
            }

            .decline-reason {
                font-size: 13px;
                padding: 6px;
            }
        }
    </style>
@endsection

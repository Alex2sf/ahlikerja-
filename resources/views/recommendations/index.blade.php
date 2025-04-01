@extends('layouts.app')

@section('title', 'Rekomendasi Kontraktor')

@section('content')
    <div class="container">
        <div class="recommendations-section">
            <h1>Rekomendasi Kontraktor untuk Anda</h1>

            @if (empty($recommendedContractors))
                <p class="text-center text-muted">Tidak ada rekomendasi saat ini.</p>
            @else
                <div class="contractor-grid">
                    @foreach ($recommendedContractors as $contractor)
                        <div class="contractor-card">
                            <div class="contractor-header">
                                @if ($contractor->contractorProfile && $contractor->contractorProfile->foto_profile)
                                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}">
                                        <img src="{{ Storage::url('contractors/' . $contractor->contractorProfile->foto_profile) }}" alt="Foto Profil" class="profile-photo">
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
                                </a>
                            </h2>
                            <p class="bidang-usaha">
                                Bidang Usaha:
                                @if ($contractor->contractorProfile && $contractor->contractorProfile->bidang_usaha && count($contractor->contractorProfile->bidang_usaha) > 0)
                                    {{ implode(', ', $contractor->contractorProfile->bidang_usaha) }}
                                @else
                                    Tidak diisi
                                @endif
                            </p>
                            <div class="button-group">
                                <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}" class="btn btn-primary">Lihat Profil</a>
                                <a href="{{ route('bookings.create', $contractor->id) }}" class="btn btn-success">Pesan</a>
                                <a href="{{ route('chats.index', $contractor->id) }}" class="btn btn-secondary">Chat</a>
                            </div>
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
        /* Recommendations Section */
        .recommendations-section {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .recommendations-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Contractor Grid */
        .contractor-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Contractor Card */
        .contractor-card {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .contractor-card:hover {
            transform: translateY(-5px);
        }

        .contractor-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
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

        .bidang-usaha {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
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

        .btn-success {
            background-color: #a8c3b8;
        }

        .btn-success:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5; /* Beige */
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
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
            .recommendations-section {
                padding: 20px;
                margin: 20px;
            }

            .recommendations-section h1 {
                font-size: 28px;
            }

            .contractor-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="profile-container">
        <!-- Notifikasi -->
        @if (session('info'))
            <div class="notification info">{{ session('info') }}</div>
        @endif
        @if (session('success'))
            <div class="notification success">{{ session('success') }}</div>
        @endif

        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ $profile && $profile->foto_profile ? asset('storage/' . $profile->foto_profile) : asset('images/default-profile.png') }}"
                     alt="Foto Profil {{ $profile->nama_lengkap ?? 'User' }}">
                <div class="avatar-overlay">
                    <a href="{{ route('profile.edit') }}" class="edit-avatar-btn">Ubah Foto</a>
                </div>
            </div>
            <div class="profile-title">
                <h1>{{ $profile->nama_lengkap }}</h1>
                <p class="profile-subtitle">{{ $profile->nama_panggilan ?? '' }}</p>
                <div class="profile-meta">
                    @if($profile->jenis_kelamin)
                        <span class="meta-item"><i class="fas fa-venus-mars"></i> {{ $profile->jenis_kelamin }}</span>
                    @endif
                    @if($profile->tanggal_lahir)
                        <span class="meta-item"><i class="fas fa-birthday-cake"></i> {{ $profile->tanggal_lahir->format('d F Y') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-bio">
            <h3 class="section-title"><i class="fas fa-quote-left"></i> Tentang Saya</h3>
            <p>{{ $profile->bio ?? 'Belum ada bio.' }}</p>
        </div>

        <div class="profile-sections">
            <div class="profile-section">
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Informasi Pribadi</h3>
                <div class="profile-details">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Tempat Lahir</span>
                        <span class="detail-value">{{ $profile->tempat_lahir ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-home"></i> Alamat</span>
                        <span class="detail-value">{{ $profile->alamat_lengkap ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-phone"></i> Telepon</span>
                        <span class="detail-value">{{ $profile->nomor_telepon ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-envelope"></i> Email</span>
                        <span class="detail-value">{{ $profile->email }}</span>
                    </div>
                </div>
            </div>

            @if ($profile->media_sosial && count($profile->media_sosial) > 0)
                <div class="profile-section">
                    <h3 class="section-title"><i class="fas fa-share-alt"></i> Media Sosial</h3>
                    <div class="social-media">
                        @foreach ($profile->media_sosial as $media)
                            <a href="#" class="social-item">{{ $media }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="profile-actions">
            <a href="{{ route('profile.edit') }}" class="btn edit-btn"><i class="fas fa-edit"></i> Edit Profil</a>
            <a href="{{ route('home') }}" class="btn back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <style>
        /* Base Styles */
        .profile-container {
            width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: #5A3E36;
            background-color: #FDFAF6;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* Notification */
        .notification {
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .notification.info {
            background-color: #CCE5FF;
            color: #004085;
            border-left: 4px solid #B8DAFF;
        }

        .notification.success {
            background-color: #D4EDDA;
            color: #155724;
            border-left: 4px solid #C3E6CB;
        }

        .notification i {
            margin-right: 0.5rem;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
            gap: 2rem;
        }

        .profile-avatar {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #E0D8C9;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(90, 62, 54, 0.7);
            padding: 0.5rem;
            text-align: center;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover .avatar-overlay {
            transform: translateY(0);
        }

        .edit-avatar-btn {
            color: white;
            font-size: 0.7rem;
            text-decoration: none;
        }

        .profile-title h1 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0 0 0.5rem;
            color: #5A3E36;
        }

        .profile-subtitle {
            font-size: 1.1rem;
            color: #8B4513;
            margin: 0 0 1rem;
            font-weight: 500;
        }

        .profile-meta {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .meta-item {
            font-size: 0.85rem;
            color: #6B5848;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Profile Bio */
        .profile-bio {
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #CD853F;
        }

        .profile-bio p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #6B5848;
            margin: 0.5rem 0 0;
        }

        /* Profile Sections */
        .profile-sections {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .profile-section {
            background-color: #FFFFFF;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.1rem;
            color: #8B4513;
            margin: 0 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #E0D8C9;
        }

        /* Profile Details */
        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.2rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #6B5848;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #5A3E36;
            font-weight: 500;
            padding-left: 1.4rem;
        }

        /* Social Media */
        .social-media {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .social-item {
            background-color: #FDFAF6;
            color: #5A3E36;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid #E0D8C9;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-item:hover {
            background-color: #E0D8C9;
            color: #5A3E36;
        }

        /* Profile Actions */
        .profile-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 3rem;
        }

        .btn {
            padding: 0.75rem 1.75rem;
            border-radius: 30px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .edit-btn {
            background-color: #8B4513;
            color: white;
            box-shadow: 0 2px 8px rgba(139, 69, 19, 0.2);
        }

        .edit-btn:hover {
            background-color: #6B5848;
            transform: translateY(-2px);
        }

        .back-btn {
            background-color: #FDFAF6;
            color: #8B4513;
            border: 1px solid #E0D8C9;
        }

        .back-btn:hover {
            background-color: #E0D8C9;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .profile-meta {
                justify-content: center;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
@endsection

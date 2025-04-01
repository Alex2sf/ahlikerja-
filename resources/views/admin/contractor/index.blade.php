@extends('layouts.app')

@section('title', 'Daftar Kontraktor Menunggu Persetujuan')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <h1>Daftar Kontraktor Menunggu Persetujuan</h1>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Daftar Kontraktor -->
        @if ($contractors->isEmpty())
            <p class="text-muted text-center">Tidak ada kontraktor yang menunggu persetujuan.</p>
        @else
            <div class="contractors-list">
                @foreach ($contractors as $contractor)
                    <div class="contractor-card">
                        <h2>
                            <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}" class="contractor-name">
                                {{ $contractor->user->name }}
                            </a>
                        </h2>
                        @if ($contractor->foto_profile)
                            <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}">
                                <img src="{{ Storage::url($contractor->foto_profile) }}" alt="Foto Profil" class="profile-preview">
                            </a>
                        @else
                            <p class="text-muted">Tidak ada foto profil.</p>
                        @endif
                        <div class="contractor-details">
                            <p><strong>Nama Lengkap:</strong> {{ $contractor->nama_depan }} {{ $contractor->nama_belakang }}</p>
                            <p><strong>Perusahaan:</strong> {{ $contractor->perusahaan }}</p>
                            <p><strong>Nomor NPWP:</strong> {{ $contractor->nomor_npwp }}</p>
                            <p><strong>Bidang Usaha:</strong>
                                @if ($contractor->bidang_usaha && count($contractor->bidang_usaha) > 0)
                                    @foreach ($contractor->bidang_usaha as $usaha)
                                        {{ $usaha }};
                                    @endforeach
                                @else
                                    Tidak diisi
                                @endif
                            </p>

                            <!-- Dokumen Pendukung -->
                            @if ($contractor->dokumen_pendukung && count($contractor->dokumen_pendukung) > 0)
                                <div class="document-section">
                                    <strong>Dokumen Pendukung:</strong>
                                    <ul class="document-list">
                                        @foreach ($contractor->dokumen_pendukung as $dokumen)
                                            <li>
                                                <a href="{{ Storage::url($dokumen) }}" target="_blank" class="item-link">
                                                    {{ basename($dokumen) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Gambar Data Diri -->
                            @if (!empty($contractor->identity_images) && count($contractor->identity_images) > 0)
                                <div class="document-section">
                                    <strong>Gambar Data Diri:</strong>
                                    <ul class="document-list">
                                        @foreach ($contractor->identity_images as $image)
                                        <li>
                                            <a href="{{ Storage::url($image) }}" target="_blank">
                                                <img src="{{ Storage::url($image) }}" alt="Image" class="item-image">
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Portofolio -->
                            @if ($contractor->portofolio && count($contractor->portofolio) > 0)
                                <div class="document-section">
                                    <strong>Portofolio:</strong>
                                    <ul class="document-list">
                                        @foreach ($contractor->portofolio as $port)
                                            <li>
                                                <a href="{{ Storage::url($port) }}" target="_blank">
                                                    <img src="{{ Storage::url($port) }}" alt="Portofolio" class="item-image">
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Form Keputusan -->
                            <form action="{{ route('admin.contractors.approve', $contractor->id) }}" method="POST" class="decision-form">
                                @csrf
                                <div class="decision-options">
                                    <label>
                                        <input type="radio" name="approved" value="1" required> Setujui
                                    </label>
                                    <label>
                                        <input type="radio" name="approved" value="0"> Tolak
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Catatan (opsional):</label>
                                    <textarea name="admin_note" placeholder="Tulis catatan untuk kontraktor..." class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                            </form>
                        </div>
                        <hr class="divider">
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Tombol Kembali -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div>
    </div>

    <style>
        /* General Container */
        .containers {
            max-width: 1200px;
            width: 100%; /* Responsif */
            margin: 0 auto;
            padding: 20px; /* Kurangi padding */
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px; /* Kurangi ukuran */
            color: #5a3e36;
            text-align: center;
            margin-bottom: 20px; /* Kurangi margin */
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; /* Kurangi ukuran */
            color: #6b5848;
            margin-bottom: 10px; /* Kurangi margin */
        }

        p, label, li {
            font-family: 'Roboto', sans-serif;
            font-size: 14px; /* Kurangi ukuran */
            color: #555;
        }

        .text-muted {
            color: #6b5848;
        }

        /* Notification */
        .notification.success {
            padding: 12px; /* Kurangi padding */
            border-radius: 6px; /* Kurangi radius */
            margin-bottom: 15px; /* Kurangi margin */
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            font-size: 14px; /* Sesuaikan ukuran teks */
        }

        /* Contractors List */
        .contractors-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px; /* Kurangi gap */
        }

        .contractor-card {
            background-color: #fdfaf6;
            padding: 15px; /* Kurangi padding */
            border-radius: 8px; /* Kurangi radius */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); /* Kurangi shadow */
            border: 1px solid #e0d8c9;
            transition: transform 0.3s ease;
        }

        .contractor-card:hover {
            transform: translateY(-3px); /* Kurangi efek hover */
        }

        .contractor-name {
            color: #5a3e36;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contractor-name:hover {
            color: #a8c3b8;
            text-decoration: underline;
        }

        .profile-preview {
            width: 80px; /* Kurangi ukuran */
            height: 80px; /* Kurangi ukuran */
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
            margin: 8px 0; /* Kurangi margin */
        }

        .contractor-details {
            margin-top: 10px; /* Kurangi margin */
        }

        .contractor-details p {
            margin-bottom: 8px; /* Kurangi margin */
        }

        .document-section {
            margin-top: 10px; /* Kurangi margin */
        }

        .document-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px; /* Kurangi gap */
        }

        .item-image {
            width: 120px; /* Kurangi ukuran */
            height: 120px; /* Kurangi ukuran */
            object-fit: cover;
            border-radius: 4px; /* Kurangi radius */
            transition: transform 0.3s ease;
        }

        .item-image:hover {
            transform: scale(1.03); /* Kurangi efek hover */
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

        /* Decision Form */
        .decision-form {
            margin-top: 15px; /* Kurangi margin */
            padding: 12px; /* Kurangi padding */
            background-color: #fff;
            border-radius: 6px; /* Kurangi radius */
            border: 1px solid #e0d8c9;
        }

        .decision-options {
            display: flex;
            gap: 12px; /* Kurangi gap */
            margin-bottom: 8px; /* Kurangi margin */
        }

        .form-group {
            margin-bottom: 12px; /* Kurangi margin */
        }

        .form-control {
            width: 100%;
            padding: 8px; /* Kurangi padding */
            border: 1px solid #d4c8b5;
            border-radius: 4px; /* Kurangi radius */
            font-size: 14px;
            color: #555;
            background-color: #fff;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 6px 12px; /* Kurangi padding */
            border-radius: 4px; /* Kurangi radius */
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

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 20px; /* Kurangi margin */
        }

        /* Divider */
        .divider {
            border: 0;
            height: 1px;
            background: #e0d8c9;
            margin: 15px 0; /* Kurangi margin */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contractors-list {
                grid-template-columns: 1fr;
            }

            .contractor-card {
                padding: 12px; /* Kurangi padding */
            }

            .profile-preview {
                width: 60px; /* Kurangi ukuran */
                height: 60px; /* Kurangi ukuran */
            }

            .item-image {
                width: 90px; /* Kurangi ukuran */
                height: 90px; /* Kurangi ukuran */
            }

            .decision-options {
                flex-direction: column;
                gap: 8px; /* Kurangi gap */
            }

            h1 {
                font-size: 24px; /* Kurangi ukuran */
            }

            h2 {
                font-size: 18px; /* Kurangi ukuran */
            }
        }
    </style>
@endsection

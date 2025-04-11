@extends('layouts.app')

@section('title', 'Edit Profil Kontraktor')

@section('content')
    <div class="container">
        <!-- Header Section -->
        <div class="profile-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Edit Profil Kontraktor</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="notification info">
                {{ session('info') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="profile-card">
            <form method="POST" action="{{ route('contractor.profile.update') }}" enctype="multipart/form-data" class="edit-form">
                @csrf
                <div class="form-group">
                    <label for="foto_profile">Foto Profil:</label>
                    @if ($profile->foto_profile)
                    <img src="{{ Storage::url($profile->foto_profile) }}" alt="Foto Profil" class="profile-preview">
                @else
                    <div class="no-image">Tidak ada foto profil</div>
                @endif
                    <input type="file" name="foto_profile" id="foto_profile">
                    @error('foto_profile')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="perusahaan">Perusahaan:</label>
                    <input type="text" name="perusahaan" id="perusahaan" value="{{ old('perusahaan', $profile->perusahaan) }}" required>
                    @error('perusahaan')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="nomor_telepon">Nomor Telepon:</label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $profile->nomor_telepon) }}">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <textarea name="alamat" id="alamat">{{ old('alamat', $profile->alamat) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="bio">Bio:</label>
                    <textarea name="bio" id="bio" rows="4" placeholder="Tulis bio Anda...">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>



                <div class="form-group">
                    <label for="nomor_npwp">Nomor NPWP:</label>
                    <input type="text" name="nomor_npwp" id="nomor_npwp" value="{{ old('nomor_npwp', $profile->nomor_npwp) }}" required>
                    @error('nomor_npwp')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Bidang Usaha (maks 10):</label>
                    @for ($i = 0; $i < 10; $i++)
                        <input type="text" name="bidang_usaha[]" value="{{ old('bidang_usaha.' . $i, $profile->bidang_usaha[$i] ?? '') }}" placeholder="Bidang Usaha {{ $i + 1 }}">
                    @endfor
                </div>

                <div class="form-group">
                    <label for="dokumen_pendukung">Dokumen Pendukung (unggah multiple):</label>
                    <input type="file" name="dokumen_pendukung[]" id="dokumen_pendukung" multiple>
                    {{-- @if ($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
                        <h4>Dokumen Pendukung Saat Ini:</h4>
                        <ul class="document-list">
                            @foreach ($profile->dokumen_pendukung as $doc)
                                <li><a href="{{ Storage::url('contractors/documents/' . $doc) }}" target="_blank">{{ basename($doc) }}</a></li>
                            @endforeach
                        </ul>
                    @endif --}}
                </div>

                <div class="form-group">
                    <label for="portofolio">Portofolio (unggah multiple):</label>
                    <input type="file" name="portofolio[]" id="portofolio" multiple>
                    {{-- @if ($profile->portofolio && count($profile->portofolio) > 0)
                        <h4>Portofolio Saat Ini:</h4>
                        <ul class="portfolio-grid">
                            @foreach ($profile->portofolio as $port)
                                @php
                                    $extension = strtolower(pathinfo($port, PATHINFO_EXTENSION));
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    $url = Storage::url('contractors/portfolios/' . $port);
                                @endphp
                                <li>
                                    @if (in_array($extension, $imageExtensions))
                                        <a href="{{ $url }}" target="_blank">
                                            <img src="{{ $url }}" alt="Portofolio" class="item-image">
                                        </a>
                                    @else
                                        <a href="{{ $url }}" target="_blank" class="item-link">{{ basename($port) }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif --}}
                </div>

                <div class="form-group">
                    <label for="identity_images">GAMBAR DATA DIRI (unggah multiple):</label>
                    <input type="file" name="identity_images[]" id="identity_images" multiple>
                    {{-- @if ($profile->identity_images && count($profile->identity_images) > 0)
                        <h4>Gambar Data Diri Saat Ini:</h4>
                        <ul class="image-list">
                            @foreach ($profile->identity_images as $image)
                                <li>
                                    <a href="{{ Storage::url($image) }}" target="_blank">
                                        <img src="{{ Storage::url($image) }}" alt="Gambar Data Diri" class="item-image">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif --}}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

            <!-- Tombol Kembali -->
            <div class="back-link">
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
            </div>
        </div>
    </div>

    <style>
        /* General Container */
        .container {
            max-width: 1200px;
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

        h4 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-top: 15px;
            margin-bottom: 10px;
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

        /* Profile Card */
        .profile-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="file"], textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="text"]:focus, input[type="file"]:focus, textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .preview-image {
            margin-bottom: 10px;
        }

        .profile-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
        }

        .document-list, .portfolio-grid, .image-list {
            list-style: none;
            padding: 0;
        }

        .portfolio-grid, .image-list {
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

        .error {
            color: #dc3545;
            font-size: 14px;
            display: block;
            margin-top: 5px;
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

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .portfolio-grid, .image-list {
                grid-template-columns: repeat(2, 1fr); /* 2 kolom di layar kecil */
            }

            .item-image {
                height: 120px;
            }

            h1 {
                font-size: 28px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            input[type="text"], textarea {
                font-size: 14px;
            }
        }
    </style>
@endsection

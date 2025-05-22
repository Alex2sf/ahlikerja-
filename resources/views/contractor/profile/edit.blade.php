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
                    <!-- Label dan Deskripsi Dokumen Pendukung -->
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 20px; color: #6b5848; margin-bottom: 15px;">
                        Dokumen Pendukung Teknis & Administratif Perusahaan
                    </h3>
                    <div style="font-family: 'Roboto', sans-serif; font-size: 14px; color: #5a3e36; margin-bottom: 20px;">
                        <p>Berikut adalah dokumen pendukung yang diperlukan untuk menunjukkan kesiapan teknis dan administratif perusahaan:</p>
                        <ul style="padding-left: 20px;">
                            <li><strong>Daftar Personel Inti:</strong> Daftar tenaga kerja utama (mandor, tukang ahli, teknisi, dll.) yang dilengkapi dengan sertifikat kompetensi seperti SKA atau SKT untuk meningkatkan kredibilitas.</li>
                            <li><strong>Daftar Peralatan Kerja:</strong> Inventaris alat kerja (scaffolding, concrete mixer, alat bor, dll.) yang dimiliki atau disewa, disertai bukti kepemilikan atau dokumen sewa jika diperlukan.</li>
                            <li><strong>Pengalaman Proyek:</strong> Rekam jejak proyek yang pernah dikerjakan, mencakup nama proyek, lokasi, tahun, nilai kontrak, dan kontak pemberi kerja.</li>
                            <li><strong>Dokumentasi Proyek:</strong> Foto tahap pre-construction, progres, hingga hasil akhir proyek. Testimoni klien dapat dilampirkan (opsional).</li>
                            <li><strong>Formulir Penawaran:</strong> Dokumen untuk tender, berisi penawaran teknis (metodologi, jadwal, tenaga kerja, alat) dan harga sesuai ketentuan pemberi proyek.</li>
                            <li><strong>Surat Pernyataan Kebenaran Dokumen:</strong> Surat resmi yang menyatakan semua dokumen yang diajukan sah, ditandatangani pimpinan perusahaan dengan stempel resmi.</li>
                        </ul>
                    </div>

                    <!-- Input Dokumen Pendukung -->
                    <label for="dokumen_pendukung">Dokumen Pendukung (unggah multiple):</label>
                    <input type="file" name="dokumen_pendukung[]" id="dokumen_pendukung" multiple>
                    {{-- @if ($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
                        <h4>Dokumen Pendukung Saat Ini:</h4>
                        <ul class="document-list">
                            @foreach ($profile->dokumen_pendukung as $index => $doc)
                                <li>
                                    <a href="{{ Storage::url($doc) }}" target="_blank">{{ basename($doc) }}</a>
                                    <a href="{{ route('contractor.profile.delete', ['type' => 'dokumen', 'index' => $index]) }}" class="delete-link" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">Hapus</a>
                                </li>
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
                            @foreach ($profile->portofolio as $index => $port)
                                @php
                                    $extension = strtolower(pathinfo($port, PATHINFO_EXTENSION));
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    $url = Storage::url($port);
                                @endphp
                                <li>
                                    @if (in_array($extension, $imageExtensions))
                                        <a href="{{ $url }}" target="_blank">
                                            <img src="{{ $url }}" alt="Portofolio" class="item-image">
                                        </a>
                                    @else
                                        <a href="{{ $url }}" target="_blank" class="item-link">{{ basename($port) }}</a>
                                    @endif
                                    <a href="{{ route('contractor.profile.delete', ['type' => 'portofolio', 'index' => $index]) }}" class="delete-link" onclick="return confirm('Yakin ingin menghapus portofolio ini?')">Hapus</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif --}}
                </div>



                <div class="form-group">
                    <!-- Label dan Deskripsi Dokumen Legalitas -->
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 20px; color: #6b5848; margin-bottom: 15px;">
                        Dokumen Legalitas Perusahaan & Perizinan Usaha
                    </h3>
                    <div style="font-family: 'Roboto', sans-serif; font-size: 14px; color: #5a3e36; margin-bottom: 20px;">
                        <p>Berikut adalah dokumen legalitas yang diperlukan untuk memastikan perusahaan Anda terdaftar secara resmi dan dapat beroperasi secara legal:</p>
                        <ul style="padding-left: 20px;">
                            <li><strong>Akta Pendirian Perusahaan:</strong> Dokumen legal yang dibuat oleh notaris, berisi informasi tentang pendirian perusahaan, termasuk nama, tujuan usaha, struktur kepemilikan, dan modal dasar.</li>
                            <li><strong>SK Kemenkumham:</strong> Pengesahan resmi dari Kementerian Hukum dan HAM terhadap Akta Pendirian Perusahaan.</li>
                            <li><strong>NIB (Nomor Induk Berusaha):</strong> Identitas resmi perusahaan yang diterbitkan melalui sistem OSS, menggantikan TDP, SIUP, dan lainnya.</li>
                            <li><strong>NPWP Perusahaan:</strong> Nomor yang menunjukkan perusahaan terdaftar sebagai subjek pajak badan.</li>
                            <li><strong>SKT (Surat Keterangan Terdaftar Pajak):</strong> Bukti pendaftaran aktif perusahaan di kantor pajak.</li>
                            <li><strong>SBU (Sertifikat Badan Usaha):</strong> Sertifikat untuk perusahaan jasa konstruksi, menunjukkan klasifikasi dan kualifikasi usaha.</li>
                            <li><strong>IUJK (Izin Usaha Jasa Konstruksi):</strong> Izin resmi dari pemerintah daerah untuk perusahaan jasa konstruksi.</li>
                            <li><strong>TDP (Nomor Induk Perusahaan Lama):</strong> Kini digantikan oleh NIB, sebelumnya wajib dimiliki setiap badan usaha.</li>
                        </ul>
                    </div>

                    <!-- Input Dokumen Legalitas -->
                    <label for="legalitas">Dokumen Legalitas (unggah multiple, PDF/Word, maks 5MB):</label>
                    <input type="file" name="legalitas[]" id="legalitas" multiple accept=".pdf,.doc,.docx">
                    @error('legalitas.*')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    {{-- @if ($profile->legalitas && count($profile->legalitas) > 0)
                        <h4>Dokumen Legalitas Saat Ini:</h4>
                        <ul class="document-list">
                            @foreach ($profile->legalitas as $index => $doc)
                                <li>
                                    <a href="{{ Storage::url($doc) }}" target="_blank">{{ basename($doc) }}</a>
                                    <a href="{{ route('contractor.profile.delete', ['type' => 'legalitas', 'index' => $index]) }}" class="delete-link" onclick="return confirm('Yakin ingin menghapus dokumen legalitas ini?')">Hapus</a>
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
            margin-bottom: 10px;
        }

        .document-list, .portfolio-grid, .image-list {
            list-style: none;
            padding: 0;
        }

        .document-list li {
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

        .delete-link {
            color: #dc3545;
            text-decoration: none;
            font-size: 12px;
        }

        .delete-link:hover {
            text-decoration: underline;
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

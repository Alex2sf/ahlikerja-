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

        <!-- Tombol Toggle Panduan -->
        <div class="toggle-guide">
            <button id="toggleGuideBtn"
                class="btn"
                style="background-color: #CD853F; /* Coklat muda */
                    color: white;
                    font-weight: 600;
                    padding: 8px 14px;
                    font-size: 0.95rem;
                    border: none;
                    border-radius: 5px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                Tampilkan Panduan
            </button>
        </div>

<!-- Panduan Dokumen -->
<div class="guide-section" id="guideSection" style="display: none;">
    <h2>Panduan Persyaratan untuk Kontraktor</h2>
    <p>Untuk mendapatkan persetujuan sebagai kontraktor, Anda wajib melengkapi data profil dan mengunggah dokumen yang diperlukan. Pastikan semua informasi yang diberikan akurat dan sesuai dengan ketentuan berikut:</p>

    <div class="guide-subsection">
        <h3>1. Data Profil Wajib</h3>
        <p>Informasi berikut harus diisi pada profil Anda untuk memenuhi syarat pendaftaran:</p>
        <ul>
            <li><strong>Foto Profil:</strong> Unggah foto profil profesional yang jelas, menampilkan wajah atau logo perusahaan.</li>
            <li><strong>Nama Perusahaan:</strong> Cantumkan nama resmi perusahaan Anda sesuai dokumen legalitas.</li>
            <li><strong>Nomor Telepon:</strong> Masukkan nomor telepon aktif yang dapat dihubungi untuk komunikasi.</li>
            <li><strong>Alamat:</strong> Berikan alamat lengkap perusahaan, termasuk kota dan kode pos.</li>
            <li><strong>Bio:</strong> Tulis deskripsi singkat (bio) tentang perusahaan, mencakup visi, misi, atau keunggulan layanan (maksimal 500 karakter).</li>
            <li><strong>Nomor NPWP:</strong> Masukkan Nomor Pokok Wajib Pajak perusahaan yang valid.</li>
            <li><strong>Bidang Usaha:</strong> Pilih minimal 1 dan maksimal 10 bidang usaha yang sesuai dengan keahlian perusahaan (contoh: konstruksi bangunan, renovasi interior, instalasi listrik).</li>
        </ul>
        <p><strong>Catatan:</strong> Pastikan semua data diisi dengan benar. Data yang tidak lengkap akan memperlambat proses persetujuan.</p>
    </div>

    <div class="guide-subsection">
        <h3>2. Dokumen Teknis dan Administratif</h3>
        <p>Dokumen berikut diperlukan untuk membuktikan kapabilitas teknis dan administratif perusahaan Anda:</p>
        <ul>
            <li><strong>Daftar Personel Inti:</strong> Lampirkan daftar tenaga kerja utama (misalnya mandor, teknisi, atau tukang ahli) beserta sertifikat kompetensi (SKA/SKT) jika ada untuk meningkatkan kredibilitas.</li>
            <li><strong>Inventaris Peralatan:</strong> Sertakan daftar peralatan kerja (contoh: scaffolding, concrete mixer, alat ukur) yang dimiliki atau disewa, disertai bukti kepemilikan atau kontrak sewa.</li>
            <li><strong>Riwayat Proyek:</strong> Cantumkan pengalaman proyek sebelumnya, termasuk nama proyek, lokasi, tahun pengerjaan, nilai kontrak, dan kontak pemberi kerja untuk verifikasi.</li>
            <li><strong>Dokumentasi Proyek:</strong> Unggah foto tahapan proyek (pra-konstruksi, proses, dan hasil akhir). Testimoni klien dapat dilampirkan sebagai nilai tambah.</li>
            <li><strong>Formulir Penawaran:</strong> Siapkan dokumen penawaran teknis dan harga (metodologi, jadwal, tenaga kerja, alat) sesuai kebutuhan tender proyek.</li>
            <li><strong>Surat Pernyataan Kebenaran:</strong> Lampirkan surat resmi yang menyatakan bahwa semua dokumen yang diajukan sah, ditandatangani oleh pimpin perusahaan dengan stempel resmi.</li>
        </ul>
        <p><strong>Catatan:</strong> Portofolio proyek minimal berisi 3 proyek yang telah diselesaikan untuk menunjukkan pengalaman yang memadai.</p>
    </div>

    <div class="guide-subsection">
        <h3>3. Dokumen Legalitas dan Perizinan</h3>
        <p>Untuk memastikan perusahaan Anda beroperasi secara legal, unggah dokumen legalitas berikut:</p>
        <ul>
            <li><strong>Akta Pendirian Perusahaan:</strong> Dokumen resmi dari notaris yang memuat informasi pendirian perusahaan, seperti nama, tujuan usaha, dan struktur kepemilikan.</li>
            <li><strong>SK Kemenkumham:</strong> Surat pengesahan dari Kementerian Hukum dan HAM atas Akta Pendirian.</li>
            <li><strong>NIB (Nomor Induk Berusaha):</strong> Nomor identitas resmi perusahaan yang diterbitkan melalui sistem OSS, mencakup izin usaha.</li>
            <li><strong>NPWP Perusahaan:</strong> Nomor Pokok Wajib Pajak badan perusahaan yang menunjukkan pendaftaran pajak aktif.</li>
            <li><strong>SKT Pajak:</strong> Surat Keterangan Terdaftar Pajak yang membuktikan status aktif di kantor pajak.</li>
            <li><strong>SBU Konstruksi:</strong> Sertifikat Badan Usaha untuk jasa konstruksi, menunjukkan klasifikasi dan kualifikasi perusahaan.</li>
            <li><strong>IUJK:</strong> Izin Usaha Jasa Konstruksi dari pemerintah daerah sebagai bukti legalitas operasional.</li>
            <li><strong>TDP (Opsional):</strong> Jika belum memiliki NIB, lampirkan Tanda Daftar Perusahaan (kini digantikan oleh NIB).</li>
        </ul>
        <p><strong>Catatan:</strong> Pastikan semua dokumen legalitas masih berlaku dan sesuai dengan data profil perusahaan.</p>
    </div>
</div>
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

                            <!-- Dokumen Legalitas -->
                            @if ($contractor->legalitas && count($contractor->legalitas) > 0)
                                <div class="document-section">
                                    <strong>Dokumen Legalitas:</strong>
                                    <ul class="document-list">
                                        @foreach ($contractor->legalitas as $doc)
                                            <li>
                                                <a href="{{ Storage::url($doc) }}" target="_blank" class="item-link">
                                                    {{ basename($doc) }}
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
                            <form action="{{ route('admin.contractors.approve', $contractor->id) }}" method="POST" class="decision-form" style="background-color: #f9f5f1; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                            @csrf
                            <div class="decision-options" style="margin-bottom: 15px; font-weight: 600;">
                                <label style="margin-right: 20px; color: #333;">
                                    <input type="radio" name="approved" value="1" required> ✅ Setujui
                                </label>
                                <label style="color: #333;">
                                    <input type="radio" name="approved" value="0"> ❌ Tolak
                                </label>
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-weight: 600; color: #333;">Catatan (opsional):</label>
                                <textarea name="admin_note" placeholder="Tulis catatan untuk kontraktor..." class="form-control"
                                    style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 0.95rem;"></textarea>
                            </div>

                            <button type="submit" class="btn" style="
                                background-color: #CD853F; /* Coklat muda */
                                color: white;
                                font-weight: 600;
                                padding: 10px 18px;
                                font-size: 1rem;
                                border: none;
                                border-radius: 5px;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                Simpan Keputusan
                            </button>
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

    <script>
        // Toggle guide section visibility
        document.getElementById('toggleGuideBtn').addEventListener('click', function () {
            const guideSection = document.getElementById('guideSection');
            if (guideSection.style.display === 'none') {
                guideSection.style.display = 'block';
                this.textContent = 'Sembunyikan Panduan';
            } else {
                guideSection.style.display = 'none';
                this.textContent = 'Tampilkan Panduan';
            }
        });
    </script>

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

        h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #5a3e36;
            margin-bottom: 8px;
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

        /* Toggle Button */
        .toggle-guide {
            text-align: center;
            margin-bottom: 15px;
        }

        .btn-toggle {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-toggle:hover {
            background-color: #8ba89a;
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

        /* Guide Section */
        .guide-section {
            margin-top: 15px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #e0d8c9;
        }

        .guide-subsection {
            margin-bottom: 20px;
        }

        .guide-subsection ul {
            padding-left: 20px;
        }

        .guide-subsection li {
            margin-bottom: 8px;
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

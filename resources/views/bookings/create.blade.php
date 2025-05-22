@extends('layouts.app')

@section('title', 'Pesan Kontraktor: ' . $contractor->name)

@section('content')
    <div class="container">
        <div class="booking-form-section">
            <h1>Pesan Kontraktor: {{ $contractor->name }}</h1>
            @if (session('error'))
                <div class="notification error">{{ session('error') }}</div>
            @endif

            <!-- Panduan Pengisian Formulir -->
            <div class="guidelines-section">
                <h2>Panduan Pengisian Formulir Proyek</h2>
                <p>Untuk memastikan proyek Anda dapat diproses dengan cepat dan profesional, silakan ikuti langkah-langkah berikut:</p>
                <ul>
                    <li>
                        <strong>1. Isi Deskripsi dan Formulir dengan Lengkap dan Jelas</strong>
                        <p>Lengkapi seluruh informasi yang diminta, seperti:</p>
                        <ul>
                            <li>Judul proyek dan deskripsi singkat</li>
                            <li>Lokasi tanah dan luas bangunan</li>
                            <li>Kebutuhan ruang (jumlah kamar, dapur, dll.)</li>
                            <li>Preferensi material dan anggaran</li>
                            <li>Target waktu pelaksanaan</li>
                        </ul>
                        <p><em>Tips: Semakin detail informasi yang Anda berikan, semakin mudah bagi kontraktor memahami kebutuhan Anda.</em></p>
                    </li>
                    <li>
                        <strong>2. Upload Gambar Pendukung (Opsional tapi Disarankan)</strong>
                        <p>Unggah file seperti:</p>
                        <ul>
                            <li>Denah kasar atau sketsa tangan</li>
                            <li>Gambar inspirasi dari internet</li>
                            <li>Foto lokasi tanah</li>
                        </ul>
                    </li>
                    <li>
                        <strong>3. Buat dan Unggah Surat Perjanjian Kerja (SPK)</strong>
                        <p>Setelah form dan gambar dikirim, Anda dapat menyusun Surat Perjanjian Kerja berisi:</p>
                        <ul>
                            <li>Rincian pekerjaan</li>
                            <li>Estimasi anggaran</li>
                            <li>Jadwal pelaksanaan</li>
                            <li>Ketentuan pembayaran</li>
                        </ul>
                        <p><em>Catatan: Template SPK bisa kami bantu sediakan jika diperlukan.</em></p>
                    </li>
                    <li>
                        <strong>4. Kirim Semua Dokumen ke Kontraktor</strong>
                        <p>Setelah semua siap (formulir, gambar, SPK), silakan kirim ke kontraktor pilihan Anda melalui platform kami atau kontak langsung.</p>
                    </li>
                    <li>
                        <strong>5. Tunggu Respons dari Kontraktor</strong>
                        <p>Kontraktor akan memeriksa dokumen dan memberikan keputusan:</p>
                        <ul>
                            <li><span class="status accepted">Menerima proyek</span>: proses pembangunan bisa segera dijadwalkan.</li>
                            <li><span class="status declined">Menolak proyek</span>: Anda akan diberi alasan dan bisa memilih kontraktor lain.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>6. Kontraktor merespons dan mengirim file final setelah diskusi dichat  </strong>
                        <p>Dokumen atau gambar akhir disesuaikan berdasarkan hasil diskusi dengan klien.</p>
                    </li>
                    <li>
                        <strong>7. Client yang memesan akan memberikan final approve terjadinya kesepakatan</strong>
                        <p>Setelah semua disepakati, proyek bisa dimulai sesuai jadwal yang ditentukan.</p>
                    </li>
                </ul>
            </div>

            <!-- Form Pemesanan -->
            <form method="POST" action="{{ route('bookings.store', $contractor->id) }}" enctype="multipart/form-data" class="booking-form">
                @csrf
                <div class="form-group">
                    <label for="judul">Judul:</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required>
                    @error('judul')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Panduan dan Template:</label>
                    <div class="file-download">
                        @if(file_exists(public_path('storage/KETERANGANDESKRIPSI.pdf')))
                            <a href="{{ asset('storage/KETERANGANDESKRIPSI.pdf') }}" target="_blank" class="file-link">
                                <i class="fas fa-file-download"></i> Unduh Panduan: KETERANGANDESKRIPSI.pdf
                            </a>
                            <p class="file-info">File: KETERANGANDESKRIPSI.pdf</p>
                        @else
                            <p class="file-info text-muted">Panduan deskripsi tidak ditemukan.</p>
                        @endif
                    </div>
                    <div class="file-download">
                        @if(file_exists(public_path('storage/SPK.docx')))
                            <a href="{{ asset('storage/SPK.docx') }}" target="_blank" class="file-link">
                                <i class="fas fa-file-download"></i> Unduh Template: SPK.docx
                            </a>
                            <p class="file-info">File: SPK.docx</p>
                        @else
                            <p class="file-info text-muted">Template SPK tidak ditemukan.</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar (unggah multiple):</label>
                    <input type="file" id="gambar" name="gambar[]" multiple>
                    @error('gambar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="dokumen">Dokumen (PDF, Word, maks 5MB):</label>
                    <input type="file" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx">
                    @error('dokumen')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi:</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required>
                    @error('lokasi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="estimasi_anggaran">Estimasi Anggaran:</label>
                    <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" step="0.01" value="{{ old('estimasi_anggaran') }}" required>
                    @error('estimasi_anggaran')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="durasi">Durasi:</label>
                    <input type="text" id="durasi" name="durasi" placeholder="Contoh: 2 minggu, 1 bulan" value="{{ old('durasi') }}" required>
                    @error('durasi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Pesan</button>
            </form>

            <div class="back-link">
                <a href="{{ route('contractors.index') }}" class="btn btn-secondary">Kembali ke Daftar Kontraktor</a>
            </div>
        </div>
    </div>

    <style>
        /* Booking Form Section */
        .booking-form-section {
            width: 900px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .booking-form-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Guidelines Section */
        .guidelines-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fdfaf6;
            border: 1px solid #e0d8c9;
            border-radius: 8px;
        }

        .guidelines-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 15px;
        }

        .guidelines-section p {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        .guidelines-section ul {
            list-style: none;
            padding: 0;
        }

        .guidelines-section ul li {
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
        }

        .guidelines-section ul li strong {
            color: #5a3e36;
            font-size: 16px;
        }

        .guidelines-section ul li ul {
            margin-top: 5px;
            padding-left: 20px;
        }

        .guidelines-section ul li ul li {
            margin-bottom: 5px;
            list-style-type: disc;
        }

        .guidelines-section em {
            color: #6b5848;
            font-style: italic;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }

        .status.accepted {
            background-color: #28a745;
        }

        .status.declined {
            background-color: #dc3545;
        }

        /* Form Styling */
        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .error-message {
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        /* File Download */
        .file-download {
            margin-top: 5px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fdfaf6;
            border: 1px solid #e0d8c9;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .file-link {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }

        .file-link:hover {
            color: #a8c3b8;
            text-decoration: underline;
        }

        .file-info {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
            color: #6b5848;
            margin: 0;
        }

        .text-muted {
            color: #999;
        }

        /* Notification */
        .notification.error {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
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
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .booking-form-section {
                padding: 20px;
                margin: 20px;
            }

            .booking-form-section h1 {
                font-size: 28px;
            }

            .guidelines-section h2 {
                font-size: 20px;
            }

            .guidelines-section ul li strong {
                font-size: 14px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

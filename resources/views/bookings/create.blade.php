@extends('layouts.app')

@section('title', 'Pesan Kontraktor: ' . $contractor->name)

@section('content')
    <div class="container">
        <div class="booking-form-section">
            <!-- Back Link -->
            <div class="back-link-top">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
            </div>

            <h1>Pesan Kontraktor: {{ $contractor->name }}</h1>
            @if (session('error'))
                <div class="notification error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="notification error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
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
                            <li>Target waktu pelaksanaan (contoh: 3 hari, 1 minggu, 2 bulan)</li>
                        </ul>
                        <p><em>Tips: Semakin detail informasi yang Anda berikan, semakin mudah bagi kontraktor memahami kebutuhan Anda.</em></p>
                    </li>
                    <li>
                        <strong>2. Upload Gambar Pendukung (Wajib)</strong>
                        <p>Unggah minimal satu file seperti:</p>
                        <ul>
                            <li>Denah kasar atau sketsa tangan</li>
                            <li>Gambar inspirasi dari internet</li>
                            <li>Foto lokasi tanah</li>
                        </ul>
                    </li>
                    <li>
                        <strong>3. Buat dan Unggah Surat Perjanjian Kerja (SPK) (Wajib)</strong>
                        <p>Anda harus menyusun dan mengunggah Surat Perjanjian Kerja berisi:</p>
                        <ul>
                            <li>Rincian pekerjaan</li>
                            <li>Estimasi anggaran</li>
                            <li>Jadwal pelaksanaan (contoh: 3 hari, 1 minggu, 2 bulan)</li>
                            <li>Ketentuan pembayaran</li>
                        </ul>
                        <p><em>Catatan: Template SPK tersedia untuk diunduh jika diperlukan.</em></p>
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
                        <strong>6. Kontraktor merespons dan mengirim file final setelah diskusi dichat</strong>
                        <p>Dokumen atau gambar akhir disesuaikan berdasarkan hasil diskusi dengan klien.</p>
                    </li>
                    <li>
                        <strong>7. Client yang memesan akan memberikan final approve terjadinya kesepakatan</strong>
                        <p>Setelah semua disepakati, proyek bisa dimulai sesuai jadwal yang ditentukan.</p>
                    </li>
                </ul>
            </div>

            <!-- Form Pemesanan -->
            <form method="POST" action="{{ route('bookings.store', $contractor->id) }}" enctype="multipart/form-data" class="booking-form" id="bookingForm">
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
                    <label for="gambar">Gambar (unggah minimal 1, hanya JPEG/PNG/JPG, maks 2MB):</label>
                    <small class="form-text" style="color: red; font-weight: bold;">
                        Mengunggah file baru akan menghapus file sebelumnya. Pastikan file yang dipilih sudah benar.
                    </small>
                    <input type="file" id="gambar" name="gambar[]" multiple accept="image/jpeg,image/png,image/jpg" required>
                    @error('gambar.*')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="dokumen">Dokumen (PDF, Word, maks 5MB, wajib):</label>
                    <input type="file" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx" required>
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
                    <label for="estimasi_anggaran">Estimasi Anggaran (minimal Rp 10,000):</label>
                    <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" step="0.01" value="{{ old('estimasi_anggaran') }}" required min="10000">
                    @error('estimasi_anggaran')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="durasi">Durasi (contoh: 3 hari, 1 minggu, 2 bulan):</label>
                    <input type="text" id="durasi" name="durasi" placeholder="Contoh: 3 hari, 1 minggu, 2 bulan" value="{{ old('durasi') }}" required>
                    @error('durasi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Pesan</button>
            </form>
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
            position: relative; /* Untuk positioning tombol */
        }

        .booking-form-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Back Link di Pojok Kiri Atas */
        .back-link-top {
            position: absolute;
            top: 15px;
            left: 15px;
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
                width: auto;
                padding: 8px 16px;
            }

            .back-link-top {
                top: 10px;
                left: 10px;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada error validasi (artinya form gagal disubmit)
        @if ($errors->any())
            // Reset hanya field gambar dan dokumen
            document.getElementById('gambar').value = '';
            document.getElementById('dokumen').value = '';
        @endif

        // Validasi ukuran file dan keberadaan file sebelum submit
        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            const gambarInput = document.getElementById('gambar');
            const dokumenInput = document.getElementById('dokumen');
            const maxGambarSize = 2 * 1024 * 1024; // 2MB dalam bytes
            const maxDokumenSize = 5 * 1024 * 1024; // 5MB dalam bytes
            let hasError = false;

            // Validasi Gambar wajib diisi
            if (gambarInput.files.length === 0) {
                alert('Gambar wajib diunggah. Silakan pilih minimal 1 gambar.');
                hasError = true;
            } else {
                // Validasi ukuran gambar
                for (let file of gambarInput.files) {
                    if (file.size > maxGambarSize) {
                        alert('Ukuran salah satu gambar melebihi 2MB. Silakan pilih file yang lebih kecil.');
                        hasError = true;
                        break;
                    }
                }
            }

            // Validasi Dokumen wajib diisi
            if (dokumenInput.files.length === 0) {
                alert('Dokumen wajib diunggah. Silakan pilih sebuah dokumen.');
                hasError = true;
            } else {
                // Validasi ukuran dokumen
                if (dokumenInput.files[0].size > maxDokumenSize) {
                    alert('Ukuran dokumen melebihi 5MB. Silakan pilih file yang lebih kecil.');
                    hasError = true;
                }
            }

            // Jika ada error, batalkan submit
            if (hasError) {
                event.preventDefault();
            }
        });
    });
    </script>
@endsection

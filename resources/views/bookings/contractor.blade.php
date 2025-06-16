@extends('layouts.app')

@section('title', 'Pesanan untuk Saya (Kontraktor)')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="booking-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Booking</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Toggle Button for Guidelines -->
        <div class="toggle-buttons">
            <button class="btn btn-secondary toggle-btn" onclick="toggleGuidelines()"
                style="background-color: #8B4513; /* coklat tua */
                    color: white;
                    font-weight: 700;
                    padding: 10px 20px;
                    font-size: 1.1rem;
                    border-radius: 6px;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            Cara Kerja Booking
            </button>
        </div>

        <!-- Guidelines Section -->
        <div class="guidelines-section" id="guidelines-section" style="display: none;">
            <h2>Cara Kerja Booking</h2>
            <div class="guidelines-grid">
                <div class="guidelines-left">
                    <div class="guidelines-content">
                        <p>Client memastikan proyek dapat diproses dengan cepat dan profesional, berikut langkah-langkah berikut:</p>
                        <ul>
                            <li>
                                <strong>1. Client Mengisi Deskripsi dan Formulir dengan Lengkap dan Jelas</strong>
                                <p>Lengkapi seluruh informasi yang diminta, seperti:</p>
                                <ul>
                                    <li>Judul proyek dan deskripsi singkat</li>
                                    <li>Lokasi tanah dan luas bangunan</li>
                                    <li>Kebutuhan ruang (jumlah kamar, dapur, dll.)</li>
                                    <li>Preferensi material dan anggaran</li>
                                    <li>Target waktu pelaksanaan (contoh: 3 hari, 1 minggu, 2 bulan)</li>
                                </ul>
                                <p><em>Tips: Semakin detail informasi yang Client berikan, semakin mudah bagi kontraktor memahami kebutuhan Client.</em></p>
                            </li>
                            <li>
                                <strong>2. Client Mengunggah Gambar Pendukung (Wajib)</strong>
                                <p>Unggah minimal satu file seperti:</p>
                                <ul>
                                    <li>Denah kasar atau sketsa tangan</li>
                                    <li>Gambar inspirasi dari internet</li>
                                    <li>Foto lokasi tanah</li>
                                </ul>
                            </li>
                            <li>
                                <strong>3. Client Membuat dan Unggah Surat Perjanjian Kerja (SPK) (Wajib)</strong>
                                <p>Client harus menyusun dan mengunggah Surat Perjanjian Kerja berisi:</p>
                                <ul>
                                    <li>Rincian pekerjaan</li>
                                    <li>Estimasi anggaran</li>
                                    <li>Jadwal pelaksanaan (contoh: 3 hari, 1 minggu, 2 bulan)</li>
                                    <li>Ketentuan pembayaran</li>
                                </ul>
                                <p><em>Catatan: Template SPK tersedia untuk diunduh jika diperlukan.</em></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="guidelines-right">
                    <div class="guidelines-content">
                        <ul>
                            <li>
                                <strong>4. Client Mengirim Semua Dokumen ke Kontraktor</strong>
                                <p>Setelah semua siap (formulir, gambar, SPK), silakan kirim ke kontraktor pilihan Client melalui platform kami atau kontak langsung.</p>
                            </li>
                            <li>
                                <strong>5. Client Menunggu Respons dari Kontraktor</strong>
                                <p>Kontraktor akan memeriksa dokumen dan memberikan keputusan:</p>
                                <ul>
                                    <li>Menerima proyek: proses pembangunan bisa segera dijadwalkan.</li>
                                    <li>Menolak proyek: Client akan diberi alasan dan bisa memilih kontraktor lain.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>6. Kontraktor Merespons dan Mengirim File Final Setelah Diskusi di Chat</strong>
                                <p>Dokumen atau gambar akhir disesuaikan berdasarkan hasil diskusi dengan klien.</p>
                            </li>
                            <li>
                                <strong>7. Client yang Memesan Akan Memberikan Final Approve Terjadinya Kesepakatan</strong>
                                <p>Setelah semua disepakati, proyek bisa dimulai sesuai jadwal yang ditentukan.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="back-link">
        <a href="{{ route('home') }}"
        class="btn"
        style="background-color: #D2B48C;  /* tan - coklat muda */
                color: #3e2723;              /* coklat tua untuk teks */
                font-weight: 600;
                padding: 6px 14px;
                font-size: 0.95rem;
                border: none;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
        Kembali ke Home
        </a>
        </div>

        <!-- Booking Content -->
        <div class="booking-content">
            @if ($bookings->isEmpty())
                <p class="empty-message">Tidak ada pesanan untuk Anda.</p>
            @else
                <div class="booking-grid">
                    @foreach ($bookings as $booking)
                        <div class="booking-card">
                            <h2 class="booking-title">{{ $booking->judul }}</h2>
                            <p class="booking-description">Deskripsi: {{ $booking->deskripsi }}</p>
                            @if ($booking->gambar && count($booking->gambar) > 0)
                                <h3 class="image-title">Gambar:</h3>
                                <div class="image-grid">
                                    @foreach ($booking->gambar as $gambar)
                                        <a href="{{ Storage::url($gambar) }}" target="_blank">
                                            <img src="{{ Storage::url($gambar) }}" alt="Portofolio" class="booking-image">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            @if ($booking->dokumen)
                            <p class="booking-detail">
                            <strong>Dokumen:</strong>
                            <a href="{{ Storage::url($booking->dokumen) }}" target="_blank"
                                style="color: #8B4513; font-weight: 700; text-decoration: underline; background-color: #FAF0E6; padding: 4px 8px; border-radius: 4px;">
                                Lihat Dokumen
                            </a>
                            </p>
                            @else
                                <p class="booking-detail text-muted">Tidak ada dokumen.</p>
                            @endif
                            @if ($booking->response_file)
                            <p class="booking-detail">
                            <strong>File Balasan:</strong>
                            <a href="{{ Storage::url($booking->response_file) }}" target="_blank"
                                style="color: #d2691e; font-weight: 700; text-decoration: underline; background-color: #fff3e0; padding: 4px 8px; border-radius: 4px; transition: all 0.3s ease;">
                                Lihat File Balasan
                            </a>
                            </p>
                            @else
                                <p class="booking-detail text-muted">Tidak ada file balasan.</p>
                            @endif
                            <p class="booking-detail"><strong>Lokasi:</strong> {{ $booking->lokasi }}</p>
                            <p class="booking-detail"><strong>Estimasi Anggaran:</strong> Rp {{ number_format($booking->estimasi_anggaran, 2, ',', '.') }}</p>
                            <p class="booking-detail"><strong>Durasi:</strong> {{ $booking->durasi }}</p>
                            <div class="user-info">
                                @if ($booking->user->profile && $booking->user->profile->foto_profile)
                                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                                        <img src="{{ Storage::url($booking->user->profile->foto_profile) }}" alt="Foto Profile" class="user-image">
                                    </a>
                                @else
                                    <div class="no-image">Tidak ada foto</div>
                                @endif
                                <p class="user-name">
                                    User:
                                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                                        {{ $booking->user->name }}
                                        @if ($booking->user->profile && $booking->user->profile->nama_panggilan)
                                            ({{ $booking->user->profile->nama_panggilan }})
                                        @endif
                                    </a>
                                </p>
                            </div>
                            <p class="booking-detail"><strong>Status:</strong>
                                <span class="status {{ $booking->status }}">
                                    {{ $booking->status }}
                                </span>
                            </p>
                            <p class="booking-detail"><strong>Final Approve (User):</strong>
                                <span class="status final-approve {{ $booking->final_approve ? 'approved' : 'pending' }}">
                                    {{ $booking->final_approve ? 'Approved' : 'Pending' }}
                                </span>
                            </p>
                            @if ($booking->status === 'pending' && $booking->deadline)
                                <p class="booking-detail"><strong>Sisa Waktu:</strong>
                                    <span id="deadline-{{ $booking->id }}" class="deadline-countdown">
                                        {{ $booking->deadline->diffForHumans() }}
                                    </span>
                                    @if (now()->greaterThan($booking->deadline->subHours(1)))
                                        <span class="warning"> (Segera tanggapi, kurang dari 1 jam tersisa!)</span>
                                    @endif
                                </p>
                            @endif
                            @if ($booking->status === 'pending')
                                <div class="status-action">
                                    <label for="status_action_{{ $booking->id }}" class="action-label">Pilih Aksi:</label>
                                    <select name="status_action" id="status_action_{{ $booking->id }}" class="status-select" data-booking-id="{{ $booking->id }}">
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="accepted">Terima</option>
                                        <option value="declined">Tolak</option>
                                    </select>
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" id="form_{{ $booking->id }}" enctype="multipart/form-data" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="status" id="status_{{ $booking->id }}">
                                        <div id="response_field_{{ $booking->id }}" style="display: none;">
                                            <!-- Konten akan diisi oleh JavaScript -->
                                        </div>
                                        <button type="submit" class="btn btn-submit" id="submit_{{ $booking->id }}">Kirim</button>
                                    </form>
                                </div>
                            @endif
                            @if ($booking->status === 'declined' && $booking->decline_reason)
                                <p class="decline-reason"><strong>Alasan Penolakan:</strong> {{ $booking->decline_reason }}</p>
                            @endif

                            <!-- Tampilkan Bukti Pembayaran untuk Setiap Tahap -->
                            @if ($booking->payment_stage > 0 || ($booking->is_completed && $booking->review))
                                <div class="payment-details">
                                    @if ($booking->is_completed && $booking->review && $booking->review->pembayaran)
                                        <div class="payment-stage">
                                            <p class="booking-detail"><strong>Bukti Pembayaran (Review):</strong></p>
                                            <img src="{{ Storage::url($booking->review->pembayaran) }}" alt="Bukti Pembayaran (Review)" class="payment-image">
                                        </div>
                                    @endif
                                    @for ($i = 1; $i <= $booking->payment_stage; $i++)
                                        @if ($booking->{"payment_proof_$i"})
                                            <div class="payment-stage">
                                                <p class="booking-detail"><strong>Bukti Pembayaran Tahap {{ $i }}:</strong></p>
                                                <img src="{{ Storage::url($booking->{"payment_proof_$i"}) }}" alt="Bukti Pembayaran Tahap {{ $i }}" class="payment-image">
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            @endif

                            <!-- Tampilkan Ulasan jika ada -->
                            @if ($booking->is_completed && $booking->review)
                                <div class="review-details">
                                    <p class="booking-detail"><strong>Rating:</strong> {{ $booking->review->rating }}/5</p>
                                    <p class="booking-detail"><strong>Ulasan:</strong> {{ $booking->review->review ?? 'Tidak ada ulasan' }}</p>
                                </div>
                            @endif

                            <p class="booking-detail"><strong>Dibuat pada:</strong> {{ $booking->created_at->format('d F Y') }}</p>
                            <div class="decoration-line"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        /* General Container */
        .containers {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Booking Header */
        .booking-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #e0d8c9;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
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

        /* Toggle Buttons */
        .toggle-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .toggle-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .toggle-btn:hover {
            opacity: 0.9;
        }

        /* Guidelines Section */
        .guidelines-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            transition: all 0.3s ease;
        }

        .guidelines-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 20px;
            grid-column: 1 / -1;
        }

        .guidelines-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }

        .guidelines-left,
        .guidelines-right {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .guidelines-content p {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .guidelines-content ul {
            list-style: none;
            padding: 0;
        }

        .guidelines-content ul li {
            margin-bottom: 20px;
        }

        .guidelines-content ul li strong {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
        }

        .guidelines-content ul li ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-top: 10px;
        }

        .guidelines-content ul li ul li {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .guidelines-content em {
            font-style: italic;
            color: #6b5848;
            font-size: 13px;
        }

        /* Back Link */
        .back-link {
            text-align: left;
            margin-bottom: 20px;
        }

        /* Booking Content */
        .booking-content {
            margin-bottom: 20px;
        }

        .empty-message {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #6b5848;
            text-align: center;
            padding: 20px;
            background-color: #fdfaf6;
            border-radius: 8px;
            border: 1px solid #e0d8c9;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .booking-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .booking-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #5a3e36;
            margin-bottom: 15px;
        }

        .image-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        .booking-description {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .booking-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .booking-image:hover {
            transform: scale(1.05);
        }

        .booking-detail {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .booking-detail a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .booking-detail a:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .user-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #a8c3b8;
            transition: transform 0.3s ease;
        }

        .user-image:hover {
            transform: scale(1.05);
        }

        .no-image {
            width: 80px;
            height: 80px;
            background-color: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b5848;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
        }

        .user-name {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #555;
        }

        .user-name a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .user-name a:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status.pending {
            color: #fff;
            background-color: #ffc107;
        }

        .status.accepted {
            color: #fff;
            background-color: #28a745;
        }

        .status.declined {
            color: #fff;
            background-color: #dc3545;
        }

        .status.expired {
            color: #fff;
            background-color: #6c757d;
        }

        .status.final-approve.approved {
            color: #fff;
            background-color: #17a2b8;
        }

        .status.final-approve.pending {
            color: #fff;
            background-color: #6c757d;
        }

        /* Decline Reason */
        .decline-reason {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #721c24;
            margin-bottom: 10px;
            background-color: #f8d7da;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }

        /* Status Action */
        .status-action {
            margin: 15px 0;
        }

        .action-label {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #6b5848;
            margin-bottom: 5px;
            display: block;
        }

        .status-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #d4c8b5;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            background-color: #fff;
            cursor: pointer;
        }

        .status-select:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .status-action form {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #e0d8c9;
            border-radius: 4px;
            background-color: #fdfaf6;
        }

        .status-action input[type="file"],
        .status-action textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #d4c8b5;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
            background-color: #fff;
            margin-bottom: 10px;
        }

        .status-action textarea {
            resize: vertical;
            height: 80px;
        }

        .status-action input[type="file"]:focus,
        .status-action textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .error-message {
            display: block;
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Payment Details */
        .payment-details {
            margin-top: 10px;
        }

        .payment-stage {
            margin-bottom: 15px;
        }

        .payment-image {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
            margin-top: 5px;
        }

        /* Review Details */
        .review-details {
            margin-top: 10px;
        }

        /* Decoration Line */
        .decoration-line {
            height: 1px;
            background-color: #d4c8b5;
            margin-top: 15px;
            opacity: 0.7;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-submit {
            background-color: #28a745;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Deadline Countdown */
        .deadline-countdown {
            font-weight: bold;
            color: #6c757d;
        }

        .warning {
            color: #dc3545;
            font-weight: bold;
            margin-left: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
            }

            .guidelines-grid {
                grid-template-columns: 1fr;
            }

            .booking-grid {
                grid-template-columns: 1fr;
            }

            .booking-card {
                padding: 15px;
            }

            .booking-title {
                font-size: 18px;
            }

            .image-title {
                font-size: 16px;
            }

            .image-grid {
                grid-template-columns: 1fr;
            }

            .booking-image {
                height: 150px;
            }

            .booking-description {
                font-size: 13px;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .user-image,
            .no-image {
                width: 60px;
                height: 60px;
            }

            .no-image {
                font-size: 12px;
            }

            .booking-detail {
                font-size: 13px;
            }

            .payment-image {
                max-width: 100px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .status-action textarea {
                height: 60px;
            }

            .toggle-buttons {
                flex-direction: column;
            }

            .toggle-btn {
                width: 100%;
            }
        }
    </style>

    <script>
        // Toggle Guidelines Section
        function toggleGuidelines() {
            const guidelinesSection = document.getElementById('guidelines-section');
            if (guidelinesSection.style.display === 'none' || guidelinesSection.style.display === '') {
                guidelinesSection.style.display = 'block';
            } else {
                guidelinesSection.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Logika untuk form status
            document.querySelectorAll('.status-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    const bookingId = this.dataset.bookingId;
                    const form = document.getElementById(`form_${bookingId}`);
                    const responseField = document.getElementById(`response_field_${bookingId}`);
                    const statusInput = document.getElementById(`status_${bookingId}`);
                    const submitButton = document.getElementById(`submit_${bookingId}`);

                    // Reset form dan sembunyikan
                    form.style.display = 'none';
                    responseField.innerHTML = '';

                    if (this.value) {
                        // Set status
                        statusInput.value = this.value;

                        // Tampilkan form
                        form.style.display = 'block';

                        // Tambahkan field sesuai pilihan
                        if (this.value === 'accepted') {
                            responseField.innerHTML = `
                                <label for="response_file_${bookingId}">File Balasan (PDF, Word, maks 5MB):</label>
                                <input type="file" name="response_file" id="response_file_${bookingId}" accept=".pdf,.doc,.docx" required>
                                <span class="error-message" id="error_response_file_${bookingId}"></span>
                            `;
                            submitButton.textContent = 'Terima';
                            submitButton.style.backgroundColor = '#28a745';
                        } else if (this.value === 'declined') {
                            responseField.innerHTML = `
                                <label for="decline_reason_${bookingId}">Alasan Penolakan:</label>
                                <textarea name="decline_reason" id="decline_reason_${bookingId}" placeholder="Masukkan alasan penolakan..." rows="3" required></textarea>
                                <span class="error-message" id="error_decline_reason_${bookingId}"></span>
                            `;
                            submitButton.textContent = 'Tolak';
                            submitButton.style.backgroundColor = '#dc3545';
                        }

                        responseField.style.display = 'block';
                    }
                });
            });

            // Validasi form sebelum submit
            document.querySelectorAll('.status-action form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    const bookingId = this.id.split('_')[1];
                    const status = document.getElementById(`status_${bookingId}`).value;
                    let isValid = true;

                    // Reset error messages
                    const errorFields = this.querySelectorAll('.error-message');
                    errorFields.forEach(field => field.textContent = '');

                    if (status === 'accepted') {
                        const fileInput = document.getElementById(`response_file_${bookingId}`);
                        if (!fileInput.files.length) {
                            document.getElementById(`error_response_file_${bookingId}`).textContent = 'File balasan wajib diunggah.';
                            isValid = false;
                        } else {
                            const file = fileInput.files[0];
                            const maxSize = 5 * 1024 * 1024; // 5MB
                            if (file.size > maxSize) {
                                document.getElementById(`error_response_file_${bookingId}`).textContent = 'Ukuran file tidak boleh lebih dari 5MB.';
                                isValid = false;
                            }
                            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                            if (!allowedTypes.includes(file.type)) {
                                document.getElementById(`error_response_file_${bookingId}`).textContent = 'File harus berupa PDF atau Word.';
                                isValid = false;
                            }
                        }
                    } else if (status === 'declined') {
                        const reasonInput = document.getElementById(`decline_reason_${bookingId}`);
                        if (!reasonInput.value.trim()) {
                            document.getElementById(`error_decline_reason_${bookingId}`).textContent = 'Alasan penolakan wajib diisi.';
                            isValid = false;
                        }
                    }

                    if (!isValid) {
                        e.preventDefault();
                    } else if (status === 'declined') {
                        if (!confirm('Yakin ingin menolak pesanan ini?')) {
                            e.preventDefault();
                        }
                    }
                });
            });

            // Update countdown setiap detik
            function updateCountdown() {
                document.querySelectorAll('.deadline-countdown').forEach(function (element) {
                    const bookingId = element.id.split('-')[1];
                    const deadline = new Date(document.getElementById(`deadline-${bookingId}`).dataset.deadline);
                    const now = new Date();
                    const diff = deadline - now;

                    if (diff <= 0) {
                        element.textContent = 'Waktu habis';
                        const booking = document.querySelector(`#form_${bookingId}`).closest('.booking-card');
                        if (booking) {
                            booking.querySelector('.status').textContent = 'expired';
                            booking.querySelector('.status').classList.add('expired');
                            document.getElementById(`form_${bookingId}`).style.display = 'none';
                        }
                    } else {
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        element.textContent = `${hours}h ${minutes}m ${seconds}s tersisa`;
                    }
                });
            }

            // Inisialisasi data-deadline dari server
            @foreach ($bookings as $booking)
                @if ($booking->status === 'pending' && $booking->deadline)
                    document.getElementById('deadline-{{ $booking->id }}').dataset.deadline = '{{ $booking->deadline->toIso8601String() }}';
                @endif
            @endforeach

            // Jalankan countdown setiap detik
            setInterval(updateCountdown, 1000);
            updateCountdown(); // Jalankan pertama kali
        });
    </script>
@endsection


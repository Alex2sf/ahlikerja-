@extends('layouts.app')

@section('title', 'Pesanan untuk Saya (Kontraktor)')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="booking-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Pesanan untuk Saya (Kontraktor)</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

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
                                <p class="booking-detail"><strong>Dokumen:</strong> <a href="{{ Storage::url($booking->dokumen) }}" target="_blank">Lihat Dokumen</a></p>
                            @else
                                <p class="booking-detail text-muted">Tidak ada dokumen.</p>
                            @endif
                            @if ($booking->response_file)
                                <p class="booking-detail"><strong>File Balasan:</strong> <a href="{{ Storage::url($booking->response_file) }}" target="_blank">Lihat File Balasan</a></p>
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
                            <p class="booking-detail"><strong>Dibuat pada:</strong> {{ $booking->created_at->format('d F Y') }}</p>
                            <div class="decoration-line"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Back Link -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
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

        /* Decoration Line */
        .decoration-line {
            height: 1px;
            background-color: #d4c8b5;
            margin-top: 15px;
            opacity: 0.7;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
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

            .btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .status-action textarea {
                height: 60px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
@endsection

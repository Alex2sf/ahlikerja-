@extends('layouts.app')

@section('title', 'Pesan Kontraktor: ' . $contractor->name)

@section('content')
    <div class="container">
        <div class="booking-form-section">
            <h1>Pesan Kontraktor: {{ $contractor->name }}</h1>
            @if (session('error'))
                <div class="notification error">{{ session('error') }}</div>
            @endif

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
            background-color: #a8c3b8; /* Hijau sage */
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
            background-color: #8ba89a; /* Hijau lebih gelap */
        }

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
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

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

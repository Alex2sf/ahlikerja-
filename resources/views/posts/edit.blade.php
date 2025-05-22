@extends('layouts.app')

@section('title', 'Edit Postingan Tugas')

@section('content')
    <div class="container">
        <div class="edit-post-section">
            <h1>Edit Postingan Tugas</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="judul">Judul:</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $post->judul) }}" required>
                    @error('judul')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $post->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar (unggah multiple, gambar lama akan diganti):</label>
                    <input type="file" id="gambar" name="gambar[]" multiple accept="image/*">
                    @error('gambar.*')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    @if ($post->gambar && count($post->gambar) > 0)
                        <h3>Gambar Lama:</h3>
                        <div class="post-images">
                            @foreach ($post->gambar as $gambar)
                                <img src="{{ Storage::url($gambar) }}" alt="Gambar Postingan">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="dokumen">Dokumen (PDF, Word, maks 5MB, dokumen lama akan diganti):</label>
                    <input type="file" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx">
                    @error('dokumen')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    @if ($post->dokumen)
                        <p>Dokumen Lama: <a href="{{ Storage::url($post->dokumen) }}" target="_blank">Lihat Dokumen</a></p>
                    @else
                        <p>Tidak ada dokumen.</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi:</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $post->lokasi) }}" required>
                    @error('lokasi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="estimasi_anggaran">Estimasi Anggaran (Rp):</label>
                    <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" value="{{ old('estimasi_anggaran', $post->estimasi_anggaran) }}" step="0.01" required>
                    @error('estimasi_anggaran')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="durasi">Durasi:</label>
                    <input type="text" id="durasi" name="durasi" value="{{ old('durasi', $post->durasi) }}" placeholder="Contoh: 2 minggu, 1 bulan" required>
                    @error('durasi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">Kembali ke Daftar Postingan</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Edit Post Section */
        .edit-post-section {
            width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .edit-post-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 8px;
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
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            height: 120px;
        }

        .form-group input[type="file"] {
            background-color: transparent;
            border: none;
            padding: 5px 0;
        }

        /* Gambar Lama */
        .form-group h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .post-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .post-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Error Message */
        .error-message {
            display: block;
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Notification */
        .notification.success {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

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

        /* Responsive Design */
        @media (max-width: 768px) {
            .edit-post-section {
                padding: 20px;
                margin: 20px;
            }

            .edit-post-section h1 {
                font-size: 28px;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('content')
    <div class="container">
        <div class="edit-profile-section">
            <!-- Back Link -->
            <div class="back-link-top">
                <a href="{{ url()->previous() }}"
                class="btn"
                style="background-color: #CD853F; /* coklat muda (peru) */
                        color: white;
                        font-weight: 600;
                        padding: 6px 14px;
                        font-size: 0.95rem;
                        border: none;
                        border-radius: 5px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
                        text-decoration: none;
                        display: inline-block;
                        cursor: pointer;">
                    Kembali
                </a>
            </div>

            <h1>Edit Profil Saya</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="notification info">{{ session('info') }}</div>
            @endif
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-columns">
                    <!-- Kolom Kiri -->
                    <div class="form-column">
                        <div class="form-group">
                        <label>Foto Profil (wajib):</label>
                        <div class="profile-photo-preview">
                            @if ($profile->foto_profile)
                                <img src="{{ Storage::url($profile->foto_profile) }}" alt="Foto Profil">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto Profil Default">
                            @endif
                        </div>
                        <input type="file" name="foto_profile" accept="image/*" required>
                        @error('foto_profile')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
</div>
                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Panggilan:</label>
                            <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $profile->nama_panggilan) }}" required>
                            @error('nama_panggilan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin:</label>
                            <select name="jenis_kelamin" required>
                                <option value="" {{ !old('jenis_kelamin', $profile->jenis_kelamin) ? 'selected' : '' }}>Pilih</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $profile->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $profile->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir:</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $profile->tanggal_lahir ? $profile->tanggal_lahir->format('Y-m-d') : '') }}" required>
                            @error('tanggal_lahir')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir:</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile->tempat_lahir) }}" required>
                            @error('tempat_lahir')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="form-column">
                        <div class="form-group">
                            <label>Alamat Lengkap:</label>
                            <textarea name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $profile->alamat_lengkap) }}</textarea>
                            @error('alamat_lengkap')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon:</label>
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $profile->nomor_telepon) }}" required placeholder="Contoh: 081234567890">
                            @error('nomor_telepon')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="{{ old('email', $profile->email) }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Media Sosial (min 1, maks 5):</label>
                            <p class="help-text">Masukkan dalam format: username(platform), contoh: nino_09(instagram)</p>
                            @for ($i = 0; $i < 5; $i++)
                                <input type="text" name="media_sosial[]" value="{{ old('media_sosial.' . $i, $profile->media_sosial[$i] ?? '') }}" placeholder="Contoh: nino_09(instagram)" {{ $i == 0 ? 'required' : '' }}>
                                @error('media_sosial.' . $i)
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            @endfor
                        </div>

                        <div class="form-group">
                            <label>Bio:</label>
                            <textarea name="bio" rows="4" placeholder="Tulis bio Anda..." required>{{ old('bio', $profile->bio) }}</textarea>
                            @error('bio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="button-group">
                <button type="submit"
                    class="btn"
                    style="background-color: #8B4513; color: white; border: none; padding: 12px 24px; font-size: 18px; border-radius: 6px;">
                    Simpan
                </button>

                </div>
            </form>
        </div>
    </div>

    <style>
        /* Edit Profile Section */
        .edit-profile-section {
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: relative; /* Untuk positioning tombol */
        }

        .edit-profile-section h1 {
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

        /* Form Columns */
        .form-columns {
            display: flex;
            gap: 40px;
        }

        .form-column {
            flex: 1;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input[type="file"] {
            background-color: transparent;
            border: none;
            padding: 5px 0;
        }

        /* Help Text for Media Sosial */
        .help-text {
            font-size: 12px;
            color: #6b5848;
            margin-bottom: 10px;
            font-style: italic;
        }

        /* Profile Photo Preview */
        .profile-photo-preview {
            margin-bottom: 15px;
            text-align: center;
        }

        .profile-photo-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
        }

        /* Error Message */
        .error-message {
            display: block;
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.info {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

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
            .edit-profile-section {
                padding: 20px;
                margin: 20px;
            }

            .edit-profile-section h1 {
                font-size: 28px;
            }

            .form-columns {
                flex-direction: column;
                gap: 20px;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: auto; /* Tombol tidak perlu full width di layar kecil */
                padding: 8px 16px;
            }

            .back-link-top {
                top: 10px;
                left: 10px;
            }
        }
    </style>
@endsection

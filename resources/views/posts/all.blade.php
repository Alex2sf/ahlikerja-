@extends('layouts.app')

@section('title', 'Semua Postingan Tugas')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter (Kiri) -->
            <div class="sidebar">
                <h2>Cari dan Filter</h2>
                <form method="GET" action="{{ route('posts.all') }}">
                    <div class="form-group">
                        <label for="search">Cari (Judul/Deskripsi):</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                    </div>
                    <div class="form-group">
                        <label for="lokasi_filter">Lokasi:</label>
                        <input type="text" id="lokasi_filter" name="lokasi" value="{{ request('lokasi') }}" placeholder="Masukkan lokasi...">
                    </div>
                    <div class="form-group">
                        <label>Estimasi Anggaran:</label>
                        <input type="number" name="anggaran_min" value="{{ request('anggaran_min') }}" placeholder="Min (Rp)" step="0.01">
                        <input type="number" name="anggaran_max" value="{{ request('anggaran_max') }}" placeholder="Max (Rp)" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="durasi_filter">Durasi:</label>
                        <input type="text" id="durasi_filter" name="durasi" value="{{ request('durasi') }}" placeholder="Contoh: 2 minggu">
                    </div>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('posts.all') }}" class="btn btn-secondary">Reset Filter</a>
                </form>
            </div>

            <!-- Main Content (Kanan) -->
            <div class="main-content">
                <h1>Semua Postingan Tugas</h1>
                @if (session('success'))
                    <div class="notification success">{{ session('success') }}</div>
                @endif

                <!-- Modal untuk Peringatan Profil Belum Lengkap -->
                <div id="profileIncompleteModal" class="modal">
                    <div class="modal-content">
                        <span class="close-profile-incomplete">×</span>
                        <p>Kamu harus mengisi profilmu dahulu.</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Isi Profil Sekarang</a>
                        <button class="btn btn-secondary" onclick="closeProfileIncompleteModal()">Tutup</button>
                    </div>
                </div>

                <!-- Form Buat Postingan Tugas (Hanya untuk User dengan role 'user') -->
                @if (Auth::check() && Auth::user()->role === 'user')
                    @php
                        $profileComplete = \App\Http\Controllers\ProfileController::isProfileComplete(Auth::user());
                    @endphp
                    <!-- Panduan Pengisian Formulir -->
                    <div class="guidelines-section">
                        <h2 class="toggle-header" onclick="toggleGuidelines()">
                            Panduan Pengisian Formulir Proyek
                            <span class="toggle-icon">▼</span>
                        </h2>
                        <div class="guidelines-content">
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
                                    <strong>4. Kirim Dokumen SPK ke Postingan</strong>
                                    <p>Setelah semua siap (formulir, gambar, SPK), silakan unggah dokumen SPK ke postingan proyek Anda melalui platform kami.</p>
                                </li>
                                <li>
                                    <strong>5. Tunggu Penawaran dari Kontraktor</strong>
                                    <p>Kontraktor akan memberikan penawaran melalui chat berupa dokumen final SPK dan harga yang ditawarkan.</p>
                                </li>
                                <li>
                                    <strong>6. Diskusi dengan Kontraktor</strong>
                                    <p>Melalui chat, Anda dan kontraktor dapat berdiskusi mengenai harga dan aturan yang disepakati.</p>
                                </li>
                                <li>
                                    <strong>7. Pilih Final Kontraktor Terpilih</strong>
                                    <p>Setelah diskusi selesai, Anda dapat memilih kontraktor dengan penawaran terbaik sebagai pemenang tender.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="create-post-section">
                        <h2>Buat Postingan Tugas</h2>
                        @if (!$profileComplete)
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.getElementById('profileIncompleteModal').style.display = 'flex';
                                });
                            </script>
                        @endif
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" @if (!$profileComplete) onsubmit="return false;" @endif>
                            @csrf
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
                                <label for="judul">Judul:</label>
                                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required @if (!$profileComplete) readonly @endif>
                                @error('judul')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi:</label>
                                <textarea id="deskripsi" name="deskripsi" required @if (!$profileComplete) readonly @endif>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar (unggah multiple):</label>
                                <input type="file" id="gambar" name="gambar[]" multiple accept="image/*" @if (!$profileComplete) disabled @endif>
                                @error('gambar.*')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="dokumen">Dokumen (PDF, Word, maks 5MB):</label>
                                <input type="file" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx" @if (!$profileComplete) disabled @endif>
                                @error('dokumen')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="lokasi">Lokasi:</label>
                                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required @if (!$profileComplete) readonly @endif>
                                @error('lokasi')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="estimasi_anggaran">Estimasi Anggaran (Rp):</label>
                                <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" step="0.01" value="{{ old('estimasi_anggaran') }}" required @if (!$profileComplete) readonly @endif>
                                @error('estimasi_anggaran')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="durasi">Durasi:</label>
                                <input type="text" id="durasi" name="durasi" value="{{ old('durasi') }}" placeholder="Contoh: 2 minggu, 1 bulan" required @if (!$profileComplete) readonly @endif>
                                @error('durasi')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" @if (!$profileComplete) disabled @endif>Posting</button>
                        </form>
                    </div>
                @endif

                <!-- Daftar Semua Postingan -->
                @if ($posts->isEmpty())
                    <p class="text-center text-muted">Tidak ada postingan.</p>
                @else
                    @foreach ($posts as $post)
                        <div class="post-card">
                            <h2>{{ $post->judul }}</h2>
                            <p>{{ $post->deskripsi }}</p>
                            <!-- Tampilkan Gambar -->
                            @if ($post->gambar && count($post->gambar) > 0)
                                <h3>Gambar:</h3>
                                <div class="post-images">
                                    @foreach ($post->gambar as $gambar)
                                        <img src="{{ Storage::url($gambar) }}" alt="Gambar Postingan" data-full-image="{{ Storage::url($gambar) }}" class="post-image">
                                    @endforeach
                                </div>
                            @else
                                <p>Tidak ada gambar.</p>
                            @endif
                            <!-- Tampilkan Dokumen -->
                            @if ($post->dokumen)
                                <p><strong>Dokumen:</strong> <a href="{{ Storage::url($post->dokumen) }}" target="_blank">Lihat Dokumen</a></p>
                            @else
                                <p>Tidak ada dokumen.</p>
                            @endif
                            <p><strong>Lokasi:</strong> {{ $post->lokasi }}</p>
                            <p><strong>Estimasi Anggaran:</strong> Rp {{ number_format($post->estimasi_anggaran, 2, ',', '.') }}</p>
                            <p><strong>Durasi:</strong> {{ $post->durasi }}</p>
                            <p><strong>Status:</strong>
                                @if ($post->status === 'open')
                                    <span class="status open">Open</span>
                                @else
                                    <span class="status closed">Closed</span>
                                @endif
                            </p>
                            <div class="user-info">
                                <p>Diposting oleh:
                                    <a href="{{ route('user.profile.show', $post->user->id) }}">
                                        {{ $post->user->name }}
                                        @if ($post->user->nama_panggilan)
                                            ({{ $post->user->nama_panggilan }})
                                        @endif
                                    </a>
                                </p>
                            </div>
                            <p><small>Dibuat pada: {{ $post->created_at->format('d F Y') }}</small></p>

                            <!-- Tombol "Berikan Penawaran" dan "Chat" untuk Kontraktor -->
                            @if (Auth::check() && Auth::user()->role === 'kontraktor' && Auth::user()->id !== $post->user_id && $post->status === 'open')
                                <div class="button-group">
                                    @php
                                        $isApproved = Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved;
                                    @endphp
                                    <form action="{{ route('offers.store', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary offer-btn" @if(!$isApproved) disabled @endif>Berikan Penawaran</button>
                                    </form>
                                    <a href="{{ route('chats.index', $post->user->id) }}" class="btn btn-primary chat-btn" @if(!$isApproved) disabled @endif>Chat</a>
                                </div>
                            @endif

                            <!-- Like -->
                            <div class="button-group">
                                <p>Jumlah Like: {{ $post->likes->count() }}</p>
                                @php
                                    $isApproved = Auth::check() && Auth::user()->role === 'kontraktor' ? (Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved) : true;
                                @endphp
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'btn-outline-primary' : 'btn-primary' }} like-btn" @if(!$isApproved) disabled @endif>
                                        {{ $post->likes()->where('user_id', Auth::id())->exists() ? 'Unlike' : 'Like' }}
                                    </button>
                                </form>
                            </div>

                            <!-- Comments -->
                            <div class="comments-section">
                                <h3>Komentar</h3>
                                @if ($post->comments->isEmpty())
                                    <p>Tidak ada komentar.</p>
                                @else
                                    @foreach ($post->comments as $comment)
                                        <div class="comment">
                                            <p><strong>
                                                <a href="{{ $comment->user->role === 'kontraktor' ? route('contractor.profile.showPublic', $comment->user->id) : route('user.profile.show', $comment->user->id) }}">
                                                    {{ $comment->user->name }}
                                                    @if ($comment->user->nama_panggilan)
                                                        ({{ $comment->user->nama_panggilan }})
                                                    @endif
                                                </a>
                                            </strong></p>
                                            <p>{{ $comment->content }}</p>
                                            <small class="text-muted">Dibuat pada: {{ $comment->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    @endforeach
                                @endif
                                <!-- Form Comment -->
                                @php
                                    $isApproved = Auth::check() && Auth::user()->role === 'kontraktor' ? (Auth::user()->contractorProfile && Auth::user()->contractorProfile->approved) : true;
                                @endphp
                                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="comment-form" @if(!$isApproved) onsubmit="return false;" @endif>
                                    @csrf
                                    <textarea name="content" placeholder="Tulis komentar..." required @if(!$isApproved) readonly @endif></textarea>
                                    <button type="submit" class="btn btn-primary comment-btn" @if(!$isApproved) disabled @endif>Kirim Komentar</button>
                                </form>
                            </div>

                            <!-- Tombol Edit, Hapus, dan Lihat Penawaran -->
                            <div class="button-group">
                                <!-- Tombol untuk Pemilik Postingan -->
                                @if (Auth::check() && Auth::user()->id === $post->user_id)
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus</button>
                                    </form>
                                    <a href="{{ route('offers.index', $post->id) }}" class="btn btn-secondary">Lihat Penawaran</a>
                                @endif

                                <!-- Tombol untuk Admin -->
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit (Admin)</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus postingan ini?')">Hapus (Admin)</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pesan untuk Kontraktor yang Belum Berlangganan -->
                    @if (Auth::check() && Auth::user()->role === 'kontraktor')
                        @php
                            $subscription = App\Models\Subscription::where('contractor_id', Auth::id())
                                ->where('is_active', true)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now())
                                ->first();
                        @endphp
                        @if (!$subscription && $totalPosts > $limit)
                            <div class="subscription-prompt">
                                <p>Anda hanya dapat melihat {{ $limit }} postingan teratas. Terdapat {{ $totalPosts - $limit }} postingan lainnya yang dapat Anda lihat dengan berlangganan.</p>
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">Berlangganan Sekarang</a>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <!-- Modal untuk Gambar Besar -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close">×</span>
                <img id="modalImage" src="" alt="Gambar Besar">
            </div>
        </div>

        <!-- Modal untuk Peringatan Belum Disetujui -->
        <div id="notApprovedModal" class="modal">
            <div class="modal-content">
                <span class="close-not-approved">×</span>
                <p>Anda harus disetujui oleh admin terlebih dahulu untuk melakukan tindakan ini.</p>
                <button class="btn btn-secondary" onclick="closeNotApprovedModal()">Tutup</button>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div>
    </div>

    <style>
        /* Row Layout */
        .row {
            display: flex;
            gap: 30px;
        }

        /* Sidebar (Filter) */
        .sidebar {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .sidebar .form-group {
            margin-bottom: 15px;
        }

        .sidebar .form-group label {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 8px;
        }

        .sidebar .form-group input {
            width: 95%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
            transition: border-color 0.3s ease;
        }

        .sidebar .form-group input:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            margin-top: 20px;
        }

        .main-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
            width: 800px;
        }

        /* Guidelines Section */
        .guidelines-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fdfaf6;
            border-radius: 10px;
            border: 1px solid #e0d8c9;
        }

        .toggle-header {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toggle-header:hover {
            color: #a8c3b8;
        }

        .toggle-icon {
            font-size: 18px;
            color: #6b5848;
            transition: transform 0.3s ease;
        }

        .guidelines-content {
            display: block;
        }

        .guidelines-content p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
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
        }

        .status {
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 5px;
        }

        .status.open {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.closed {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Create Post Section */
        .create-post-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fdfaf6;
            border-radius: 10px;
            width: 95%;
        }

        .create-post-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .create-post-section .form-group {
            margin-bottom: 15px;
        }

        .create-post-section .form-group label {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
            margin-bottom: 8px;
        }

        .create-post-section .form-group input,
        .create-post-section .form-group textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fff;
            transition: border-color 0.3s ease;
        }

        .create-post-section .form-group input:focus,
        .create-post-section .form-group textarea:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .create-post-section .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .create-post-section .form-group input[type="file"] {
            background-color: transparent;
            border: none;
            padding: 5px 0;
        }

        /* Post Card */
        .post-card {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
        }

        .post-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .post-card p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .post-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        .post-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .post-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .post-image:hover {
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
        }

        #modalImage {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 5px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #5a3e36;
            cursor: pointer;
        }

        .close:hover {
            color: #a8c3b8;
        }

        /* Modal untuk Peringatan Profil Belum Lengkap */
        #profileIncompleteModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        #profileIncompleteModal .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        #profileIncompleteModal .modal-content p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        #profileIncompleteModal .close-profile-incomplete {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #5a3e36;
            cursor: pointer;
        }

        #profileIncompleteModal .close-profile-incomplete:hover {
            color: #a8c3b8;
        }

        /* Modal untuk Peringatan Belum Disetujui */
        #notApprovedModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        #notApprovedModal .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        #notApprovedModal .modal-content p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        #notApprovedModal .close-not-approved {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            color: #5a3e36;
            cursor: pointer;
        }

        #notApprovedModal .close-not-approved:hover {
            color: #a8c3b8;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .user-info p {
            font-size: 14px;
            margin: 0;
        }

        .user-info a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .user-info a:hover {
            text-decoration: underline;
        }

        /* Like Section */
        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        /* Comments Section */
        .comments-section {
            margin-top: 20px;
        }

        .comments-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6b5848;
            margin-bottom: 15px;
        }

        .comment {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #e0d8c9;
        }

        .comment p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .comment a {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .comment a:hover {
            text-decoration: underline;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            resize: vertical;
        }

        /* Subscription Prompt */
        .subscription-prompt {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            border: 1px solid #e0d8c9;
        }

        .subscription-prompt p {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            margin-bottom: 15px;
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

        /* Error Message */
        .error-message {
            display: block;
            color: #721c24;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 8px 15px;
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

        .btn-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-danger:hover {
            background-color: #f5c6cb;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 1px solid #a8c3b8;
            color: #a8c3b8;
        }

        .btn-outline-primary:hover {
            background-color: #a8c3b8;
            color: #fff;
        }

        button[disabled], a[disabled] {
            background-color: #d4c8b5 !important;
            color: #6b5848 !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        textarea[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
            }

            .main-content {
                width: 100%;
            }

            .post-images img {
                width: 100px;
                height: 100px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .modal-content {
                width: 90%;
                padding: 10px;
            }

            #modalImage {
                max-height: 70vh;
            }

            .subscription-prompt p {
                font-size: 14px;
            }
        }
    </style>

    <script>
        // Modal untuk gambar besar
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementsByClassName('close')[0];

        document.querySelectorAll('.post-image').forEach(image => {
            image.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalImage.src = image.getAttribute('data-full-image');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Toggle untuk guidelines
        function toggleGuidelines() {
            const content = document.querySelector('.guidelines-content');
            const icon = document.querySelector('.toggle-icon');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.textContent = '▲';
            } else {
                content.style.display = 'none';
                icon.textContent = '▼';
            }
        }

        // Modal untuk peringatan belum disetujui
        const notApprovedModal = document.getElementById('notApprovedModal');
        const closeNotApproved = document.getElementsByClassName('close-not-approved')[0];

        function openNotApprovedModal() {
            notApprovedModal.style.display = 'flex';
        }

        function closeNotApprovedModal() {
            notApprovedModal.style.display = 'none';
        }

        document.querySelectorAll('.offer-btn[disabled], .chat-btn[disabled], .like-btn[disabled], .comment-btn[disabled]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                openNotApprovedModal();
            });
        });

        closeNotApproved.addEventListener('click', closeNotApprovedModal);

        notApprovedModal.addEventListener('click', (e) => {
            if (e.target === notApprovedModal) {
                closeNotApprovedModal();
            }
        });

        // Modal untuk peringatan profil belum lengkap
        const profileIncompleteModal = document.getElementById('profileIncompleteModal');
        const closeProfileIncomplete = document.getElementsByClassName('close-profile-incomplete')[0];

        function openProfileIncompleteModal() {
            profileIncompleteModal.style.display = 'flex';
        }

        function closeProfileIncompleteModal() {
            profileIncompleteModal.style.display = 'none';
        }

        closeProfileIncomplete.addEventListener('click', closeProfileIncompleteModal);

        profileIncompleteModal.addEventListener('click', (e) => {
            if (e.target === profileIncompleteModal) {
                closeProfileIncompleteModal();
            }
        });
    </script>
@endsection

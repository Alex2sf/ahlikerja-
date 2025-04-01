<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Postingan Tugas</title>

</head>
<body>
    <div class="container">
        <h1>Buat Postingan Tugas</h1>
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar (unggah multiple):</label>
                <input type="file" id="gambar" name="gambar[]" multiple>
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi:</label>
                <input type="text" id="lokasi" name="lokasi" required>
            </div>
            <div class="form-group">
                <label for="estimasi_anggaran">Estimasi Anggaran:</label>
                <input type="number" id="estimasi_anggaran" name="estimasi_anggaran" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="durasi">Durasi:</label>
                <input type="text" id="durasi" name="durasi" placeholder="Contoh: 2 minggu, 1 bulan" required>
            </div>
            <button type="submit">Posting</button>
        </form>
        <a href="{{ route('home') }}" class="back-link">Kembali ke Home</a>
    </div>
</body>
</html>

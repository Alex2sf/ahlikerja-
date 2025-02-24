<!DOCTYPE html>
<html>
<head>
    <title>Buat Postingan Tugas</title>
</head>
<body>
    <h1>Buat Postingan Tugas</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Judul:</label>
            <input type="text" name="judul" required>
        </div>
        <div>
            <label>Deskripsi:</label>
            <textarea name="deskripsi" required></textarea>
        </div>
        <div>
            <label>Gambar (unggah multiple):</label>
            <input type="file" name="gambar[]" multiple>
        </div>
        <div>
            <label>Lokasi:</label>
            <input type="text" name="lokasi" required>
        </div>
        <div>
            <label>Estimasi Anggaran:</label>
            <input type="number" name="estimasi_anggaran" step="0.01" required>
        </div>
        <div>
            <label>Durasi:</label>
            <input type="text" name="durasi" placeholder="Contoh: 2 minggu, 1 bulan" required>
        </div>
        <button type="submit">Posting</button>
    </form>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

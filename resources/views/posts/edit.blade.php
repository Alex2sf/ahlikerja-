<!DOCTYPE html>
<html>
<head>
    <title>Edit Postingan Tugas</title>
</head>
<body>
    <h1>Edit Postingan Tugas</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label>Judul:</label>
            <input type="text" name="judul" value="{{ old('judul', $post->judul) }}" required>
        </div>
        <div>
            <label>Deskripsi:</label>
            <textarea name="deskripsi" required>{{ old('deskripsi', $post->deskripsi) }}</textarea>
        </div>
        <div>
            <label>Gambar (unggah multiple, gambar lama akan diganti):</label>
            <input type="file" name="gambar[]" multiple>
            @if ($post->gambar && count($post->gambar) > 0)
                <h3>Gambar Lama:</h3>
                <div>
                    @foreach ($post->gambar as $gambar)
                        <img src="{{ Storage::url('posts/' . $gambar) }}" width="150" alt="Gambar Postingan">
                    @endforeach
                </div>
            @endif
        </div>
        <div>
            <label>Lokasi:</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $post->lokasi) }}" required>
        </div>
        <div>
            <label>Estimasi Anggaran:</label>
            <input type="number" name="estimasi_anggaran" value="{{ old('estimasi_anggaran', $post->estimasi_anggaran) }}" step="0.01" required>
        </div>
        <div>
            <label>Durasi:</label>
            <input type="text" name="durasi" value="{{ old('durasi', $post->durasi) }}" placeholder="Contoh: 2 minggu, 1 bulan" required>
        </div>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="{{ route('posts.index') }}">Kembali ke Daftar Postingan</a>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>
</head>
<body>
    <h1>Edit Profil</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Foto Profil:</label><br>
            @if (!empty($profile->foto_profile))
                <img src="{{ asset('storage/' . $profile->foto_profile) }}" width="100" alt="Foto Profil">
            @else
                <img src="{{ asset('images/default-profile.png') }}" width="100" alt="Foto Default">
            @endif
            <br>
            <input type="file" name="foto_profile">
        </div>
        <div>
            <label>Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}" required>
        </div>
        <div>
            <label>Nama Panggilan:</label>
            <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $profile->nama_panggilan) }}">
        </div>
        <div>
            <label>Jenis Kelamin:</label>
            <select name="jenis_kelamin">
                <option value="">Pilih</option>
                <option value="laki-laki" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div>
            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $profile->tanggal_lahir) }}">
        </div>
        <div>
            <label>Tempat Lahir:</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile->tempat_lahir) }}">
        </div>
        <div>
            <label>Alamat Lengkap:</label>
            <textarea name="alamat_lengkap">{{ old('alamat_lengkap', $profile->alamat_lengkap) }}</textarea>
        </div>
        <div>
            <label>Nomor Telepon:</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $profile->nomor_telepon) }}">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $profile->email ?? auth()->user()->email) }}" required>
        </div>
        <div>
            <label>Media Sosial:</label>
            <input type="text" name="media_sosial[]" value="{{ old('media_sosial.0', $profile->media_sosial[0] ?? '') }}" placeholder="Contoh: @username">
            <input type="text" name="media_sosial[]" value="{{ old('media_sosial.1', $profile->media_sosial[1] ?? '') }}" placeholder="Contoh: @username">
            <input type="text" name="media_sosial[]" value="{{ old('media_sosial.2', $profile->media_sosial[2] ?? '') }}" placeholder="Contoh: @username">
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

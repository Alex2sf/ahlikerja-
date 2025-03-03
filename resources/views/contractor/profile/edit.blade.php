<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil Kontraktor</title>
</head>
<body>
    <h1>Edit Profil Kontraktor</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div>{{ session('info') }}</div>
    @endif
    <form method="POST" action="{{ route('contractor.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Foto Profil:</label>
            @if ($profile->foto_profile)
                <img src="{{ Storage::url('contractors/' . $profile->foto_profile) }}" width="100">
            @endif
            <input type="file" name="foto_profile">
        </div>
        <div>
            <label>Nama Depan:</label>
            <input type="text" name="nama_depan" value="{{ old('nama_depan', $profile->nama_depan) }}" required>
        </div>
        <div>
            <label>Nama Belakang:</label>
            <input type="text" name="nama_belakang" value="{{ old('nama_belakang', $profile->nama_belakang) }}" required>
        </div>
        <div>
            <label>Nomor Telepon:</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $profile->nomor_telepon) }}">
        </div>
        <div>
            <label>Alamat:</label>
            <textarea name="alamat">{{ old('alamat', $profile->alamat) }}</textarea>
        </div>
        <div>
            <label>Perusahaan:</label>
            <input type="text" name="perusahaan" value="{{ old('perusahaan', $profile->perusahaan) }}" required>
        </div>
        <div>
            <label>Nomor NPWP:</label>
            <input type="text" name="nomor_npwp" value="{{ old('nomor_npwp', $profile->nomor_npwp) }}" required>
        </div>
        <div>
            <label>Bidang Usaha (maks 10):</label>
            @for ($i = 0; $i < 10; $i++)
                <input type="text" name="bidang_usaha[]" value="{{ old('bidang_usaha.' . $i, $profile->bidang_usaha[$i] ?? '') }}" placeholder="Bidang Usaha {{ $i + 1 }}">
            @endfor
        </div>
        <div>
            <label>Dokumen Pendukung (unggah multiple):</label>
            <input type="file" name="dokumen_pendukung[]" multiple>
            @if ($profile->dokumen_pendukung)
                <ul>
                    @foreach ($profile->dokumen_pendukung as $doc)
                        <li><a href="{{ Storage::url('contractors/documents/' . $doc) }}" target="_blank">{{ $doc }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div>
            <label>Portofolio (unggah multiple):</label>
            <input type="file" name="portofolio[]" multiple>

            @if (!empty($profile->portofolio) && count($profile->portofolio) > 0)
                <ul>
                    @foreach ($profile->portofolio as $port)
                        <li>
                            <a href="{{ Storage::url('contractors/portfolios/' . $port) }}" target="_blank">{{ $port }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Tambahan: Gambar Data Diri -->
        <div>
            <label>Gambar Data Diri (unggah multiple, minimal 2):</label>
            <input type="file" name="identity_images[]" multiple>

            @if (!empty($profile->identity_images) && count($profile->identity_images) > 0)
                <h3>Gambar Data Diri Saat Ini:</h3>
                <ul>
                    @foreach ($profile->identity_images as $image)
                        <li>
                            <a href="{{ Storage::url($image) }}" target="_blank">
                                <img src="{{ Storage::url($image) }}" width="150" alt="Gambar Data Diri" style="margin: 5px;">
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

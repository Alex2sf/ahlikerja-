<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kontraktor Menunggu Persetujuan</title>
</head>
<body>
    <h1>Daftar Kontraktor Menunggu Persetujuan</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($contractors->isEmpty())
        <p>Tidak ada kontraktor yang menunggu persetujuan.</p>
    @else
        @foreach ($contractors as $contractor)
            <div>
                <h2>
                    <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}">
                        {{ $contractor->user->name }}
                    </a>
                </h2>
                @if ($contractor->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}">
                        <img src="{{ Storage::url('contractors/' . $contractor->foto_profile) }}" width="100" alt="Foto Profil">
                    </a>
                @else
                    <p>Tidak ada foto profil.</p>
                @endif
                <p>Nama Lengkap: {{ $contractor->nama_depan }} {{ $contractor->nama_belakang }}</p>
                <p>Perusahaan: {{ $contractor->perusahaan }}</p>
                <p>Nomor NPWP: {{ $contractor->nomor_npwp }}</p>
                <p>Bidang Usaha:
                    @if ($contractor->bidang_usaha && count($contractor->bidang_usaha) > 0)
                        @foreach ($contractor->bidang_usaha as $usaha)
                            {{ $usaha }};
                        @endforeach
                    @else
                        Tidak diisi
                    @endif
                </p>

                <form action="{{ route('admin.contractors.approve', $contractor->id) }}" method="POST">
                    @csrf
                    <label>
                        <input type="radio" name="approved" value="1" required> Setujui
                    </label>
                    <label>
                        <input type="radio" name="approved" value="0"> Tolak
                    </label>
                    <br>
                    <label>Catatan (opsional):</label>
                    <textarea name="admin_note" placeholder="Tulis catatan untuk kontraktor..."></textarea>
                    <button type="submit">Simpan Keputusan</button>
                </form>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Publik - {{ $user->name }}</title>
</head>
<body>
    <h1>Profil Publik - {{ $user->name }}</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        <h2>Foto Profil</h2>
        @if ($profile->foto_profile)
            <img src="{{ Storage::url('profiles/' . $profile->foto_profile) }}" width="150" alt="Foto Profil">
        @else
            <p>Tidak ada foto profil.</p>
        @endif
    </div>

    <div>
        <h2>Nama Lengkap</h2>
        <p>{{ $profile->nama_lengkap }}</p>
    </div>

    <div>
        <h2>Nama Panggilan</h2>
        <p>{{ $profile->nama_panggilan ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Jenis Kelamin</h2>
        <p>{{ $profile->jenis_kelamin ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Tanggal Lahir</h2>
        <p>{{ $profile->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->format('d F Y') : 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Tempat Lahir</h2>
        <p>{{ $profile->tempat_lahir ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Alamat Lengkap</h2>
        <p>{{ $profile->alamat_lengkap ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Nomor Telepon</h2>
        <p>{{ $profile->nomor_telepon ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Email</h2>
        <p>{{ $profile->email }}</p>
    </div>

    <div>
        <h2>Media Sosial</h2>
        @if ($profile->media_sosial && count($profile->media_sosial) > 0)
            <ul>
                @foreach ($profile->media_sosial as $media)
                    <li>{{ $media ?? 'Tidak diisi' }}</li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada media sosial yang diisi.</p>
        @endif
    </div>

    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

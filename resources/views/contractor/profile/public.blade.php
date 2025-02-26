<!DOCTYPE html>
<html>
<head>
    <title>Profil Publik Kontraktor - {{ $user->name }}</title>
</head>
<body>
    <h1>Profil Publik Kontraktor - {{ $user->name }}</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        <h2>Foto Profil</h2>
        @if ($profile->foto_profile)
            <img src="{{ Storage::url('contractors/' . $profile->foto_profile) }}" width="150" alt="Foto Profil">
        @else
            <p>Tidak ada foto profil.</p>
        @endif
    </div>

    <div>
        <h2>Nama Lengkap</h2>
        <p>{{ $profile->nama_depan }} {{ $profile->nama_belakang }}</p>
    </div>

    <div>
        <h2>Nomor Telepon</h2>
        <p>{{ $profile->nomor_telepon ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Alamat</h2>
        <p>{{ $profile->alamat ?? 'Tidak diisi' }}</p>
    </div>

    <div>
        <h2>Perusahaan</h2>
        <p>{{ $profile->perusahaan }}</p>
    </div>

    <div>
        <h2>Nomor NPWP</h2>
        <p>{{ $profile->nomor_npwp }}</p>
    </div>

    <div>
        <h2>Bidang Usaha</h2>
        @if ($profile->bidang_usaha && count($profile->bidang_usaha) > 0)
            <ul>
                @foreach ($profile->bidang_usaha as $usaha)
                    <li>{{ $usaha ?? 'Tidak diisi' }}</li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada bidang usaha yang diisi.</p>
        @endif
    </div>

    <div>
        <h2>Dokumen Pendukung</h2>
        @if ($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
            <ul>
                @foreach ($profile->dokumen_pendukung as $doc)
                    <li><a href="{{ Storage::url('contractors/documents/' . $doc) }}" target="_blank">{{ $doc }}</a></li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada dokumen pendukung.</p>
        @endif
    </div>

    <div>
        <h2>Portofolio</h2>
        @if ($profile->portofolio && count($profile->portofolio) > 0)
            <ul>
                @foreach ($profile->portofolio as $port)
                    <li><a href="{{ Storage::url('contractors/portfolios/' . $port) }}" target="_blank">{{ $port }}</a></li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada portofolio.</p>
        @endif
    </div>
    @if (Auth::check())
        <a href="{{ route('chats.show', $user->id) }}">
            <button>Chat</button>
        </a>
    @endif

    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

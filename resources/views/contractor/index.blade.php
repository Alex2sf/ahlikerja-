<!DOCTYPE html>
<html>
<head>
    <title>Daftar Semua Kontraktor</title>
</head>
<body>
    <h1>Daftar Semua Kontraktor</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($contractors->isEmpty())
        <p>Tidak ada kontraktor yang terdaftar.</p>
    @else
        @foreach ($contractors as $contractor)
            <div>
                <h2>
                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}">
                        {{ $contractor->name }}
                        @if ($contractor->contractorProfile && $contractor->contractorProfile->nama_panggilan)
                            ({{ $contractor->contractorProfile->nama_panggilan }})
                        @endif
                    </a>
                </h2>
                @if ($contractor->contractorProfile && $contractor->contractorProfile->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}">
                        <img src="{{ Storage::url('contractors/' . $contractor->contractorProfile->foto_profile) }}" width="100" alt="Foto Profil">
                    </a>
                @else
                    <p>Tidak ada foto profil.</p>
                @endif
                <p>Perusahaan: {{ $contractor->contractorProfile->perusahaan ?? 'Tidak diisi' }}</p>

                @if (Auth::check())
                    <a href="{{ route('chats.show', $contractor->id) }}">
                        <button>Chat</button>
                    </a>
                @endif
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

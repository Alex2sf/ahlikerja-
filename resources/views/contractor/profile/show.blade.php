<!DOCTYPE html>
<html>
<head>
    <title>Profil Kontraktor</title>
</head>
<body>
    <h1>Profil Kontraktor</h1>
    @if (session('info'))
        <div>{{ session('info') }}</div>
    @endif

    <h1 class="mb-4">Profil Kontraktor</h1>

    <div class="card">
        <div class="card-body">
            <!-- Foto Profil -->
            @if($profile->foto_profile)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/contractors/' . $profile->foto_profile) }}" alt="Foto Profil" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                </div>
            @endif

            <!-- Nama -->
            <div class="mb-3">
                <h2>{{ $profile->nama_depan }} {{ $profile->nama_belakang }}</h2>
            </div>

            <!-- Nomor Telepon -->
            @if($profile->nomor_telepon)
                <div class="mb-3">
                    <strong>Nomor Telepon:</strong> {{ $profile->nomor_telepon }}
                </div>
            @endif

            <!-- Alamat -->
            @if($profile->alamat)
                <div class="mb-3">
                    <strong>Alamat:</strong> {{ $profile->alamat }}
                </div>
            @endif

            <!-- Perusahaan -->
            <div class="mb-3">
                <strong>Perusahaan:</strong> {{ $profile->perusahaan }}
            </div>

            <!-- NPWP -->
            <div class="mb-3">
                <strong>Nomor NPWP:</strong> {{ $profile->nomor_npwp }}
            </div>

            <!-- Bidang Usaha -->
            @if($profile->bidang_usaha && count($profile->bidang_usaha) > 0)
                <div class="mb-3">
                    <strong>Bidang Usaha:</strong>
                    <ul>
                        @foreach($profile->bidang_usaha as $bidang)
                            <li>{{ $bidang }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Dokumen Pendukung -->
            @if($profile->dokumen_pendukung && count($profile->dokumen_pendukung) > 0)
                <div class="mb-3">
                    <strong>Dokumen Pendukung:</strong>
                    <ul>
                        @foreach($profile->dokumen_pendukung as $dokumen)
                            <li>
                                <a href="{{ asset('storage/contractors/documents/' . $dokumen) }}" target="_blank">
                                    {{ $dokumen }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Portofolio -->
            @if($profile->portofolio && count($profile->portofolio) > 0)
                <div class="mb-3">
                    <strong>Portofolio:</strong>
                    <ul>
                        @if ($profile->portofolio)
                        <ul>
                            @foreach ($profile->portofolio as $port)
                                <li><a href="{{ Storage::url('contractors/portfolios/' . $port) }}" target="_blank">{{ $port }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                    </ul>
                </div>
            @endif

            <!-- Gambar Data Diri -->
            @if($profile->identity_images && count($profile->identity_images) > 0)
                <div class="mb-3">
                    {{-- <strong>Gambar Data Diri:</strong> --}}
                    {{-- @if (!empty($profile->identity_images) && count($profile->identity_images) > 0) --}}
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

            <!-- Tombol Edit -->
            <div class="text-center mt-4">
                <a href="{{ route('contractor.profile.edit') }}" class="btn btn-primary">Edit Profil</a>
            </div>
        </div>
    </div>
    <a href="{{ route('contractor.profile.edit') }}">
        <button>Edit Profil</button>
    </a>
    <a href="{{ route('home') }}">
        <button>Kembali ke Home</button>
    </a>
</body>
</html>

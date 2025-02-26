<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Saya</title>
</head>
<body>
    <h1>Pesanan Saya</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($bookings->isEmpty())
        <p>Tidak ada pesanan.</p>
    @else
        @foreach ($bookings as $booking)
            <div>
                <h2>Judul: {{ $booking->judul }}</h2>
                <p>Deskripsi: {{ $booking->deskripsi }}</p>
                @if ($booking->gambar && count($booking->gambar) > 0)
                    <h3>Gambar:</h3>
                    <div>
                        @foreach ($booking->gambar as $gambar)
                            <img src="{{ Storage::url('bookings/' . $gambar) }}" width="150" alt="Gambar Pesanan">
                        @endforeach
                    </div>
                @endif
                <p>Lokasi: {{ $booking->lokasi }}</p>
                <p>Estimasi Anggaran: Rp {{ number_format($booking->estimasi_anggaran, 2, ',', '.') }}</p>
                <p>Durasi: {{ $booking->durasi }}</p>
                <p>Kontraktor:
                    <a href="{{ route('contractor.profile.showPublic', $booking->contractor->id) }}">
                        {{ $booking->contractor->name }}
                        @if ($booking->contractor->contractorProfile && $booking->contractor->contractorProfile->nama_panggilan)
                            ({{ $booking->contractor->contractorProfile->nama_panggilan }})
                        @endif
                    </a>
                </p>
                @if ($booking->contractor->contractorProfile && $booking->contractor->contractorProfile->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $booking->contractor->id) }}">
                        <img src="{{ Storage::url('contractors/' . $booking->contractor->contractorProfile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Status: {{ $booking->status }}</p>
                <p>Dibuat pada: {{ $booking->created_at->format('d F Y') }}</p>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

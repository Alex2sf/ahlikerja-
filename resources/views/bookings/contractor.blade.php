<!DOCTYPE html>
<html>
<head>
    <title>Pesanan untuk Saya (Kontraktor)</title>
</head>
<body>
    <h1>Pesanan untuk Saya (Kontraktor)</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($bookings->isEmpty())
        <p>Tidak ada pesanan untuk Anda.</p>
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
                <p>User:
                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                        {{ $booking->user->name }}
                        @if ($booking->user->profile && $booking->user->profile->nama_panggilan)
                            ({{ $booking->user->profile->nama_panggilan }})
                        @endif
                    </a>
                </p>
                @if ($booking->user->profile && $booking->user->profile->foto_profile)
                    <a href="{{ route('user.profile.show', $booking->user->id) }}">
                        <img src="{{ Storage::url('profiles/' . $booking->user->profile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Status: {{ $booking->status }}</p>
                @if ($booking->status === 'pending')
                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit">Terima</button>
                    </form>
                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="declined">
                        <button type="submit" onclick="return confirm('Yakin ingin menolak pesanan ini?')">Tolak</button>
                    </form>
                @endif
                <p>Dibuat pada: {{ $booking->created_at->format('d F Y') }}</p>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

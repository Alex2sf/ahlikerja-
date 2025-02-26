<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Pemesanan Saya (Kontraktor)</title>
</head>
<body>
    <h1>Keranjang Pemesanan Saya (Kontraktor)</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($orders->isEmpty())
        <p>Tidak ada pemesanan untuk Anda.</p>
    @else
        @foreach ($orders as $order)
            <div>
                <h2>Postingan: {{ $order->post->judul }}</h2>
                <p>User:
                    <a href="{{ route('user.profile.show', $order->user->id) }}">
                        {{ $order->user->name }}
                        @if ($order->user->profile && $order->user->profile->nama_panggilan)
                            ({{ $order->user->profile->nama_panggilan }})
                        @endif
                    </a>
                </p>
                @if ($order->user->profile && $order->user->profile->foto_profile)
                    <a href="{{ route('user.profile.show', $order->user->id) }}">
                        <img src="{{ Storage::url('profiles/' . $order->user->profile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Lokasi: {{ $order->post->lokasi }}</p>
                <p>Durasi: {{ $order->post->durasi }}</p>
                <p>Dibuat pada: {{ $order->created_at->format('d F Y') }}</p>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

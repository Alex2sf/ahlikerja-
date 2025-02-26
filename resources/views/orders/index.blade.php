<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Pemesanan Saya</title>
</head>
<body>
    <h1>Keranjang Pemesanan Saya</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <h2>Pemesanan dari Postingan</h2>
    @if ($postOrders->isEmpty())
        <p>Tidak ada pemesanan dari postingan di keranjang.</p>
    @else
        @foreach ($postOrders as $order)
            <div>
                <h3>Postingan: {{ $order->post->judul }}</h3>
                <p>Kontraktor:
                    <a href="{{ route('contractor.profile.showPublic', $order->contractor->id) }}">
                        {{ $order->contractor->name }}
                        @if ($order->contractor->contractorProfile && $order->contractor->contractorProfile->nama_panggilan)
                            ({{ $order->contractor->contractorProfile->nama_panggilan }})
                        @endif
                    </a>
                </p>
                @if ($order->contractor->contractorProfile && $order->contractor->contractorProfile->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $order->contractor->id) }}">
                        <img src="{{ Storage::url('contractors/' . $order->contractor->contractorProfile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Lokasi: {{ $order->post->lokasi }}</p>
                <p>Durasi: {{ $order->post->durasi }}</p>
                <p>Dibuat pada: {{ $order->created_at->format('d F Y') }}</p>
                <hr>
            </div>
        @endforeach
    @endif

    <h2>Pemesanan Langsung ke Kontraktor</h2>
    @if ($bookingOrders->isEmpty())
        <p>Tidak ada pemesanan langsung ke kontraktor di keranjang.</p>
    @else
        @foreach ($bookingOrders as $bookingOrder)
            <div>
                <h3>Judul Pesanan: {{ $bookingOrder->judul }}</h3>
                <p>Deskripsi: {{ $bookingOrder->deskripsi }}</p>
                @if ($bookingOrder->gambar && count($bookingOrder->gambar) > 0)
                    <h4>Gambar:</h4>
                    <div>
                        @foreach ($bookingOrder->gambar as $gambar)
                            <img src="{{ Storage::url('bookings/' . $gambar) }}" width="150" alt="Gambar Pesanan">
                        @endforeach
                    </div>
                @endif
                <p>Lokasi: {{ $bookingOrder->lokasi }}</p>
                <p>Estimasi Anggaran: Rp {{ number_format($bookingOrder->estimasi_anggaran, 2, ',', '.') }}</p>
                <p>Durasi: {{ $bookingOrder->durasi }}</p>
                <p>Kontraktor:
                    <a href="{{ route('contractor.profile.showPublic', $bookingOrder->contractor->id) }}">
                        {{ $bookingOrder->contractor->name }}
                        @if ($bookingOrder->contractor->contractorProfile && $bookingOrder->contractor->contractorProfile->nama_panggilan)
                            ({{ $bookingOrder->contractor->contractorProfile->nama_panggilan }})
                        @endif
                    </a>
                </p>
                @if ($bookingOrder->contractor->contractorProfile && $bookingOrder->contractor->contractorProfile->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $bookingOrder->contractor->id) }}">
                        <img src="{{ Storage::url('contractors/' . $bookingOrder->contractor->contractorProfile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Status: {{ $bookingOrder->status }}</p>
                <p>Dibuat pada: {{ $bookingOrder->created_at->format('d F Y') }}</p>
                <hr>
            </div>
        @endforeach
    @endif

    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

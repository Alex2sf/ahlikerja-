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
                <p>Kontraktor: <a href="{{ route('contractor.profile.showPublic', $order->contractor->id) }}">{{ $order->contractor->name }}</a></p>
                <p>Status: {{ $order->is_completed ? 'Selesai' : 'Belum Selesai' }}</p>

                @if (!$order->is_completed)
                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Tandai pemesanan ini selesai?')">Selesai</button>
                    </form>
                @elseif (!$order->review)
                    <form action="{{ route('orders.review', $order->id) }}" method="POST">
                        @csrf
                        <label>Rating (1-5):</label>
                        <select name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <br>
                        <label>Ulasan:</label>
                        <textarea name="review" placeholder="Tulis ulasan Anda..." rows="3"></textarea>
                        <br>
                        <button type="submit">Kirim Ulasan</button>
                    </form>
                @else
                    <p>Rating: {{ $order->review->rating }}/5</p>
                    <p>Ulasan: {{ $order->review->review ?? 'Tidak ada ulasan' }}</p>
                @endif
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
                <p>Kontraktor: <a href="{{ route('contractor.profile.showPublic', $bookingOrder->contractor->id) }}">{{ $bookingOrder->contractor->name }}</a></p>
                <p>Status: {{ $bookingOrder->is_completed ? 'Selesai' : 'Belum Selesai' }}</p>

                @if (!$bookingOrder->is_completed)
                    <form action="{{ route('bookings.complete', $bookingOrder->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Tandai pemesanan ini selesai?')">Selesai</button>
                    </form>
                @elseif (!$bookingOrder->review)
                    <form action="{{ route('orders.review', $bookingOrder->id) }}" method="POST">
                        @csrf
                        <label>Rating (1-5):</label>
                        <select name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <br>
                        <label>Ulasan:</label>
                        <textarea name="review" placeholder="Tulis ulasan Anda..." rows="3"></textarea>
                        <br>
                        <button type="submit">Kirim Ulasan</button>
                    </form>
                @else
                    <p>Rating: {{ $bookingOrder->review->rating }}/5</p>
                    <p>Ulasan: {{ $bookingOrder->review->review ?? 'Tidak ada ulasan' }}</p>
                @endif
                <hr>
            </div>
        @endforeach
    @endif

    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>

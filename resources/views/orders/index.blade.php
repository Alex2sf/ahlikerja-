@extends('layouts.app')

@section('title', 'Keranjang Pemesanan Saya')

@section('content')
    <div class="container">
        <div class="orders-section">
            <!-- Back Link -->
            <div class="back-link-top">
                <a href="{{ url()->previous() }}"
                class="btn"
                style="background-color: #CD853F; /* coklat muda (peru) */
                        color: white;
                        font-weight: 600;
                        padding: 6px 14px;
                        font-size: 0.95rem;
                        border: none;
                        border-radius: 5px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
                        text-decoration: none;
                        display: inline-block;
                        cursor: pointer;">
                    Kembali
                </a>
            </div>

            <h1>Keranjang Pembayaran</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif

            <div class="orders-columns">
                <!-- Pemesanan dari Postingan (Kolom Kiri) -->
                <div class="orders-column">
                    <h2>Pemesanan dari Postingan</h2>
                    @if ($postOrders->isEmpty())
                        <p class="text-center text-muted">Tidak ada pemesanan dari postingan di keranjang.</p>
                    @else
                        @foreach ($postOrders as $order)
                            <div class="order-card">
                                <h3>Postingan: {{ $order->post->judul }}</h3>
                                <p>Kontraktor:
                                    <a href="{{ route('contractor.profile.showPublic', $order->contractor->id) }}" class="contractor-link">
                                        {{ $order->contractor->contractorProfile ? $order->contractor->contractorProfile->perusahaan : 'Perusahaan belum diatur' }}
                                    </a>
                                </p>
                                <p>Status: <span class="status {{ $order->is_completed ? 'completed' : 'pending' }}">{{ $order->is_completed ? 'Selesai' : 'Belum Selesai' }}</span></p>
<small style="color: black; font-weight: bold;">
    Jika Anda memiliki beberapa tahapan pembayaran, pastikan setiap tahapan diselesaikan secara berurutan.<br>
        <span style="color: red;">Contoh: Tahap 1 →  Selesai.</span><br>
    <span style="color: red;">Contoh: Tahap 1 → Tahap 2 → Selesai.</span><br>
    <span style="color: red;">Contoh: Tahap 1 → Tahap 2 → Tahap 3 → Selesai.</span><br>

    Setelah status menjadi <strong style="color: red;">Selesai</strong>, maka tahapan terakhir dianggap terselesaikan otomatis dan tidak perlu melanjutkan ke tahap berikutnya.
</small>

                                @if (!$order->is_completed)
                                    <div class="button-group">
                                        <!-- Tombol Selesai -->
<button class="btn btn-success"
        onclick="toggleForm('complete-form-order-{{ $order->id }}')"
        style="background-color: #5a3e36; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease;"
        onmouseover="this.style.backgroundColor='#3f2b24'"
        onmouseout="this.style.backgroundColor='#5a3e36'">Selesai</button>
                                        <!-- Tombol Pembayaran Bertahap (Opsional) -->
                                        @if ($order->payment_stage < 4)
<button class="btn btn-primary"
        onclick="toggleForm('payment-form-order-{{ $order->id }}')"
        style="background-color: #5a3e36; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease;"
        onmouseover="this.style.backgroundColor='#3f2b24'"
        onmouseout="this.style.backgroundColor='#5a3e36'">Pembayaran Tahap {{ $order->payment_stage + 1 }}</button>                                        @endif
                                    </div>

                                    <!-- Form Selesai -->
                                    <form id="complete-form-order-{{ $order->id }}" action="{{ route('orders.complete', $order->id) }}" method="POST" enctype="multipart/form-data" class="form-hidden">
                                        @csrf
                                        <div class="form-row">
                                            <label>Bukti Pembayaran Terakhir (Wajib):</label>
                                            <input type="file" name="payment_proof" accept="image/*" required>
                                            @error('payment_proof')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-row">
                                            <label>Rating (1-5):</label>
                                            <select name="rating" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <label>Ulasan:</label>
                                            <textarea name="review" placeholder="Tulis ulasan Anda..." rows="3" required></textarea>
                                        </div>
                                        <div class="form-actions">
<button type="submit"
        class="btn"
        style="background-color: #8B4513;   /* saddle brown - coklat gelap */
               color: white;
               font-weight: 600;
               padding: 8px 18px;
               font-size: 0.95rem;
               border: none;
               border-radius: 5px;
               box-shadow: 0 3px 6px rgba(0,0,0,0.15);">
  Selesaikan dan Kirim Ulasan
</button>
                                            <button type="button" class="btn btn-secondary" onclick="toggleForm('complete-form-order-{{ $order->id }}')">Batal</button>
                                        </div>
                                    </form>

                                    <!-- Form Pembayaran Bertahap -->
                                    @if ($order->payment_stage < 4)
                                        <form id="payment-form-order-{{ $order->id }}" action="{{ route('orders.uploadPaymentProof', ['id' => $order->id, 'type' => 'order', 'stage' => $order->payment_stage + 1]) }}" method="POST" enctype="multipart/form-data" class="form-hidden">
                                            @csrf
                                            <div class="form-row">
                                                <label>Bukti Pembayaran Tahap {{ $order->payment_stage + 1 }}:</label>
                                                <input type="file" name="payment_proof" accept="image/*" required>
                                                @error('payment_proof')
                                                    <span class="error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-actions">
                                            <button type="submit"
                                                    class="btn"
                                                    style="background-color: #8B4513;  /* saddle brown */
                                                        color: white;
                                                        font-weight: 600;
                                                        padding: 6px 14px;
                                                        font-size: 0.95rem;
                                                        border: none;
                                                        border-radius: 5px;
                                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            Unggah
                                            </button>
                                                <button type="button" class="btn btn-secondary" onclick="toggleForm('payment-form-order-{{ $order->id }}')">Batal</button>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- Tampilkan Bukti Pembayaran yang Sudah Diunggah -->
                                    @for ($i = 1; $i <= $order->payment_stage; $i++)
                                        @if ($order->{"payment_proof_$i"})
                                            <div class="payment-proof">
                                                <p>Bukti Pembayaran Tahap {{ $i }}:</p>
                                                <img src="{{ Storage::url($order->{"payment_proof_$i"}) }}" alt="Bukti Pembayaran Tahap {{ $i }}" class="payment-image">
                                            </div>
                                        @endif
                                    @endfor
                                @elseif ($order->is_completed && $order->review)
                                    <div class="review-details">
                                        <p>Rating: {{ $order->review->rating }}/5</p>
                                        <p>Ulasan: {{ $order->review->review ?? 'Tidak ada ulasan' }}</p>
                                        @if ($order->review->pembayaran)
                                            <p>Bukti Pembayaran (Review):</p>
                                            <img src="{{ Storage::url($order->review->pembayaran) }}" alt="Bukti Pembayaran" class="payment-image">
                                        @else
                                            <p>Tidak ada bukti pembayaran (review).</p>
                                        @endif
                                        <!-- Tampilkan semua bukti pembayaran -->
                                        @for ($i = 1; $i <= $order->payment_stage; $i++)
                                            @if ($order->{"payment_proof_$i"})
                                                <div class="payment-proof">
                                                    <p>Bukti Pembayaran Tahap {{ $i }}:</p>
                                                    <img src="{{ Storage::url($order->{"payment_proof_$i"}) }}" alt="Bukti Pembayaran Tahap {{ $i }}" class="payment-image">
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Pemesanan Langsung ke Kontraktor (Kolom Kanan) -->
                <div class="orders-column">
                    <h2>Pemesanan Langsung ke Kontraktor</h2>
                    @if ($bookingOrders->isEmpty())
                        <p class="text-center text-muted">Tidak ada pemesanan langsung ke kontraktor di keranjang.</p>
                    @else
                        @foreach ($bookingOrders as $bookingOrder)
                            <div class="order-card">
                                <h3>Judul Pesanan: {{ $bookingOrder->judul }}</h3>
                                <p>Kontraktor:
                                    <a href="{{ route('contractor.profile.showPublic', $bookingOrder->contractor->id) }}" class="contractor-link">
                                        {{ $bookingOrder->contractor->contractorProfile ? $bookingOrder->contractor->contractorProfile->perusahaan : 'Perusahaan belum diatur' }}
                                    </a>
                                </p>
                                <p>Status: <span class="status {{ $bookingOrder->is_completed ? 'completed' : 'pending' }}">{{ $bookingOrder->is_completed ? 'Selesai' : 'Belum Selesai' }}</span></p>
<small style="color: black; font-weight: bold;">
    Jika Anda memiliki beberapa tahapan pembayaran, pastikan setiap tahapan diselesaikan secara berurutan.<br>
        <span style="color: red;">Contoh: Tahap 1 →  Selesai.</span><br>
    <span style="color: red;">Contoh: Tahap 1 → Tahap 2 → Selesai.</span><br>
    <span style="color: red;">Contoh: Tahap 1 → Tahap 2 → Tahap 3 → Selesai.</span><br>

    Setelah status menjadi <strong style="color: red;">Selesai</strong>, maka tahapan terakhir dianggap terselesaikan otomatis dan tidak perlu melanjutkan ke tahap berikutnya.
</small>


                                @if (!$bookingOrder->is_completed)
                                    <div class="button-group">
                                        <!-- Tombol Selesai -->
<button class="btn btn-success"
        onclick="toggleForm('complete-form-booking-{{ $bookingOrder->id }}')"
        style="background-color: #5a3e36; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease;"
        onmouseover="this.style.backgroundColor='#3f2b24'"
        onmouseout="this.style.backgroundColor='#5a3e36'">Selesai</button>
                                        <!-- Tombol Pembayaran Bertahap (Opsional) -->
                                        @if ($bookingOrder->payment_stage < 4)
<button class="btn btn-primary"
        onclick="toggleForm('payment-form-booking-{{ $bookingOrder->id }}')"
        style="background-color: #5a3e36; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease;"
        onmouseover="this.style.backgroundColor='#3f2b24'"
        onmouseout="this.style.backgroundColor='#5a3e36'">Pembayaran Tahap {{ $bookingOrder->payment_stage + 1 }}</button>                                        @endif
                                    </div>

                                    <!-- Form Selesai -->
                                    <form id="complete-form-booking-{{ $bookingOrder->id }}" action="{{ route('bookings.complete', $bookingOrder->id) }}" method="POST" enctype="multipart/form-data" class="form-hidden">
                                        @csrf
                                        <div class="form-row">
                                            <label>Bukti Pembayaran Terakhir (Wajib):</label>
                                            <input type="file" name="payment_proof" accept="image/*" required>
                                            @error('payment_proof')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-row">
                                            <label>Rating (1-5):</label>
                                            <select name="rating" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <label>Ulasan:</label>
                                            <textarea name="review" placeholder="Tulis ulasan Anda..." rows="3" required></textarea>
                                        </div>
                                        <div class="form-actions">
                                        <button type="submit"
                                                class="btn"
                                                style="background-color: #8B4513;   /* saddle brown - coklat gelap */
                                                    color: white;
                                                    font-weight: 600;
                                                    padding: 8px 18px;
                                                    font-size: 0.95rem;
                                                    border: none;
                                                    border-radius: 5px;
                                                    box-shadow: 0 3px 6px rgba(0,0,0,0.15);">
                                        Selesaikan dan Kirim Ulasan
                                        </button>
                                            <button type="button" class="btn btn-secondary" onclick="toggleForm('complete-form-booking-{{ $bookingOrder->id }}')">Batal</button>
                                        </div>
                                    </form>

                                    <!-- Form Pembayaran Bertahap -->
                                    @if ($bookingOrder->payment_stage < 4)
                                        <form id="payment-form-booking-{{ $bookingOrder->id }}" action="{{ route('orders.uploadPaymentProof', ['id' => $bookingOrder->id, 'type' => 'booking', 'stage' => $bookingOrder->payment_stage + 1]) }}" method="POST" enctype="multipart/form-data" class="form-hidden">
                                            @csrf
                                            <div class="form-row">
                                                <label>Bukti Pembayaran Tahap {{ $bookingOrder->payment_stage + 1 }}:</label>
                                                <input type="file" name="payment_proof" accept="image/*" required>
                                                @error('payment_proof')
                                                    <span class="error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-actions">
                                            <button type="submit"
                                                    class="btn"
                                                    style="background-color: #8B4513;  /* saddle brown */
                                                        color: white;
                                                        font-weight: 600;
                                                        padding: 6px 14px;
                                                        font-size: 0.95rem;
                                                        border: none;
                                                        border-radius: 5px;
                                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            Unggah
                                            </button>
                                            <button type="button" class="btn btn-secondary" onclick="toggleForm('payment-form-booking-{{ $bookingOrder->id }}')">Batal</button>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- Tampilkan Bukti Pembayaran yang Sudah Diunggah -->
                                    @for ($i = 1; $i <= $bookingOrder->payment_stage; $i++)
                                        @if ($bookingOrder->{"payment_proof_$i"})
                                            <div class="payment-proof">
                                                <p>Bukti Pembayaran Tahap {{ $i }}:</p>
                                                <img src="{{ Storage::url($bookingOrder->{"payment_proof_$i"}) }}" alt="Bukti Pembayaran Tahap {{ $i }}" class="payment-image">
                                            </div>
                                        @endif
                                    @endfor
                                @elseif ($bookingOrder->is_completed && $bookingOrder->review)
                                    <div class="review-details">
                                        <p>Rating: {{ $bookingOrder->review->rating }}/5</p>
                                        <p>Ulasan: {{ $bookingOrder->review->review ?? 'Tidak ada ulasan' }}</p>
                                        @if ($bookingOrder->review->pembayaran)
                                            <p>Bukti Pembayaran (Review):</p>
                                            <img src="{{ Storage::url($bookingOrder->review->pembayaran) }}" alt="Bukti Pembayaran" class="payment-image">
                                        @else
                                            <p>Tidak ada bukti pembayaran (review).</p>
                                        @endif
                                        <!-- Tampilkan semua bukti pembayaran -->
                                        @for ($i = 1; $i <= $bookingOrder->payment_stage; $i++)
                                            @if ($bookingOrder->{"payment_proof_$i"})
                                                <div class="payment-proof">
                                                    <p>Bukti Pembayaran Tahap {{ $i }}:</p>
                                                    <img src="{{ Storage::url($bookingOrder->{"payment_proof_$i"}) }}" alt="Bukti Pembayaran Tahap {{ $i }}" class="payment-image">
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Orders Section */

        .orders-section {
            width: 1200px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: relative;
        }

        .orders-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Back Link di Pojok Kiri Atas */
        .back-link-top {
            position: absolute;
            top: 15px;
            left: 15px;
        }

        /* Orders Columns */
        .orders-columns {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .orders-column {
            flex: 1;
            padding: 10px;
        }

        .orders-column h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #6b5848;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Order Card */
        .order-card {
            background-color: #fdfaf6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-4px);
        }

        .order-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #5a3e36;
            margin-bottom: 8px;
        }

        .order-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .contractor-link {
            color: #5a3e36;
            text-decoration: none;
            font-weight: 500;
        }

        .contractor-link:hover {
            text-decoration: underline;
            color: #a8c3b8;
        }

        .status {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Payment Proof */
        .payment-proof {
            margin-top: 10px;
        }

        .payment-proof p {
            font-size: 14px;
            color: #6b5848;
        }

        .payment-image {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
            margin-top: 5px;
        }

        /* Review Form */
        .form-hidden {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #e0d8c9;
            border-radius: 5px;
        }

        .form-row {
            margin-bottom: 8px;
        }

        .form-row label {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            color: #6b5848;
            margin-bottom: 4px;
        }

        .form-row select,
        .form-row textarea,
        .form-row input[type="file"] {
            width: 100%;
            padding: 6px;
            border: 1px solid #d4c8b5;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
            background-color: #fff;
        }

        .form-row textarea {
            resize: vertical;
            height: 60px;
        }

        .form-row select:focus,
        .form-row textarea:focus,
        .form-row input[type="file"]:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        /* Review Details */
        .review-details {
            margin-top: 10px;
        }

        .review-details p {
            font-size: 14px;
            color: #555;
            margin: 4px 0;
        }

        /* Error Message */
        .error-message {
            display: block;
            color: #721c24;
            font-size: 12px;
            margin-top: 3px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8ba89a;
        }

        .btn-success {
            background-color: #a8c3b8;
        }

        .btn-success:hover {
            background-color: #8ba89a;
        }

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5;
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Notification */
        .notification.success {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 13px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .orders-section {
                width: 100%;
                padding: 15px;
                margin: 15px;
            }

            .orders-section h1 {
                font-size: 24px;
            }

            .orders-columns {
                flex-direction: column;
                gap: 10px;
            }

            .orders-column h2 {
                font-size: 18px;
            }

            .order-card h3 {
                font-size: 16px;
            }

            .button-group {
                flex-direction: row;
                gap: 5px;
            }

            .btn {
                padding: 5px 10px;
                font-size: 12px;
            }

            .back-link-top {
                top: 10px;
                left: 10px;
            }

            .form-row textarea {
                height: 50px;
            }

            .payment-image {
                max-width: 100px;
            }
        }
    </style>

    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            if (form.style.display === 'block') {
                form.style.display = 'none';
            } else {
                // Sembunyikan semua form terlebih dahulu
                document.querySelectorAll('.form-hidden').forEach(f => f.style.display = 'none');
                form.style.display = 'block';
            }
        }
    </script>
@endsection

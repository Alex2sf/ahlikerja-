@extends('layouts.app')

@section('title', 'Daftar Semua Penawaran Aktif')

@section('content')
    <!-- Header Section -->
    <header class="header">
        <h1 class="header-title">Daftar Semua Penawaran Aktif</h1>
        <nav class="header-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            <a href="#" class="nav-link">Keluar</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Notifikasi Sukses (Opsional, sesuaikan dengan logika) -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Penawaran -->
        <div class="table-container">
            @if ($bookings->isEmpty())
                <p class="text-muted text-center">Tidak ada penawaran aktif saat ini.</p>
            @else
                <table class="offer-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Durasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->durasi }}</td>
                                <td>{{ $booking->status ?? 'Pending' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="back-link">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} - Sistem Manajemen Kontraktor. Semua hak dilindungi.</p>
        <div class="footer-links">
            <a href="#">Tentang Kami</a> | <a href="#">Kebijakan Privasi</a> | <a href="#">Kontak</a>
        </div>
    </footer>
@endsection

<style>
    /* General Styles */
    body {
        background-color: #fdfaf6; /* Krem vintage */
        font-family: 'Roboto', sans-serif;
        color: #555;
        margin: 0;
        padding: 0;
    }

    /* Header */
    .header {
        background-color: #a8c3b8; /* Hijau sage */
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .header-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #fff;
        margin: 0;
    }

    .header-nav {
        margin-top: 10px;
    }

    .nav-link {
        color: #fff;
        text-decoration: none;
        margin: 0 15px;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #d4c8b5; /* Beige */
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
    }

    /* Notification */
    .notification.success {
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        font-size: 14px;
    }

    /* Table Container */
    .table-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0d8c9;
    }

    .offer-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .offer-table th,
    .offer-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0d8c9;
        font-size: 14px;
    }

    .offer-table th {
        background-color: #d4c8b5; /* Beige */
        color: #5a3e36;
        font-family: 'Playfair Display', serif;
        font-size: 16px;
    }

    .offer-table tr:hover {
        background-color: #f5f0e9; /* Efek hover vintage */
        transition: background-color 0.3s ease;
    }

    .text-muted {
        color: #6b5848;
        font-style: italic;
    }

    /* Back Link */
    .back-link {
        text-align: center;
        margin-top: 20px;
    }

    /* Button Styles */
    .btn {
        background-color: #a8c3b8;
        border: none;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn:hover {
        background-color: #8ba89a;
    }

    .btn-secondary {
        background-color: #d4c8b5;
        color: #5a3e36;
    }

    .btn-secondary:hover {
        background-color: #c7b9a1;
    }

    /* Footer */
    .footer {
        background-color: #5a3e36; /* Cokelat tua */
        color: #fff;
        text-align: center;
        padding: 15px 0;
        margin-top: 30px;
        border-top: 1px solid #a8c3b8;
    }

    .footer p {
        margin: 0;
        font-size: 12px;
    }

    .footer-links a {
        color: #d4c8b5;
        text-decoration: none;
        margin: 0 10px;
        font-size: 12px;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #a8c3b8;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-title {
            font-size: 24px;
        }

        .nav-link {
            margin: 0 10px;
            font-size: 12px;
        }

        .container {
            margin: 20px auto;
            padding: 15px;
        }

        .table-container {
            padding: 15px;
        }

        .offer-table th,
        .offer-table td {
            padding: 10px;
            font-size: 12px;
        }

        .offer-table th {
            font-size: 14px;
        }

        .btn {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>

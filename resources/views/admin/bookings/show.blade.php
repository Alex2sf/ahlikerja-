@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
    <!-- Header -->
    <header class="header">
        <div class="container">
            <h2 class="logo">Admin Panel</h2>
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-link">Daftar Booking</a>
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <h1 class="page-title">Detail Booking</h1>

            <div class="booking-card">
                <div class="booking-detail">
                    <p><strong>Pengguna:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Durasi:</strong> {{ $booking->durasi }}</p>
                    <p><strong>Status:</strong> <span class="status {{ $booking->status === 'Approved' ? 'approved' : ($booking->status === 'Rejected' ? 'rejected' : 'pending') }}">{{ $booking->status ?? 'Pending' }}</span></p>
                </div>
                <div class="action-links">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">&copy; {{ date('Y') }} xAI Project. All rights reserved.</p>
            <p class="footer-text">Kontak: support@xai-project.com | +62 812 3456 7890</p>
        </div>
    </footer>
@endsection

<style>
    /* General Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f9f5f0;
        color: #555;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Header */
    .header {
        background-color: #5a3e36;
        color: #fff;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
    }

    .nav {
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: flex-end;
    }

    .nav-link {
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #a8c3b8;
    }

    /* Main Content */
    .main-content {
        padding: 40px 0;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #5a3e36;
        text-align: center;
        margin-bottom: 30px;
    }

    .booking-card {
        background-color: #fdfaf6;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0d8c9;
        max-width: 600px;
        margin: 0 auto;
        transition: transform 0.3s ease;
    }

    .booking-card:hover {
        transform: translateY(-5px);
    }

    .booking-detail p {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .booking-detail strong {
        color: #6b5848;
    }

    .status {
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
    }

    .status.pending {
        background-color: #f8e4b3;
        color: #8a6d3b;
    }

    .status.approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status.rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .action-links {
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
        background-color: #5a3e36;
        color: #fff;
        padding: 20px 0;
        margin-top: 40px;
        text-align: center;
    }

    .footer-text {
        font-size: 14px;
        margin: 5px 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }

        .header .nav {
            flex-direction: column;
            gap: 10px;
        }

        .page-title {
            font-size: 24px;
        }

        .booking-card {
            padding: 15px;
        }

        .booking-detail p {
            font-size: 14px;
        }

        .footer {
            padding: 15px 0;
        }
    }
</style>

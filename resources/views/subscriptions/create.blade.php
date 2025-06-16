@extends('layouts.app')

@section('title', 'Berlangganan untuk Melihat Semua Postingan Tugas')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <div class="subscription-header" style="background-color: #a8c3b8; padding: 20px; border-radius: 10px 10px 0 0;">
            <h1 class="text-center text-white">Berlangganan untuk Melihat Semua Postingan Tugas</h1>
        </div>

        <!-- Notifikasi -->
        @if (session('error'))
            <div class="notification error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notification error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Subscription Card -->
        <div class="subscription-card">
            <div class="subscription-content">
                <p class="subscription-text">
                    Dengan berlangganan sebesar <strong>Rp1 per bulan</strong>, Anda dapat melihat semua postingan tugas selama 1 bulan. Nikmati akses penuh untuk mendukung produktivitas Anda!
                </p>
                <form action="{{ route('subscriptions.store') }}" method="POST" class="subscription-form">
                    @csrf
                    <input type="hidden" name="plan_id" value="1"> <!-- Ganti "1" sesuai ID plan yang ada -->
                    <button type="submit"
                        class="btn"
                        style="background-color: #A0522D;  /* sienna (coklat hangat) */
                            color: white;
                            font-weight: 600;
                            padding: 6px 16px;
                            font-size: 0.95rem;
                            border: none;
                            border-radius: 5px;
                            box-shadow: 0 3px 5px rgba(0,0,0,0.1);">
                    Berlangganan Sekarang
                    </button>
                </form>
            </div>
            <div class="decoration-line"></div>
            <div class="back-link">
                <a href="{{ route('home') }}"
                class="btn"
                style="background-color: #CD853F;  /* peru - coklat muda */
                        color: white;
                        font-weight: 600;
                        padding: 6px 14px;
                        font-size: 0.95rem;
                        border: none;
                        border-radius: 5px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                Kembali ke Home
                </a>
            </div>
        </div>
    </div>

    <style>
        /* General Container */
        .containers{
            width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Subscription Header */
        .subscription-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #e0d8c9;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* Subscription Card */
        .subscription-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            text-align: center;
        }

        .subscription-content {
            margin-bottom: 20px;
        }

        .subscription-text {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #5a3e36;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .subscription-form {
            margin-bottom: 20px;
        }

        /* Decoration Line */
        .decoration-line {
            height: 2px;
            background-color: #d4c8b5;
            margin: 20px 0;
            opacity: 0.7;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8;
            border: none;
            color: #fff;
            padding: 10px 20px;
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

        /* Back Link */
        .back-link {
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .containers {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
            }

            .subscription-card {
                padding: 20px;
            }

            .subscription-text {
                font-size: 14px;
            }

            .btn {
                padding: 8px 15px;
                font-size: 12px;
            }
        }
    </style>
@endsection

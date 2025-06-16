@extends('layouts.app')

@section('title', 'Penawaran Saya')

@section('content')
    <div class="container">
        <h1>Penawaran Saya</h1>

        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        @if ($offers->isEmpty())
            <p class="text-muted text-center">Anda belum mengirimkan penawaran apa pun.</p>
        @else
            <div class="offers-list">
                @foreach ($offers as $offer)
                    <div class="offer-card">
                        <h3>{{ $offer->post->judul }}</h3>
                        <p><strong>Status:</strong>
                            @if ($offer->status === 'accepted')
                                <span style="color: #28a745;">Diterima</span>
                            @elseif ($offer->status === 'not_selected')
                                <span style="color: #dc3545;">Tidak Dipilih</span>
                            @else
                                <span>Pending</span>
                            @endif
                        </p>
                        <p><strong>Pemilik Tender:</strong> <a href="{{ route('user.profile.show', $offer->post->user->id) }}">{{ $offer->post->user->name }}</a></p>
                        <p><strong>Tanggal Pengajuan:</strong> {{ $offer->created_at->format('d M Y H:i') }}</p>
                        <a href="{{ route('posts.all') }}?search={{ urlencode($offer->post->judul) }}"
                        class="btn"
                        style="
                            background-color: #CD853F; /* Coklat muda */
                            color: white;
                            font-weight: 600;
                            padding: 8px 16px;
                            font-size: 0.95rem;
                            border: none;
                            border-radius: 5px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                            text-decoration: none;
                            display: inline-block;
                            cursor: pointer;">
                        Lihat Detail Tender
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .container {
            width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 20px;
        }

        .notification.success {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            font-size: 14px;
        }

        .offers-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .offer-card {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 1px solid #e0d8c9;
        }

        .offer-card:hover {
            transform: translateY(-5px);
        }

        .offer-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #6b5848;
            margin-bottom: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .offer-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .offer-card a {
            color: #a8c3b8;
            text-decoration: none;
        }

        .offer-card a:hover {
            color: #8ba89a;
            text-decoration: underline;
        }

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

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
            background-color: #8ba89a;
        }

        @media (max-width: 768px) {
            .offers-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="containers">
        <!-- Header Section -->
        <h1>Dashboard Admin</h1>

        <!-- Statistik Ringkas -->
        <div class="stats-section">
            <div class="stat-card">
                <h3>Total Pengguna</h3>
                <p>{{ $totalUsers }}</p>
            </div>
            <div class="stat-card">
                <h3>Kontraktor Menunggu</h3>
                <p>{{ $pendingContractors }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Postingan Tugas</h3>
                <p>{{ $totalPosts }}</p>
            </div>
            <div class="stat-card">
                <h3>Penawaran Aktif</h3>
                <p>{{ $activeOffers }}</p>
            </div>
            <div class="stat-card">
                <h3>Postingan Baru Hari Ini</h3>
                <p>{{ $postsToday }}</p>
            </div>
        </div>

        <!-- Daftar Kontraktor Menunggu Persetujuan -->
        <div class="pending-contractors-section">
            <h2>Kontraktor Menunggu Persetujuan</h2>
            @if ($pendingContractorsList->isEmpty())
                <p class="text-muted">Tidak ada kontraktor menunggu persetujuan.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingContractorsList as $contractor)
                                <tr>
                                    <td>{{ $contractor->user->name }}</td>
                                    <td>{{ $contractor->perusahaan }}</td>
                                    <td>Pending</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('admin.contractors.index') }}" class="btn btn-secondary mt-3">Lihat Semua</a>
        </div>

        <!-- Log Aktivitas -->
        <div class="activity-log-section">
            <h2>Log Aktivitas Terbaru</h2>
            @if ($activityLogs->isEmpty())
                <p class="text-muted">Tidak ada aktivitas terbaru.</p>
            @else
                <ul>
                    @foreach ($activityLogs as $log)
                        <li>
                            {{ $log->description }} -
                            <small>{{ $log->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Daftar Penawaran Aktif -->
        <div class="active-offers-section">
            <h2>Penawaran Aktif</h2>
            @if ($activeOffersList->isEmpty())
                <p class="text-muted">Tidak ada penawaran aktif saat ini.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Kontraktor</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeOffersList as $offer)
                                <tr>
                                    <td>{{ $offer->user->name }}</td>
                                    <td>{{ $offer->contractor->name ?? 'Belum ditugaskan' }}</td>
                                    <td>{{ $offer->durasi }}</td>
                                    <td>{{ $offer->status ?? 'Pending' }}</td>
                                    {{-- <td>
                                        <a href="{{ route('admin.bookings.show', $offer->id) }}" class="btn btn-primary btn-sm">Detail</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            {{-- <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary mt-3">Lihat Semua</a> --}}
        </div>

        {{-- <!-- Tombol Kembali -->
        <div class="back-link">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div> --}}
    </div>

    <style>
        /* General Container */
        .containers {
            width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36; /* Cokelat tua elegan */
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #6b5848; /* Cokelat muda */
            margin-bottom: 20px;
        }

        h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        p, li, small, td, th {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #555;
        }

        /* Statistik Ringkas */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #fdfaf6; /* Krem vintage */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9; /* Border natural */
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card p {
            font-size: 24px;
            font-weight: 700;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        /* Tabel */
        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0d8c9;
        }

        th {
            background-color: #a8c3b8; /* Hijau sage */
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 16px;
        }

        td {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Pending Contractors Section */
        .pending-contractors-section {
            margin-bottom: 40px;
        }

        /* Activity Log Section */
        .activity-log-section {
            margin-bottom: 40px;
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
        }

        .activity-log-section ul {
            list-style: none;
            padding: 0;
        }

        .activity-log-section li {
            padding: 10px 0;
            border-bottom: 1px solid #e0d8c9;
        }

        .activity-log-section li:last-child {
            border-bottom: none;
        }

        .activity-log-section small {
            color: #6b5848;
            font-size: 12px;
        }

        /* Active Offers Section */
        .active-offers-section {
            margin-bottom: 40px;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8; /* Hijau sage */
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
            background-color: #8ba89a; /* Hijau lebih gelap */
        }

        .btn-primary {
            background-color: #a8c3b8;
        }

        .btn-primary:hover {
            background-color: #8ba89a;
        }

        .btn-secondary {
            background-color: #d4c8b5; /* Beige */
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-section {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-card p {
                font-size: 20px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
@endsection

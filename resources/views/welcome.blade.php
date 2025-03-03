<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jasa Kontraktor Profesional</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Kontraktor Profesional</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Jasa Kontraktor Profesional</h1>
            <p class="lead">Membangun Impian Anda dengan Kualitas Terbaik</p>
            <a href="#layanan" class="btn btn-light btn-lg">Lihat Layanan</a>
        </div>
    </header>

    <!-- Layanan Section -->
    <section id="layanan" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Konstruksi Bangunan</h5>
                            <p class="card-text">Kami menyediakan jasa konstruksi bangunan dengan kualitas terbaik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Renovasi Rumah</h5>
                            <p class="card-text">Layanan renovasi rumah untuk membuat rumah Anda lebih nyaman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Desain Interior</h5>
                            <p class="card-text">Desain interior modern dan elegan untuk hunian Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Tentang Kami</h2>
            <p class="text-center">Kami adalah perusahaan kontraktor profesional yang telah berpengalaman dalam membangun dan merenovasi berbagai jenis properti. Dengan tim yang handal dan material berkualitas, kami siap mewujudkan impian Anda.</p>
        </div>
    </section>

    <!-- Kontraktor Terdaftar Section -->
    <section id="kontraktor" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Kontraktor Terdaftar</h2>
            @if ($contractors->isEmpty())
                <p class="text-center">Belum ada kontraktor yang terdaftar.</p>
            @else
                <div class="row">
                    @foreach ($contractors as $contractor)
                        <div class="col-md-4 text-center mb-4">
                            <div class="card">
                                <div class="card-body">
                                    @if ($contractor->contractorProfile && $contractor->contractorProfile->foto_profile)
                                        <img src="{{ Storage::url('contractors/' . $contractor->contractorProfile->foto_profile) }}" class="rounded-circle mb-3" width="100" height="100" alt="Foto Profil {{ $contractor->name }}">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" class="rounded-circle mb-3" width="100" height="100" alt="Foto Default">
                                    @endif
                                    <h5 class="card-title">{{ $contractor->name }}</h5>
                                    <p class="card-text">
                                        Bidang Usaha:
                                        @if ($contractor->contractorProfile && $contractor->contractorProfile->bidang_usaha && count($contractor->contractorProfile->bidang_usaha) > 0)
                                            {{ implode(', ', $contractor->contractorProfile->bidang_usaha) }}
                                        @else
                                            Tidak diisi
                                        @endif
                                    </p>
                                    <a href="{{ route('contractor.profile.showPublic', $contractor->id) }}" class="btn btn-primary">Lihat Profil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Hubungi Kami</h2>
            <form>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan</label>
                    <textarea class="form-control" id="pesan" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>© 2023 Jasa Kontraktor Profesional. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
</body>
</html>

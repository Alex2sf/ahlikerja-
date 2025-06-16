@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Intro Section -->
    <section class="intro-alt" id="intro-alt">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>Selamat Datang, {{ auth()->user()->name }}</h2>
                    @if (auth()->user()->role === 'kontraktor' && auth()->user()->contractorProfile)
                        {{-- Notifikasi kontraktor (opsional, bisa diaktifkan kembali jika diperlukan) --}}
                    @endif
                    <h1>Solusi Konstruksi Modern dengan Sentuhan Profesional</h1>
                    <p>Kami menghubungkan Anda dengan kontraktor terbaik untuk proyek Anda, dengan layanan yang terpercaya dan hasil yang berkualitas tinggi.</p>
                    {{-- <a href="#services" class="btn btn-primary">Temukan Kontraktor</a> --}}
                </div>
                <div class="latest-tenders">
                    <h2 class="section-subtitle">Tender Terbaru</h2>
                    <div class="tender-cards">
                        @forelse ($latestTenders as $index => $tender)
                            <div class="tender-card" style="animation-delay: {{ $index * 0.2 }}s;">
                                <h3>{{ $tender->judul }}</h3>
                                <p>Dibuat oleh: <a href="{{ route('user.profile.show', $tender->user->id) }}">{{ $tender->user->name }}</a></p>
                                <p class="tender-date">Tanggal: {{ $tender->created_at->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p>Tidak ada tender terbaru saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Layanan Kami</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Temukan Kontraktor">
                    </div>
                    <h3>Temukan Kontraktor</h3>
                    <p>Cari kontraktor terpercaya berdasarkan lokasi, bidang usaha, dan kebutuhan proyek Anda.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Pesan Langsung">
                    </div>
                    <h3>Pesan Langsung</h3>
                    <p>Kirimkan pesanan proyek Anda langsung ke kontraktor pilihan dengan mudah.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Ulasan dan Rating">
                    </div>
                    <h3>Ulasan dan Rating</h3>
                    <p>Lihat ulasan dan rating dari pengguna lain untuk memilih kontraktor terbaik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="testimoni-section" id="testimonial">
        <div class="container">
            <h2 class="section-title">Testimoni</h2>
            <div class="testimoni-container">
                <div class="testimoni">
                    <p>"Platform ini sangat membantu! Saya menemukan kontraktor yang sangat profesional untuk proyek rumah saya."</p>
                    <div class="author">- Rina, Jakarta</div>
                </div>
                <div class="testimoni">
                    <p>"Proses pemesanan sangat mudah dan kontraktor yang saya pilih sangat responsif."</p>
                    <div class="author">- Budi, Bandung</div>
                </div>
                <div class="testimoni">
                    <p>"Saya puas dengan hasilnya, kualitas kerja kontraktor sangat baik."</p>
                    <div class="author">- Sari, Surabaya</div>
                </div>
                <div class="testimoni">
                    <p>"Pilihan kontraktor sangat banyak dan sesuai dengan kebutuhan saya."</p>
                    <div class="author">- Dewa, Bali</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq" id="faq">
        <div class="container">
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bagaimana cara memesan kontraktor?</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        <p>Anda dapat mencari kontraktor di halaman "Daftar Kontraktor", pilih kontraktor yang sesuai, lalu klik tombol "Pesan" untuk mengirimkan detail proyek Anda.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apakah saya bisa melihat ulasan kontraktor sebelum memesan?</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        <p>Ya, Anda dapat melihat rating dan ulasan kontraktor di halaman profil mereka sebelum memesan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Intro Section */
        .intro-alt {
            padding: 100px 0;
            background: linear-gradient(135deg, #fdfaf6 0%, #fff 100%);
        }

        .intro-content {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            align-items: center;
        }

        .intro-text {
            max-width: 50%;
            text-align: left;
        }

        .intro-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #4a372f;
            margin-bottom: 15px;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        .intro-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #4a372f;
            margin-bottom: 20px;
            line-height: 1.2;
            opacity: 0;
            animation: fadeInUp 0.8s ease 0.2s forwards;
        }

        .intro-text p {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            opacity: 0;
            animation: fadeInUp 0.8s ease 0.4s forwards;
        }

        .latest-tenders {
            max-width: 45%;
        }

        .section-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #4a372f;
            text-align: left;
            margin-bottom: 25px;
        }

        .tender-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .tender-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideDown 0.5s ease forwards;
        }

        .tender-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .tender-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #4a372f;
            margin-bottom: 8px;
        }

        .tender-card p {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .tender-card a {
            color: #7aa08e;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .tender-card a:hover {
            color: #5e7d6b;
            text-decoration: underline;
        }

        .tender-date {
            font-size: 12px;
            color: #4a372f;
            font-style: italic;
        }

        /* Animation for Tender Cards */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Services Section */
        .services {
            padding: 80px 0;
            background-color: #f8f1e9;
            position: relative;
            z-index: 2;
            margin-bottom: -150px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #4a372f;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 3px;
            background-color: #7aa08e;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .service-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .service-icon img {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
        }

        .service-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #4a372f;
            margin-bottom: 15px;
        }

        .service-card p {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            color: #666;
        }

        /* Testimoni Section */
        .testimoni-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #fdfaf6 0%, #fff 100%);
            position: relative;
            z-index: 1;
        }

        .testimoni-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .testimoni {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .testimoni:nth-child(4) {
            opacity: 0.5;
        }

        .testimoni:hover {
            transform: translateY(-5px);
            opacity: 1;
        }

        .testimoni p {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }

        .testimoni .author {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            color: #4a372f;
            font-weight: 600;
        }

        /* FAQ Section */
        .faq {
            padding: 80px 0;
            background-color: #f8f1e9;
        }

        .faq-list {
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-item {
            margin-bottom: 20px;
        }

        .faq-question {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .faq-question:hover {
            background-color: #f5ece3;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .faq-question span {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #4a372f;
        }

        .faq-icon {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .faq-item.active .faq-answer {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .faq-answer p {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            color: #666;
        }

        /* Button Styles */
        .btn {
            background-color: #7aa08e;
            border: none;
            color: #fff;
            padding: 14px 24px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #5e7d6b;
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #7aa08e;
        }

        .btn-primary:hover {
            background-color: #5e7d6b;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .intro-content {
                flex-direction: column;
                align-items: center;
            }

            .intro-text, .latest-tenders {
                max-width: 100%;
                text-align: center;
            }

            .intro-text h1 {
                font-size: 36px;
            }

            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimoni-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .services {
                margin-bottom: -100px;
            }

            .testimoni:nth-child(4) {
                opacity: 0.5;
            }
        }

        @media (max-width: 768px) {
            .intro-text h1 {
                font-size: 28px;
            }

            .intro-text h2 {
                font-size: 24px;
            }

            .services-grid, .testimoni-container {
                grid-template-columns: 1fr;
            }

            .service-card, .testimoni {
                padding: 20px;
            }

            .services {
                margin-bottom: -80px;
            }

            .testimoni:nth-child(4) {
                opacity: 0.5;
            }
        }
    </style>

    <script>
        // FAQ toggle with smooth animation
        document.querySelectorAll('.faq-question').forEach((question) => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all other FAQ items
                document.querySelectorAll('.faq-item').forEach((item) => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                    }
                });

                // Toggle current FAQ item
                faqItem.classList.toggle('active');
            });
        });
    </script>
@endsection

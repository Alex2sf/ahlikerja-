@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Intro Section (Existing) -->
    {{-- <section class="intro" id="intro">
        <div class="container">
            <div class="intro-text">
                <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
                <p>Role: {{ auth()->user()->role }}</p>
            </div>
            <div class="intro-content">
                <!-- Pemberitahuan untuk kontraktor -->
                @if (auth()->user()->role === 'kontraktor' && auth()->user()->contractorProfile)
                    @if (auth()->user()->contractorProfile->approved === true)
                        <div class="notification success">
                            Selamat! Profil Anda telah disetujui oleh admin.
                            @if (auth()->user()->contractorProfile->admin_note)
                                <br>Catatan dari admin: {{ auth()->user()->contractorProfile->admin_note }}
                            @endif
                        </div>
                    @elseif (auth()->user()->contractorProfile->approved === false && auth()->user()->contractorProfile->admin_note)
                        <div class="notification danger">
                            Profil Anda ditolak oleh admin.
                            @if (auth()->user()->contractorProfile->admin_note)
                                <br>Catatan dari admin: {{ auth()->user()->contractorProfile->admin_note }}
                            @endif
                            <br><a href="{{ route('contractor.profile.edit') }}">Edit Profil</a> untuk memperbaiki dan ajukan ulang.
                        </div>
                    @else
                        <div class="notification">
                            Profil Anda sedang menunggu persetujuan admin.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section> --}}

    <!-- Intro Section (New) -->
    <section class="intro-alt" id="intro-alt">
        <div class="container">
            <div class="intro-text">
                <h2>Selamat Datang, {{ auth()->user()->name }}</h1>
                    @if (auth()->user()->role === 'kontraktor' && auth()->user()->contractorProfile)
                    @if (auth()->user()->contractorProfile->approved === true)
                        <div class="notification success">
                            Selamat! Profil Anda telah disetujui oleh admin.
                            @if (auth()->user()->contractorProfile->admin_note)
                                <br>Catatan dari admin: {{ auth()->user()->contractorProfile->admin_note }}
                            @endif
                        </div>
                    @elseif (auth()->user()->contractorProfile->approved === false && auth()->user()->contractorProfile->admin_note)
                        <div class="notification danger">
                            Profil Anda ditolak oleh admin.
                            @if (auth()->user()->contractorProfile->admin_note)
                                <br>Catatan dari admin: {{ auth()->user()->contractorProfile->admin_note }}
                            @endif
                            <br><a href="{{ route('contractor.profile.edit') }}">Edit Profil</a> untuk memperbaiki dan ajukan ulang.
                        </div>
                    @else
                        <div class="notification">
                            Profil Anda sedang menunggu persetujuan admin.
                        </div>
                    @endif
                @endif
                <h1>Solusi Konstruksi Modern dengan Sentuhan Profesional</h1>
                <p>Kami menghubungkan Anda dengan kontraktor terbaik untuk proyek Anda, dengan layanan yang terpercaya dan hasil yang berkualitas tinggi.</p>
                <a href="#services" class="btn btn-primary">Temukan Kontraktor</a>
            </div>
            <div class="intro-image">
                <img src="{{ asset('images/batuk.jpeg') }}" alt="Intro Image">
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
                        <span class="faq-icon">▼</span>                    </div>
                    <div class="faq-answer">
                        <p>Ya, Anda dapat melihat rating dan ulasan kontraktor di halaman profil mereka sebelum memesan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Intro Section (Existing) */
        .intro {
            padding: 80px 0;
            background-color: #f8f1e9;
        }

        .intro .container {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .intro-text {
            max-width: 50%;
        }

        .intro-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .intro-text p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .intro-content {
            max-width: 50%;
        }

        /* Intro Section (New) */
        .intro-alt {
            padding: 80px 0;
            background-color: #fff;
        }

        .intro-alt .container {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .intro-alt .intro-text {
            max-width: 50%;
        }

        .intro-alt .intro-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 40px;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        .intro-alt .intro-text p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        .intro-image {
            max-width: 50%;
            text-align: center;
        }

        .intro-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Services Section */
        .services {
            padding: 60px 0;
            background-color: #f8f1e9;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 40px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .service-card {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .service-icon {
            margin-bottom: 15px;
        }

        .service-icon img {
            width: 50px;
            height: 50px;
        }

        .service-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #6b5848;
            margin-bottom: 10px;
        }

        .service-card p {
            font-size: 14px;
            color: #555;
        }

        /* Testimoni Section */
        .testimoni-section {
            padding: 60px 0;
            background-color: #fff;
        }

        .testimoni-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .testimoni {
            background-color: #fdfaf6;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .testimoni p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .testimoni .author {
            font-size: 12px;
            color: #6b5848;
            font-weight: 500;
        }

        /* FAQ Section */
        .faq {
            padding: 60px 0;
            background-color: #f8f1e9;
        }

        .faq-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-question {
            background-color: #fdfaf6;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background-color: #f5ece3;
        }

        .faq-question span {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #6b5848;
        }

        .faq-icon {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            margin-top: 5px;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        .faq-answer p {
            font-size: 14px;
            color: #555;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .notification a {
            color: #5a3e36;
            text-decoration: underline;
        }

        /* Button Styles */
        .btn {
            background-color: #a8c3b8; /* Hijau sage */
            border: none;
            color: #fff;
            padding: 12px 20px;
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimoni-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .intro .container, .intro-alt .container {
                flex-direction: column;
                text-align: center;
            }

            .intro-text, .intro-content, .intro-alt .intro-text, .intro-image {
                max-width: 100%;
            }

            .intro-text h1, .intro-alt .intro-text h1 {
                font-size: 32px;
            }

            .services-grid, .testimoni-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        // FAQ toggle
        document.querySelectorAll('.faq-question').forEach((question) => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    </script>
@endsection

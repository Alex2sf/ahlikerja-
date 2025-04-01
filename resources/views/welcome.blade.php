<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Vintage Design</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span class="logo-text">Ahli Kerja</span>
            </div>
            <nav class="nav" id="nav">
                <ul>
                    <li><a href="#intro">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#testimonial">Testimonial</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
            </nav>
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <section class="intro" id="intro">
        <div class="container">
            <div class="intro-text">
                <h1>Solusi Modern dengan Sentuhan Klasik</h1>
                <p>Kami menyediakan layanan berkualitas tinggi dengan desain yang elegan dan timeless. Temukan solusi terbaik untuk kebutuhan Anda.</p>
                <a href="#services" class="cta-button">Pelajari Lebih Lanjut</a>
            </div>
            <div class="intro-image">
                <img src="{{ asset('images/batuk.jpeg') }}" alt="Intro Image">
            </div>
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Layanan Kami</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Desain Kreatif">
                    </div>
                    <h3>Desain Kreatif</h3>
                    <p>Kami menawarkan solusi desain yang kreatif dan modern, dengan sentuhan klasik yang timeless.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Pengembangan Web">
                    </div>
                    <h3>Pengembangan Web</h3>
                    <p>Pengembangan website berkualitas tinggi dengan teknologi terkini dan performa optimal.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('images/plant.png') }}" alt="Konsultasi Profesional">
                    </div>
                    <h3>Konsultasi Profesional</h3>
                    <p>Konsultasi dengan tim ahli kami untuk solusi bisnis yang tepat dan terpercaya.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimoni-section" id="testimonial">
        <div class="container">
            <h2 class="section-title">Testimoni</h2>
            <div class="testimoni-container">
                <div class="testimoni">
                    <p>"Produk ini luar biasa! Saya sangat menyukainya!"</p>
                    <div class="author">- Rina, Jakarta</div>
                </div>
                <div class="testimoni">
                    <p>"Desainnya sangat estetis dan kualitasnya juga sangat baik."</p>
                    <div class="author">- Budi, Bandung</div>
                </div>
                <div class="testimoni">
                    <p>"Pelayanan pelanggan sangat ramah dan responsif."</p>
                    <div class="author">- Sari, Surabaya</div>
                </div>
                <div class="testimoni">
                    <p>"Barang sampai dengan cepat dan sesuai ekspektasi!"</p>
                    <div class="author">- Dewa, Bali</div>
                </div>
            </div>
        </div>
    </section>

    <section class="faq" id="faq">
        <div class="container">
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apa saja layanan yang Anda tawarkan?</span>
                        <img src="{{ asset('images/arrow-down.png') }}" alt="Arrow Icon" class="faq-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Kami menawarkan berbagai layanan seperti desain kreatif, pengembangan web, dan konsultasi profesional.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Berapa lama proses pengerjaan proyek?</span>
                        <img src="{{ asset('images/arrow-down.png') }}" alt="Arrow Icon" class="faq-icon">
                    </div>
                    <div class="faq-answer">
                        <p>Waktu pengerjaan bervariasi tergantung kompleksitas proyek, biasanya antara 1-4 minggu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-info">
                <h3>Hubungi Kami</h3>
                <p>Email: info@example.com</p>
                <p>Telepon: +62 123 4567 890</p>
                <p>Alamat: Jl. Contoh No. 123, Kota Contoh</p>
            </div>
            <div class="footer-social">
                <h3>Ikuti Kami</h3>
                <div class="social-icons">
                    <a href="#"><img src="{{ asset('images/icon-facebook.png') }}" alt="Facebook"></a>
                    <a href="#"><img src="{{ asset('images/icon-instagram.png') }}" alt="Instagram"></a>
                    <a href="#"><img src="{{ asset('images/icon-twitter.png') }}" alt="Twitter"></a>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <p>© 2023 Nama Brand. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Toggle menu for mobile
        const menuToggle = document.getElementById('menuToggle');
        const nav = document.getElementById('nav');

        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
        });

        // FAQ toggle
        document.querySelectorAll('.faq-question').forEach((question) => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    </script>
</body>
</html>

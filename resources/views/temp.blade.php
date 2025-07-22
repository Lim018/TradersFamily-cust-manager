<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Family - Konsultasi Trading Forex Terpercaya Indonesia</title>
    <meta name="description" content="Perusahaan trader terbesar di Indonesia yang membimbing Anda mencapai profit konsisten melalui konsultasi dan mentorship trading forex profesional">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #ffffff 0%, #f0fffe 100%);
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2D5A27;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #2D5A27;
        }

        .cta-header {
            background: #2D5A27;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-header:hover {
            background: #40E0D0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(64, 224, 208, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            position: relative;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #2D5A27;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: #2D5A27;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #40E0D0;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(64, 224, 208, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #2D5A27;
            padding: 1rem 2rem;
            border: 2px solid #2D5A27;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #2D5A27;
            color: white;
            transform: translateY(-3px);
        }

        /* 3D Visual Elements */
        .hero-visual {
            position: relative;
            height: 500px;
            perspective: 1000px;
        }

        .visual-container {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            animation: float 6s ease-in-out infinite;
        }

        .chart-bars {
            position: absolute;
            display: flex;
            gap: 15px;
            align-items: flex-end;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .bar {
            width: 40px;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            border-radius: 5px 5px 0 0;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            animation: growBar 2s ease-out forwards;
            transform-origin: bottom;
        }

        .bar:nth-child(1) { height: 80px; animation-delay: 0.2s; }
        .bar:nth-child(2) { height: 120px; animation-delay: 0.4s; }
        .bar:nth-child(3) { height: 160px; animation-delay: 0.6s; }
        .bar:nth-child(4) { height: 200px; animation-delay: 0.8s; }
        .bar:nth-child(5) { height: 240px; animation-delay: 1s; }

        .floating-coins {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .coin {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            animation: floatCoin 4s ease-in-out infinite;
        }

        .coin::before {
            content: '$';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .coin:nth-child(1) { top: 20%; right: 10%; animation-delay: 0s; }
        .coin:nth-child(2) { top: 60%; right: 20%; animation-delay: 1s; }
        .coin:nth-child(3) { top: 40%; right: 5%; animation-delay: 2s; }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: rgba(255, 255, 255, 0.5);
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2D5A27;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #666;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(64, 224, 208, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #2D5A27, #40E0D0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2D5A27;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: #2D5A27;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 3rem;
            font-weight: 700;
            color: #40E0D0;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            color: #40E0D0;
            margin-bottom: 1rem;
        }

        .footer-section p,
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: #40E0D0;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            color: #999;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-20px) rotateY(10deg); }
        }

        @keyframes growBar {
            0% { transform: scaleY(0); }
            100% { transform: scaleY(1); }
        }

        @keyframes floatCoin {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-visual {
                height: 300px;
                margin-top: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .btn-primary,
            .btn-secondary {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo">Trader Family</div>
            {{-- <ul class="nav-links">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#layanan">Layanan</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul> --}}
            {{-- <a href="#konsultasi" class="cta-header">Konsultasi Gratis</a> --}}
            <div class="navbar-nav ms-auto">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Konsisten Profit dengan Trading Forex Terpercaya</h1>
                    <p>Perusahaan trader terbesar di Indonesia yang membimbing Anda mencapai profit konsisten melalui konsultasi dan mentorship trading forex profesional</p>
                    <div class="hero-buttons">
                        <a href="#konsultasi" class="btn-primary">Konsultasi Gratis</a>
                        <a href="#strategi" class="btn-secondary">Pelajari Strategi</a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="visual-container">
                        <div class="chart-bars">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                        <div class="floating-coins">
                            <div class="coin"></div>
                            <div class="coin"></div>
                            <div class="coin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>10,000+</h3>
                    <p>Trader Sukses</p>
                </div>
                <div class="stat-item">
                    <h3>95%</h3>
                    <p>Tingkat Keberhasilan</p>
                </div>
                <div class="stat-item">
                    <h3>5+</h3>
                    <p>Tahun Pengalaman</p>
                </div>
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Support Trading</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="layanan">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Unggulan Kami</h2>
                <p>Solusi lengkap untuk kesuksesan trading forex Anda</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Mentorship 1-on-1</h3>
                    <p>Bimbingan personal dari trader expert dengan pengalaman lebih dari 5 tahun di pasar forex. Dapatkan strategi khusus sesuai profil risiko Anda.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Strategi Teruji</h3>
                    <p>Akses ke strategi trading yang telah terbukti menghasilkan profit konsisten dengan track record yang dapat diverifikasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Komunitas Trader</h3>
                    <p>Bergabung dengan komunitas eksklusif trader sukses untuk berbagi pengalaman, tips, dan analisis pasar terkini.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3>Analisis Harian</h3>
                    <p>Dapatkan analisis pasar harian, sinyal trading, dan update kondisi ekonomi global yang mempengaruhi pergerakan mata uang.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Risk Management</h3>
                    <p>Pelajari teknik manajemen risiko profesional untuk melindungi modal dan memaksimalkan potensi keuntungan trading Anda.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Support 24/7</h3>
                    <p>Tim support yang siap membantu Anda 24 jam sehari, 7 hari seminggu untuk menjawab pertanyaan dan memberikan bantuan trading.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Trader Family</h3>
                    <p>Perusahaan trading forex terpercaya di Indonesia yang telah membantu ribuan trader mencapai kesuksesan finansial melalui pendidikan dan mentorship berkualitas.</p>
                </div>
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <p><a href="#">Konsultasi Trading</a></p>
                    <p><a href="#">Mentorship 1-on-1</a></p>
                    <p><a href="#">Analisis Pasar</a></p>
                    <p><a href="#">Komunitas Trader</a></p>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p><i class="fas fa-envelope"></i> info@traderfamily.id</p>
                    <p><i class="fas fa-phone"></i> +62 21 1234 5678</p>
                    <p><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</p>
                </div>
                <div class="footer-section">
                    <h3>Ikuti Kami</h3>
                    <p><a href="#"><i class="fab fa-instagram"></i> Instagram</a></p>
                    <p><a href="#"><i class="fab fa-telegram"></i> Telegram</a></p>
                    <p><a href="#"><i class="fab fa-youtube"></i> YouTube</a></p>
                    <p><a href="#"><i class="fab fa-whatsapp"></i> WhatsApp</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Trader Family. Semua hak dilindungi. | Investasi mengandung risiko.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Animate stats on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-item h3');
                    statNumbers.forEach(stat => {
                        const finalNumber = stat.textContent;
                        const number = parseInt(finalNumber.replace(/\D/g, ''));
                        const suffix = finalNumber.replace(/[\d,]/g, '');
                        
                        let current = 0;
                        const increment = number / 50;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= number) {
                                stat.textContent = number.toLocaleString() + suffix;
                                clearInterval(timer);
                            } else {
                                stat.textContent = Math.floor(current).toLocaleString() + suffix;
                            }
                        }, 30);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>

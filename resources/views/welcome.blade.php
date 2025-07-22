<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CustomerSync - Sales Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-graph-up-arrow"></i> CustomerSync
            </a>
            <div class="navbar-nav ms-auto">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">CustomerSync</h1>
                    <p class="lead mb-4">Sistem manajemen customer terintegrasi dengan Google Spreadsheet untuk tim sales yang efektif dan produktif.</p>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Sinkronisasi otomatis dengan Google Spreadsheet</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Manajemen follow-up dan reminder</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Dashboard analytics dan reporting</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span>Integrasi WhatsApp untuk komunikasi</span>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-speedometer2"></i> Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login to Start
                        </a>
                    @endauth
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="bi bi-graph-up feature-icon" style="font-size: 15rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="display-5 fw-bold">Fitur Unggulan</h2>
                    <p class="lead text-muted">Kelola customer dengan lebih efisien dan terorganisir</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-cloud-arrow-down feature-icon mb-3"></i>
                            <h5 class="card-title">Auto Sync</h5>
                            <p class="card-text">Data customer otomatis tersinkronisasi dari Google Spreadsheet melalui webhook tanpa perlu upload manual.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-calendar-check feature-icon mb-3"></i>
                            <h5 class="card-title">Follow-up Management</h5>
                            <p class="card-text">Atur jadwal follow-up customer dengan reminder otomatis dan tracking yang mudah.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-bar-chart feature-icon mb-3"></i>
                            <h5 class="card-title">Analytics Dashboard</h5>
                            <p class="card-text">Dashboard lengkap dengan statistik performance agent dan conversion rate customer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="display-5 fw-bold">Cara Kerja</h2>
                    <p class="lead text-muted">Proses sederhana untuk memulai</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">1</span>
                        </div>
                        <h5>Setup Google Apps Script</h5>
                        <p class="text-muted">Konfigurasi webhook di Google Spreadsheet untuk mengirim data otomatis.</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">2</span>
                        </div>
                        <h5>Agent Login</h5>
                        <p class="text-muted">Agent login ke sistem dan mulai mengelola customer mereka.</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">3</span>
                        </div>
                        <h5>Manage Customers</h5>
                        <p class="text-muted">Update status, atur follow-up, dan komunikasi via WhatsApp.</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">4</span>
                        </div>
                        <h5>Monitor Performance</h5>
                        <p class="text-muted">Admin memantau performance dan statistik tim sales.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-graph-up-arrow"></i> CustomerSync</h5>
                    <p class="text-muted">Sistem manajemen customer untuk tim sales yang efektif.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} CustomerSync. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

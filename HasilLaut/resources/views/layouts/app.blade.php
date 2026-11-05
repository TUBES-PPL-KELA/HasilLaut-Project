<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - HasilLaut</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #eaeaea;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar-link {
            color: #495057;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            margin-bottom: 5px;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #e8f5e9;
            color: #1b4d3e;
        }
    </style>
</head>
<body>

    <!-- Sidebar Kiri -->
    <div class="sidebar d-flex flex-column justify-content-between p-4">
        <div>
            <!-- Logo HasilLaut -->
            <!-- Bagian Header / Sidebar tempat Logo diletakkan -->
            <div class="mb-4 px-2">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HASILLAUT Logo" style="max-width: 140px; height: auto;">
                </a>
            </div>

            <!-- Menu Navigasi -->
            <nav class="nav flex-column gap-1">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-house-door"></i> Beranda
                </a>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-grid"></i> Katalog Ikan
                </a>
                <a href="#" class="sidebar-link active">
                    <i class="bi bi-journal-text"></i> Pesanan Saya
                </a>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </nav>
        </div>

        <!-- Profil User di Sidebar Bawah -->
        <div class="border-top pt-3">
            <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white shadow-sm">
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    HS
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Hilya Shafwa</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Pembeli B2C</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Utama Kanan -->
    <div class="main-content">
        <!-- Top Navbar kecil -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 justify-content-end">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light rounded-circle position-relative p-2">
                    <i class="bi bi-bell fs-5"></i>
                </button>
            </div>
        </nav>

        <!-- Dynamic Body Content -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-white border-top py-4 px-5 text-muted small">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 mb-3">
    <!-- Logo dan Teks Berdampingan -->
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ asset('images/hasillaut-logo.png') }}" alt="HASILLAUT Logo" style="max-width: 60px; height: auto;">
                                <h6 class="fw-bold text-dark m-0" style="font-size: 2rem; letter-spacing: 0.5px;">HASILLAUT</h6>
                            </div>
                            <p class="small text-muted mt-2">Platform B2C penyedia hasil laut segar langsung dari nelayan pesisir Indonesia dengan jaminan kualitas rantai dingin dan harga yang adil.</p>
                        </div>
                    <div class="col-md-2 mb-3">
                        <h6 class="fw-bold text-dark fs-6">Navigasi</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-decoration-none text-muted">Tentang Kami</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Katalog Ikan</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="fw-bold text-dark fs-6">Layanan</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-decoration-none text-muted">Lacak Pengiriman</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Bantuan & FAQ</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="fw-bold text-dark fs-6">Legal</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-decoration-none text-muted">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-decoration-none text-muted">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="text-muted opacity-25">
                <div class="d-flex justify-content-between">
                    <span>© 2026 HASILLAUT. Seluruh hak cipta dilindungi.</span>
                    <span>✉ info@hasillaut.id | ☎ +62 812-3456-7890</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HasilLaut menyediakan ikan dan hasil laut segar langsung dari nelayan Indonesia.">
    <title>HasilLaut | Ikan Segar Langsung dari Nelayan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            color-scheme: light;
            --midnight-teal: #013137;
            --ocean-teal: #215e68;
            --dusty-turq: #297376;
            --soft-seafoam: #5c9396;
            --pale-ice: #c1d9de;
            --page: #f7f9fa;
            --muted: #64777b;
            --border: #e2e9ea;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background: var(--page);
            color: var(--midnight-teal);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        button { font: inherit; }

        .site-header {
            height: 68px;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .header-inner, .page-content, .footer-inner {
            width: min(1120px, calc(100% - 48px));
            margin: 0 auto;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .brand img { width: 42px; height: 38px; object-fit: contain; }

        .header-actions { display: flex; align-items: center; gap: 12px; }

        .icon-link, .buyer-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #fff;
            color: var(--midnight-teal);
        }

        .icon-link { width: 36px; font-size: 14px; }
        .buyer-link { gap: 7px; padding: 0 14px; font-size: 12px; font-weight: 600; }
        .icon-link:hover, .buyer-link:hover { border-color: var(--soft-seafoam); background: #f4f9f9; }

        .page-content { padding: 22px 0 38px; }

        .hero {
            position: relative;
            display: flex;
            min-height: 340px;
            align-items: center;
            overflow: hidden;
            border-radius: 15px;
            background-color: var(--midnight-teal);
            background-image:
                linear-gradient(90deg, rgba(1, 32, 36, .86) 0%, rgba(1, 49, 55, .66) 52%, rgba(1, 49, 55, .24) 100%),
                url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1800&q=85');
            background-position: center 48%;
            background-size: cover;
            color: #fff;
        }

        .hero-content { max-width: 610px; padding: 42px 52px; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            color: #d7eff0;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #9bd5c3; }

        .hero h1 {
            max-width: 560px;
            margin: 0;
            font-size: clamp(30px, 4vw, 46px);
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1.12;
        }

        .hero p {
            max-width: 510px;
            margin: 15px 0 0;
            color: rgba(255,255,255,.82);
            font-size: 13px;
            line-height: 1.8;
        }

        .hero-buttons { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }

        .button {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            transition: background .2s, border-color .2s, transform .2s;
        }

        .button:hover { transform: translateY(-1px); }
        .button-primary { background: var(--ocean-teal); color: #fff; }
        .button-primary:hover { background: var(--dusty-turq); }
        .button-outline { border-color: rgba(255,255,255,.62); background: rgba(255,255,255,.08); color: #fff; }
        .button-outline:hover { background: rgba(255,255,255,.18); }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 20px;
        }

        .stat-card {
            min-height: 150px;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
        }

        .stat-icon {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 9px;
            background: #eaf2f3;
            color: var(--ocean-teal);
            font-size: 15px;
        }

        .stat-card h2 { margin: 10px 0 2px; font-size: 25px; letter-spacing: -.04em; }
        .stat-card strong { display: block; font-size: 11px; }
        .stat-card p { margin: 7px 0 0; color: var(--muted); font-size: 10px; line-height: 1.6; }

        .products-section { padding-top: 31px; }

        .section-heading { display: flex; align-items: end; justify-content: space-between; gap: 16px; margin-bottom: 14px; }
        .section-heading h2 { margin: 0; font-size: 21px; letter-spacing: -.04em; }
        .section-heading p { margin: 5px 0 0; color: var(--muted); font-size: 11px; }
        .text-link { color: var(--ocean-teal); font-size: 11px; font-weight: 700; }
        .text-link:hover { color: var(--midnight-teal); }

        .product-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }

        .product-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            transition: transform .2s, box-shadow .2s;
        }

        .product-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(1,49,55,.09); }
        .product-image { position: relative; height: 168px; overflow: hidden; background: #e8eeee; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }

        .product-tag {
            position: absolute;
            top: 11px;
            left: 11px;
            padding: 6px 9px;
            border-radius: 999px;
            background: rgba(1,49,55,.9);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
        }

        .product-body { padding: 14px; }
        .product-body h3 { margin: 0; font-size: 13px; }
        .product-origin { display: flex; align-items: center; gap: 5px; margin-top: 6px; color: var(--muted); font-size: 10px; }
        .product-price { margin: 9px 0 13px; color: var(--ocean-teal); font-size: 14px; font-weight: 800; }
        .product-bottom { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .seller { color: var(--muted); font-size: 9px; }

        .buy-link {
            display: inline-flex;
            min-height: 34px;
            align-items: center;
            gap: 7px;
            padding: 0 12px;
            border-radius: 7px;
            background: var(--midnight-teal);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
        }

        .buy-link:hover { background: var(--ocean-teal); }

        .site-footer { border-top: 1px solid var(--border); background: #fff; }
        .footer-inner { padding: 27px 0 15px; }
        .footer-columns { display: grid; grid-template-columns: 1.5fr repeat(3, 1fr); gap: 35px; }
        .footer-brand p { max-width: 260px; margin: 12px 0 0; color: var(--muted); font-size: 10px; line-height: 1.7; }
        .footer-column h2 { margin: 2px 0 12px; font-size: 10px; }
        .footer-column a { display: block; width: fit-content; margin: 0 0 9px; color: var(--muted); font-size: 10px; }
        .footer-column a:hover { color: var(--ocean-teal); }
        .footer-bottom { display: flex; justify-content: space-between; gap: 16px; margin-top: 20px; padding-top: 13px; border-top: 1px solid #edf0f1; color: var(--muted); font-size: 9px; }

        @media (max-width: 720px) {
            .header-inner, .page-content, .footer-inner { width: min(100% - 32px, 560px); }
            .hero { min-height: 320px; background-position: center; }
            .hero-content { padding: 32px 26px; }
            .stats { gap: 10px; }
            .stat-card { min-height: 140px; padding: 14px; }
            .stat-card h2 { font-size: 21px; }
            .product-grid { gap: 10px; }
            .product-image { height: 135px; }
            .product-body { padding: 11px; }
            .buy-link { padding: 0 9px; }
            .footer-columns { grid-template-columns: repeat(2, 1fr); gap: 24px; }
        }

        @media (max-width: 500px) {
            .site-header { height: 60px; }
            .brand { gap: 8px; font-size: 13px; }
            .brand img { width: 35px; height: 32px; }
            .header-actions { gap: 7px; }
            .header-actions .icon-link:nth-child(2) { display: none; }
            .buyer-link { height: 34px; padding: 0 11px; }
            .page-content { padding-top: 15px; }
            .hero { min-height: 330px; border-radius: 12px; }
            .hero-content { padding: 27px 20px; }
            .hero h1 { font-size: 32px; }
            .hero p { font-size: 12px; }
            .stats { grid-template-columns: 1fr; }
            .stat-card { min-height: 0; }
            .stat-card h2 { margin-top: 7px; }
            .product-grid { grid-template-columns: 1fr; gap: 13px; }
            .product-image { height: 190px; }
            .product-body { padding: 14px; }
            .section-heading { align-items: start; }
            .section-heading h2 { font-size: 18px; }
            .footer-bottom { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="HasilLaut, kembali ke beranda">
                <img src="{{ asset('images/hasillaut-logo.png') }}" alt="">
                <span>HASILLAUT</span>
            </a>
            <nav class="header-actions" aria-label="Navigasi utama">
                <a class="icon-link" href="#produk-unggulan" aria-label="Lihat produk unggulan"><i class="bi bi-bell"></i></a>
                <a class="icon-link" href="#tentang" aria-label="Tentang HasilLaut"><i class="bi bi-gear"></i></a>
                <a class="buyer-link" href="{{ route('pembeli.cart') }}"><i class="bi bi-person"></i> Pembeli</a>
            </nav>
        </div>
    </header>

    <main class="page-content">
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-content">
                <span class="eyebrow"><span class="eyebrow-dot"></span> Hasil laut segar pilihan</span>
                <h1 id="hero-title">Ikan Segar Langsung dari Nelayan, Pesisir ke Dapur Anda</h1>
                <p>Dapatkan produk laut berkualitas tanpa perantara. Setiap pembelian Anda membantu mewujudkan kesejahteraan dan harga yang adil bagi nelayan tradisional Indonesia.</p>
                <div class="hero-buttons">
                    <a class="button button-primary" href="#produk-unggulan">Beli Ikan Segar <i class="bi bi-arrow-right"></i></a>
                    <a class="button button-outline" href="#produk-unggulan">Jelajahi Katalog</a>
                </div>
            </div>
        </section>

        <section id="tentang" class="stats" aria-label="Hasil dan layanan HasilLaut">
            <article class="stat-card">
                <span class="stat-icon"><i class="bi bi-basket2-fill"></i></span>
                <h2>12.5+ Ton</h2>
                <strong>Total Ikan Terjual</strong>
                <p>Hasil tangkapan segar yang berhasil didistribusikan langsung dari HasilLaut Hub ke konsumen.</p>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><i class="bi bi-people-fill"></i></span>
                <h2>450+</h2>
                <strong>Nelayan Terhubung</strong>
                <p>Nelayan tradisional pesisir yang telah menjadi mitra dan mendapatkan harga jual adil.</p>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><i class="bi bi-shield-fill-check"></i></span>
                <h2>100%</h2>
                <strong>Jaminan Rantai Cold Chain</strong>
                <p>Pengemasan berpendingin standar menjaga kesegaran ikan hingga tiba di lokasi Anda.</p>
            </article>
        </section>

        <section id="produk-unggulan" class="products-section" aria-labelledby="products-title">
            <div class="section-heading">
                <div>
                    <h2 id="products-title">Produk Unggulan Hari Ini</h2>
                    <p>Tangkapan segar terbaik yang masuk melalui HasilLaut Hub pesisir.</p>
                </div>
                <a class="text-link" href="#produk-unggulan">Lihat semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="product-grid">
                <article class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1753537358141-a72fbfea529c?auto=format&fit=crop&w=900&q=85" alt="Olahan ikan kakap merah segar" loading="lazy">
                        <span class="product-tag">Baru Masuk Hub</span>
                    </div>
                    <div class="product-body">
                        <h3>Ikan Kakap Merah Segar</h3>
                        <span class="product-origin"><i class="bi bi-geo-alt-fill"></i> Hub Pangandaran</span>
                        <p class="product-price">Rp 85.000 / kg</p>
                        <div class="product-bottom">
                            <span class="seller">● Pak Rudi (Nelayan)</span>
                            <a class="buy-link" href="{{ route('pembeli.cart') }}">Beli Sekarang</a>
                        </div>
                    </div>
                </article>
                <article class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1548587468-971ebe4c8c3b?auto=format&fit=crop&w=900&q=85" alt="Udang windu laut segar" loading="lazy">
                        <span class="product-tag">Kualitas Super</span>
                    </div>
                    <div class="product-body">
                        <h3>Udang Windu Laut</h3>
                        <span class="product-origin"><i class="bi bi-geo-alt-fill"></i> Hub Jepara</span>
                        <p class="product-price">Rp 115.000 / kg</p>
                        <div class="product-bottom">
                            <span class="seller">● Pak Rudi (Nelayan)</span>
                            <a class="buy-link" href="{{ route('pembeli.cart') }}">Beli Sekarang</a>
                        </div>
                    </div>
                </article>
                <article class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1682457569891-53e4f6ef9271?auto=format&fit=crop&w=900&q=85" alt="Hidangan tuna loin segar" loading="lazy">
                        <span class="product-tag">Tangkapan Pagi</span>
                    </div>
                    <div class="product-body">
                        <h3>Tuna Loin Segar</h3>
                        <span class="product-origin"><i class="bi bi-geo-alt-fill"></i> Hub Makassar</span>
                        <p class="product-price">Rp 95.000 / kg</p>
                        <div class="product-bottom">
                            <span class="seller">● Pak Anol</span>
                            <a class="buy-link" href="{{ route('pembeli.cart') }}">Beli Sekarang</a>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-columns">
                <div class="footer-brand">
                    <a class="brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/hasillaut-logo.png') }}" alt="">
                        <span>HASILLAUT</span>
                    </a>
                    <p>Platform B2C penyedia hasil laut segar langsung dari nelayan pesisir Indonesia dengan jaminan kualitas rantai dingin hingga ke tangan Anda.</p>
                </div>
                <nav class="footer-column" aria-label="Navigasi footer">
                    <h2>Navigasi</h2>
                    <a href="#tentang">Tentang Kami</a>
                    <a href="#produk-unggulan">Katalog Ikan</a>
                    <a href="#produk-unggulan">Lokasi Hub</a>
                    <a href="#tentang">Kemitraan Nelayan</a>
                </nav>
                <nav class="footer-column" aria-label="Layanan">
                    <h2>Layanan</h2>
                    <a href="#produk-unggulan">Lacak Pengiriman</a>
                    <a href="#produk-unggulan">Metode Pembayaran &amp; COD</a>
                    <a href="#tentang">Garansi Kesegaran</a>
                    <a href="#tentang">Bantuan &amp; FAQ</a>
                </nav>
                <nav class="footer-column" aria-label="Informasi legal">
                    <h2>Legal</h2>
                    <a href="#tentang">Kebijakan Privasi</a>
                    <a href="#tentang">Syarat &amp; Ketentuan</a>
                    <a href="mailto:info@hasillaut.id">Kontak Kami</a>
                </nav>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} HASILLAUT. Seluruh hak cipta dilindungi.</span>
                <span>info@hasillaut.id&nbsp;&nbsp; · &nbsp;&nbsp;+62 812-3456-7890</span>
            </div>
        </div>
    </footer>
</body>
</html>

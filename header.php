<?php
/**
 * header.php - Header Template Portal Informasi Akademik Teknik Informatika
 */
require_once __DIR__ . '/config.php';

// Custom HTTP Header untuk verifikasi layanan (Nmap / cURL banner grabbing)
header("X-Lab-Version: VulnLab-PHP/1.2");
header("X-Target-Server: NPT-Lab-Teknik-Informatika");

$is_logged_in = isset($_SESSION['user']);
$logged_user = $is_logged_in ? $_SESSION['user'] : null;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Informasi & Berita Akademik - Teknik Informatika</title>
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --accent: #0ea5e9;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 8px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --font-mono: SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            font-family: var(--font-sans);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar Informasi Kampus */
        .top-utility-bar {
            background-color: #0f172a;
            color: #94a3b8;
            font-size: 0.8rem;
            padding: 7px 24px;
            border-bottom: 1px solid #1e293b;
        }

        .top-utility-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .utility-links a {
            color: #cbd5e1;
            text-decoration: none;
            margin-left: 14px;
            transition: color 0.2s;
        }

        .utility-links a:hover {
            color: #38bdf8;
        }

        /* Navbar Utama */
        .main-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .navbar-inner {
            max-width: 1140px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 800;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px rgba(30, 64, 175, 0.2);
        }

        .brand-text h1 {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--primary-dark);
            letter-spacing: -0.2px;
        }

        .brand-text span {
            font-size: 0.76rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .nav-item a {
            display: inline-block;
            padding: 8px 14px;
            color: #334155;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-item a:hover {
            color: var(--primary);
            background-color: #f1f5f9;
        }

        .nav-item a.active {
            color: var(--primary);
            background-color: #eff6ff;
        }

        .btn-portal-login {
            background-color: var(--primary);
            color: #ffffff !important;
            padding: 8px 16px !important;
            border-radius: 6px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 4px rgba(30, 64, 175, 0.15);
        }

        .btn-portal-login:hover {
            background-color: var(--primary-dark) !important;
        }

        /* Container & Layout */
        .container {
            max-width: 1140px;
            width: 100%;
            margin: 28px auto;
            padding: 0 20px;
            flex: 1;
        }

        /* Cards & Components */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 40px 32px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .hero-banner h2 {
            font-size: 1.85rem;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .hero-banner p {
            font-size: 1.05rem;
            color: #dbeafe;
            max-width: 680px;
            margin-bottom: 22px;
        }

        /* Search Form in Hero */
        .hero-search-box {
            display: flex;
            gap: 8px;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px;
            border-radius: 8px;
            backdrop-filter: blur(4px);
        }

        .hero-search-input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: #ffffff;
            font-size: 0.95rem;
            outline: none;
        }

        .hero-search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .btn-search {
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-search:hover {
            background-color: #1e293b;
        }

        /* Grid */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 20px;
        }

        /* Article / News Card */
        .news-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .news-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .news-card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-category {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--primary);
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .news-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .news-snippet {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 16px;
            flex: 1;
            line-height: 1.5;
        }

        .news-meta {
            font-size: 0.78rem;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
        }

        /* Buttons & Forms */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-outline:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 6px;
            font-size: 0.92rem;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-info {
            background-color: #eff6ff;
            border-color: #bfdbfe;
            color: #1e40af;
        }

        .alert-warning {
            background-color: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 0.74rem;
            font-weight: 600;
            border-radius: 4px;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-slate {
            background: #f1f5f9;
            color: #475569;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
            text-align: left;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
        }

        tr:hover td {
            background-color: #fbfcfd;
        }

        .code-box {
            background: #0f172a;
            color: #e2e8f0;
            font-family: var(--font-mono);
            padding: 14px 18px;
            border-radius: 6px;
            font-size: 0.88rem;
            overflow-x: auto;
        }

        /* Footer */
        footer {
            background-color: #0f172a;
            color: #94a3b8;
            margin-top: auto;
            border-top: 1px solid #1e293b;
        }

        .footer-main {
            max-width: 1140px;
            margin: 0 auto;
            padding: 40px 24px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 32px;
        }

        .footer-col h4 {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .footer-col p {
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 8px;
        }

        .footer-col ul li a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: #38bdf8;
        }

        .footer-bottom {
            background-color: #090d16;
            padding: 14px 24px;
            border-top: 1px solid #1e293b;
            font-size: 0.78rem;
            color: #64748b;
        }

        .footer-bottom-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .footer-main {
                grid-template-columns: 1fr;
            }
            .navbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-links {
                flex-wrap: wrap;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

    <!-- Utility Bar Atas -->
    <div class="top-utility-bar">
        <div class="top-utility-inner">
            <div>
                <span>🏛️ Fakultas Ilmu Komputer &bull; Program Studi Teknik Informatika</span>
            </div>
            <div class="utility-links">
                <a href="view.php?page=panduan_layanan.txt">Panduan Layanan</a>
                <a href="view.php?page=tentang_kami.txt">Tentang Jurusan</a>
                <a href="robots.txt" target="_blank">Sitemap</a>
            </div>
        </div>
    </div>

    <!-- Navigasi Utama -->
    <header class="main-header">
        <div class="navbar-inner">
            <a href="index.php" class="brand-logo">
                <div class="logo-icon">TI</div>
                <div class="brand-text">
                    <h1>Portal Akademik TI</h1>
                    <span>Sistem Informasi & Publikasi Berita</span>
                </div>
            </a>

            <nav>
                <ul class="nav-links">
                    <li class="nav-item">
                        <a href="index.php" class="<?= ($current_page === 'index.php') ? 'active' : ''; ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="search.php" class="<?= ($current_page === 'search.php') ? 'active' : ''; ?>">Berita & Pengumuman</a>
                    </li>
                    <li class="nav-item">
                        <a href="view.php?page=panduan_layanan.txt" class="<?= ($current_page === 'view.php') ? 'active' : ''; ?>">Pusat Dokumen</a>
                    </li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a href="dashboard.php" class="<?= ($current_page === 'dashboard.php') ? 'active' : ''; ?>" style="color: var(--primary);">
                                Akun (<?= htmlspecialchars($logged_user['username']); ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" style="color: #ef4444;">Keluar</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn-portal-login">Portal Masuk</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

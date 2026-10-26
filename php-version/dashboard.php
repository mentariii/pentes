<?php
/**
 * dashboard.php - Dashboard Pengguna & Panel Administrasi Jurusan
 * Ditampilkan setelah pengguna berhasil login (atau bypass SQLi).
 */
require_once __DIR__ . '/header.php';

// Pastikan hanya bisa diakses setelah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<div class="container">

    <!-- Card Profil Pengguna -->
    <div class="card" style="border-top: 4px solid var(--primary);">
        <div class="card-header">
            <div>
                <h2 class="card-title" style="font-size: 1.35rem;">
                    Dashboard Administrasi & Profil Civitas
                </h2>
                <p style="color: var(--text-muted); font-size: 0.85rem;">
                    Selamat datang kembali di panel kendali sistem informasi akademik.
                </p>
            </div>
            <a href="logout.php" class="btn btn-outline" style="color: #ef4444; border-color: #fca5a5;">
                Keluar Akun
            </a>
        </div>

        <!-- Banner Otorisasi / Kunci Token Rahasia (Menampilkan FLAG SQLi) -->
        <div class="alert alert-success" style="border-left: 4px solid #16a34a; background: #f0fdf4;">
            <div style="font-weight: 700; color: #15803d; margin-bottom: 4px;">
                🎉 Sesi Otorisasi Berhasil Divalidasi:
            </div>
            <div style="font-family: var(--font-mono); font-size: 1.1rem; font-weight: 700; color: #166534; word-break: break-all;">
                FLAG{SQL_Injection_Success}
            </div>
            <?php if (!empty($user['flag']) && $user['flag'] !== 'FLAG{SQL_Injection_Success}'): ?>
                <div style="margin-top: 8px; font-size: 0.88rem; color: #15803d;">
                    Token Hak Akses Pengguna: <code><?= htmlspecialchars($user['flag']); ?></code>
                </div>
            <?php endif; ?>
        </div>

        <!-- Detail Akun -->
        <div class="table-responsive" style="margin-top: 20px;">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 25%;">ID Pengguna</th>
                        <td><code>#<?= htmlspecialchars($user['id'] ?? '1'); ?></code></td>
                    </tr>
                    <tr>
                        <th>Nama Pengguna (Username)</th>
                        <td><strong><?= htmlspecialchars($user['username']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Jabatan / Hak Akses</th>
                        <td><span class="badge badge-blue"><?= htmlspecialchars($user['role'] ?? 'Administrator'); ?></span></td>
                    </tr>
                    <tr>
                        <th>Waktu Sesi Masuk</th>
                        <td><?= date('d F Y, H:i:s'); ?> WIB</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;">
            <a href="search.php" class="btn btn-primary">
                Kelola & Cari Pengumuman &rarr;
            </a>
            <a href="view.php?page=panduan_layanan.txt" class="btn btn-outline">
                Lihat Panduan Layanan
            </a>
            <a href="index.php" class="btn btn-outline">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Catatan Internal Staf & Dokumentasi -->
    <div class="card">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
            Catatan Tim Pengelola Sistem Informasi
        </h3>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 10px;">
            Daftar pengingat untuk administrator sistem dan staf teknis laboratorium:
        </p>
        <ul style="padding-left: 20px; font-size: 0.88rem; color: #475569; line-height: 1.8;">
            <li>Salinan konfigurasi basis data lama tersimpan pada berkas <code>backup-config.txt</code> di folder utama server.</li>
            <li>Seluruh dokumen panduan dapat diakses langsung menggunakan modul dokumen <code>view.php?page=panduan_layanan.txt</code>.</li>
            <li>Daftar pembatasan perayap mesin pencari dikonfigurasi melalui berkas <code>robots.txt</code>.</li>
        </ul>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>

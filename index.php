<?php
/**
 * index.php - Halaman Utama Portal Akademik Teknik Informatika
 * Menampilkan hero banner, berita terbaru dari database, layanan kampus, dan status server.
 */
require_once __DIR__ . '/header.php';

// Ambil beberapa berita terbaru dari database
$recent_posts = [];
if ($db_connected) {
    $res = @mysqli_query($conn, "SELECT id, title, category, content, author, created_at FROM posts ORDER BY id DESC LIMIT 3");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $recent_posts[] = $row;
        }
    }
}
?>

<div class="container">

    <!-- Notifikasi Status Database jika Belum Terhubung -->
    <?php if (!$db_connected): ?>
        <div class="alert alert-warning">
            <strong>Pemberitahuan Sistem:</strong> Basis data MySQL belum terkonfigurasi. Pastikan pengaturan pada berkas <code>config.php</code> sudah sesuai dengan kredensial database hosting Anda dan file <code>database.sql</code> telah di-import melalui phpMyAdmin.
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-banner">
        <h2>Pusat Informasi & Layanan Terpadu<br>Teknik Informatika</h2>
        <p>
            Portal publikasi resmi kegiatan akademik, pengumuman jadwal perkuliahan, riset komputasi, dan layanan administrasi laboratorium.
        </p>

        <!-- Form Pencarian Langsung -->
        <form action="search.php" method="GET" class="hero-search-box">
            <input type="text" name="q" class="hero-search-input" placeholder="Cari artikel, pengumuman, atau jadwal...">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </section>

    <!-- Berita & Pengumuman Terkini (Dinamis dari MySQL) -->
    <section style="margin-bottom: 36px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div>
                <h3 style="font-size: 1.35rem; font-weight: 700; color: #1e293b;">Berita & Pengumuman Terkini</h3>
                <p style="color: var(--text-muted); font-size: 0.88rem;">Informasi dan agenda terbaru seputar kegiatan civitas akademika</p>
            </div>
            <a href="search.php" class="btn btn-outline" style="font-size: 0.85rem; padding: 6px 14px;">Lihat Semua &rarr;</a>
        </div>

        <?php if (!empty($recent_posts)): ?>
            <div class="grid-3">
                <?php foreach ($recent_posts as $post): ?>
                    <article class="news-card">
                        <div class="news-card-body">
                            <span class="news-category"><?= htmlspecialchars($post['category']); ?></span>
                            <h4 class="news-title"><?= htmlspecialchars($post['title']); ?></h4>
                            <p class="news-snippet">
                                <?= htmlspecialchars(mb_strimwidth($post['content'], 0, 110, "...")); ?>
                            </p>
                            <div class="news-meta">
                                <span>✍️ <?= htmlspecialchars($post['author']); ?></span>
                                <span>📅 <?= date('d M Y', strtotime($post['created_at'])); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Belum ada artikel yang dimuat. Hubungkan database untuk melihat berita terbaru.
            </div>
        <?php endif; ?>
    </section>

    <!-- Menu Layanan Kampus -->
    <section style="margin-bottom: 36px;">
        <h3 style="font-size: 1.35rem; font-weight: 700; color: #1e293b; margin-bottom: 16px;">Layanan Akademik & Administrasi</h3>
        <div class="grid-3">
            <div class="card" style="margin-bottom: 0;">
                <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Pusat Dokumen & Panduan</h4>
                <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 16px;">
                    Akses berkas standar operasional, panduan skripsi, dan formulir akademik secara daring.
                </p>
                <a href="view.php?page=panduan_layanan.txt" class="btn btn-outline" style="font-size: 0.85rem; width: 100%;">Buka Dokumen</a>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <div style="font-size: 2rem; margin-bottom: 10px;">🔍</div>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Pencarian Arsip Berita</h4>
                <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 16px;">
                    Cari arsip kegiatan, surat keputusan jurusan, dan riwayat pengumuman semester terdahulu.
                </p>
                <a href="search.php" class="btn btn-outline" style="font-size: 0.85rem; width: 100%;">Cari Berita</a>
            </div>

            <div class="card" style="margin-bottom: 0;">
                <div style="font-size: 2rem; margin-bottom: 10px;">🔐</div>
                <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Portal Masuk Staf & Dosen</h4>
                <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 16px;">
                    Akses portal internal untuk pengelolaan pengumuman, data riset, dan administrasi jurusan.
                </p>
                <a href="login.php" class="btn btn-primary" style="font-size: 0.85rem; width: 100%;">Masuk Portal</a>
            </div>
        </div>
    </section>

    <!-- Informasi Lingkungan & Status Server (Cocok untuk Bukti Screenshot Laporan Praktikum) -->
    <section class="card" style="background: #ffffff; border-left: 4px solid var(--primary);">
        <div class="card-header" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
            <div>
                <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">
                    Status Infrastruktur Server & Layanan Web (System Diagnostic)
                </h4>
                <p style="color: var(--text-muted); font-size: 0.82rem;">
                    Informasi teknis node server web untuk pemantauan layanan intra-kampus.
                </p>
            </div>
            <span class="badge badge-green">LAYANAN AKTIF</span>
        </div>

        <div class="table-responsive" style="margin-top: 14px;">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 25%;">Host Domain</th>
                        <td><code><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost'); ?></code></td>
                    </tr>
                    <tr>
                        <th>Web Server Software</th>
                        <td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache / LiteSpeed'); ?></td>
                    </tr>
                    <tr>
                        <th>Versi PHP</th>
                        <td><span class="badge badge-blue">PHP <?= htmlspecialchars(phpversion()); ?></span></td>
                    </tr>
                    <tr>
                        <th>Custom Header Banner</th>
                        <td><code>X-Lab-Version: VulnLab-PHP/1.2</code></td>
                    </tr>
                    <tr>
                        <th>Koneksi Database</th>
                        <td>
                            <?php if ($db_connected): ?>
                                <span class="badge badge-green">Terhubung (MySQL Online)</span>
                            <?php else: ?>
                                <span class="badge" style="background:#fee2e2; color:#b91c1c;">Belum Terhubung</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>

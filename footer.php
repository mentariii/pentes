<?php
/**
 * footer.php - Footer Template Portal Informasi Akademik Teknik Informatika
 */
?>
    <!-- Footer Web Normal -->
    <footer>
        <div class="footer-main">
            <div class="footer-col">
                <h4>Portal Informasi Teknik Informatika</h4>
                <p>
                    Sistem informasi publikasi berita, pengumuman jadwal akademik, dan layanan terpadu civitas akademika Jurusan Teknik Informatika.
                </p>
                <p style="font-size: 0.8rem; color: #64748b;">
                    Gedung Laboratorium Komputasi & Jaringan Terpadu, Lantai 3.<br>
                    Jl. Kampus Ilmu Komputer No. 1 &bull; Email: sekretariat@informatika.ac.id
                </p>
            </div>
            <div class="footer-col">
                <h4>Layanan Akademik</h4>
                <ul>
                    <li><a href="search.php?q=Akademik">Pengumuman Akademik</a></li>
                    <li><a href="search.php?q=Workshop">Kegiatan & Workshop</a></li>
                    <li><a href="view.php?page=panduan_layanan.txt">Panduan Tugas Akhir</a></li>
                    <li><a href="login.php">Portal Masuk Staf & Dosen</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Pusat Bantuan</h4>
                <ul>
                    <li><a href="view.php?page=tentang_kami.txt">Profil Program Studi</a></li>
                    <li><a href="view.php?page=panduan_layanan.txt">Standar Operasional Prosedur</a></li>
                    <li><a href="robots.txt">Pengaturan Sitemap</a></li>
                </ul>
            </div>
        </div>

        <!-- Bar Status Diagnostik Sistem (Bisa untuk screenshot praktikum) -->
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <div>
                    &copy; <?= date('Y'); ?> Program Studi Teknik Informatika. Hak Cipta Dilindungi.
                </div>
                <div style="font-family: var(--font-mono); font-size: 0.73rem; color: #475569;">
                    Host: <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost'); ?> &bull; 
                    PHP: <?= htmlspecialchars(phpversion()); ?> &bull; 
                    Build: X-Lab-Version: VulnLab-PHP/1.2
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

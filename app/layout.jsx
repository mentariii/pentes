import './globals.css';
import Link from 'next/link';
import { getSession } from '@/lib/session';

export const metadata = {
  title: 'Portal Akademik & Riset - Teknik Informatika',
  description: 'Portal Informasi Resmi, Berita Akademik, dan Layanan Mahasiswa Jurusan Teknik Informatika',
};

export default async function RootLayout({ children }) {
  const user = await getSession();

  return (
    <html lang="id">
      <body>
        {/* Top Utility Bar */}
        <div className="top-utility-bar">
          <div className="top-utility-inner">
            <div>
              <span>🏛️ Fakultas Ilmu Komputer &bull; Program Studi Teknik Informatika</span>
            </div>
            <div className="utility-links">
              <Link href="/view?page=public/docs/panduan_layanan.txt">Panduan Layanan</Link>
              <Link href="/view?page=public/docs/tentang_kami.txt">Tentang Jurusan</Link>
              <Link href="/robots.txt" target="_blank">Sitemap</Link>
            </div>
          </div>
        </div>

        {/* Main Header / Navbar */}
        <header className="main-header">
          <div className="navbar-inner">
            <Link href="/" className="brand-logo">
              <div className="logo-icon">TI</div>
              <div className="brand-text">
                <h1>Portal Akademik TI</h1>
                <span>Sistem Informasi & Publikasi Berita</span>
              </div>
            </Link>

            <nav>
              <ul className="nav-links">
                <li className="nav-item">
                  <Link href="/">Beranda</Link>
                </li>
                <li className="nav-item">
                  <Link href="/search">Berita & Pengumuman</Link>
                </li>
                <li className="nav-item">
                  <Link href="/view?page=public/docs/panduan_layanan.txt">Pusat Dokumen</Link>
                </li>

                {user ? (
                  <>
                    <li className="nav-item">
                      <Link href="/dashboard" style={{ color: 'var(--primary)', fontWeight: 700 }}>
                        Akun ({user.username})
                      </Link>
                    </li>
                  </>
                ) : (
                  <li className="nav-item">
                    <Link href="/login" className="btn-portal-login">
                      Portal Masuk
                    </Link>
                  </li>
                )}
              </ul>
            </nav>
          </div>
        </header>

        {/* Page Content */}
        <main style={{ flex: 1 }}>{children}</main>

        {/* Official University Footer */}
        <footer>
          <div className="footer-main">
            <div className="footer-col">
              <h4>Portal Informasi Teknik Informatika</h4>
              <p>
                Sistem informasi publikasi berita, pengumuman jadwal akademik, dan layanan terpadu civitas akademika Jurusan Teknik Informatika.
              </p>
              <p style={{ fontSize: '0.8rem', color: '#64748b' }}>
                Gedung Laboratorium Komputasi & Jaringan Terpadu, Lantai 3.<br />
                Jl. Kampus Ilmu Komputer No. 1 &bull; Email: sekretariat@informatika.ac.id
              </p>
            </div>
            <div className="footer-col">
              <h4>Layanan Akademik</h4>
              <ul>
                <li><Link href="/search?q=Akademik">Pengumuman Akademik</Link></li>
                <li><Link href="/search?q=Workshop">Kegiatan & Workshop</Link></li>
                <li><Link href="/view?page=public/docs/panduan_layanan.txt">Panduan Tugas Akhir</Link></li>
                <li><Link href="/login">Portal Masuk Staf & Dosen</Link></li>
              </ul>
            </div>
            <div className="footer-col">
              <h4>Pusat Bantuan</h4>
              <ul>
                <li><Link href="/view?page=public/docs/tentang_kami.txt">Profil Program Studi</Link></li>
                <li><Link href="/view?page=public/docs/standar_laboratorium.txt">Tata Tertib Laboratorium</Link></li>
                <li><Link href="/robots.txt">Pengaturan Index Mesin Pencari</Link></li>
              </ul>
            </div>
          </div>

          {/* Bar Status Diagnostik Server (Bukti Screenshot Praktikum) */}
          <div className="footer-bottom">
            <div className="footer-bottom-inner">
              <div>
                &copy; {new Date().getFullYear()} Program Studi Teknik Informatika. Hak Cipta Dilindungi.
              </div>
              <div style={{ fontFamily: 'var(--font-mono)', fontSize: '0.74rem', color: '#64748b' }}>
                Runtime: Next.js 15 (Vercel Serverless) &bull; Banner: X-Lab-Version: VulnLab-PHP/1.2 &bull; Port: 80/443
              </div>
            </div>
          </div>
        </footer>
      </body>
    </html>
  );
}

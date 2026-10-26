import Link from 'next/link';
import { executeQuery, getDbInfo } from '@/lib/db';

export const revalidate = 0; // Dynamic rendering

export default async function HomePage() {
  let recentPosts = [];
  let dbInfo = getDbInfo();

  try {
    recentPosts = await executeQuery(
      "SELECT id, title, category, content, author, created_at FROM posts ORDER BY id DESC LIMIT 3"
    );
  } catch (err) {
    console.error("Gagal memuat berita beranda:", err.message);
  }

  return (
    <div className="container">
      {/* Hero Section */}
      <section className="hero-banner">
        <h2>Pusat Informasi & Layanan Terpadu<br />Teknik Informatika</h2>
        <p>
          Portal publikasi resmi kegiatan akademik, pengumuman jadwal perkuliahan, riset komputasi, dan layanan administrasi laboratorium.
        </p>

        {/* Form Pencarian Terpadu */}
        <form action="/search" method="GET" className="hero-search-box">
          <input
            type="text"
            name="q"
            className="hero-search-input"
            placeholder="Cari artikel, pengumuman, atau jadwal..."
          />
          <button type="submit" className="btn-search">
            Cari
          </button>
        </form>
      </section>

      {/* Berita & Pengumuman Terkini */}
      <section style={{ marginBottom: '36px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '18px' }}>
          <div>
            <h3 style={{ fontSize: '1.35rem', fontWeight: 700, color: '#1e293b' }}>
              Berita & Pengumuman Terkini
            </h3>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem' }}>
              Informasi dan agenda terbaru seputar kegiatan civitas akademika
            </p>
          </div>
          <Link href="/search" className="btn btn-outline" style={{ fontSize: '0.85rem', padding: '6px 14px' }}>
            Lihat Semua &rarr;
          </Link>
        </div>

        {recentPosts && recentPosts.length > 0 ? (
          <div className="grid-3">
            {recentPosts.map((post) => (
              <article key={post.id} className="news-card">
                <div className="news-card-body">
                  <span className="news-category">{post.category}</span>
                  <h4 className="news-title">{post.title}</h4>
                  <p className="news-snippet">
                    {post.content && post.content.length > 120
                      ? post.content.substring(0, 120) + '...'
                      : post.content}
                  </p>
                  <div className="news-meta">
                    <span>✍️ {post.author}</span>
                    <span>📅 {post.created_at ? post.created_at.substring(0, 10) : '2026-03-20'}</span>
                  </div>
                </div>
              </article>
            ))}
          </div>
        ) : (
          <div className="alert alert-info">
            Belum ada pengumuman yang dimuat.
          </div>
        )}
      </section>

      {/* Menu Layanan Akademik */}
      <section style={{ marginBottom: '36px' }}>
        <h3 style={{ fontSize: '1.35rem', fontWeight: 700, color: '#1e293b', marginBottom: '16px' }}>
          Layanan Akademik & Administrasi
        </h3>
        <div className="grid-3">
          <div className="card" style={{ marginBottom: 0 }}>
            <div style={{ fontSize: '2rem', marginBottom: '10px' }}>📋</div>
            <h4 style={{ fontSize: '1.05rem', fontWeight: 700, marginBottom: '8px' }}>Pusat Dokumen & Panduan</h4>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem', marginBottom: '16px' }}>
              Akses berkas standar operasional, panduan skripsi, dan formulir akademik secara daring.
            </p>
            <Link href="/view?page=public/docs/panduan_layanan.txt" className="btn btn-outline" style={{ fontSize: '0.85rem', width: '100%' }}>
              Buka Dokumen
            </Link>
          </div>

          <div className="card" style={{ marginBottom: 0 }}>
            <div style={{ fontSize: '2rem', marginBottom: '10px' }}>🔍</div>
            <h4 style={{ fontSize: '1.05rem', fontWeight: 700, marginBottom: '8px' }}>Pencarian Arsip Berita</h4>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem', marginBottom: '16px' }}>
              Cari arsip kegiatan, surat keputusan jurusan, dan riwayat pengumuman semester terdahulu.
            </p>
            <Link href="/search" className="btn btn-outline" style={{ fontSize: '0.85rem', width: '100%' }}>
              Cari Berita
            </Link>
          </div>

          <div className="card" style={{ marginBottom: 0 }}>
            <div style={{ fontSize: '2rem', marginBottom: '10px' }}>🔐</div>
            <h4 style={{ fontSize: '1.05rem', fontWeight: 700, marginBottom: '8px' }}>Portal Masuk Staf & Dosen</h4>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem', marginBottom: '16px' }}>
              Akses portal internal untuk pengelolaan pengumuman, data riset, dan administrasi jurusan.
            </p>
            <Link href="/login" className="btn btn-primary" style={{ fontSize: '0.85rem', width: '100%' }}>
              Masuk Portal
            </Link>
          </div>
        </div>
      </section>

      {/* Informasi Diagnostik Sistem (Bukti Screenshot Praktikum) */}
      <section className="card" style={{ background: '#ffffff', borderLeft: '4px solid var(--primary)' }}>
        <div className="card-header" style={{ borderBottom: 'none', marginBottom: 0, paddingBottom: 0 }}>
          <div>
            <h4 style={{ fontSize: '1.05rem', fontWeight: 700, color: '#1e293b' }}>
              Status Infrastruktur Server & Layanan Web (System Diagnostic)
            </h4>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.82rem' }}>
              Informasi teknis layanan web untuk validasi HTTP / Nmap Service Discovery.
            </p>
          </div>
          <span className="badge badge-green">LAYANAN AKTIF</span>
        </div>

        <div className="table-responsive" style={{ marginTop: '14px' }}>
          <table>
            <tbody>
              <tr>
                <th style={{ width: '25%' }}>Teknologi Framework</th>
                <td><strong>Next.js 15 (React 19) &bull; Vercel Serverless Platform</strong></td>
              </tr>
              <tr>
                <th>Custom Header Banner</th>
                <td><code>X-Lab-Version: VulnLab-PHP/1.2</code></td>
              </tr>
              <tr>
                <th>Identitas Layanan</th>
                <td><code>X-Target-Server: NPT-Lab-Teknik-Informatika</code></td>
              </tr>
              <tr>
                <th>Basis Data Aktif</th>
                <td>
                  <span className="badge badge-blue">{dbInfo.engine}</span>{' '}
                  <span className="badge badge-green">ONLINE</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  );
}

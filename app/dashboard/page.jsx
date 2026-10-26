import { redirect } from 'next/navigation';
import Link from 'next/link';
import { getSession } from '@/lib/session';
import LogoutButton from './logout-button';

export const revalidate = 0;

export default async function DashboardPage() {
  const user = await getSession();

  if (!user) {
    redirect('/login');
  }

  return (
    <div className="container">
      {/* Profil Pengguna */}
      <div className="card" style={{ borderTop: '4px solid var(--primary)' }}>
        <div className="card-header">
          <div>
            <h2 className="card-title" style={{ fontSize: '1.35rem' }}>
              Dashboard Administrasi & Profil Civitas
            </h2>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
              Selamat datang kembali di panel kendali sistem informasi akademik.
            </p>
          </div>
          <LogoutButton />
        </div>

        {/* Banner Otorisasi / Flag Utama */}
        <div className="alert alert-success" style={{ borderLeft: '4px solid #16a34a', background: '#f0fdf4' }}>
          <div style={{ fontWeight: 700, color: '#15803d', marginBottom: '4px' }}>
            🎉 Sesi Otorisasi Berhasil Divalidasi:
          </div>
          <div style={{ fontFamily: 'var(--font-mono)', fontSize: '1.1rem', fontWeight: 700, color: '#166534', wordBreak: 'break-all' }}>
            FLAG&#123;SQL_Injection_Success&#125;
          </div>
          {user.flag && user.flag !== 'FLAG{SQL_Injection_Success}' && (
            <div style={{ marginTop: '8px', fontSize: '0.88rem', color: '#15803d' }}>
              Token Hak Akses Pengguna: <code>{user.flag}</code>
            </div>
          )}
        </div>

        {/* Detail Akun */}
        <div className="table-responsive" style={{ marginTop: '20px' }}>
          <table>
            <tbody>
              <tr>
                <th style={{ width: '25%' }}>ID Pengguna</th>
                <td><code>#{user.id || '1'}</code></td>
              </tr>
              <tr>
                <th>Nama Pengguna (Username)</th>
                <td><strong>{user.username}</strong></td>
              </tr>
              <tr>
                <th>Jabatan / Hak Akses</th>
                <td><span className="badge badge-blue">{user.role || 'Administrator'}</span></td>
              </tr>
              <tr>
                <th>Status Sesi</th>
                <td><span className="badge badge-green">Aktif Terautentikasi</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div style={{ display: 'flex', gap: '12px', marginTop: '24px', flexWrap: 'wrap' }}>
          <Link href="/search" className="btn btn-primary">
            Kelola & Cari Pengumuman &rarr;
          </Link>
          <Link href="/view?page=public/docs/panduan_layanan.txt" className="btn btn-outline">
            Lihat Panduan Layanan
          </Link>
          <Link href="/" className="btn btn-outline">
            Kembali ke Beranda
          </Link>
        </div>
      </div>

      {/* Catatan Internal Staf */}
      <div className="card">
        <h3 style={{ fontSize: '1.1rem', fontWeight: 700, color: '#1e293b', marginBottom: '12px' }}>
          Catatan Tim Pengelola Sistem Informasi
        </h3>
        <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem', marginBottom: '10px' }}>
          Daftar pengingat untuk administrator sistem dan staf teknis laboratorium:
        </p>
        <ul style={{ paddingLeft: '20px', fontSize: '0.88rem', color: '#475569', lineHeight: 1.8 }}>
          <li>Salinan konfigurasi basis data tersimpan pada berkas <code>public/backup-config.txt</code> di folder utama server.</li>
          <li>Seluruh dokumen panduan dapat diakses langsung menggunakan modul dokumen <code>/view?page=public/docs/panduan_layanan.txt</code>.</li>
          <li>Daftar pembatasan perayap mesin pencari dikonfigurasi melalui berkas <code>public/robots.txt</code>.</li>
        </ul>
      </div>
    </div>
  );
}

import Link from 'next/link';
import fs from 'fs/promises';
import path from 'path';

export const revalidate = 0;

export default async function ViewPage({ searchParams }) {
  const params = await searchParams;
  const page = params?.page || 'public/docs/panduan_layanan.txt';

  let fileContent = '';
  let error = '';

  // =====================================================================
  // [VULNERABILITY] LOCAL FILE INCLUSION (LFI) & PATH TRAVERSAL
  // Celah: Parameter page digabungkan langsung tanpa basename() atau whitelist
  // =====================================================================
  try {
    let targetPath = path.resolve(process.cwd(), page);
    try {
      fileContent = await fs.readFile(targetPath, 'utf-8');
    } catch (e1) {
      try {
        const altPath = path.resolve(process.cwd(), 'public', page);
        fileContent = await fs.readFile(altPath, 'utf-8');
      } catch (e2) {
        const altPathDocs = path.resolve(process.cwd(), 'public/docs', page);
        fileContent = await fs.readFile(altPathDocs, 'utf-8');
      }
    }
  } catch (err) {
    error = `Dokumen '${page}' tidak ditemukan atau gagal dimuat pada repositori server.`;
  }

  return (
    <div className="container">
      <div className="card">
        <div className="card-header">
          <div>
            <h2 className="card-title" style={{ fontSize: '1.3rem' }}>
              Pusat Dokumen & Panduan Layanan Terpadu
            </h2>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
              Repositori berkas digital, panduan operasional, dan peraturan akademik.
            </p>
          </div>
          <span className="badge badge-slate">ARSIP RESMI</span>
        </div>

        {/* Pilihan Dokumen Cepat */}
        <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap', marginBottom: '20px', alignItems: 'center' }}>
          <span style={{ fontSize: '0.88rem', fontWeight: 600, color: '#475569' }}>Pilihan Dokumen:</span>
          <Link
            href="/view?page=public/docs/panduan_layanan.txt"
            className="btn btn-outline"
            style={{ fontSize: '0.82rem', padding: '6px 12px' }}
          >
            📄 Panduan Layanan
          </Link>
          <Link
            href="/view?page=public/docs/tentang_kami.txt"
            className="btn btn-outline"
            style={{ fontSize: '0.82rem', padding: '6px 12px' }}
          >
            🏛️ Profil Jurusan
          </Link>
          <Link
            href="/view?page=public/docs/standar_laboratorium.txt"
            className="btn btn-outline"
            style={{ fontSize: '0.82rem', padding: '6px 12px' }}
          >
            🔬 Tata Tertib Lab
          </Link>
        </div>

        {/* Form Pemanggilan Berkas Manual */}
        <form action="/view" method="GET" style={{ display: 'flex', gap: '10px', marginBottom: '24px', flexWrap: 'wrap' }}>
          <input
            type="text"
            name="page"
            className="form-control"
            style={{ flex: 1, minWidth: '250px' }}
            placeholder="Ketik path berkas panduan..."
            defaultValue={page}
          />
          <button type="submit" className="btn btn-primary">
            Buka Berkas
          </button>
        </form>

        {error && <div className="alert alert-danger">{error}</div>}

        {fileContent && (
          <div style={{ border: '1px solid var(--border)', borderRadius: '8px', overflow: 'hidden' }}>
            <div
              style={{
                background: '#f8fafc',
                padding: '12px 18px',
                borderBottom: '1px solid var(--border)',
                fontSize: '0.85rem',
                fontWeight: 600,
                color: '#475569',
              }}
            >
              Menampilkan Berkas: <code>{page}</code>
            </div>
            <div
              style={{
                padding: '24px',
                background: '#ffffff',
                color: '#1e293b',
                whiteSpace: 'pre-wrap',
                fontFamily: 'var(--font-sans)',
                lineHeight: 1.7,
                fontSize: '0.95rem',
              }}
            >
              {fileContent}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

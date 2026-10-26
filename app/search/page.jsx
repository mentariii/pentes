import Link from 'next/link';
import { executeQuery } from '@/lib/db';

export const revalidate = 0;

export default async function SearchPage({ searchParams }) {
  const params = await searchParams;
  const q = params?.q || '';
  let results = [];
  let sqlError = '';
  let executedQuery = '';

  if (q !== '') {
    // =====================================================================
    // [VULNERABILITY 1] UNION-BASED SQL INJECTION
    // Mengembalikan 5 kolom: (id, title, category, content, author)
    // Parameter q langsung digabungkan ke query SQL
    // =====================================================================
    executedQuery = `SELECT id, title, category, content, author FROM posts WHERE title LIKE '%${q}%' OR content LIKE '%${q}%'`;

    try {
      results = await executeQuery(executedQuery);
    } catch (err) {
      sqlError = err.message;
    }
  } else {
    try {
      results = await executeQuery(
        `SELECT id, title, category, content, author, created_at FROM posts ORDER BY id DESC`
      );
    } catch (err) {
      sqlError = err.message;
    }
  }

  return (
    <div className="container">
      <div className="card">
        <div className="card-header">
          <div>
            <h2 className="card-title" style={{ fontSize: '1.3rem' }}>
              Pencarian Berita & Pengumuman Akademik
            </h2>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
              Cari arsip berita, surat edaran, kegiatan kemahasiswaan, dan agenda akademik.
            </p>
          </div>
        </div>

        {/* Form Pencarian */}
        <form action="/search" method="GET" style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', marginBottom: '20px' }}>
          <input
            type="text"
            name="q"
            className="form-control"
            style={{ flex: 1, minWidth: '250px' }}
            placeholder="Ketik kata kunci (contoh: Sidang, Workshop, Beasiswa)..."
            defaultValue={q}
          />
          <button type="submit" className="btn btn-primary">
            Cari Berita
          </button>
          {q !== '' && (
            <Link href="/search" className="btn btn-outline">
              Reset
            </Link>
          )}
        </form>

        {/* ===================================================================== */}
        {/* [VULNERABILITY 2] REFLECTED CROSS-SITE SCRIPTING (XSS)                */}
        {/* Nilai q dicetak langsung tanpa encoding menggunakan dangerouslySetInnerHTML */}
        {/* ===================================================================== */}
        {q !== '' && (
          <div
            style={{
              marginBottom: '20px',
              padding: '12px 16px',
              background: '#f8fafc',
              borderRadius: '6px',
              border: '1px solid var(--border)',
            }}
          >
            <p
              style={{ fontSize: '0.95rem', color: '#334155' }}
              dangerouslySetInnerHTML={{
                __html: `Menampilkan hasil pencarian untuk kata kunci: <strong>${q}</strong>`,
              }}
            />
          </div>
        )}

        {/* Error Query Database jika Payload Error */}
        {sqlError && (
          <div className="alert alert-danger">
            <strong>Terjadi Kesalahan Kueri Database:</strong>
            <div style={{ fontFamily: 'var(--font-mono)', fontSize: '0.82rem', marginTop: '6px' }}>
              {sqlError}
            </div>
            {executedQuery && (
              <div style={{ marginTop: '8px', fontSize: '0.78rem', color: '#b91c1c' }}>
                Query: <code>{executedQuery}</code>
              </div>
            )}
          </div>
        )}

        {/* Daftar Hasil Pencarian / Data Terekstraksi */}
        {results && results.length > 0 ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
            {results.map((item, idx) => (
              <article
                key={idx}
                style={{
                  border: '1px solid var(--border)',
                  borderRadius: '8px',
                  padding: '18px',
                  background: '#ffffff',
                }}
              >
                <div
                  style={{
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    marginBottom: '8px',
                    flexWrap: 'wrap',
                    gap: '8px',
                  }}
                >
                  <span className="badge badge-blue">{item.category || 'Umum'}</span>
                  <span style={{ fontSize: '0.78rem', color: '#94a3b8' }}>
                    Penulis / User: <strong>{item.author || '-'}</strong>
                  </span>
                </div>
                <h3 style={{ fontSize: '1.15rem', fontWeight: 700, color: '#1e293b', marginBottom: '8px' }}>
                  {item.title || '-'}
                </h3>
                <p style={{ fontSize: '0.9rem', color: '#475569', lineHeight: 1.6, whiteSpace: 'pre-wrap' }}>
                  {item.content || '-'}
                </p>
              </article>
            ))}
          </div>
        ) : (
          !sqlError && (
            <div className="alert alert-info">
              Tidak ada informasi yang sesuai dengan kata kunci pencarian Anda. Silakan coba kata kunci lain.
            </div>
          )
        )}
      </div>
    </div>
  );
}

'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function LoginPage() {
  const router = useRouter();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [debugSql, setDebugSql] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setDebugSql('');

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      });

      const data = await res.json();

      if (data.executedQuery) {
        setDebugSql(data.executedQuery);
      }

      if (data.success) {
        router.push('/dashboard');
        router.refresh();
      } else {
        setError(data.error || 'Autentikasi gagal.');
        if (data.sqlError) {
          setError(`${data.error} (Detail SQL: ${data.sqlError})`);
        }
      }
    } catch (err) {
      setError('Terjadi kendala jaringan saat menghubungi server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container" style={{ maxWidth: '520px' }}>
      <div
        className="card"
        style={{
          boxShadow: 'var(--shadow-lg)',
          borderTop: '4px solid var(--primary)',
          padding: '32px 28px',
        }}
      >
        <div style={{ textAlign: 'center', marginBottom: '24px' }}>
          <div
            className="logo-icon"
            style={{ margin: '0 auto 12px auto', width: '48px', height: '48px', fontSize: '1.4rem' }}
          >
            TI
          </div>
          <h2 style={{ fontSize: '1.35rem', fontWeight: 700, color: '#1e293b' }}>
            Portal Masuk Civitas
          </h2>
          <p style={{ color: 'var(--text-muted)', fontSize: '0.88rem', marginTop: '4px' }}>
            Masuk ke sistem informasi akademik & manajemen laboratorium
          </p>
        </div>

        {error && (
          <div className="alert alert-danger" style={{ fontSize: '0.88rem' }}>
            <strong>Gagal:</strong> {error}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label className="form-label" htmlFor="username">
              ID Pengguna / Username / NIP:
            </label>
            <input
              type="text"
              id="username"
              className="form-control"
              placeholder="Masukkan ID pengguna Anda"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
              autoFocus
            />
          </div>

          <div className="form-group">
            <div
              style={{
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                marginBottom: '6px',
              }}
            >
              <label className="form-label" htmlFor="password" style={{ marginBottom: 0 }}>
                Kata Sandi:
              </label>
              <Link
                href="/view?page=public/docs/panduan_layanan.txt"
                style={{ fontSize: '0.78rem', color: 'var(--primary)' }}
              >
                Bantuan Akun?
              </Link>
            </div>
            <input
              type="password"
              id="password"
              className="form-control"
              placeholder="Masukkan kata sandi"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          <button
            type="submit"
            className="btn btn-primary"
            style={{ width: '100%', padding: '12px', marginTop: '6px' }}
            disabled={loading}
          >
            {loading ? 'Memverifikasi...' : 'Masuk ke Akun'}
          </button>
        </form>

        {debugSql && (
          <div style={{ marginTop: '16px', fontSize: '0.75rem', color: '#64748b' }}>
            <strong>Query Terakhir:</strong> <code>{debugSql}</code>
          </div>
        )}

        <div
          style={{
            textAlign: 'center',
            marginTop: '24px',
            paddingTop: '20px',
            borderTop: '1px solid var(--border)',
            fontSize: '0.82rem',
            color: 'var(--text-muted)',
          }}
        >
          Lupa kata sandi atau kendala akses? Hubungi Unit Layanan Terpadu Fakultas atau baca panduan di{' '}
          <Link href="/view?page=public/docs/panduan_layanan.txt" style={{ color: 'var(--primary)' }}>
            Pusat Dokumen
          </Link>
          .
        </div>
      </div>
    </div>
  );
}

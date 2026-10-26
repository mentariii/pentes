'use client';

import { useRouter } from 'next/navigation';

export default function LogoutButton() {
  const router = useRouter();

  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      router.push('/login');
      router.refresh();
    } catch (err) {
      console.error("Gagal logout:", err);
    }
  };

  return (
    <button
      onClick={handleLogout}
      className="btn btn-outline"
      style={{ color: '#ef4444', borderColor: '#fca5a5' }}
    >
      Keluar Akun
    </button>
  );
}

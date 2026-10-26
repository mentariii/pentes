import { cookies } from 'next/headers';

const SESSION_COOKIE = 'portal_session';

/**
 * Simpan data sesi pengguna ke cookie HTTP
 */
export async function setSession(userData) {
  const cookieStore = await cookies();
  const sessionValue = Buffer.from(JSON.stringify(userData)).toString('base64');

  cookieStore.set(SESSION_COOKIE, sessionValue, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    path: '/',
    maxAge: 60 * 60 * 24, // 24 jam
  });
}

/**
 * Ambil data sesi pengguna saat ini
 */
export async function getSession() {
  try {
    const cookieStore = await cookies();
    const sessionCookie = cookieStore.get(SESSION_COOKIE);
    if (!sessionCookie || !sessionCookie.value) {
      return null;
    }
    const decoded = Buffer.from(sessionCookie.value, 'base64').toString('utf-8');
    return JSON.parse(decoded);
  } catch (err) {
    return null;
  }
}

/**
 * Hapus sesi pengguna (Logout)
 */
export async function clearSession() {
  const cookieStore = await cookies();
  cookieStore.delete(SESSION_COOKIE);
}

import { NextResponse } from 'next/server';
import { executeQuery } from '@/lib/db';
import { setSession } from '@/lib/session';

export async function POST(request) {
  try {
    const body = await request.json();
    const username = body.username || '';
    const password = body.password || '';

    // =====================================================================
    // [VULNERABILITY] SQL INJECTION (Authentication Bypass)
    // String query dibuat langsung melalui konkatenasi tanpa sanitasi
    // =====================================================================
    const sql = `SELECT * FROM users WHERE username = '${username}' AND password = '${password}'`;

    let rows = [];
    try {
      rows = await executeQuery(sql);
    } catch (sqlErr) {
      return NextResponse.json(
        {
          success: false,
          error: "Kesalahan sintaks query database.",
          sqlError: sqlErr.message,
          executedQuery: sql,
        },
        { status: 400 }
      );
    }

    if (rows && rows.length > 0) {
      const user = rows[0];
      await setSession(user);

      return NextResponse.json({
        success: true,
        message: "Autentikasi berhasil.",
        user: {
          id: user.id,
          username: user.username,
          role: user.role,
          flag: user.flag,
        },
        executedQuery: sql,
      });
    } else {
      return NextResponse.json(
        {
          success: false,
          error: "Kombinasi ID Pengguna / NIP dan Kata Sandi tidak sesuai.",
          executedQuery: sql,
        },
        { status: 401 }
      );
    }
  } catch (err) {
    return NextResponse.json(
      { success: false, error: err.message },
      { status: 500 }
    );
  }
}

import { NextResponse } from 'next/server';
import { executeQuery } from '@/lib/db';

export async function GET(request) {
  const { searchParams } = new URL(request.url);
  const q = searchParams.get('q');

  let sql = '';
  if (q !== null && q !== undefined && q !== '') {
    // =====================================================================
    // [VULNERABILITY] UNION-BASED SQL INJECTION
    // Mengembalikan 5 kolom: (id, title, category, content, author)
    // Parameter q langsung dikonkatenasi ke string query
    // =====================================================================
    sql = `SELECT id, title, category, content, author FROM posts WHERE title LIKE '%${q}%' OR content LIKE '%${q}%'`;
  } else {
    sql = `SELECT id, title, category, content, author, created_at FROM posts ORDER BY id DESC`;
  }

  try {
    const rows = await executeQuery(sql);
    return NextResponse.json({
      success: true,
      query: sql,
      posts: rows,
    });
  } catch (err) {
    return NextResponse.json(
      {
        success: false,
        error: "Kesalahan eksekusi query database.",
        sqlError: err.message,
        executedQuery: sql,
      },
      { status: 400 }
    );
  }
}

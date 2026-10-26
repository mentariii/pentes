import { NextResponse } from 'next/server';
import fs from 'fs/promises';
import path from 'path';

export async function GET(request) {
  const { searchParams } = new URL(request.url);
  let page = searchParams.get('page') || 'public/docs/panduan_layanan.txt';

  // =====================================================================
  // [VULNERABILITY] LOCAL FILE INCLUSION (LFI) & DIRECTORY TRAVERSAL
  // Celah: Parameter page digabungkan langsung tanpa basename() atau whitelist
  // =====================================================================
  try {
    // Jika user hanya mengetik 'panduan_layanan.txt', cek juga di public/docs/ jika di root tidak ada
    let targetPath = path.resolve(process.cwd(), page);
    let content = '';

    try {
      content = await fs.readFile(targetPath, 'utf-8');
    } catch (e1) {
      // Coba cari di folder public/ atau public/docs/
      try {
        const altPath = path.resolve(process.cwd(), 'public', page);
        content = await fs.readFile(altPath, 'utf-8');
        targetPath = altPath;
      } catch (e2) {
        const altPathDocs = path.resolve(process.cwd(), 'public/docs', page);
        content = await fs.readFile(altPathDocs, 'utf-8');
        targetPath = altPathDocs;
      }
    }

    return NextResponse.json({
      success: true,
      page: page,
      resolvedPath: targetPath,
      content: content,
    });
  } catch (err) {
    return NextResponse.json(
      {
        success: false,
        error: `Dokumen '${page}' tidak ditemukan atau gagal dimuat: ${err.message}`,
      },
      { status: 404 }
    );
  }
}

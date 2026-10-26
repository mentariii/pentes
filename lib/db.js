/**
 * lib/db.js - Database Manager untuk Next.js (Vercel Ready)
 * Mendukung koneksi MySQL eksternal dan Embedded In-Memory SQL Engine bawaan.
 */

const USERS = [
  { id: 1, username: 'admin', password: 'SuperAdmin#2026!', role: 'Kepala Laboratorium', flag: 'FLAG{SQL_Injection_Success}' },
  { id: 2, username: 'dosen_ti', password: 'DosenInformatika2026', role: 'Dosen Pengampu', flag: 'FLAG{Lecturer_Secret_Token_9921}' },
  { id: 3, username: 'staff_tu', password: 'StaffAkademik2026', role: 'Staff Administrasi', flag: 'FLAG{Staff_Privilege_Token_5542}' },
  { id: 4, username: 'mahasiswa', password: 'mhs12345', role: 'Mahasiswa Aktif', flag: 'FLAG{Student_Credentials_Bypassed_1120}' },
];

const POSTS = [
  { id: 1, title: 'Pendaftaran Sidang Tugas Akhir dan Skripsi Periode Semester Genap', category: 'Akademik', content: 'Diumumkan kepada seluruh mahasiswa tingkat akhir Teknik Informatika, pendaftaran sidang skripsi dan tugas akhir telah dibuka hingga akhir bulan ini. Berkas pendaftaran dan lembar persetujuan pembimbing dapat diserahkan ke bagian Tata Usaha.', author: 'Bagian Akademik', created_at: '2026-03-01 09:00:00' },
  { id: 2, title: 'Workshop Nasional: Arsitektur Cloud Computing dan Keamanan Siber Modern', category: 'Kegiatan', content: 'Jurusan Teknik Informatika menyelenggarakan workshop nasional bertajuk "Modern Cloud & Cyber Defense Architecture" dengan pembicara praktisi industri. Seluruh mahasiswa dapat mengikuti kegiatan ini secara luring di Gedung Auditorium.', author: 'Himpunan Mahasiswa TI', created_at: '2026-03-05 13:30:00' },
  { id: 3, title: 'Jadwal Penggunaan Laboratorium Komputasi dan Jaringan Terpadu', category: 'Fasilitas', content: 'Laboratorium Komputasi dan Jaringan dibuka setiap hari kerja pukul 08.00 - 17.00 WIB. Bagi mahasiswa yang ingin menggunakan fasilitas riset mandiri atau praktikum terstruktur, silakan mendaftar melalui koordinator laboratorium.', author: 'Kepala Laboratorium', created_at: '2026-03-10 10:15:00' },
  { id: 4, title: 'Sosialisasi Program Magang Industri dan Studi Independen Bersertifikat', category: 'Kemahasiswaan', content: 'Program magang bersertifikat mitra industri teknologi terkemuka kembali membuka kesempatan bagi mahasiswa semester 5 dan 7. Kuota terbatas untuk penempatan di bidang Software Engineering, Data Analyst, dan Network Security.', author: 'Pusat Karir & Alumni', created_at: '2026-03-15 11:00:00' },
  { id: 5, title: 'Pemeliharaan Server Portal Akademik dan Jaringan Internet Kampus', category: 'Pengumuman', content: 'Akan dilakukan perawatan rutin infrastruktur server dan router utama pada akhir pekan ini. Akses layanan sistem informasi intra-kampus mungkin mengalami penyesuaian sementara selama proses pemeliharaan berlangsung.', author: 'Tim IT Infrastruktur', created_at: '2026-03-20 16:00:00' },
  { id: 6, title: 'Pengumuman Penerima Bantuan Dana Hibah Riset Mahasiswa', category: 'Prestasi', content: 'Selamat kepada 5 kelompok riset mahasiswa Teknik Informatika yang berhasil lolos seleksi pendanaan program inovasi komputasi dan riset teknologi informasi tahun akademik ini.', author: 'Dewan Riset Fakultas', created_at: '2026-03-22 14:20:00' }
];

let mysqlPool = null;

/**
 * Eksekusi kueri SQL
 */
export async function executeQuery(sqlString) {
  // 1. Opsi Remote MySQL jika MYSQL_HOST dikonfigurasi
  if (process.env.MYSQL_HOST) {
    try {
      const mysql = await import('mysql2/promise');
      if (!mysqlPool) {
        mysqlPool = mysql.createPool({
          host: process.env.MYSQL_HOST,
          user: process.env.MYSQL_USER,
          password: process.env.MYSQL_PASSWORD,
          database: process.env.MYSQL_DATABASE,
          port: Number(process.env.MYSQL_PORT || 3306),
          waitForConnections: true,
          connectionLimit: 10,
        });
      }
      const [rows] = await mysqlPool.query(sqlString);
      return rows;
    } catch (mysqlErr) {
      console.warn("MySQL error, fallback ke embedded engine:", mysqlErr.message);
    }
  }

  // 2. Embedded SQL Engine (Zero-Config untuk Vercel Serverless)
  return runEmbeddedSql(sqlString);
}

/**
 * Evaluator SQL In-Memory yang mendukung skenario SQL Injection autentik
 */
function runEmbeddedSql(sql) {
  const trimmed = sql.trim();

  // Deteksi komentar SQL (-- atau #)
  let activeSql = trimmed;
  let hasComment = false;
  const commentIndex = trimmed.search(/--|#/);
  if (commentIndex !== -1) {
    activeSql = trimmed.substring(0, commentIndex).trim();
    hasComment = true;
  }

  // Validasi tanda kutip ganjil jika tidak dikomentari (Syntax Error)
  if (!hasComment) {
    const quoteCount = (activeSql.match(/'/g) || []).length;
    if (quoteCount % 2 !== 0) {
      throw new Error(`You have an error in your SQL syntax near '${activeSql.slice(-15)}'`);
    }
  }

  const upperActive = activeSql.toUpperCase();

  // -------------------------------------------------------------
  // SKENARIO 1: UNION-BASED SQL INJECTION (HARUS DIPERIKSA DULU)
  // Contoh: ' UNION SELECT 1, username, role, flag, password FROM users --
  // -------------------------------------------------------------
  if (upperActive.includes('UNION') && upperActive.includes('SELECT')) {
    const unionUsers = USERS.map((u) => ({
      id: u.id,
      title: u.username,
      category: u.role,
      content: u.flag,
      author: u.password,
    }));

    // Cek apakah ada filter normal sebelum UNION
    let baseResults = [];
    const beforeUnion = activeSql.substring(0, upperActive.indexOf('UNION'));
    const likeMatch = beforeUnion.match(/LIKE\s*'%([^%']*)'/i);
    if (likeMatch && likeMatch[1] && likeMatch[1].trim() !== '') {
      const term = likeMatch[1].toLowerCase();
      baseResults = POSTS.filter(
        (p) => p.title.toLowerCase().includes(term) || p.content.toLowerCase().includes(term)
      );
    }

    return [...baseResults, ...unionUsers];
  }

  // -------------------------------------------------------------
  // SKENARIO 2: LOGIN QUERY (SELECT * FROM users WHERE ...)
  // -------------------------------------------------------------
  if (upperActive.includes('FROM USERS') && upperActive.includes('WHERE')) {
    // Cek kondisi bypass SQL Injection umum:
    // Contoh: ' OR '1'='1, ' OR 1=1, admin' --, ' OR ''='
    const isAlwaysTrue = 
      /OR\s+['"]?1['"]?\s*=\s*['"]?1['"]?/i.test(activeSql) ||
      /OR\s+1\s*=\s*1/i.test(activeSql) ||
      /OR\s+true/i.test(activeSql) ||
      /OR\s+['"]?a['"]?\s*=\s*['"]?a['"]?/i.test(activeSql) ||
      /OR\s+''=''/i.test(activeSql);

    if (isAlwaysTrue) {
      return [USERS[0]];
    }

    // Ekstrak username jika ada pola username = '...'
    const userMatch = activeSql.match(/username\s*=\s*'([^']*)'/i);
    const passMatch = activeSql.match(/password\s*=\s*'([^']*)'/i);

    if (userMatch) {
      const u = userMatch[1];

      // Jika ada komentar setelah username (misal admin' -- )
      if (hasComment) {
        const found = USERS.find((usr) => usr.username.toLowerCase() === u.toLowerCase());
        return found ? [found] : [];
      }

      // Jika username dan password normal
      if (passMatch) {
        const p = passMatch[1];
        const found = USERS.find(
          (usr) => usr.username.toLowerCase() === u.toLowerCase() && usr.password === p
        );
        return found ? [found] : [];
      }
    }

    return [];
  }

  // -------------------------------------------------------------
  // SKENARIO 3: PENCARIAN BERITA NORMAL (SELECT ... FROM posts)
  // -------------------------------------------------------------
  if (upperActive.includes('FROM POSTS')) {
    const likeMatch = activeSql.match(/LIKE\s*'%([^%']*)%'/i);
    if (likeMatch && likeMatch[1]) {
      const term = likeMatch[1].toLowerCase();
      return POSTS.filter(
        (p) => p.title.toLowerCase().includes(term) || p.content.toLowerCase().includes(term)
      );
    }

    if (upperActive.includes('LIMIT 3')) {
      return POSTS.slice(0, 3);
    }
    return POSTS;
  }

  return [];
}

/**
 * Status basis data saat ini
 */
export function getDbInfo() {
  const isMysql = Boolean(process.env.MYSQL_HOST);
  return {
    engine: isMysql ? "MySQL Server (Remote)" : "Embedded In-Memory Engine (Vercel Serverless Ready)",
    host: process.env.MYSQL_HOST || "Vercel Serverless Node",
    database: process.env.MYSQL_DATABASE || "portal_ti_db",
    status: "CONNECTED",
  };
}

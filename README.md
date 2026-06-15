# Website Company Profile Sekolah

Website Company Profile untuk SMA dengan fitur modern dan elegan menggunakan PHP Native, MySQL, dan Bootstrap 5.

## 📋 Fitur Utama

### Frontend (Pengunjung)
- ✅ Halaman Beranda dengan slider hero
- ✅ Profil Sekolah (Sejarah, Visi, Misi, Fasilitas)
- ✅ Daftar Guru & Staff
- ✅ Berita & Pengumuman
- ✅ Galeri Foto Kegiatan
- ✅ Prestasi Sekolah
- ✅ Halaman Kontak dengan Form
- ✅ Desain Responsive (Mobile Friendly)
- ✅ Animasi Modern (AOS)

### Backend (Admin & Staff)
- ✅ Dashboard dengan statistik
- ✅ CRUD Berita (Tambah, Edit, Hapus)
- ✅ CRUD Galeri
- ✅ CRUD Prestasi
- ✅ Kelola Data Guru (Admin only)
- ✅ Kelola Data Staff (Admin only)
- ✅ Kelola User/Login (Admin only)
- ✅ Kelola Profil Sekolah (Admin only)
- ✅ Lihat Pesan Kontak
- ✅ **Audit Log** (Track semua aktivitas user)
- ✅ Role-Based Access Control (Admin & Staff)

### Keamanan
- ✅ Password Hashing (bcrypt)
- ✅ SQL Injection Prevention
- ✅ XSS Protection
- ✅ Session Management
- ✅ Audit Log Activity

## 🚀 Teknologi

- **Backend**: PHP Native (7.4+)
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5.3
- **Icons**: Font Awesome 6.4
- **Animations**: AOS (Animate On Scroll)
- **Fonts**: Google Fonts (Inter & Poppins)

## 📦 Struktur Folder

```
school-profile/
├── admin/                  # Dashboard Admin & Staff
│   ├── assets/            # CSS & JS untuk admin
│   ├── includes/          # Header & sidebar admin
│   ├── berita/            # CRUD Berita
│   ├── galeri/            # CRUD Galeri
│   ├── prestasi/          # CRUD Prestasi
│   ├── guru/              # CRUD Guru (Admin only)
│   ├── staff/             # CRUD Staff (Admin only)
│   ├── users/             # CRUD Users (Admin only)
│   ├── profil/            # Edit Profil Sekolah (Admin only)
│   ├── kontak/            # Lihat Pesan Kontak
│   ├── audit-log/         # Lihat Activity Log
│   ├── index.php          # Dashboard
│   ├── login.php          # Halaman Login
│   └── logout.php         # Logout
├── assets/                 # Assets untuk frontend
│   ├── css/               # File CSS
│   └── js/                # File JavaScript
├── config/                 # Konfigurasi
│   ├── config.php         # Config utama
│   └── database.php       # Config database
├── includes/               # Header & Footer
│   ├── header.php
│   └── footer.php
├── uploads/                # Folder upload file
│   ├── logo/
│   ├── berita/
│   ├── galeri/
│   ├── guru/
│   ├── staff/
│   └── prestasi/
├── index.php               # Homepage
├── profil.php              # Halaman Profil
├── guru.php                # Halaman Guru & Staff
├── berita.php              # List Berita
├── berita-detail.php       # Detail Berita
├── galeri.php              # Galeri Foto
├── prestasi.php            # Halaman Prestasi
├── kontak.php              # Halaman Kontak
├── database.sql            # File SQL Database
└── README.md               # Dokumentasi ini
```

## 🔧 Instalasi

### 1. Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3+
- Apache/Nginx Web Server
- Extension PHP: mysqli, gd, mbstring

### 2. Langkah Instalasi

**A. Clone atau Download Project**
```bash
# Download dan ekstrak ke folder htdocs (XAMPP) atau www (WAMP)
```

**B. Import Database**
1. Buka **phpMyAdmin**
2. Buat database baru: `school_profile`
3. Import file `database.sql` ke database tersebut
4. Database akan terisi dengan data dummy

**C. Konfigurasi Database**
1. Buka file `config/database.php`
2. Sesuaikan konfigurasi database:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Sesuaikan username
define('DB_PASS', '');              // Sesuaikan password
define('DB_NAME', 'school_profile');
```

**D. Konfigurasi Site URL**
1. Buka file `config/config.php`
2. Sesuaikan SITE_URL dengan lokasi project:
```php
define('SITE_URL', 'http://localhost/school-profile');
```

**E. Buat Folder Upload**
Pastikan folder `uploads/` dan subfoldernya ada dan memiliki permission write:
```bash
mkdir -p uploads/{logo,berita,galeri,guru,staff,prestasi}
chmod -R 777 uploads/
```

**F. Akses Website**
- **Frontend**: `http://localhost/school-profile`
- **Admin Login**: `http://localhost/school-profile/admin/login.php`

## 🔐 Default Login

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Full Access

### Staff Account
- **Username**: `staff1`
- **Password**: `staff123`
- **Role**: Limited Access

⚠️ **PENTING**: Segera ganti password default setelah login pertama!

## 👥 Role & Permission

### Admin (Full Access)
- ✅ Kelola semua berita (tambah, edit, hapus)
- ✅ Kelola semua galeri
- ✅ Kelola semua prestasi
- ✅ Kelola data guru
- ✅ Kelola data staff
- ✅ Kelola profil sekolah
- ✅ Kelola user (tambah admin/staff)
- ✅ Lihat audit log
- ✅ Lihat dan hapus pesan kontak

### Staff (Limited Access)
- ✅ Kelola semua berita (tambah, edit, hapus)
- ✅ Kelola semua galeri
- ✅ Kelola semua prestasi
- ✅ Lihat pesan kontak
- 👁️ Lihat data guru (read-only)
- 👁️ Lihat data staff (read-only)
- 👁️ Lihat profil sekolah (read-only)
- ❌ Tidak bisa kelola user
- ❌ Tidak bisa edit profil sekolah

## 📊 Database Schema

### users
Login untuk admin dan staff

### profil_sekolah
Informasi sekolah (visi, misi, kontak, dll)

### guru
Data guru dan mata pelajaran

### staff
Data staff administrasi

### kategori_berita
Kategori untuk berita

### berita
Berita dan pengumuman sekolah

### galeri
Galeri foto kegiatan

### prestasi
Prestasi siswa dan sekolah

### fasilitas
Fasilitas yang dimiliki sekolah

### kontak_masuk
Pesan dari pengunjung

### audit_log ⭐
**Fitur Audit Log** mencatat semua aktivitas:
- Siapa yang login
- Siapa yang tambah/edit/hapus data
- Waktu aktivitas
- IP Address
- User Agent

## 🎨 Kustomisasi

### Mengubah Warna Tema
Edit file `assets/css/style.css` pada bagian `:root`:
```css
:root {
    --primary-color: #2563eb;    /* Warna utama */
    --secondary-color: #1e40af;  /* Warna sekunder */
    --accent-color: #f59e0b;     /* Warna aksen */
}
```

### Mengubah Logo
Upload logo baru ke folder `uploads/logo/` dan update di database tabel `profil_sekolah`.

### Mengubah Foto Slider
Edit file `index.php` pada bagian carousel dan ganti URL gambar.

## 📝 Data Dummy

Database sudah terisi dengan data dummy:
- 1 Admin
- 1 Staff
- 10 Guru
- 4 Staff Administrasi
- 5 Berita
- 8 Galeri
- 8 Prestasi
- 8 Fasilitas
- 5 Kategori Berita

Foto menggunakan placeholder dari Unsplash.

## 🐛 Troubleshooting

### Error: Cannot connect to database
- Pastikan MySQL/MariaDB sudah running
- Periksa username dan password di `config/database.php`
- Pastikan database `school_profile` sudah dibuat

### Error: Undefined function mysqli_connect
- Aktifkan extension mysqli di `php.ini`
- Restart Apache/Nginx

### Upload file gagal
- Pastikan folder `uploads/` memiliki permission write (777)
- Periksa `php.ini`: `upload_max_filesize` dan `post_max_size`

### Gambar tidak muncul
- Periksa path SITE_URL di `config/config.php`
- Pastikan file gambar ada di folder uploads

## 📧 Support

Jika ada pertanyaan atau bug, silakan hubungi tim developer.

## 📄 License

Project ini dibuat untuk keperluan pendidikan dan dapat dimodifikasi sesuai kebutuhan.

---

**Developed with ❤️ by School Development Team**

🎓 **Selamat Menggunakan!**

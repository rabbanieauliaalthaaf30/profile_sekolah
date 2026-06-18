-- Database: school_profile
-- Updated: 2026-06-17
-- Removed: berita, kategori_berita, prestasi, staff, audit_log

CREATE DATABASE IF NOT EXISTS school_profile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_profile;

-- ====================================
-- Table: users (Admin & Staff Login)
-- ====================================
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    foto_profil VARCHAR(255) DEFAULT 'default-avatar.png',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: profil_sekolah
-- ====================================
CREATE TABLE profil_sekolah (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_sekolah VARCHAR(200) NOT NULL,
    npsn VARCHAR(20),
    sejarah TEXT,
    visi TEXT,
    misi TEXT,
    alamat_lengkap TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    logo VARCHAR(255) DEFAULT 'logo-sekolah.png',
    foto_gedung VARCHAR(255),
    kepala_sekolah_nama VARCHAR(100),
    kepala_sekolah_foto VARCHAR(255),
    tahun_berdiri YEAR,
    akreditasi VARCHAR(5),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    diedit_oleh INT(11) NULL DEFAULT NULL,
    CONSTRAINT fk_profil_diedit FOREIGN KEY (diedit_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: guru
-- ====================================
CREATE TABLE guru (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nip VARCHAR(30),
    nama_lengkap VARCHAR(100) NOT NULL,
    mata_pelajaran VARCHAR(100),
    pendidikan_terakhir VARCHAR(50),
    foto VARCHAR(255) DEFAULT 'default-teacher.png',
    email VARCHAR(100),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    urutan INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    dibuat_oleh INT(11) NULL DEFAULT NULL,
    diedit_oleh INT(11) NULL DEFAULT NULL,
    CONSTRAINT fk_guru_dibuat FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_guru_diedit FOREIGN KEY (diedit_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: galeri
-- ====================================
CREATE TABLE galeri (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255) NOT NULL,
    kategori ENUM('Akademik','Ekstrakurikuler','Upacara','Olahraga','Kompetisi') NOT NULL DEFAULT 'Akademik',
    tanggal_kegiatan DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dibuat_oleh INT(11) NULL DEFAULT NULL,
    diedit_oleh INT(11) NULL DEFAULT NULL,
    CONSTRAINT fk_galeri_dibuat FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_galeri_diedit FOREIGN KEY (diedit_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: fasilitas
-- ====================================
CREATE TABLE fasilitas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_fasilitas VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255),
    urutan INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dibuat_oleh INT(11) NULL DEFAULT NULL,
    diedit_oleh INT(11) NULL DEFAULT NULL,
    CONSTRAINT fk_fasilitas_dibuat FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_fasilitas_diedit FOREIGN KEY (diedit_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: kontak_masuk
-- ====================================
CREATE TABLE kontak_masuk (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_pengirim VARCHAR(100) NOT NULL,
    email_pengirim VARCHAR(100) NOT NULL,
    subjek VARCHAR(200),
    pesan TEXT NOT NULL,
    status ENUM('belum_dibaca', 'sudah_dibaca') DEFAULT 'belum_dibaca',
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- DUMMY DATA
-- ====================================

-- Insert Default Users (Password: admin123 dan staff123)
INSERT INTO users (username, password, nama_lengkap, email, role, status) VALUES
('admin', '$2y$10$h2P1KPvzK2cbvIkKF.uDouuKR1mGGyZ.IETEPmB6rgkI1Or6qnc16', 'Administrator', 'admin@sekolah.sch.id', 'admin', 'aktif'),
('staff1', '$2y$10$poYPhiHTf.da8wbw1omZWOiaxenXb.IdUb0LcyiPZoSuc9qlLJ9Fi', 'Dian Pratiwi', 'dian.pratiwi@sekolah.sch.id', 'staff', 'aktif');

-- Insert Profil Sekolah
INSERT INTO profil_sekolah (
    nama_sekolah, npsn, sejarah, visi, misi, alamat_lengkap, telepon, email, website,
    kepala_sekolah_nama, tahun_berdiri, akreditasi
) VALUES (
    'SMA Negeri 1 Harapan Bangsa',
    '20123456',
    'SMA Negeri 1 Harapan Bangsa didirikan pada tahun 1985 dengan visi mencerdaskan kehidupan bangsa.',
    'Mewujudkan peserta didik yang beriman, bertaqwa, berakhlak mulia, berprestasi, berbudaya lingkungan, dan berwawasan global.',
    '1. Menyelenggarakan pembelajaran yang aktif, inovatif, kreatif, efektif, dan menyenangkan.\n2. Menumbuhkan semangat keunggulan secara intensif kepada seluruh warga sekolah.\n3. Mendorong dan membantu siswa untuk mengenali potensi dirinya.\n4. Menumbuhkan penghayatan terhadap ajaran agama dan budaya bangsa.\n5. Menerapkan manajemen partisipatif dengan melibatkan seluruh warga sekolah.',
    'Jl. Pendidikan No. 123, Kota Harapan, Provinsi Contoh, 12345',
    '(021) 1234-5678',
    'info@sman1harapanbangsa.sch.id',
    'www.sman1harapanbangsa.sch.id',
    'Dr. Budi Santoso, M.Pd.',
    1985,
    'A'
);

-- Insert Dummy Guru
INSERT INTO guru (nip, nama_lengkap, mata_pelajaran, pendidikan_terakhir, email, urutan) VALUES
('196801051990031001', 'Drs. Ahmad Hidayat, M.Pd.', 'Matematika', 'S2 Pendidikan Matematika', 'ahmad.hidayat@sekolah.sch.id', 1),
('197205102000122001', 'Sri Wahyuni, S.Pd., M.Si.', 'Fisika', 'S2 Fisika', 'sri.wahyuni@sekolah.sch.id', 2),
('198003152005011002', 'Bambang Sutrisno, S.Pd.', 'Bahasa Indonesia', 'S1 Pendidikan Bahasa Indonesia', 'bambang.sutrisno@sekolah.sch.id', 3),
('198509202009122003', 'Rina Kusuma, S.Pd., M.Pd.', 'Bahasa Inggris', 'S2 Pendidikan Bahasa Inggris', 'rina.kusuma@sekolah.sch.id', 4),
('199001012015031001', 'Andi Prasetyo, S.Kom.', 'Informatika', 'S1 Ilmu Komputer', 'andi.prasetyo@sekolah.sch.id', 5),
('198807152010122002', 'Dewi Lestari, S.Pd.', 'Kimia', 'S1 Pendidikan Kimia', 'dewi.lestari@sekolah.sch.id', 6),
('197812252003121001', 'Hendra Gunawan, S.Pd.', 'Biologi', 'S1 Pendidikan Biologi', 'hendra.gunawan@sekolah.sch.id', 7),
('199203102017011001', 'Siti Nurjanah, S.Pd.', 'Sejarah', 'S1 Pendidikan Sejarah', 'siti.nurjanah@sekolah.sch.id', 8),
('198605252011012001', 'Ratna Sari, S.Pd.', 'Geografi', 'S1 Pendidikan Geografi', 'ratna.sari@sekolah.sch.id', 9),
('199505152019031002', 'Yoga Pratama, S.Pd.', 'Pendidikan Jasmani', 'S1 Pendidikan Olahraga', 'yoga.pratama@sekolah.sch.id', 10);

-- Insert Dummy Galeri
INSERT INTO galeri (judul, deskripsi, foto, kategori, tanggal_kegiatan) VALUES
('Upacara Bendera Pembukaan Tahun Ajaran Baru', 'Upacara bendera dalam rangka pembukaan tahun ajaran baru 2026/2027', 'galeri-1.jpg', 'Upacara', '2026-06-15'),
('Lomba Olimpiade Matematika', 'Persiapan siswa sebelum mengikuti olimpiade matematika tingkat provinsi', 'galeri-2.jpg', 'Kompetisi', '2026-06-10'),
('Workshop Guru', 'Workshop penulisan karya ilmiah untuk para guru', 'galeri-3.jpg', 'Workshop', '2026-06-08'),
('Pentas Seni HUT Sekolah', 'Penampilan drama musikal dalam acara HUT sekolah ke-41', 'galeri-4.jpg', 'Pentas Seni', '2026-06-05'),
('Kegiatan Ekstrakurikuler Pramuka', 'Latihan rutin ekstrakurikuler pramuka di lapangan sekolah', 'galeri-5.jpg', 'Ekstrakurikuler', '2026-05-28'),
('Praktikum Kimia', 'Siswa sedang melakukan praktikum di laboratorium kimia', 'galeri-6.jpg', 'Akademik', '2026-05-25');

-- Insert Dummy Fasilitas
INSERT INTO fasilitas (nama_fasilitas, deskripsi, urutan) VALUES
('Laboratorium Komputer', 'Lab komputer dengan 40 unit PC terkoneksi internet untuk pembelajaran TIK dan pemrograman', 1),
('Laboratorium IPA', 'Lab IPA lengkap untuk praktikum Fisika, Kimia, dan Biologi dengan peralatan modern', 2),
('Perpustakaan', 'Perpustakaan dengan koleksi 5000+ buku dan ruang baca yang nyaman', 3),
('Aula Serbaguna', 'Aula berkapasitas 500 orang untuk berbagai acara dan kegiatan sekolah', 4),
('Lapangan Olahraga', 'Lapangan basket, voli, dan futsal untuk kegiatan olahraga siswa', 5),
('Ruang Multimedia', 'Ruang multimedia dengan proyektor dan sound system untuk pembelajaran interaktif', 6),
('Kantin Sekolah', 'Kantin bersih dan sehat dengan berbagai pilihan makanan bergizi', 7),
('Masjid', 'Masjid sekolah untuk kegiatan ibadah dan kajian keagamaan', 8);

-- Note: Password untuk login
-- Username: admin | Password: admin123
-- Username: staff1 | Password: staff123

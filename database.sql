-- Database: school_profile
-- Created: 2026-06-15

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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: staff
-- ====================================
CREATE TABLE staff (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_lengkap VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    foto VARCHAR(255) DEFAULT 'default-staff.png',
    email VARCHAR(100),
    telepon VARCHAR(20),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: kategori_berita
-- ====================================
CREATE TABLE kategori_berita (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: berita
-- ====================================
CREATE TABLE berita (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    ringkasan TEXT,
    konten TEXT NOT NULL,
    kategori_id INT(11),
    penulis_id INT(11) NOT NULL,
    foto_utama VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    tanggal_publish DATETIME,
    views INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_berita(id) ON DELETE SET NULL,
    FOREIGN KEY (penulis_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: galeri
-- ====================================
CREATE TABLE galeri (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255) NOT NULL,
    kategori VARCHAR(100),
    tanggal_kegiatan DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
-- Table: prestasi
-- ====================================
CREATE TABLE prestasi (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_prestasi VARCHAR(255) NOT NULL,
    kategori ENUM('akademik', 'non-akademik') DEFAULT 'akademik',
    tingkat ENUM('sekolah', 'kota', 'provinsi', 'nasional', 'internasional') DEFAULT 'sekolah',
    tahun YEAR NOT NULL,
    deskripsi TEXT,
    foto_dokumentasi VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
-- Table: audit_log (Activity Tracking)
-- ====================================
CREATE TABLE audit_log (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    aksi VARCHAR(50) NOT NULL,
    tabel VARCHAR(50) NOT NULL,
    id_data INT(11),
    deskripsi TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
    'SMA Negeri 1 Harapan Bangsa didirikan pada tahun 1985 dengan visi mencerdaskan kehidupan bangsa. Sekolah ini telah menghasilkan ribuan alumni yang sukses di berbagai bidang dan telah menorehkan berbagai prestasi baik di tingkat daerah maupun nasional.',
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

-- Insert Kategori Berita
INSERT INTO kategori_berita (nama_kategori, slug, deskripsi) VALUES
('Pengumuman', 'pengumuman', 'Pengumuman resmi sekolah'),
('Kegiatan', 'kegiatan', 'Kegiatan dan event sekolah'),
('Prestasi', 'prestasi', 'Prestasi siswa dan sekolah'),
('Akademik', 'akademik', 'Informasi akademik'),
('Umum', 'umum', 'Berita umum sekolah');

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

-- Insert Dummy Staff
INSERT INTO staff (nama_lengkap, jabatan, email, telepon) VALUES
('Eko Susanto', 'Kepala Tata Usaha', 'eko.susanto@sekolah.sch.id', '081234567890'),
('Fitri Handayani', 'Staff Administrasi', 'fitri.handayani@sekolah.sch.id', '081234567891'),
('Muhammad Rizki', 'Staff Perpustakaan', 'muhammad.rizki@sekolah.sch.id', '081234567892'),
('Anisa Rahmawati', 'Staff Keuangan', 'anisa.rahmawati@sekolah.sch.id', '081234567893');

-- Insert Dummy Berita
INSERT INTO berita (judul, slug, ringkasan, konten, kategori_id, penulis_id, foto_utama, status, tanggal_publish, views) VALUES
(
    'Pembukaan Tahun Ajaran Baru 2026/2027',
    'pembukaan-tahun-ajaran-baru-2026-2027',
    'SMA Negeri 1 Harapan Bangsa resmi membuka tahun ajaran baru 2026/2027 dengan upacara bendera yang dihadiri seluruh siswa dan guru.',
    'SMA Negeri 1 Harapan Bangsa resmi membuka tahun ajaran baru 2026/2027 pada hari Senin, 15 Juni 2026. Acara pembukaan dimulai dengan upacara bendera yang dihadiri oleh seluruh siswa, guru, dan staff sekolah.\n\nDalam sambutannya, Kepala Sekolah Dr. Budi Santoso, M.Pd. menyampaikan harapan besar untuk tahun ajaran ini. "Kita harus terus berinovasi dalam pembelajaran dan menghasilkan lulusan yang berkualitas dan siap menghadapi tantangan global," ujarnya.\n\nTahun ajaran ini diikuti oleh 720 siswa yang terbagi dalam 24 rombongan belajar. Sekolah juga memperkenalkan beberapa program baru seperti kelas coding dan kelas entrepreneur untuk mengembangkan keterampilan siswa di era digital.',
    1,
    1,
    'berita-1.jpg',
    'published',
    '2026-06-15 08:00:00',
    156
),
(
    'Siswa SMAN 1 Raih Juara 1 Olimpiade Matematika Tingkat Provinsi',
    'siswa-sman-1-raih-juara-1-olimpiade-matematika-tingkat-provinsi',
    'Andi Setiawan, siswa kelas XI IPA 2, berhasil meraih juara 1 dalam Olimpiade Matematika tingkat provinsi yang diselenggarakan di Universitas Negeri.',
    'Prestasi membanggakan kembali ditorehkan oleh siswa SMA Negeri 1 Harapan Bangsa. Andi Setiawan, siswa kelas XI IPA 2, berhasil meraih juara 1 dalam Olimpiade Matematika tingkat provinsi yang diselenggarakan pada 10-12 Juni 2026 di Universitas Negeri.\n\nAndi harus bersaing dengan 150 peserta dari seluruh SMA di provinsi. Dengan persiapan matang dan bimbingan dari guru matematika, Drs. Ahmad Hidayat, M.Pd., Andi berhasil mengungguli peserta lainnya.\n\n"Saya sangat senang bisa mengharumkan nama sekolah. Ini berkat dukungan guru dan orang tua saya," ujar Andi. Kepala Sekolah memberikan apresiasi tinggi atas prestasi ini dan berharap dapat memotivasi siswa lain untuk terus berprestasi.',
    3,
    2,
    'berita-2.jpg',
    'published',
    '2026-06-13 10:30:00',
    243
),
(
    'Workshop Penulisan Karya Ilmiah untuk Guru',
    'workshop-penulisan-karya-ilmiah-untuk-guru',
    'Sekolah mengadakan workshop penulisan karya ilmiah bagi para guru untuk meningkatkan kompetensi dan profesionalisme.',
    'SMA Negeri 1 Harapan Bangsa mengadakan workshop penulisan karya ilmiah yang diikuti oleh 35 guru pada hari Sabtu, 8 Juni 2026. Workshop ini menghadirkan narasumber Prof. Dr. Siti Maryam dari Universitas Pendidikan Indonesia.\n\nKegiatan ini bertujuan untuk meningkatkan kompetensi guru dalam menulis karya ilmiah dan penelitian tindakan kelas (PTK). Materi yang disampaikan meliputi metodologi penelitian, teknik pengumpulan data, hingga cara publikasi jurnal ilmiah.\n\nKepala Sekolah berharap para guru dapat menghasilkan karya ilmiah berkualitas yang dapat dipublikasikan di jurnal nasional maupun internasional. "Guru yang menulis adalah guru yang terus belajar dan berkembang," tutupnya.',
    4,
    1,
    'berita-3.jpg',
    'published',
    '2026-06-08 14:00:00',
    98
),
(
    'Pentas Seni dan Budaya Memeriahkan HUT Sekolah ke-41',
    'pentas-seni-dan-budaya-memeriahkan-hut-sekolah-ke-41',
    'Perayaan HUT sekolah ke-41 dimeriahkan dengan pentas seni yang menampilkan berbagai talenta siswa dalam bidang musik, tari, dan teater.',
    'Dalam rangka memperingati HUT sekolah ke-41, SMA Negeri 1 Harapan Bangsa menggelar pentas seni dan budaya pada Kamis, 5 Juni 2026. Acara ini menampilkan berbagai penampilan dari siswa mulai dari paduan suara, tari tradisional, band, hingga drama teater.\n\nPenampilan yang paling menarik perhatian adalah drama musikal yang menceritakan perjalanan sejarah sekolah dari awal berdiri hingga sekarang. Drama ini dibawakan oleh siswa kelas XII dan mendapat standing ovation dari penonton.\n\nAcara juga dimeriahkan dengan bazar makanan dan pameran karya siswa. Kepala Sekolah mengapresiasi kreativitas siswa dan berharap acara ini menjadi ajang untuk mengembangkan bakat dan minat siswa di bidang seni dan budaya.',
    2,
    2,
    'berita-4.jpg',
    'published',
    '2026-06-05 16:00:00',
    321
),
(
    'Kerjasama dengan Universitas Terkemuka untuk Program Dual Enrollment',
    'kerjasama-dengan-universitas-terkemuka-untuk-program-dual-enrollment',
    'Sekolah menjalin kerjasama dengan universitas terkemuka untuk program dual enrollment yang memungkinkan siswa mengambil mata kuliah sejak SMA.',
    'SMA Negeri 1 Harapan Bangsa menandatangani MoU dengan Universitas Indonesia pada Selasa, 3 Juni 2026 untuk program dual enrollment. Program ini memungkinkan siswa kelas XII yang berprestasi untuk mengambil mata kuliah di universitas sambil menyelesaikan pendidikan SMA.\n\nProgram dual enrollment ini memberikan kesempatan kepada siswa untuk mendapatkan kredit mata kuliah yang dapat diakui ketika mereka resmi menjadi mahasiswa. Ini akan mempercepat masa studi dan memberikan pengalaman belajar di perguruan tinggi lebih awal.\n\nDr. Budi Santoso menyampaikan bahwa program ini adalah bentuk komitmen sekolah untuk memberikan pendidikan terbaik. "Kami ingin siswa kami siap bersaing di level pendidikan tinggi dan dunia kerja," ujarnya.\n\nPendaftaran program ini akan dibuka pada semester depan dengan syarat memiliki nilai akademik minimal 85 dan rekomendasi dari guru.',
    1,
    1,
    'berita-5.jpg',
    'published',
    '2026-06-03 09:00:00',
    187
);

-- Insert Dummy Galeri
INSERT INTO galeri (judul, deskripsi, foto, kategori, tanggal_kegiatan) VALUES
('Upacara Bendera Pembukaan Tahun Ajaran Baru', 'Upacara bendera dalam rangka pembukaan tahun ajaran baru 2026/2027', 'galeri-1.jpg', 'Upacara', '2026-06-15'),
('Lomba Olimpiade Matematika', 'Persiapan siswa sebelum mengikuti olimpiade matematika tingkat provinsi', 'galeri-2.jpg', 'Kompetisi', '2026-06-10'),
('Workshop Guru', 'Workshop penulisan karya ilmiah untuk para guru', 'galeri-3.jpg', 'Workshop', '2026-06-08'),
('Pentas Seni HUT Sekolah', 'Penampilan drama musikal dalam acara HUT sekolah ke-41', 'galeri-4.jpg', 'Pentas Seni', '2026-06-05'),
('Penandatanganan MoU dengan Universitas', 'Penandatanganan kerjasama dengan Universitas Indonesia', 'galeri-5.jpg', 'Kerjasama', '2026-06-03'),
('Kegiatan Ekstrakurikuler Pramuka', 'Latihan rutin ekstrakurikuler pramuka di lapangan sekolah', 'galeri-6.jpg', 'Ekstrakurikuler', '2026-05-28'),
('Praktikum Kimia', 'Siswa sedang melakukan praktikum di laboratorium kimia', 'galeri-7.jpg', 'Akademik', '2026-05-25'),
('Turnamen Futsal Antar Kelas', 'Final turnamen futsal antar kelas yang seru dan meriah', 'galeri-8.jpg', 'Olahraga', '2026-05-20');

-- Insert Dummy Prestasi
INSERT INTO prestasi (nama_prestasi, kategori, tingkat, tahun, deskripsi) VALUES
('Juara 1 Olimpiade Matematika', 'akademik', 'provinsi', 2026, 'Andi Setiawan meraih juara 1 olimpiade matematika tingkat provinsi'),
('Juara 2 Lomba Karya Tulis Ilmiah', 'akademik', 'nasional', 2026, 'Tim KTI sekolah meraih juara 2 di tingkat nasional'),
('Juara 1 Lomba Debat Bahasa Inggris', 'akademik', 'kota', 2026, 'Tim debat bahasa Inggris menjadi juara 1 tingkat kota'),
('Juara 1 Basket Putri POPDA', 'non-akademik', 'provinsi', 2026, 'Tim basket putri juara 1 POPDA tingkat provinsi'),
('Juara 3 Lomba Vocal Group', 'non-akademik', 'kota', 2026, 'Kelompok paduan suara meraih juara 3 tingkat kota'),
('Juara 1 Lomba Film Pendek', 'non-akademik', 'provinsi', 2025, 'Karya film pendek siswa menjadi juara 1 di festival film pelajar'),
('Juara 2 Olimpiade Fisika', 'akademik', 'provinsi', 2025, 'Meraih medali perak olimpiade fisika tingkat provinsi'),
('Juara 1 Paskibra Terbaik', 'non-akademik', 'kota', 2025, 'Tim paskibra menjadi yang terbaik dalam upacara kemerdekaan');

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

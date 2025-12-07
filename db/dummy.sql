-- ============================================
-- DUMMY DATA untuk SPK Customer Service Evaluation
-- Profile Matching Method
-- Produk: Herbal/Suplemen Kesehatan
-- ============================================

-- ============================================
-- 1. DATA PENGGUNA (Users)
-- ============================================
INSERT INTO `pengguna` (`nik`, `nama_pengguna`, `email`, `password`, `level`, `id_alasan`) VALUES
-- Admin
('ADM001', 'Administrator System', 'admin@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
('ADM002', 'Admin HR', 'admin.hr@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),

-- Junior Manager
('JM001', 'Budi Santoso', 'budi.santoso@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'junior_manager', NULL),
('JM002', 'Siti Nurhaliza', 'siti.nurhaliza@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'junior_manager', NULL),
('JM003', 'Ahmad Fauzi', 'ahmad.fauzi@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'junior_manager', NULL),

-- Supervisor
('SPV001', 'Dewi Lestari', 'dewi.lestari@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),
('SPV002', 'Rudi Hermawan', 'rudi.hermawan@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),
('SPV003', 'Maya Kusuma', 'maya.kusuma@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),
('SPV004', 'Hendra Wijaya', 'hendra.wijaya@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),
('SPV005', 'Rina Marlina', 'rina.marlina@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),
('SPV006', 'Doni Setiawan', 'doni.setiawan@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL),

-- Leader
('LDR001', 'Agus Setiawan', 'agus.setiawan@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR002', 'Fitri Handayani', 'fitri.handayani@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR003', 'Bambang Prasetyo', 'bambang.prasetyo@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR004', 'Diana Putri', 'diana.putri@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR005', 'Eko Prasetyo', 'eko.prasetyo@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR006', 'Yuli Rahmawati', 'yuli.rahmawati@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR007', 'Hendro Gunawan', 'hendro.gunawan@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL),
('LDR008', 'Ratna Sari', 'ratna.sari@herbalspk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leader', NULL);

-- Password untuk semua user: 'password'

-- ============================================
-- 2. DATA PRODUK HERBAL (Products)
-- ============================================
INSERT INTO `produk` (`sku_produk`, `nama_produk`, `deskripsi`, `gambar`) VALUES
('ETLW-001', 'Etawalin', 'Susu kambing etawa untuk stamina dan kesehatan tulang', 'etawalin.jpg'),
('ETWK-002', 'Etawaku', 'Susu kambing etawa platinum dengan kandungan kalsium tinggi', 'etawaku.jpg'),
('FRMG-003', 'Freshmag', 'Suplemen herbal untuk kesehatan pencernaan dan detoksifikasi', 'freshmag.jpg'),
('ZYIM-004', 'Zyimuno', 'Suplemen peningkat daya tahan tubuh dan imunitas', 'zyimuno.jpg'),
('BINS-005', 'Bio Insuleaf', 'Herbal untuk membantu mengontrol gula darah', 'bioinsuleaf.jpg'),
('NTFL-006', 'Nurtiflakes', 'Sereal herbal bergizi tinggi untuk sarapan sehat', 'nurtiflakes.jpg');

-- ============================================
-- 3. DATA KANAL (Channels)
-- ============================================
INSERT INTO `kanal` (`nama_kanal`) VALUES
('Phone Call'),
('WhatsApp Business'),
('Email'),
('Live Chat Website'),
('Instagram DM'),
('Facebook Messenger'),
('Tokopedia'),
('Shopee'),
('Lazada'),
('TikTok Shop');

-- ============================================
-- 4. DATA TIM (Teams)
-- ============================================
INSERT INTO `tim` (`id_leader`, `id_supervisor`, `nama_tim`) VALUES
-- Tim untuk produk Etawalin & Etawaku
(12, 7, 'Team Susu Etawa Alpha'),
(13, 7, 'Team Susu Etawa Beta'),
-- Tim untuk produk Freshmag
(14, 8, 'Team Freshmag'),
-- Tim untuk produk Zyimuno
(15, 9, 'Team Zyimuno'),
-- Tim untuk produk Bio Insuleaf
(16, 10, 'Team Bio Insuleaf'),
-- Tim untuk produk Nurtiflakes
(17, 11, 'Team Nurtiflakes Alpha'),
(18, 11, 'Team Nurtiflakes Beta'),
-- Tim untuk multi-produk
(19, 7, 'Team Multi Product');

-- ============================================
-- 5. DATA SUPERVISOR SCOPE
-- ============================================
INSERT INTO `supervisor_scope` (`id_supervisor`, `id_kanal`, `id_produk`) VALUES
-- Supervisor 1 (Dewi Lestari) - Handle Etawalin via Phone, WA, Email
(7, 1, 1), (7, 2, 1), (7, 3, 1),
-- Supervisor 2 (Rudi Hermawan) - Handle Etawaku via Multiple Channels
(8, 1, 2), (8, 2, 2), (8, 4, 2), (8, 5, 2), (8, 7, 2),
-- Supervisor 3 (Maya Kusuma) - Handle Freshmag
(9, 2, 3), (9, 4, 3), (9, 5, 3), (9, 8, 3),
-- Supervisor 4 (Hendra Wijaya) - Handle Zyimuno
(10, 1, 4), (10, 2, 4), (10, 3, 4), (10, 7, 4), (10, 8, 4),
-- Supervisor 5 (Rina Marlina) - Handle Bio Insuleaf
(11, 2, 5), (11, 4, 5), (11, 5, 5), (11, 9, 5),
-- Supervisor 6 (Doni Setiawan) - Handle Nurtiflakes
(12, 1, 6), (12, 2, 6), (12, 7, 6), (12, 8, 6), (12, 9, 6), (12, 10, 6);

-- ============================================
-- 6. DATA CUSTOMER SERVICE (CS)
-- ============================================
INSERT INTO `customer_service` (`id_produk`, `id_kanal`, `id_tim`, `nik`, `nama_cs`) VALUES
-- Team Susu Etawa Alpha (Etawalin)
(1, 1, 1, 'CS001', 'Ani Wijaya'),
(1, 2, 1, 'CS002', 'Budi Kurniawan'),
(1, 3, 1, 'CS003', 'Citra Dewi'),
(1, 2, 1, 'CS004', 'Dedi Supriadi'),
(1, 1, 1, 'CS005', 'Eka Putri'),

-- Team Susu Etawa Beta (Etawalin)
(1, 2, 2, 'CS006', 'Fajar Ramadhan'),
(1, 1, 2, 'CS007', 'Gita Savitri'),
(1, 2, 2, 'CS008', 'Hadi Pranoto'),
(1, 3, 2, 'CS009', 'Indah Permata'),

-- Team Susu Etawa (Etawaku)
(2, 1, 2, 'CS010', 'Joko Widodo'),
(2, 2, 2, 'CS011', 'Kartika Sari'),
(2, 4, 2, 'CS012', 'Lukman Hakim'),
(2, 5, 2, 'CS013', 'Mega Wati'),
(2, 7, 2, 'CS014', 'Nanda Pratama'),
(2, 2, 2, 'CS015', 'Oki Setiana'),

-- Team Freshmag
(3, 2, 3, 'CS016', 'Putri Andini'),
(3, 4, 3, 'CS017', 'Qori Sandioriva'),
(3, 5, 3, 'CS018', 'Rizky Febian'),
(3, 8, 3, 'CS019', 'Sinta Jojo'),
(3, 2, 3, 'CS020', 'Tomi Kurnia'),

-- Team Zyimuno
(4, 1, 4, 'CS021', 'Umi Kalsum'),
(4, 2, 4, 'CS022', 'Vino Bastian'),
(4, 3, 4, 'CS023', 'Winda Viska'),
(4, 7, 4, 'CS024', 'Xavier Nugroho'),
(4, 8, 4, 'CS025', 'Yuni Shara'),
(4, 2, 4, 'CS026', 'Zainal Abidin'),

-- Team Bio Insuleaf
(5, 2, 5, 'CS027', 'Aida Saskia'),
(5, 4, 5, 'CS028', 'Baim Wong'),
(5, 5, 5, 'CS029', 'Cinta Laura'),
(5, 9, 5, 'CS030', 'Dimas Anggara'),
(5, 2, 5, 'CS031', 'Elvira Devinamira'),

-- Team Nurtiflakes Alpha
(6, 1, 6, 'CS032', 'Febri Hariyadi'),
(6, 2, 6, 'CS033', 'Gading Marten'),
(6, 7, 6, 'CS034', 'Herlin Kenza'),
(6, 8, 6, 'CS035', 'Irfan Hakim'),
(6, 2, 6, 'CS036', 'Jessica Iskandar'),

-- Team Nurtiflakes Beta
(6, 9, 7, 'CS037', 'Kevin Julio'),
(6, 10, 7, 'CS038', 'Luna Maya'),
(6, 2, 7, 'CS039', 'Marsha Aruan'),
(6, 8, 7, 'CS040', 'Naysilla Mirdad'),

-- Team Multi Product
(1, 2, 8, 'CS041', 'Olla Ramlan'),
(2, 2, 8, 'CS042', 'Paula Verhoeven'),
(3, 2, 8, 'CS043', 'Raffi Ahmad'),
(4, 2, 8, 'CS044', 'Syahrini'),
(5, 2, 8, 'CS045', 'Tara Basro');

-- ============================================
-- 7. DATA KRITERIA (Evaluation Criteria)
-- ============================================
INSERT INTO `kriteria` (`kode_kriteria`, `nama_kriteria`, `bobot`, `jenis_kriteria`, `deskripsi`) VALUES
('K01', 'Product Knowledge', 30.00, 'core_factor', 'Pengetahuan tentang produk herbal dan manfaatnya'),
('K02', 'Communication Skill', 25.00, 'core_factor', 'Kemampuan komunikasi dengan pelanggan'),
('K03', 'Response Time', 20.00, 'core_factor', 'Kecepatan respon terhadap pelanggan'),
('K04', 'Problem Solving', 15.00, 'secondary_factor', 'Kemampuan menyelesaikan masalah pelanggan'),
('K05', 'Customer Satisfaction', 10.00, 'secondary_factor', 'Tingkat kepuasan pelanggan');

-- ============================================
-- 8. DATA SUB KRITERIA
-- ============================================
INSERT INTO `sub_kriteria` (`id_kriteria`, `nama_sub_kriteria`, `bobot_sub`, `target`, `keterangan`) VALUES
-- Sub Kriteria untuk Product Knowledge (K01)
(1, 'Pengetahuan Komposisi Produk', 35.00, 85.00, 'Memahami komposisi dan bahan herbal'),
(1, 'Pengetahuan Manfaat Produk', 35.00, 85.00, 'Memahami manfaat kesehatan produk'),
(1, 'Pengetahuan Cara Konsumsi', 30.00, 80.00, 'Memahami cara pemakaian yang benar'),

-- Sub Kriteria untuk Communication Skill (K02)
(2, 'Kejelasan Komunikasi', 40.00, 85.00, 'Menyampaikan informasi dengan jelas'),
(2, 'Kesopanan', 30.00, 90.00, 'Sikap sopan dan ramah'),
(2, 'Empati', 30.00, 80.00, 'Kemampuan berempati dengan pelanggan'),

-- Sub Kriteria untuk Response Time (K03)
(3, 'First Response Time', 50.00, 5.00, 'Waktu respon pertama (menit)'),
(3, 'Average Response Time', 50.00, 10.00, 'Rata-rata waktu respon (menit)'),

-- Sub Kriteria untuk Problem Solving (K04)
(4, 'Identifikasi Masalah', 40.00, 80.00, 'Kemampuan mengidentifikasi masalah'),
(4, 'Solusi yang Diberikan', 60.00, 85.00, 'Kualitas solusi yang ditawarkan'),

-- Sub Kriteria untuk Customer Satisfaction (K05)
(5, 'Rating Kepuasan', 60.00, 4.50, 'Rating kepuasan pelanggan (skala 1-5)'),
(5, 'Customer Retention', 40.00, 75.00, 'Persentase pelanggan yang kembali');

-- ============================================
-- 9. DATA RANGE (Value Ranges)
-- ============================================

-- Range untuk Pengetahuan Komposisi Produk (Sub Kriteria 1)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(1, 100, 90, 5.00, 'Sangat Baik'),
(1, 89, 80, 4.50, 'Baik'),
(1, 79, 70, 4.00, 'Cukup'),
(1, 69, 60, 3.00, 'Kurang'),
(1, 59, 0, 2.00, 'Sangat Kurang');

-- Range untuk Pengetahuan Manfaat Produk (Sub Kriteria 2)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(2, 100, 90, 5.00, 'Sangat Baik'),
(2, 89, 80, 4.50, 'Baik'),
(2, 79, 70, 4.00, 'Cukup'),
(2, 69, 60, 3.00, 'Kurang'),
(2, 59, 0, 2.00, 'Sangat Kurang');

-- Range untuk Pengetahuan Cara Konsumsi (Sub Kriteria 3)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(3, 100, 85, 5.00, 'Sangat Baik'),
(3, 84, 75, 4.50, 'Baik'),
(3, 74, 65, 4.00, 'Cukup'),
(3, 64, 55, 3.00, 'Kurang'),
(3, 54, 0, 2.00, 'Sangat Kurang');

-- Range untuk Kejelasan Komunikasi (Sub Kriteria 4)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(4, 100, 90, 5.00, 'Sangat Baik'),
(4, 89, 80, 4.50, 'Baik'),
(4, 79, 70, 4.00, 'Cukup'),
(4, 69, 60, 3.00, 'Kurang'),
(4, 59, 0, 2.00, 'Sangat Kurang');

-- Range untuk Kesopanan (Sub Kriteria 5)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(5, 100, 92, 5.00, 'Sangat Baik'),
(5, 91, 85, 4.50, 'Baik'),
(5, 84, 75, 4.00, 'Cukup'),
(5, 74, 65, 3.00, 'Kurang'),
(5, 64, 0, 2.00, 'Sangat Kurang');

-- Range untuk Empati (Sub Kriteria 6)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(6, 100, 85, 5.00, 'Sangat Baik'),
(6, 84, 75, 4.50, 'Baik'),
(6, 74, 65, 4.00, 'Cukup'),
(6, 64, 55, 3.00, 'Kurang'),
(6, 54, 0, 2.00, 'Sangat Kurang');

-- Range untuk First Response Time (Sub Kriteria 7) - dalam menit, semakin kecil semakin baik
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(7, 3, 0, 5.00, 'Sangat Cepat'),
(7, 5, 3.01, 4.50, 'Cepat'),
(7, 8, 5.01, 4.00, 'Cukup'),
(7, 12, 8.01, 3.00, 'Lambat'),
(7, 999, 12.01, 2.00, 'Sangat Lambat');

-- Range untuk Average Response Time (Sub Kriteria 8) - dalam menit
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(8, 8, 0, 5.00, 'Sangat Cepat'),
(8, 12, 8.01, 4.50, 'Cepat'),
(8, 18, 12.01, 4.00, 'Cukup'),
(8, 25, 18.01, 3.00, 'Lambat'),
(8, 999, 25.01, 2.00, 'Sangat Lambat');

-- Range untuk Identifikasi Masalah (Sub Kriteria 9)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(9, 100, 85, 5.00, 'Sangat Baik'),
(9, 84, 75, 4.50, 'Baik'),
(9, 74, 65, 4.00, 'Cukup'),
(9, 64, 55, 3.00, 'Kurang'),
(9, 54, 0, 2.00, 'Sangat Kurang');

-- Range untuk Solusi yang Diberikan (Sub Kriteria 10)
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(10, 100, 90, 5.00, 'Sangat Baik'),
(10, 89, 80, 4.50, 'Baik'),
(10, 79, 70, 4.00, 'Cukup'),
(10, 69, 60, 3.00, 'Kurang'),
(10, 59, 0, 2.00, 'Sangat Kurang');

-- Range untuk Rating Kepuasan (Sub Kriteria 11) - skala 1-5
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(11, 5.00, 4.50, 5.00, 'Sangat Puas'),
(11, 4.49, 4.00, 4.50, 'Puas'),
(11, 3.99, 3.50, 4.00, 'Cukup Puas'),
(11, 3.49, 3.00, 3.00, 'Kurang Puas'),
(11, 2.99, 1.00, 2.00, 'Tidak Puas');

-- Range untuk Customer Retention (Sub Kriteria 12) - persentase
INSERT INTO `range` (`id_sub_kriteria`, `batas_atas`, `batas_bawah`, `nilai_range`, `keterangan`) VALUES
(12, 100, 80, 5.00, 'Sangat Baik'),
(12, 79, 70, 4.50, 'Baik'),
(12, 69, 60, 4.00, 'Cukup'),
(12, 59, 50, 3.00, 'Kurang'),
(12, 49, 0, 2.00, 'Sangat Kurang');

-- ============================================
-- 10. DATA NILAI (Evaluation Scores) - Sample untuk beberapa CS
-- ============================================
INSERT INTO `nilai` (`id_cs`, `id_sub_kriteria`, `nilai`) VALUES
-- Nilai untuk CS001 (Ani Wijaya)
(1, 1, 88.00), -- Pengetahuan Komposisi
(1, 2, 92.00), -- Pengetahuan Manfaat
(1, 3, 85.00), -- Cara Konsumsi
(1, 4, 90.00), -- Kejelasan Komunikasi
(1, 5, 95.00), -- Kesopanan
(1, 6, 87.00), -- Empati
(1, 7, 3.50), -- First Response Time
(1, 8, 8.00), -- Average Response Time
(1, 9, 83.00), -- Identifikasi Masalah
(1, 10, 88.00), -- Solusi
(1, 11, 4.60), -- Rating Kepuasan
(1, 12, 82.00), -- Customer Retention

-- Nilai untuk CS002 (Budi Kurniawan)
(2, 1, 82.00),
(2, 2, 85.00),
(2, 3, 78.00),
(2, 4, 88.00),
(2, 5, 92.00),
(2, 6, 80.00),
(2, 7, 4.20),
(2, 8, 10.50),
(2, 9, 79.00),
(2, 10, 84.00),
(2, 11, 4.40),
(2, 12, 76.00),

-- Nilai untuk CS003 (Citra Dewi)
(3, 1, 91.00),
(3, 2, 89.00),
(3, 3, 87.00),
(3, 4, 93.00),
(3, 5, 96.00),
(3, 6, 91.00),
(3, 7, 2.80),
(3, 8, 7.50),
(3, 9, 88.00),
(3, 10, 91.00),
(3, 11, 4.75),
(3, 12, 85.00),

-- Nilai untuk CS004 (Dedi Supriadi)
(4, 1, 76.00),
(4, 2, 80.00),
(4, 3, 72.00),
(4, 4, 82.00),
(4, 5, 88.00),
(4, 6, 75.00),
(4, 7, 6.50),
(4, 8, 14.00),
(4, 9, 74.00),
(4, 10, 78.00),
(4, 11, 4.10),
(4, 12, 68.00),

-- Nilai untuk CS005 (Eka Putri)
(5, 1, 85.00),
(5, 2, 87.00),
(5, 3, 82.00),
(5, 4, 89.00),
(5, 5, 93.00),
(5, 6, 84.00),
(5, 7, 4.00),
(5, 8, 9.80),
(5, 9, 81.00),
(5, 10, 86.00),
(5, 11, 4.50),
(5, 12, 78.00),

-- Nilai untuk CS006 (Fajar Ramadhan)
(6, 1, 90.00),
(6, 2, 93.00),
(6, 3, 88.00),
(6, 4, 91.00),
(6, 5, 94.00),
(6, 6, 89.00),
(6, 7, 3.20),
(6, 8, 8.30),
(6, 9, 86.00),
(6, 10, 90.00),
(6, 11, 4.65),
(6, 12, 83.00),

-- Nilai untuk CS007 (Gita Savitri)
(7, 1, 88.00),
(7, 2, 91.00),
(7, 3, 85.00),
(7, 4, 87.00),
(7, 5, 92.00),
(7, 6, 86.00),
(7, 7, 3.80),
(7, 8, 9.20),
(7, 9, 82.00),
(7, 10, 87.00),
(7, 11, 4.55),
(7, 12, 80.00),

-- Nilai untuk CS008 (Hadi Pranoto)
(8, 1, 79.00),
(8, 2, 82.00),
(8, 3, 75.00),
(8, 4, 84.00),
(8, 5, 89.00),
(8, 6, 78.00),
(8, 7, 5.50),
(8, 8, 12.80),
(8, 9, 76.00),
(8, 10, 81.00),
(8, 11, 4.25),
(8, 12, 71.00),

-- Nilai untuk CS009 (Indah Permata)
(9, 1, 92.00),
(9, 2, 94.00),
(9, 3, 90.00),
(9, 4, 95.00),
(9, 5, 97.00),
(9, 6, 92.00),
(9, 7, 2.50),
(9, 8, 7.00),
(9, 9, 90.00),
(9, 10, 93.00),
(9, 11, 4.80),
(9, 12, 87.00),

-- Nilai untuk CS010 (Joko Widodo)
(10, 1, 84.00),
(10, 2, 86.00),
(10, 3, 81.00),
(10, 4, 88.00),
(10, 5, 91.00),
(10, 6, 83.00),
(10, 7, 4.50),
(10, 8, 10.20),
(10, 9, 80.00),
(10, 10, 85.00),
(10, 11, 4.45),
(10, 12, 75.00),

-- Nilai untuk CS011-CS020 (data lebih bervariasi)
(11, 1, 87.00), (11, 2, 89.00), (11, 3, 84.00), (11, 4, 90.00), (11, 5, 93.00), 
(11, 6, 85.00), (11, 7, 3.70), (11, 8, 8.90), (11, 9, 83.00), (11, 10, 88.00), 
(11, 11, 4.52), (11, 12, 79.00),

(12, 1, 81.00), (12, 2, 83.00), (12, 3, 77.00), (12, 4, 85.00), (12, 5, 90.00), 
(12, 6, 80.00), (12, 7, 5.20), (12, 8, 11.50), (12, 9, 78.00), (12, 10, 82.00), 
(12, 11, 4.30), (12, 12, 73.00),

(13, 1, 93.00), (13, 2, 95.00), (13, 3, 91.00), (13, 4, 94.00), (13, 5, 96.00), 
(13, 6, 90.00), (13, 7, 2.30), (13, 8, 6.80), (13, 9, 89.00), (13, 10, 92.00), 
(13, 11, 4.78), (13, 12, 86.00),

(14, 1, 78.00), (14, 2, 81.00), (14, 3, 74.00), (14, 4, 83.00), (14, 5, 87.00), 
(14, 6, 76.00), (14, 7, 6.80), (14, 8, 15.20), (14, 9, 75.00), (14, 10, 79.00), 
(14, 11, 4.05), (14, 12, 67.00),

(15, 1, 86.00), (15, 2, 88.00), (15, 3, 83.00), (15, 4, 89.00), (15, 5, 92.00), 
(15, 6, 84.00), (15, 7, 4.10), (15, 8, 9.50), (15, 9, 81.00), (15, 10, 86.00), 
(15, 11, 4.48), (15, 12, 77.00),

(16, 1, 89.00), (16, 2, 91.00), (16, 3, 86.00), (16, 4, 92.00), (16, 5, 94.00), 
(16, 6, 87.00), (16, 7, 3.40), (16, 8, 8.20), (16, 9, 84.00), (16, 10, 89.00), 
(16, 11, 4.62), (16, 12, 81.00),

(17, 1, 82.00), (17, 2, 84.00), (17, 3, 78.00), (17, 4, 86.00), (17, 5, 90.00), 
(17, 6, 81.00), (17, 7, 5.00), (17, 8, 11.00), (17, 9, 79.00), (17, 10, 83.00), 
(17, 11, 4.35), (17, 12, 74.00),

(18, 1, 91.00), (18, 2, 93.00), (18, 3, 89.00), (18, 4, 93.00), (18, 5, 95.00), 
(18, 6, 90.00), (18, 7, 2.90), (18, 8, 7.60), (18, 9, 87.00), (18, 10, 91.00), 
(18, 11, 4.70), (18, 12, 84.00),

(19, 1, 85.00), (19, 2, 87.00), (19, 3, 82.00), (19, 4, 88.00), (19, 5, 91.00), 
(19, 6, 83.00), (19, 7, 4.30), (19, 8, 9.90), (19, 9, 80.00), (19, 10, 85.00), 
(19, 11, 4.42), (19, 12, 76.00),

(20, 1, 77.00), (20, 2, 80.00), (20, 3, 73.00), (20, 4, 82.00), (20, 5, 86.00), 
(20, 6, 75.00), (20, 7, 7.20), (20, 8, 16.00), (20, 9, 74.00), (20, 10, 78.00), 
(20, 11, 4.00), (20, 12, 66.00);

-- ============================================
-- 11. DATA KONVERSI (Sample Conversion Data)
-- ============================================
-- Konversi untuk CS001 (Ani Wijaya)
INSERT INTO `konversi` (`id_cs`, `id_sub_kriteria`, `id_range`, `nilai_asli`, `nilai_konversi`) VALUES
(1, 1, 2, 88.00, 4.50),
(1, 2, 1, 92.00, 5.00),
(1, 3, 1, 85.00, 5.00),
(1, 4, 1, 90.00, 5.00),
(1, 5, 6, 95.00, 5.00),
(1, 6, 6, 87.00, 5.00),
(1, 7, 32, 3.50, 4.50),
(1, 8, 36, 8.00, 5.00),
(1, 9, 42, 83.00, 4.50),
(1, 10, 46, 88.00, 4.50),
(1, 11, 51, 4.60, 5.00),
(1, 12, 56, 82.00, 5.00);

-- Konversi untuk CS003 (Citra Dewi) - Top Performer
INSERT INTO `konversi` (`id_cs`, `id_sub_kriteria`, `id_range`, `nilai_asli`, `nilai_konversi`) VALUES
(3, 1, 1, 91.00, 5.00),
(3, 2, 2, 89.00, 4.50),
(3, 3, 1, 87.00, 5.00),
(3, 4, 1, 93.00, 5.00),
(3, 5, 6, 96.00, 5.00),
(3, 6, 6, 91.00, 5.00),
(3, 7, 31, 2.80, 5.00),
(3, 8, 36, 7.50, 5.00),
(3, 9, 41, 88.00, 5.00),
(3, 10, 46, 91.00, 5.00),
(3, 11, 51, 4.75, 5.00),
(3, 12, 56, 85.00, 5.00);

-- Konversi untuk CS009 (Indah Permata) - Top Performer
INSERT INTO `konversi` (`id_cs`, `id_sub_kriteria`, `id_range`, `nilai_asli`, `nilai_konversi`) VALUES
(9, 1, 1, 92.00, 5.00),
(9, 2, 1, 94.00, 5.00),
(9, 3, 1, 90.00, 5.00),
(9, 4, 1, 95.00, 5.00),
(9, 5, 6, 97.00, 5.00),
(9, 6, 6, 92.00, 5.00),
(9, 7, 31, 2.50, 5.00),
(9, 8, 36, 7.00, 5.00),
(9, 9, 41, 90.00, 5.00),
(9, 10, 46, 93.00, 5.00),
(9, 11, 51, 4.80, 5.00),
(9, 12, 56, 87.00, 5.00);

-- ============================================
-- 12. DATA RANKING (Sample Ranking Results)
-- ============================================
INSERT INTO `ranking` (`id_produk`, `id_cs`, `nilai_akhir`, `peringkat`, `periode`, `status`) VALUES
-- Ranking untuk Etawalin (Produk 1) - Periode Oktober 2024
(1, 'CS009', 94.75, 1, '2024-10', 'published'),
(1, 'CS003', 92.80, 2, '2024-10', 'published'),
(1, 'CS006', 89.65, 3, '2024-10', 'published'),
(1, 'CS001', 88.50, 4, '2024-10', 'published'),
(1, 'CS007', 86.30, 5, '2024-10', 'published'),
(1, 'CS005', 84.75, 6, '2024-10', 'published'),
(1, 'CS002', 82.40, 7, '2024-10', 'published'),
(1, 'CS008', 79.60, 8, '2024-10', 'published'),
(1, 'CS004', 76.25, 9, '2024-10', 'published'),

-- Ranking untuk Etawaku (Produk 2) - Periode Oktober 2024
(2, 'CS013', 93.50, 1, '2024-10', 'published'),
(2, 'CS011', 87.80, 2, '2024-10', 'published'),
(2, 'CS015', 85.40, 3, '2024-10', 'published'),
(2, 'CS010', 83.90, 4, '2024-10', 'published'),
(2, 'CS012', 80.50, 5, '2024-10', 'published'),
(2, 'CS014', 77.30, 6, '2024-10', 'published'),

-- Ranking untuk Freshmag (Produk 3) - Periode Oktober 2024
(3, 'CS018', 91.20, 1, '2024-10', 'published'),
(3, 'CS016', 88.70, 2, '2024-10', 'published'),
(3, 'CS019', 84.60, 3, '2024-10', 'published'),
(3, 'CS017', 81.90, 4, '2024-10', 'published'),
(3, 'CS020', 76.80, 5, '2024-10', 'published'),

-- Ranking untuk periode November 2024 (status draft)
(1, 'CS003', 93.20, 1, '2024-11', 'draft'),
(1, 'CS009', 92.90, 2, '2024-11', 'draft'),
(1, 'CS001', 89.10, 3, '2024-11', 'draft'),
(2, 'CS013', 94.10, 1, '2024-11', 'draft'),
(3, 'CS018', 90.80, 1, '2024-11', 'draft');

-- ============================================
-- COMPLETED: Dummy Data Generation
-- ============================================

-- Verification Queries (Optional - untuk testing)
-- SELECT COUNT(*) as total_users FROM pengguna;
-- SELECT COUNT(*) as total_products FROM produk;
-- SELECT COUNT(*) as total_cs FROM customer_service;
-- SELECT COUNT(*) as total_teams FROM tim;
-- SELECT COUNT(*) as total_criteria FROM kriteria;
-- SELECT COUNT(*) as total_sub_criteria FROM sub_kriteria;
-- SELECT COUNT(*) as total_evaluations FROM nilai;
-- SELECT COUNT(*) as total_rankings FROM ranking;

-- Quick Summary Report
-- SELECT 
--     p.nama_produk,
--     COUNT(DISTINCT cs.id_cs) as total_cs,
--     COUNT(DISTINCT t.id_tim) as total_teams
-- FROM produk p
-- LEFT JOIN customer_service cs ON p.id_produk = cs.id_produk
-- LEFT JOIN tim t ON cs.id_tim = t.id_tim
-- GROUP BY p.id_produk, p.nama_produk;

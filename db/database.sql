-- ============================================
-- DATABASE: SPK Customer Service Evaluation
-- Metode: Profile Matching
-- ============================================

-- Drop tables if exists (untuk development)
DROP TABLE IF EXISTS `konversi`;
DROP TABLE IF EXISTS `range`;
DROP TABLE IF EXISTS `sub_kriteria`;
DROP TABLE IF EXISTS `nilai`;
DROP TABLE IF EXISTS `ranking`;
DROP TABLE IF EXISTS `supervisor_scope`;
DROP TABLE IF EXISTS `customer_service`;
DROP TABLE IF EXISTS `tim`;
DROP TABLE IF EXISTS `kriteria`;
DROP TABLE IF EXISTS `kanal`;
DROP TABLE IF EXISTS `produk`;
DROP TABLE IF EXISTS `pengguna`;

-- ============================================
-- Tabel: pengguna (User Management)
-- ============================================
CREATE TABLE `pengguna` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `id_atasan` INT(11) DEFAULT NULL,
  `nik` VARCHAR(50) NOT NULL,
  `nama_pengguna` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `level` ENUM('admin', 'junior_manager', 'supervisor', 'leader') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tim (Team Management)
-- ============================================
CREATE TABLE `tim` (
  `id_tim` INT(11) NOT NULL AUTO_INCREMENT,
  `id_leader` INT(11) DEFAULT NULL,
  `id_supervisor` INT(11) DEFAULT NULL,
  `nama_tim` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tim`),
  KEY `fk_tim_leader` (`id_leader`),
  KEY `fk_tim_supervisor` (`id_supervisor`),
  CONSTRAINT `fk_tim_leader` FOREIGN KEY (`id_leader`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_tim_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `pengguna` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: produk (Product Master)
-- ============================================
CREATE TABLE `produk` (
  `id_produk` INT(11) NOT NULL AUTO_INCREMENT,
  `sku_produk` VARCHAR(50) NOT NULL,
  `nama_produk` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `gambar` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  UNIQUE KEY `sku_produk` (`sku_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: kanal (Channel Master)
-- ============================================
CREATE TABLE `kanal` (
  `id_kanal` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kanal` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kanal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: supervisor_scope (Supervisor Scope Assignment)
-- ============================================
CREATE TABLE `supervisor_scope` (
  `id_scope` INT(11) NOT NULL AUTO_INCREMENT,
  `id_supervisor` INT(11) NOT NULL,
  `id_kanal` INT(11) NOT NULL,
  `id_produk` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_scope`),
  KEY `fk_scope_supervisor` (`id_supervisor`),
  KEY `fk_scope_kanal` (`id_kanal`),
  KEY `fk_scope_produk` (`id_produk`),
  CONSTRAINT `fk_scope_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `pengguna` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_scope_kanal` FOREIGN KEY (`id_kanal`) REFERENCES `kanal` (`id_kanal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_scope_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: customer_service (CS Master Data)
-- ============================================
CREATE TABLE `customer_service` (
  `id_cs` INT(11) NOT NULL AUTO_INCREMENT,
  `id_produk` INT(11) NOT NULL,
  `id_kanal` INT(11) NOT NULL,
  `id_tim` INT(11) NOT NULL,
  `nik` VARCHAR(50) NOT NULL,
  `nama_cs` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cs`),
  UNIQUE KEY `nik` (`nik`),
  KEY `fk_cs_produk` (`id_produk`),
  KEY `fk_cs_kanal` (`id_kanal`),
  KEY `fk_cs_tim` (`id_tim`),
  CONSTRAINT `fk_cs_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cs_kanal` FOREIGN KEY (`id_kanal`) REFERENCES `kanal` (`id_kanal`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cs_tim` FOREIGN KEY (`id_tim`) REFERENCES `tim` (`id_tim`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: kriteria (Criteria for Evaluation)
-- ============================================
CREATE TABLE `kriteria` (
  `id_kriteria` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_kriteria` VARCHAR(10) NOT NULL,
  `nama_kriteria` VARCHAR(100) NOT NULL,
  `bobot` DECIMAL(5,2) NOT NULL,
  `jenis_kriteria` ENUM('core_factor', 'secondary_factor') NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kriteria`),
  UNIQUE KEY `kode_kriteria` (`kode_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: sub_kriteria (Sub Criteria)
-- ============================================
CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` INT(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` INT(11) NOT NULL,
  `nama_sub_kriteria` VARCHAR(100) NOT NULL,
  `bobot_sub` DECIMAL(5,2) NOT NULL,
  `target` DECIMAL(5,2) NOT NULL COMMENT 'Nilai target/standar yang diharapkan',
  `keterangan` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sub_kriteria`),
  KEY `fk_subkriteria_kriteria` (`id_kriteria`),
  CONSTRAINT `fk_subkriteria_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: range (Range Nilai untuk Sub Kriteria)
-- ============================================
CREATE TABLE `range` (
  `id_range` INT(11) NOT NULL AUTO_INCREMENT,
  `id_sub_kriteria` INT(11) NOT NULL,
  `batas_atas` DECIMAL(10,2) NOT NULL,
  `batas_bawah` DECIMAL(10,2) NOT NULL,
  `nilai_range` DECIMAL(5,2) NOT NULL COMMENT 'Nilai konversi untuk range ini',
  `keterangan` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_range`),
  KEY `fk_range_subkriteria` (`id_sub_kriteria`),
  CONSTRAINT `fk_range_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: konversi (Conversion/Gap Mapping Table)
-- ============================================
CREATE TABLE `konversi` (
  `id_konversi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_cs` INT(11) NOT NULL,
  `id_sub_kriteria` INT(11) NOT NULL,
  `id_range` INT(11) NULL,
  `nilai_asli` DECIMAL(10,2) NOT NULL COMMENT 'Nilai asli sebelum konversi',
  `nilai_konversi` DECIMAL(5,2) NOT NULL COMMENT 'Nilai setelah konversi/gap',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_konversi`),
  KEY `fk_konversi_cs` (`id_cs`),
  KEY `fk_konversi_subkriteria` (`id_sub_kriteria`),
  CONSTRAINT `fk_konversi_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_konversi_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: nilai (Evaluation Scores)
-- ============================================
CREATE TABLE `nilai` (
  `id_nilai` INT(11) NOT NULL AUTO_INCREMENT,
  `id_cs` INT(11) NOT NULL,
  `id_sub_kriteria` INT(11) NOT NULL,
  `nilai` DECIMAL(10,2) NOT NULL,
  `periode` VARCHAR(20) NOT NULL COMMENT 'Format: YYYY-MM',  -- ADDED
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nilai`),
  KEY `fk_nilai_cs` (`id_cs`),
  KEY `fk_nilai_subkriteria` (`id_sub_kriteria`),
  KEY `idx_periode` (`periode`),  -- ADDED
  CONSTRAINT `fk_nilai_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nilai_subkriteria` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: ranking (Final Ranking Results)
-- ============================================
CREATE TABLE `ranking` (
  `id_ranking` INT(11) NOT NULL AUTO_INCREMENT,
  `id_produk` INT(11) NOT NULL,
  `id_cs` INT(11) NOT NULL COMMENT 'Foreign key ke customer_service',
  `nilai_akhir` DECIMAL(10,2) NOT NULL COMMENT 'Hasil akhir perhitungan Profile Matching',
  `peringkat` INT(11) NOT NULL,
  `periode` VARCHAR(20) NOT NULL COMMENT 'Format: YYYY-MM atau custom',
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ranking`),
  KEY `fk_ranking_produk` (`id_produk`),
  KEY `fk_ranking_cs` (`id_cs`),
  KEY `idx_periode` (`periode`),
  KEY `idx_peringkat` (`peringkat`),
  CONSTRAINT `fk_ranking_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ranking_cs` FOREIGN KEY (`id_cs`) REFERENCES `customer_service` (`id_cs`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INDEXES untuk Optimasi Query
-- ============================================

-- Index untuk pencarian berdasarkan level user
CREATE INDEX `idx_pengguna_level` ON `pengguna`(`level`);

-- Index untuk pencarian CS berdasarkan tim
CREATE INDEX `idx_cs_tim` ON `customer_service`(`id_tim`);

-- Index untuk pencarian nilai berdasarkan periode
CREATE INDEX `idx_nilai_periode` ON `nilai`(`created_at`);

-- Index komposit untuk supervisor scope
CREATE INDEX `idx_scope_composite` ON `supervisor_scope`(`id_supervisor`, `id_produk`, `id_kanal`);

-- ============================================
-- SAMPLE DATA untuk Testing
-- ============================================

-- Insert Admin User (Password: 'password')
INSERT INTO `pengguna` (`nik`, `nama_pengguna`, `email`, `password`, `level`) VALUES
('ADM001', 'Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

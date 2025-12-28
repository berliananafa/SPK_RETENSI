# SPK Retensi Customer Service

Sistem Pendukung Keputusan (SPK) untuk menentukan retensi Customer Service menggunakan metode Profile Matching dengan flow approval bertingkat (Leader → Supervisor → Published).

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Role & Hak Akses](#role--hak-akses)
- [Flow Approval](#flow-approval)
- [Struktur Database](#struktur-database)
- [Troubleshooting](#troubleshooting)

## 🚀 Fitur Utama

### Dashboard & Monitoring
- **Dashboard Multi-Role**: Dashboard khusus untuk setiap role (Admin, Junior Manager, Supervisor, Leader)
- **Real-time Statistics**: Menampilkan statistik CS, penilaian, dan ranking secara real-time
- **Visual Analytics**: Chart dan grafik untuk analisis performa tim

### Manajemen Data
- **Master Data**: Kelola data pengguna, produk, kanal, tim, dan CS
- **Hierarki Organisasi**: Junior Manager → Supervisor → Leader → Customer Service
- **Import/Export**: Import data CS dan export laporan ke Excel

### SPK Profile Matching
- **Kriteria & Sub-Kriteria**: Konfigurasi kriteria penilaian dengan bobot
- **Core Factor & Secondary Factor**: Pembobotan 90% CF dan 10% SF
- **Gap Analysis**: Perhitungan gap antara nilai aktual dan target
- **NCF, NSF, Skor Akhir**: Perhitungan otomatis dengan formula Profile Matching

### Approval Workflow
- **Multi-Level Approval**: Leader → Supervisor → Published
- **Approve/Reject**: Setiap level dapat menyetujui atau menolak dengan catatan
- **Bulk Approval**: Supervisor dapat menyetujui semua ranking sekaligus
- **Status Tracking**: Tracking lengkap status approval dengan history

### Laporan
- **Filter Multi-Parameter**: Filter berdasarkan periode, tim, produk, kanal
- **Export Excel**: Export laporan ranking dan penilaian
- **Print Report**: Cetak laporan dalam format yang rapi

## 🛠 Teknologi

- **Backend**: CodeIgniter 3.1.x (PHP Framework)
- **Frontend**: Bootstrap 4, jQuery, DataTables
- **Database**: MySQL 5.7+
- **Charts**: Chart.js
- **Export**: PHPSpreadsheet (Excel)
- **Icons**: Feather Icons

## 📦 Persyaratan Sistem

- PHP >= 7.4
- MySQL >= 5.7 atau MariaDB >= 10.2
- Apache/Nginx Web Server
- Extension PHP:
  - `php-mysql`
  - `php-mbstring`
  - `php-xml`
  - `php-zip`
  - `php-gd`
  - `php-intl`

## 💻 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/berliananafa/SPK_RETENSI.git
cd spk-retensi
```

### 2. Install Dependencies (jika menggunakan Composer)

```bash
composer install
```

### 3. Import Database

1. Buat database baru di MySQL:
```sql
CREATE DATABASE db_spk_retensi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import file SQL yang tersedia:
```bash
mysql -u root -p db_spk_retensi < db/db_spk.sql
```

Atau melalui phpMyAdmin:
- Buka phpMyAdmin
- Pilih database `db_spk_retensi`
- Klik tab "Import"
- Pilih file `db/db_spk.sql`
- Klik "Go"

### 4. Konfigurasi File

#### a. Database Configuration

Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',        // Sesuaikan dengan host MySQL Anda
    'username' => 'root',              // Username MySQL
    'password' => '',                  // Password MySQL
    'database' => 'db_spk_retensi',   // Nama database
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### b. Base URL Configuration

Edit file `application/config/config.php`:

```php
// Development
$config['base_url'] = 'http://localhost/spk-retensi/';

// Production (sesuaikan dengan domain Anda)
// $config['base_url'] = 'https://yourdomain.com/';
```

#### c. Session & Security

Edit file `application/config/config.php`:

```php
// Session Configuration
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'spk_retensi_session';
$config['sess_expiration'] = 7200; // 2 jam
$config['sess_save_path'] = APPPATH . 'cache/sessions/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

// Cookie Configuration
$config['cookie_prefix']    = '';
$config['cookie_domain']    = '';
$config['cookie_path']      = '/';
$config['cookie_secure']    = FALSE; // TRUE jika menggunakan HTTPS
$config['cookie_httponly']  = TRUE;

// Encryption Key (UBAH dengan key unik Anda!)
$config['encryption_key'] = 'your-32-character-encryption-key';
```

## ⚙️ Konfigurasi

### Environment Configuration

Edit file `index.php` di root project untuk mengatur environment:

```php
// Development
define('ENVIRONMENT', 'development');

// Production
// define('ENVIRONMENT', 'production');
```

### Upload Configuration

Edit `application/config/config.php`:

```php
$config['upload_path'] = './uploads/';
$config['allowed_types'] = 'xlsx|xls|csv';
$config['max_size'] = 10240; // 10MB
```

## 👥 Role & Hak Akses

### 1. Admin
**Hak Akses Penuh:**
- Manajemen semua master data (pengguna, produk, kanal, kriteria)
- Manajemen organisasi (Junior Manager, Supervisor, Leader, Tim, CS)
- Input dan import data penilaian
- Proses ranking dengan Profile Matching
- Lihat semua ranking dan laporan
- Export dan download laporan

### 2. Junior Manager
**Monitoring & Oversight:**
- Dashboard overview semua tim
- Lihat data supervisor dan tim di bawahnya
- Monitoring penilaian dan ranking (read-only)
- Export laporan untuk tim yang diawasi

### 3. Supervisor
**Manajemen Tim & Approval:**
- Dashboard tim yang diawasi
- Lihat data leader dan CS di timnya
- Monitoring penilaian (read-only)
- **Approve/Reject ranking** (level terakhir)
- Bulk approval untuk efisiensi
- Export laporan tim

### 4. Leader
**Manajemen CS & Approval:**
- Dashboard tim yang dipimpin
- Lihat data CS dalam timnya
- Monitoring penilaian (read-only)
- **Approve/Reject ranking** (level pertama)
- Export laporan tim
- Detail perhitungan Profile Matching

## 🔄 Flow Approval

```
1. DRAFT (Admin)
   ↓
2. PENDING_LEADER (Menunggu Leader)
   ↓
   ├─→ REJECTED_LEADER (Ditolak Leader) → END
   └─→ APPROVED (Leader setuju)
       ↓
3. PENDING_SUPERVISOR (Menunggu Supervisor)
   ↓
   ├─→ REJECTED_SUPERVISOR (Ditolak Supervisor) → END
   └─→ APPROVED (Supervisor setuju)
       ↓
4. PUBLISHED (Final, tampil di laporan)
```

**Status Enum:**
- `draft` - Baru dibuat oleh Admin
- `pending_leader` - Menunggu approval Leader
- `rejected_leader` - Ditolak oleh Leader
- `pending_supervisor` - Disetujui Leader, menunggu Supervisor
- `rejected_supervisor` - Ditolak oleh Supervisor
- `published` - Final, sudah disetujui semua level
- `archived` - Diarsipkan

## 🗄️ Struktur Database

### Tabel Utama

- `pengguna` - Data user dan role
- `tim` - Data tim dengan Leader dan Supervisor
- `customer_service` - Data CS yang dinilai
- `kriteria` - Kriteria penilaian
- `sub_kriteria` - Sub kriteria dan target nilai
- `range_nilai` - Mapping nilai gap Profile Matching
- `nilai` - Data penilaian CS
- `ranking` - Hasil perhitungan ranking

### Relasi Penting

```
pengguna (Junior Manager)
  └── pengguna (Supervisor)
      └── tim
          ├── pengguna (Leader)
          └── customer_service
              ├── nilai
              └── ranking
```

## 🔐 Default Login

Setelah import database, gunakan kredensial berikut:

| Role | Username | Password |
|------|----------|----------|
| Admin | admin@example.com | password |

**⚠️ PENTING:** Segera ubah password default setelah login pertama!

## 🐛 Troubleshooting

### Error 500 - Internal Server Error

1. Pastikan `mod_rewrite` aktif
2. Cek permission folder `application/cache` dan `application/logs`
3. Cek error di `application/logs/log-YYYY-MM-DD.php`

### Database Connection Error

1. Cek kredensial database di `application/config/database.php`
2. Pastikan MySQL service berjalan
3. Cek apakah database sudah di-import

### Session Error / Auto Logout

1. Buat folder `application/cache/sessions` jika belum ada
2. Set permission 755: `chmod -R 755 application/cache/sessions`
3. Pastikan `sess_save_path` sesuai di `config.php`

### Upload Error

1. Cek permission folder `uploads`: `chmod -R 755 uploads`
2. Cek `upload_max_filesize` dan `post_max_size` di `php.ini`
3. Restart web server setelah mengubah php.ini

### Calculation Error (NCF/NSF)

1. Pastikan kriteria memiliki `jenis_kriteria` yang benar (`core_factor` atau `secondary_factor`)
2. Cek apakah range nilai sudah dikonfigurasi
3. Pastikan sub kriteria memiliki target nilai

## 📚 Dokumentasi Tambahan

### Profile Matching Formula

```
1. Gap = Nilai Aktual - Target
2. Bobot Gap = Lihat tabel range_nilai
3. NCF = Σ(Bobot Gap CF) / Jumlah Item CF
4. NSF = Σ(Bobot Gap SF) / Jumlah Item SF
5. Skor Akhir = (NCF × 0.9) + (NSF × 0.1)
```


Distributed under the MIT License. See `LICENSE` for more information.

## 📧 Kontak

Project Link: [https://github.com/berliananafa/SPK_RETENSI](https://github.com/berliananafa/SPK_RETENSI)

---

**Dibuat dengan ❤️ menggunakan CodeIgniter 3**


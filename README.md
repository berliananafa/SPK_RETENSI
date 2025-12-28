# SPK Retensi Customer Service

Deskripsi singkat
Sistem Pendukung Keputusan (SPK) untuk membantu penentuan retensi Customer Service menggunakan metode Profile Matching. Dirancang untuk keperluan penelitian dengan alur approval bertingkat (Leader → Supervisor → Published).

Tujuan
- Menyediakan mekanisme perhitungan ranking CS berdasarkan kriteria dan sub-kriteria.
- Memfasilitasi replikasi eksperimen untuk studi akademik.

Fitur utama (ringkas)
- Import data CS dan penilaian
- Perhitungan Profile Matching (NCF, NSF, skor akhir)
- Alur approval bertingkat (Leader, Supervisor)
- Export laporan (Excel)

Metodologi singkat
Profile Matching: hitung gap antara nilai aktual dan target, konversi gap → bobot, hitung NCF (core) dan NSF (secondary), lalu skor akhir = (NCF × 0.9) + (NSF × 0.1).

Persiapan & Instalasi
1. Clone repository:

	git clone https://github.com/berliananafa/SPK_RETENSI.git
	cd spk-retensi

2. (Opsional) Install dependensi:

	composer install

3. Buat database dan import SQL:

	- Buat database (contoh: `db_spk_retensi`) di MySQL
	- Import file: `db/db_spk.sql`

Konfigurasi penting
- Edit `application/config/database.php` untuk hostname, username, password, dan nama database.
- Atur `base_url` di `application/config/config.php` (contoh: `http://localhost/spk-retensi/`).
- Pastikan folder `application/cache/sessions` ada jika memakai session file.

Menjalankan secara lokal
- Gunakan Laragon/XAMPP: tempatkan project di `www` atau `htdocs`, buka `http://localhost/spk-retensi/`.

File penting & lokasi kode perhitungan
- Library/Model Profile Matching: cek `application/libraries/ProfileMatching.php` atau file serupa.
- Model ranking: `application/models/RankingModel.php`.
- Controller untuk proses: lihat `application/controllers/` (mis. Admin, Leader, Supervisor).

Replikasi eksperimen 
- Gunakan dataset yang sama dan catat konfigurasi `range_nilai`, bobot kriteria, dan periode data.
- Simpan perubahan konfigurasi dan hasil untuk reproducibility.

Troubleshooting singkat
- Error 500: cek `application/logs/` dan konfigurasi `base_url` serta `mod_rewrite`.
- Koneksi DB: cek `application/config/database.php` dan service MySQL berjalan.

Lisensi & Kontak
- Lisensi: MIT (lihat file LICENSE)
- Repo: https://github.com/berliananafa/SPK_RETENSI


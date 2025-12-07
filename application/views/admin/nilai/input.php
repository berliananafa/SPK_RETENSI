<!-- Input Penilaian CS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fe fe-upload"></i> Input Penilaian Customer Service</h5>
            </div>
            <div class="card-body">
                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- Info Alert -->
                <div class="alert alert-info">
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Upload file Excel (.xlsx/.xls) sesuai template</li>
                        <li>NIK CS harus sudah terdaftar di sistem</li>
                        <li>Maksimal ukuran file: 5MB</li>
                    </ul>
                </div>

                <!-- Download Template -->
                <div class="mb-4">
                    <a href="<?= base_url('admin/nilai/download-template') ?>" class="btn btn-success">
                        <i class="fe fe-download"></i> Download Template Excel
                    </a>
                    <a href="<?= base_url('admin/nilai') ?>" class="btn btn-info">
                        <i class="fe fe-list"></i> Lihat Data Penilaian
                    </a>
                </div>

                <!-- Upload Form -->
                <?= form_open_multipart('admin/nilai/store') ?>
                    <div class="form-group">
                        <label>Periode Penilaian <span class="text-danger">*</span></label>
                        <input type="month" name="periode" class="form-control" 
                               value="<?= date('Y-m') ?>" required>
                        <small class="text-muted">Pilih bulan dan tahun penilaian</small>
                    </div>

                    <div class="form-group">
                        <label>File Excel <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="file_excel" class="custom-file-input" 
                                   id="file_excel" accept=".xlsx,.xls" required>
                            <label class="custom-file-label" for="file_excel">Pilih file Excel...</label>
                        </div>
                        <small class="text-muted">Format: .xlsx atau .xls | Max: 5MB</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="replace_existing" value="1" 
                                   class="custom-control-input" id="replace" checked>
                            <label class="custom-control-label" for="replace">
                                Replace data yang sudah ada
                            </label>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fe fe-upload"></i> Upload & Proses
                    </button>
                    <a href="<?= base_url('admin/nilai') ?>" class="btn btn-secondary">
                        <i class="fe fe-arrow-left"></i> Kembali
                    </a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Format Template Info -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong><i class="fe fe-info-circle"></i> Format Template Excel</strong>
            </div>
            <div class="card-body">
                <p>Template Excel memiliki kolom-kolom berikut (otomatis menyesuaikan dengan database):</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kolom</th>
                                <th>Keterangan</th>
                                <th>Contoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>NIK CS</strong></td>
                                <td>Nomor Induk Karyawan (WAJIB)</td>
                                <td>202301001</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>Nama CS</strong></td>
                                <td>Nama lengkap (Opsional, untuk verifikasi)</td>
                                <td>John Doe</td>
                            </tr>
                            
                            <?php if (!empty($kriteria)): ?>
                                <?php 
                                $no = 3;
                                foreach ($kriteria as $krt): 
                                    // Fix: bobot menjadi bobot_sub
                                    $sub_kriteria = $this->db->select('nama_sub_kriteria, bobot_sub, target')
                                                             ->from('sub_kriteria')
                                                             ->where('id_kriteria', $krt->id_kriteria)
                                                             ->order_by('id_sub_kriteria', 'ASC')
                                                             ->get()
                                                             ->result();
                                    
                                    foreach ($sub_kriteria as $sk):
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= $sk->nama_sub_kriteria ?></strong>
                                            <br>
                                            <small class="text-muted"><?= $krt->nama_kriteria ?></small>
                                        </td>
                                        <td>
                                            Nilai penilaian (WAJIB)
                                            <br>
                                            <small>Bobot: <?= $sk->bobot_sub ?>% | Target: <?= $sk->target ?></small>
                                        </td>
                                        <td><?= rand(75, 100) ?></td>
                                    </tr>
                                <?php 
                                    endforeach;
                                endforeach; 
                                ?>
                            <?php endif; ?>
                            
                            <tr class="table-light">
                                <td><?= $no ?></td>
                                <td><strong>Produk, Leader, Tim, SPV</strong></td>
                                <td>Informasi tambahan (tidak diproses)</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-warning mb-0">
                    <strong>Catatan:</strong>
                    <ul class="mb-0">
                        <li>NIK CS harus sudah terdaftar di sistem</li>
                        <li>Jika menambah kriteria/sub kriteria, download ulang template</li>
                        <li>Nilai bisa desimal (contoh: 85.5)</li>
                        <li>Nilai 0 atau kosong akan dilewati</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script>
$(document).ready(function() {
    // Update file name display
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
<?php
add_js(ob_get_clean());
?>

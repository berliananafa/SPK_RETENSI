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

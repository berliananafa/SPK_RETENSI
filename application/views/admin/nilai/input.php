<!-- Input Penilaian CS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="fe fe-upload"></i> Input Penilaian Customer Service</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Alert Info -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fe fe-info fe-24 mr-3"></i>
                        <div>
                            <strong>Informasi Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Upload file Excel (.xlsx/.xls) yang berisi data penilaian CS</li>
                                <li>Pastikan format file sesuai dengan template yang disediakan</li>
                                <li>Data yang diupload akan menggantikan penilaian periode yang sama</li>
                                <li>Maksimal ukuran file: 5MB</li>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Download Template -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-left-success shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg mr-3">
                                        <span class="avatar-title rounded-circle bg-success text-white">
                                            <i class="fe fe-download fe-24"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Download Template Excel</h6>
                                        <p class="text-muted small mb-2">Gunakan template ini untuk input data penilaian</p>
                                        <a href="<?= base_url('admin/nilai/download-template') ?>" class="btn btn-success btn-sm">
                                            <i class="fe fe-download"></i> Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg mr-3">
                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                            <i class="fe fe-file-text fe-24"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Panduan Pengisian</h6>
                                        <p class="text-muted small mb-2">Lihat panduan lengkap format Excel</p>
                                        <a href="<?= base_url('admin/nilai/panduan') ?>" class="btn btn-warning btn-sm" target="_blank">
                                            <i class="fe fe-eye"></i> Lihat Panduan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <?= form_open_multipart('admin/nilai/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periode">Periode Penilaian <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="periode" name="periode" 
                                       value="<?= set_value('periode', date('Y-m')) ?>" required>
                                <small class="form-text text-muted">Pilih bulan dan tahun penilaian</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kriteria">Kriteria (Optional)</label>
                                <select class="form-control" id="id_kriteria" name="id_kriteria">
                                    <option value="">-- Semua Kriteria --</option>
                                    <?php if (!empty($kriteria)): ?>
                                        <?php foreach ($kriteria as $krt): ?>
                                            <option value="<?= $krt->id_kriteria ?>" <?= set_select('id_kriteria', $krt->id_kriteria) ?>>
                                                <?= htmlspecialchars($krt->kode_kriteria . ' - ' . $krt->nama_kriteria) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted">Filter spesifik kriteria (kosongkan untuk semua)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file_excel">File Excel Penilaian <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file_excel" name="file_excel" 
                                   accept=".xlsx,.xls" required>
                            <label class="custom-file-label" for="file_excel">Pilih file Excel...</label>
                        </div>
                        <small class="form-text text-muted">Format: .xlsx atau .xls (Maksimal 5MB)</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="replace_existing" 
                                   name="replace_existing" value="1" checked>
                            <label class="custom-control-label" for="replace_existing">
                                Replace data yang sudah ada untuk periode yang sama
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Catatan atau keterangan tambahan untuk upload ini"><?= set_value('keterangan') ?></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-sm btn-primary btn-lg">
                            <i class="fe fe-upload"></i> Upload & Proses Data
                        </button>
                        <a href="<?= base_url('admin/nilai') ?>" class="btn btn-sm btn-secondary btn-lg">
                            <i class="fe fe-list"></i> Lihat Data Penilaian
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Format Excel Info -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <strong><i class="fe fe-help-circle"></i> Format Template Excel</strong>
            </div>
            <div class="card-body">
                <p class="mb-3">Template Excel harus memiliki kolom-kolom berikut:</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead >
                            <tr>
                                <th>No</th>
                                <th>Nama Kolom</th>
                                <th>Keterangan</th>
                                <th>Contoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><code>NIK</code></td>
                                <td>Nomor Induk Pegawai CS</td>
                                <td>CS001</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><code>Nama CS</code></td>
                                <td>Nama lengkap Customer Service (untuk verifikasi)</td>
                                <td>John Doe</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><code>Kode Kriteria</code></td>
                                <td>Kode kriteria penilaian (C1, C2, dst)</td>
                                <td>C1</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><code>Kode Sub Kriteria</code></td>
                                <td>Nomor urut sub kriteria dalam kriteria (1, 2, 3, dst)</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><code>Nilai</code></td>
                                <td>Nilai penilaian (angka)</td>
                                <td>85</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fe fe-alert-triangle"></i> <strong>Perhatian:</strong> 
                    Pastikan NIK CS sudah terdaftar di sistem, Kode Kriteria sesuai dengan master kriteria, dan Kode Sub Kriteria adalah nomor urut (1, 2, 3, dst). Periode penilaian diisi di form upload.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update file name in custom file input
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>

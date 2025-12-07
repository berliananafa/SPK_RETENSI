<!-- Form Tambah Kriteria -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Tambah Kriteria Penilaian</strong>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle mr-2"></i>
                        <?= validation_errors() ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?= form_open('admin/kriteria/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_kriteria">Kode Kriteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_kriteria" name="kode_kriteria" 
                                       value="<?= set_value('kode_kriteria') ?>" placeholder="Contoh: C1, C2, K1" required>
                                <small class="form-text text-muted">Kode unik untuk kriteria (contoh: C1, C2, K1)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kriteria">Nama Kriteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" 
                                       value="<?= set_value('nama_kriteria') ?>" placeholder="Contoh: Produktivitas" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang kriteria ini"><?= set_value('deskripsi') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kriteria">Jenis Kriteria <span class="text-danger">*</span></label>
                                <select class="form-control" id="jenis_kriteria" name="jenis_kriteria" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="core_factor" <?= set_select('jenis_kriteria', 'core_factor') ?>>Core Factor (Faktor Utama - 60%)</option>
                                    <option value="secondary_factor" <?= set_select('jenis_kriteria', 'secondary_factor') ?>>Secondary Factor (Faktor Pendukung - 40%)</option>
                                </select>
                                <small class="form-text text-muted">
                                    <strong>Core Factor:</strong> Faktor utama yang paling berpengaruh<br>
                                    <strong>Secondary Factor:</strong> Faktor pendukung
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bobot">Bobot (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="bobot" name="bobot" 
                                       value="<?= set_value('bobot') ?>" min="0" max="100" step="0.01" 
                                       placeholder="0.00" required>
                                <small class="form-text text-muted">Bobot menunjukkan tingkat kepentingan kriteria</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan Data
                        </button>
                        <a href="<?= base_url('admin/kriteria') ?>" class="btn btn-secondary">
                            <i class="fe fe-x"></i> Batal
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-left-info">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-help-circle fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Tips Pengisian</h6>
                        <ul class="mb-0 small text-muted">
                            <li><strong>Kode Kriteria:</strong> Gunakan kode yang mudah diingat dan konsisten (C1, C2, dst.)</li>
                            <li><strong>Core Factor:</strong> Kriteria yang paling penting dan berpengaruh terhadap hasil (bobot 60%)</li>
                            <li><strong>Secondary Factor:</strong> Kriteria pendukung yang melengkapi core factor (bobot 40%)</li>
                            <li><strong>Bobot:</strong> Tingkat kepentingan kriteria (dalam persen)</li>
                            <li><strong>Nilai Target:</strong> Akan diatur di <strong>Sub Kriteria</strong> untuk setiap aspek penilaian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

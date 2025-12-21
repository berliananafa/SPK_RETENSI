<!-- Form Edit Kriteria -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Edit Kriteria Penilaian</strong>
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

                <?= form_open('admin/kriteria/update/' . $kriteria->id_kriteria) ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_kriteria">Kode Kriteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_kriteria" name="kode_kriteria" 
                                       value="<?= set_value('kode_kriteria', $kriteria->kode_kriteria) ?>" placeholder="Contoh: C1, C2, K1" required>
                                <small class="form-text text-muted">Kode unik untuk kriteria (contoh: C1, C2, K1)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kriteria">Nama Kriteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" 
                                       value="<?= set_value('nama_kriteria', $kriteria->nama_kriteria) ?>" placeholder="Contoh: Produktivitas" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang kriteria ini"><?= set_value('deskripsi', $kriteria->deskripsi) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jenis_kriteria">Jenis Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="jenis_kriteria" name="jenis_kriteria" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="core_factor" <?= set_select('jenis_kriteria', 'core_factor', ($kriteria->jenis_kriteria == 'core_factor')) ?>>Core Factor (Faktor Utama - 90%)</option>
                            <option value="secondary_factor" <?= set_select('jenis_kriteria', 'secondary_factor', ($kriteria->jenis_kriteria == 'secondary_factor')) ?>>Secondary Factor (Faktor Pendukung - 10%)</option>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Core Factor:</strong> Faktor utama yang paling berpengaruh (Bobot otomatis 90%)<br>
                            <strong>Secondary Factor:</strong> Faktor pendukung (Bobot otomatis 10%)
                        </small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Update Data
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
        <div class="card shadow-sm border-left-warning">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-alert-triangle fe-24 text-warning"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Perhatian</h6>
                        <p class="mb-0 text-muted small">
                            Perubahan kriteria akan mempengaruhi perhitungan ranking yang sudah ada. 
                            Pastikan untuk mengecek ulang hasil ranking setelah mengubah kriteria.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

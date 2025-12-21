<!-- Form Edit Sub Kriteria -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Edit Sub Kriteria</strong>
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

                <?= form_open('admin/sub-kriteria/update/' . $sub_kriteria->id_sub_kriteria) ?>
                    <div class="form-group">
                        <label for="id_kriteria">Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_kriteria" name="id_kriteria" required>
                            <option value="">-- Pilih Kriteria --</option>
                            <?php if (!empty($kriteria)): ?>
                                <?php foreach ($kriteria as $krt): ?>
                                    <option value="<?= $krt->id_kriteria ?>" <?= set_select('id_kriteria', $krt->id_kriteria, ($krt->id_kriteria == $sub_kriteria->id_kriteria)) ?>>
                                        <?= htmlspecialchars($krt->kode_kriteria . ' - ' . $krt->nama_kriteria) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Pilih kriteria induk untuk sub kriteria ini</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_sub_kriteria">Nama Sub Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_sub_kriteria" name="nama_sub_kriteria" 
                               value="<?= set_value('nama_sub_kriteria', $sub_kriteria->nama_sub_kriteria) ?>" placeholder="Contoh: Tingkat Produktivitas Tinggi" required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Keterangan singkat tentang sub kriteria ini"><?= set_value('keterangan', $sub_kriteria->keterangan) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="bobot_sub">Bobot (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="bobot_sub" name="bobot_sub" 
                               value="<?= set_value('bobot_sub', $sub_kriteria->bobot_sub) ?>" min="0" max="100" step="0.01" 
                               placeholder="50.00" required>
                        <small class="form-text text-muted">Persentase dari total 100% (bukan dari kriteria induk)</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Update Data
                        </button>
                        <a href="<?= base_url('admin/sub-kriteria') ?>" class="btn btn-secondary">
                            <i class="fe fe-x"></i> Batal
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Warning Card -->
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
                            Perubahan nilai sub kriteria akan mempengaruhi perhitungan ranking yang sudah ada. 
                            Pastikan untuk mengecek ulang hasil ranking setelah mengubah sub kriteria.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

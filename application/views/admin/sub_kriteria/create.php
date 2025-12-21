<!-- Form Tambah Sub Kriteria -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Tambah Sub Kriteria</strong>
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

                <?= form_open('admin/sub-kriteria/store') ?>
                    <div class="form-group">
                        <label for="id_kriteria">Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_kriteria" name="id_kriteria" required>
                            <option value="">-- Pilih Kriteria --</option>
                            <?php if (!empty($kriteria)): ?>
                                <?php foreach ($kriteria as $krt): ?>
                                    <option value="<?= $krt->id_kriteria ?>" <?= set_select('id_kriteria', $krt->id_kriteria) ?>>
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
                               value="<?= set_value('nama_sub_kriteria') ?>" placeholder="Contoh: Tingkat Produktivitas Tinggi" required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Keterangan singkat tentang sub kriteria ini"><?= set_value('keterangan') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="bobot_sub">Bobot (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="bobot_sub" name="bobot_sub" 
                               value="<?= set_value('bobot_sub') ?>" min="0" max="100" step="0.01" 
                               placeholder="50.00" required>
                        <small class="form-text text-muted">Persentase dari total 100% (bukan dari kriteria induk)</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan Data
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

<!-- Example Card -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-left-info">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-help-circle fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Contoh Sub Kriteria</h6>
                        <div class="small text-muted">
                            <strong>Kriteria: Performa (Core Factor - 90%)</strong>
                            <ul class="mb-2 mt-2">
                                <li>KPI (Key Performance Index) - Bobot: <strong>50%</strong></li>
                                <li>Rasio Ketercapaian Target - Bobot: <strong>40%</strong></li>
                            </ul>
                            <strong>Kriteria: Kedisiplinan (Secondary Factor - 10%)</strong>
                            <ul class="mb-0 mt-2">
                                <li>Absensi - Bobot: <strong>5%</strong></li>
                                <li>Keterlambatan - Bobot: <strong>5%</strong></li>
                            </ul>
                            <small class="text-info">Total: KPI(50%) + Rasio(40%) + Absensi(5%) + Keterlambatan(5%) = 100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

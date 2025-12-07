<!-- Form Tambah Range Nilai -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Tambah Range Nilai</strong>
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

                <?= form_open('admin/range/store') ?>
                    <div class="form-group">
                        <label for="id_sub_kriteria">Sub Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_sub_kriteria" name="id_sub_kriteria" required>
                            <option value="">-- Pilih Sub Kriteria --</option>
                            <?php if (!empty($sub_kriteria)): ?>
                                <?php foreach ($sub_kriteria as $sub): ?>
                                    <option value="<?= $sub->id_sub_kriteria ?>" <?= set_select('id_sub_kriteria', $sub->id_sub_kriteria) ?>>
                                        <?= htmlspecialchars(($sub->kode_kriteria ?? '') . ' - ' . ($sub->nama_kriteria ?? '') . ' > ' . $sub->nama_sub_kriteria) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Pilih sub kriteria untuk range nilai ini</small>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batas_bawah">Batas Bawah <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="batas_bawah" name="batas_bawah" 
                                       value="<?= set_value('batas_bawah') ?>" step="0.01" 
                                       placeholder="Contoh: 0" required>
                                <small class="form-text text-muted">Nilai minimum range</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batas_atas">Batas Atas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="batas_atas" name="batas_atas" 
                                       value="<?= set_value('batas_atas') ?>" step="0.01" 
                                       placeholder="Contoh: 100" required>
                                <small class="form-text text-muted">Nilai maksimum range</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nilai_range">Nilai Range <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="nilai_range" name="nilai_range" 
                                       value="<?= set_value('nilai_range') ?>" step="0.01" 
                                       placeholder="Contoh: 5" required>
                                <small class="form-text text-muted">Nilai hasil konversi</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                  placeholder="Keterangan singkat tentang range ini (opsional)"><?= set_value('keterangan') ?></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan Data
                        </button>
                        <a href="<?= base_url('admin/range') ?>" class="btn btn-secondary">
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
                        <h6 class="mb-1">Contoh Range Nilai</h6>
                        <div class="small text-muted">
                            <strong>Kriteria: Produktivitas (Transaksi per Hari)</strong>
                            <ul class="mb-0 mt-2">
                                <li>0 - 50 transaksi → Nilai Konversi: 1 (Sangat Rendah)</li>
                                <li>51 - 100 transaksi → Nilai Konversi: 2 (Rendah)</li>
                                <li>101 - 150 transaksi → Nilai Konversi: 3 (Sedang)</li>
                                <li>151 - 200 transaksi → Nilai Konversi: 4 (Tinggi)</li>
                                <li>201 - 250 transaksi → Nilai Konversi: 5 (Sangat Tinggi)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

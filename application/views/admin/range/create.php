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
                                <label for="batas_bawah">Batas Bawah</label>
                                <input type="number" class="form-control" id="batas_bawah" name="batas_bawah" 
                                       value="<?= set_value('batas_bawah') ?>" step="0.01" 
                                       placeholder="Kosongkan untuk ≤ (tak terbatas bawah)">
                                <small class="form-text text-muted">Nilai minimum (kosongkan untuk ≤)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batas_atas">Batas Atas</label>
                                <input type="number" class="form-control" id="batas_atas" name="batas_atas" 
                                       value="<?= set_value('batas_atas') ?>" step="0.01" 
                                       placeholder="Kosongkan untuk ≥ (tak terbatas atas)">
                                <small class="form-text text-muted">Nilai maksimum (kosongkan untuk ≥)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nilai_range">Poin/Nilai <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="nilai_range" name="nilai_range" 
                                       value="<?= set_value('nilai_range') ?>" step="1" min="1" max="5"
                                       placeholder="1-5" required>
                                <small class="form-text text-muted">Poin hasil (1-5)</small>
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
                        <h6 class="mb-1">Contoh Range Nilai (Sub Kriteria: KPI)</h6>
                        <div class="small text-muted">
                            <table class="table table-sm table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Range Nilai</th>
                                        <th>Poin</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>≥ 100.000</td>
                                        <td><strong>5</strong></td>
                                        <td>Sangat Baik</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>80.000 - 99.999</td>
                                        <td><strong>4</strong></td>
                                        <td>Baik</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>50.000 - 79.999</td>
                                        <td><strong>3</strong></td>
                                        <td>Cukup</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>30.000 - 49.999</td>
                                        <td><strong>2</strong></td>
                                        <td>Kurang</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>≤ 29.999</td>
                                        <td><strong>1</strong></td>
                                        <td>Sangat Kurang</td>
                                    </tr>
                                </tbody>
                            </table>
                            <small class="text-info"><strong>Catatan:</strong> Kosongkan batas bawah untuk ≤, kosongkan batas atas untuk ≥</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

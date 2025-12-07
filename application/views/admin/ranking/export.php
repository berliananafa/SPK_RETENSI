<!-- Export Data Ranking -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-gradient-success text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="fe fe-download"></i> Export Data Ranking</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Export Excel -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-left-success shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar avatar-lg mr-3">
                                        <span class="avatar-title rounded-circle bg-success text-white">
                                            <i class="fe fe-file-text fe-24"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">Export ke Excel</h5>
                                        <p class="text-muted mb-3">
                                            Download hasil ranking dalam format Excel (.xlsx) lengkap dengan detail perhitungan per kriteria.
                                        </p>
                                        
                                        <?= form_open('admin/ranking/export-excel') ?>
                                            <div class="form-group">
                                                <label for="periode_excel">Periode:</label>
                                                <input type="month" class="form-control" id="periode_excel" 
                                                       name="periode" value="<?= date('Y-m') ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="tim_excel">Tim (Optional):</label>
                                                <select class="form-control" id="tim_excel" name="id_tim">
                                                    <option value="">-- Semua Tim --</option>
                                                    <?php if (!empty($tim)): ?>
                                                        <?php foreach ($tim as $t): ?>
                                                            <option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="include_detail_excel" 
                                                       name="include_detail" value="1" checked>
                                                <label class="custom-control-label" for="include_detail_excel">
                                                    Sertakan detail nilai per kriteria
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fe fe-download"></i> Download Excel
                                            </button>
                                        <?= form_close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Export PDF -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-left-danger shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar avatar-lg mr-3">
                                        <span class="avatar-title rounded-circle bg-danger text-white">
                                            <i class="fe fe-file fe-24"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">Export ke PDF</h5>
                                        <p class="text-muted mb-3">
                                            Download hasil ranking dalam format PDF untuk keperluan laporan resmi atau presentasi.
                                        </p>
                                        
                                        <?= form_open('admin/ranking/export-pdf', ['target' => '_blank']) ?>
                                            <div class="form-group">
                                                <label for="periode_pdf">Periode:</label>
                                                <input type="month" class="form-control" id="periode_pdf" 
                                                       name="periode" value="<?= date('Y-m') ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="tim_pdf">Tim (Optional):</label>
                                                <select class="form-control" id="tim_pdf" name="id_tim">
                                                    <option value="">-- Semua Tim --</option>
                                                    <?php if (!empty($tim)): ?>
                                                        <?php foreach ($tim as $t): ?>
                                                            <option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="limit_pdf">Tampilkan:</label>
                                                <select class="form-control" id="limit_pdf" name="limit">
                                                    <option value="10">Top 10</option>
                                                    <option value="20">Top 20</option>
                                                    <option value="50">Top 50</option>
                                                    <option value="">Semua</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-block">
                                                <i class="fe fe-download"></i> Download PDF
                                            </button>
                                        <?= form_close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export History -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fe fe-clock"></i> Riwayat Export</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <thead >
                                    <tr>
                                        <th>Tanggal Export</th>
                                        <th>Periode Data</th>
                                        <th>Format</th>
                                        <th>User</th>
                                        <th>File</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($export_history)): ?>
                                        <?php foreach ($export_history as $history): ?>
                                            <tr>
                                                <td>
                                                    <small><?= date('d M Y H:i', strtotime($history->created_at)) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary"><?= date('M Y', strtotime($history->periode . '-01')) ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($history->format == 'excel'): ?>
                                                        <span class="badge badge-success"><i class="fe fe-file-text"></i> Excel</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger"><i class="fe fe-file"></i> PDF</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?= htmlspecialchars($history->username) ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= htmlspecialchars($history->filename) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('uploads/exports/' . $history->filename) ?>" 
                                                       class="btn btn-sm btn-info" download title="Download">
                                                        <i class="fe fe-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                Belum ada riwayat export
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
                        <i class="fe fe-info fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi Export</h6>
                        <p class="mb-0 text-muted small">
                            <strong>Format Excel:</strong> Cocok untuk analisis data lebih lanjut, dapat diedit dan mencakup detail perhitungan.<br>
                            <strong>Format PDF:</strong> Cocok untuk laporan resmi, presentasi, atau dokumen yang tidak dapat diubah.<br>
                            <strong>Riwayat:</strong> File hasil export disimpan selama 30 hari dan dapat diunduh kembali.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

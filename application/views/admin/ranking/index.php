<!-- Hasil Ranking CS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="fe fe-award"></i> Hasil Ranking Customer Service</h5>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalProcess">
                            <i class="fe fe-refresh-cw"></i> Proses Ranking
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filterPeriode" class="font-weight-bold">Periode:</label>
                        <input type="month" class="form-control form-control-sm" id="filterPeriode" 
                               value="<?= date('Y-m') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterTim" class="font-weight-bold">Tim:</label>
                        <select class="form-control form-control-sm" id="filterTim">
                            <option value="">-- Semua Tim --</option>
                            <?php if (!empty($tim)): ?>
                                <?php foreach ($tim as $t): ?>
                                    <option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterLimit" class="font-weight-bold">Tampilkan:</label>
                        <select class="form-control form-control-sm" id="filterLimit">
                            <option value="10">Top 10</option>
                            <option value="20">Top 20</option>
                            <option value="50">Top 50</option>
                            <option value="">Semua</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold d-block">&nbsp;</label>
                        <button class="btn btn-info btn-sm btn-block" id="btnFilter">
                            <i class="fe fe-filter"></i> Filter Data
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mb-3">
                    <a href="<?= base_url('admin/ranking/export') ?>" class="btn btn-success btn-sm">
                        <i class="fe fe-download"></i> Export Excel
                    </a>
                    <a href="<?= base_url('admin/ranking/print') ?>" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fe fe-printer"></i> Print
                    </a>
                    <button class="btn btn-info btn-sm" onclick="window.location.reload()">
                        <i class="fe fe-refresh-ccw"></i> Refresh
                    </button>
                </div>

                <!-- Ranking Cards for Top 3 -->
                <?php if (!empty($ranking) && count($ranking) >= 3): ?>
                <div class="row mb-4">
                    <!-- Rank 2 -->
                    <div class="col-md-4 order-md-1 mb-3">
                        <div class="card border-0 shadow-sm bg-gradient-secondary text-white">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <span class="badge badge-pill badge-light" style="font-size: 1.5rem; padding: 1rem;">
                                        <i class="fe fe-award text-secondary"></i> #2
                                    </span>
                                </div>
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-white text-secondary" style="font-size: 2rem;">
                                        <?= strtoupper(substr($ranking[1]->nama_cs, 0, 1)) ?>
                                    </span>
                                </div>
                                <h5 class="mb-1"><?= htmlspecialchars($ranking[1]->nama_cs) ?></h5>
                                <p class="mb-2"><span class="badge badge-light"><?= htmlspecialchars($ranking[1]->nip) ?></span></p>
                                <p class="mb-2 small"><?= htmlspecialchars($ranking[1]->nama_tim ?? '-') ?></p>
                                <h3 class="mb-0 font-weight-bold"><?= number_format($ranking[1]->skor_akhir, 4) ?></h3>
                                <small>Skor Akhir</small>
                            </div>
                        </div>
                    </div>

                    <!-- Rank 1 -->
                    <div class="col-md-4 order-md-2 mb-3">
                        <div class="card border-0 shadow-lg bg-gradient-warning text-white" style="transform: scale(1.05);">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <span class="badge badge-pill badge-light" style="font-size: 2rem; padding: 1.2rem;">
                                        <i class="fe fe-award text-warning"></i> #1
                                    </span>
                                </div>
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-white text-warning" style="font-size: 2.5rem;">
                                        <?= strtoupper(substr($ranking[0]->nama_cs, 0, 1)) ?>
                                    </span>
                                </div>
                                <h4 class="mb-1 font-weight-bold"><?= htmlspecialchars($ranking[0]->nama_cs) ?></h4>
                                <p class="mb-2"><span class="badge badge-light"><?= htmlspecialchars($ranking[0]->nip) ?></span></p>
                                <p class="mb-2"><?= htmlspecialchars($ranking[0]->nama_tim ?? '-') ?></p>
                                <h2 class="mb-0 font-weight-bold"><?= number_format($ranking[0]->skor_akhir, 4) ?></h2>
                                <small>Skor Akhir</small>
                            </div>
                        </div>
                    </div>

                    <!-- Rank 3 -->
                    <div class="col-md-4 order-md-3 mb-3">
                        <div class="card border-0 shadow-sm bg-gradient-danger text-white">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <span class="badge badge-pill badge-light" style="font-size: 1.5rem; padding: 1rem;">
                                        <i class="fe fe-award text-danger"></i> #3
                                    </span>
                                </div>
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-white text-danger" style="font-size: 2rem;">
                                        <?= strtoupper(substr($ranking[2]->nama_cs, 0, 1)) ?>
                                    </span>
                                </div>
                                <h5 class="mb-1"><?= htmlspecialchars($ranking[2]->nama_cs) ?></h5>
                                <p class="mb-2"><span class="badge badge-light"><?= htmlspecialchars($ranking[2]->nip) ?></span></p>
                                <p class="mb-2 small"><?= htmlspecialchars($ranking[2]->nama_tim ?? '-') ?></p>
                                <h3 class="mb-0 font-weight-bold"><?= number_format($ranking[2]->skor_akhir, 4) ?></h3>
                                <small>Skor Akhir</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Full Ranking Table -->
                <div class="table-responsive">
                    <table id="dataTable-1" class="table table-hover table-borderless">
                        <thead >
                            <tr>
                                <th width="5%">Rank</th>
                                <th>NIP</th>
                                <th>Nama CS</th>
                                <th>Tim</th>
                                <th>Leader</th>
                                <th>NCF (60%)</th>
                                <th>NSF (40%)</th>
                                <th>Skor Akhir</th>
                                <th>Status</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ranking)): ?>
                                <?php foreach ($ranking as $index => $rank): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index == 0): ?>
                                                <span class="badge badge-warning badge-lg"><i class="fe fe-award"></i> <?= $index + 1 ?></span>
                                            <?php elseif ($index == 1): ?>
                                                <span class="badge badge-secondary badge-lg"><i class="fe fe-award"></i> <?= $index + 1 ?></span>
                                            <?php elseif ($index == 2): ?>
                                                <span class="badge badge-danger badge-lg"><i class="fe fe-award"></i> <?= $index + 1 ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-light badge-lg"><?= $index + 1 ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($rank->nip) ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        <?= strtoupper(substr($rank->nama_cs, 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <strong><?= htmlspecialchars($rank->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info"><?= htmlspecialchars($rank->nama_tim ?? '-') ?></span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($rank->nama_leader ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-danger" style="font-size: 0.85rem;">
                                                <?= number_format($rank->ncf ?? 0, 3) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-primary" style="font-size: 0.85rem;">
                                                <?= number_format($rank->nsf ?? 0, 3) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <?php 
                                                $max_score = !empty($ranking) ? $ranking[0]->skor_akhir : 1;
                                                $percentage = ($rank->skor_akhir / $max_score) * 100;
                                                ?>
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?= $percentage ?>%;" 
                                                     aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <strong><?= number_format($rank->skor_akhir, 4) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($index < 10): ?>
                                                <span class="badge badge-success">Top Performer</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Good</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#modalDetail"
                                                    data-id="<?= $rank->id_cs ?>"
                                                    title="Detail Nilai">
                                                <i class="fe fe-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fe fe-inbox fe-24 mb-3"></i>
                                        <p>Belum ada data ranking untuk periode ini</p>
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalProcess">
                                            <i class="fe fe-refresh-cw"></i> Proses Ranking Sekarang
                                        </button>
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

<!-- Modal Proses Ranking -->
<div class="modal fade" id="modalProcess" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fe fe-refresh-cw"></i> Proses Ranking</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/ranking/process') ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="periode_ranking">Periode <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" id="periode_ranking" name="periode" 
                           value="<?= date('Y-m') ?>" required>
                </div>
                <div class="alert alert-info">
                    <i class="fe fe-info"></i> <strong>Proses ranking menggunakan metode Profile Matching:</strong>
                    <ul class="mb-0 mt-2 small">
                        <li><strong>GAP:</strong> Selisih nilai aktual dengan nilai target per sub kriteria</li>
                        <li><strong>Mapping GAP:</strong> Konversi GAP ke nilai range sesuai tabel range dan konversi</li>
                        <li><strong>NCF (Core Factor):</strong> Rata-rata nilai konversi kriteria core_factor (60%)</li>
                        <li><strong>NSF (Secondary Factor):</strong> Rata-rata nilai konversi kriteria secondary_factor (40%)</li>
                        <li><strong>Skor Akhir:</strong> (NCF × 0.6) + (NSF × 0.4)</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-refresh-cw"></i> Proses Sekarang
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Modal Detail Nilai -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fe fe-eye"></i> Detail Nilai & Perhitungan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

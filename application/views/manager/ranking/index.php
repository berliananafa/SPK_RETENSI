<!-- Ranking Results -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Hasil Ranking Customer Service</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Periode:</label>
                        <select class="form-control" id="filterPeriode">
                            <option value="">-- Semua Periode --</option>
                            <?php if (!empty($periods)): ?>
                                <?php foreach ($periods as $period): ?>
                                    <option value="<?= $period->periode ?>"><?= $period->periode ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless datatable">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>NIK</th>
                                <th>Nama CS</th>
                                <th>Tim</th>
                                <th>Produk</th>
                                <th>Nilai Akhir</th>
                                <th>Periode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rankings)): ?>
                                <?php foreach ($rankings as $ranking): ?>
                                    <tr>
                                        <td>
                                            <?php if ($ranking->peringkat <= 3): ?>
                                                <span class="badge badge-warning badge-lg">
                                                    <i class="fe fe-award"></i> #<?= $ranking->peringkat ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-secondary">#<?= $ranking->peringkat ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($ranking->nik) ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        <?= strtoupper(substr($ranking->nama_cs, 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <strong><?= htmlspecialchars($ranking->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($ranking->nama_tim) ?></td>
                                        <td><?= htmlspecialchars($ranking->nama_produk) ?></td>
                                        <td><span class="badge badge-success badge-lg"><?= number_format($ranking->nilai_akhir, 2) ?></span></td>
                                        <td><?= $ranking->periode ?></td>
                                        <td>
                                            <?php if ($ranking->status === 'published'): ?>
                                                <span class="badge badge-success">Published</span>
                                            <?php elseif ($ranking->status === 'draft'): ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Archived</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data ranking</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leader Detail -->
<div class="row">
    <!-- Leader Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-body">
                <!-- Leader Header -->
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl mb-3 mx-auto" style="width: 100px; height: 100px;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($leader->nama_pengguna) ?>&background=4e73df&color=fff&size=128" 
                             alt="<?= htmlspecialchars($leader->nama_pengguna) ?>"
                             class="avatar-img rounded-circle">
                    </div>
                    <h4 class="mb-2 font-weight-bold"><?= htmlspecialchars($leader->nama_pengguna) ?></h4>
                    <p class="text-muted mb-0">
                        <i class="fe fe-tag mr-1"></i><?= htmlspecialchars($leader->nik) ?>
                    </p>
                </div>

                <!-- Leader Info -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted mb-3">Informasi Leader</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong><?= htmlspecialchars($leader->email) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tim</small>
                        <span class="badge badge-soft-success">
                            <i class="fe fe-users mr-1"></i><?= htmlspecialchars($leader->nama_tim) ?>
                        </span>
                    </div>
                </div>

                <!-- Team Stats -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted mb-3">Statistik Tim</h6>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded border">
                                <h2 class="mb-0 text-dark"><?= count($cs_list) ?></h2>
                                <small class="text-muted">Total CS</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded border">
                                <?php 
                                    $totalPenilaian = 0;
                                    foreach ($cs_list as $cs) {
                                        $totalPenilaian += $cs->total_penilaian;
                                    }
                                ?>
                                <h2 class="mb-0 text-dark"><?= $totalPenilaian ?></h2>
                                <small class="text-muted">Penilaian</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Indicator -->
                <?php 
                    $avgPenilaian = count($cs_list) > 0 ? round($totalPenilaian / count($cs_list), 1) : 0;
                    $performanceClass = $avgPenilaian >= 5 ? 'success' : ($avgPenilaian >= 3 ? 'info' : 'warning');
                    $performanceLevel = $avgPenilaian >= 5 ? 'Sangat Aktif' : ($avgPenilaian >= 3 ? 'Aktif' : 'Perlu Perhatian');
                ?>
                <div>
                    <h6 class="text-uppercase text-muted mb-2">Kinerja Penilaian</h6>
                    <div class="text-center p-3 bg-light rounded">
                        <h3 class="mb-1 text-<?= $performanceClass ?>"><?= $avgPenilaian ?></h3>
                        <small class="text-muted">Penilaian per CS</small>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-<?= $performanceClass ?>" 
                                 style="width: <?= min($avgPenilaian * 15, 100) ?>%"></div>
                        </div>
                        <span class="badge badge-<?= $performanceClass ?> mt-2"><?= $performanceLevel ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <!-- CS Members List -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title">Anggota Customer Service</strong>
                <span class="badge badge-soft-primary badge-pill"><?= count($cs_list) ?> CS</span>
            </div>
            <div class="card-body">
                <?php if (!empty($cs_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable-1">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">NIK</th>
                                    <th width="20%">Nama CS</th>
                                    <th width="15%">Produk</th>
                                    <th width="15%">Kanal</th>
                                    <th width="10%" class="text-center">Penilaian</th>
                                    <th width="10%" class="text-center">Ranking</th>
                                    <th width="13%" class="text-center">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cs_list as $index => $cs): ?>
                                    <?php
                                        $performanceCS = $cs->total_penilaian >= 5 ? 'success' : ($cs->total_penilaian >= 3 ? 'info' : 'secondary');

                                        // Determine ranking badge color
                                        $rankBadge = 'secondary';
                                        if (!empty($cs->peringkat)) {
                                            if ($cs->peringkat <= 3) {
                                                $rankBadge = 'warning';
                                            } elseif ($cs->peringkat <= 10) {
                                                $rankBadge = 'success';
                                            } else {
                                                $rankBadge = 'info';
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($cs->nik) ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2" style="width: 32px; height: 32px;">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=<?= $performanceCS == 'success' ? '1cc88a' : ($performanceCS == 'info' ? '36b9cc' : '858796') ?>&color=fff&size=32"
                                                         alt="<?= htmlspecialchars($cs->nama_cs) ?>"
                                                         class="avatar-img rounded-circle">
                                                </div>
                                                <strong><?= htmlspecialchars($cs->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fe fe-package mr-1"></i><?= htmlspecialchars($cs->nama_produk) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fe fe-message-circle mr-1"></i><?= htmlspecialchars($cs->nama_kanal) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-<?= $performanceCS ?>"><?= $cs->total_penilaian ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($cs->peringkat)): ?>
                                                <span class="badge badge-<?= $rankBadge ?>">
                                                    #<?= $cs->peringkat ?>
                                                </span>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($cs->nilai_akhir)): ?>
                                                <span class="badge badge-light">
                                                    <i class="fe fe-star text-warning"></i>
                                                    <?= number_format($cs->nilai_akhir, 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fe fe-user-x text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada customer service</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Team Detail -->
<div class="row">
    <!-- Team Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-body">
                <!-- Team Header -->
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl mb-3 mx-auto" style="width: 100px; height: 100px;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->nama_tim) ?>&background=1cc88a&color=fff&size=128" 
                             alt="<?= htmlspecialchars($team->nama_tim) ?>"
                             class="avatar-img rounded-circle">
                    </div>
                    <h4 class="mb-2 font-weight-bold"><?= htmlspecialchars($team->nama_tim) ?></h4>
                </div>

                <!-- Team Leadership -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted mb-3">Kepemimpinan</h6>
                    <div class="d-flex align-items-center p-2 bg-light rounded">
                        <div class="avatar avatar-md mr-3" style="width: 48px; height: 48px;">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->leader_name ?? 'N/A') ?>&background=4e73df&color=fff&size=48" 
                                 alt="<?= htmlspecialchars($team->leader_name ?? 'Belum ditentukan') ?>"
                                 class="avatar-img rounded-circle">
                        </div>
                        <div>
                            <small class="text-muted d-block">Leader</small>
                            <strong><?= htmlspecialchars($team->leader_name ?? 'Belum ditentukan') ?></strong>
                        </div>
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
                    <div class="row">
                        <?php foreach ($cs_list as $cs): ?>
                            <?php 
                                $performanceCS = $cs->total_penilaian >= 5 ? 'success' : ($cs->total_penilaian >= 3 ? 'info' : 'secondary');
                            ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2" style="width: 32px; height: 32px;">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=<?= $performanceCS == 'success' ? '1cc88a' : ($performanceCS == 'info' ? '36b9cc' : '858796') ?>&color=fff&size=32" 
                                                         alt="<?= htmlspecialchars($cs->nama_cs) ?>"
                                                         class="avatar-img rounded-circle">
                                                </div>
                                                <div>
                                                    <strong class="d-block"><?= htmlspecialchars($cs->nama_cs) ?></strong>
                                                    <small class="text-muted"><?= htmlspecialchars($cs->nik) ?></small>
                                                </div>
                                            </div>
                                            <span class="badge badge-<?= $performanceCS ?>"><?= $cs->total_penilaian ?></span>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">
                                                <i class="fe fe-package mr-1"></i><?= htmlspecialchars($cs->nama_produk) ?>
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fe fe-message-circle mr-1"></i><?= htmlspecialchars($cs->nama_kanal) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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

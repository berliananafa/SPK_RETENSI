<!-- Dashboard Supervisor -->
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-primary" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Leader</span>
                        <span class="h3 font-weight-bold mb-0 text-primary"><?= number_format($total_leader) ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-user-check text-primary mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-success" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Tim</span>
                        <span class="h3 font-weight-bold mb-0 text-success"><?= number_format($total_tim) ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-users text-success mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-info" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Customer Service</span>
                        <span class="h3 font-weight-bold mb-0 text-info"><?= number_format($total_cs) ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-user text-info mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-warning" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Penilaian</span>
                        <span class="h3 font-weight-bold mb-0 text-warning"><?= number_format($total_penilaian) ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-edit text-warning mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team List & CS Performance -->
<div class="row">
    <div class="col-md-12 col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Daftar Tim</strong>
                <span class="small ml-2" style="opacity: 0.9;">Tim yang berada di bawah tanggung jawab Anda</span>
            </div>
            <div class="card-body">
                <?php if (!empty($teams)): ?>
                    <div class="row">
                        <?php foreach (array_slice($teams, 0, 4) as $team): ?>
                            <div class="col-12 mb-3">
                                <div class="card border-0" style="border-left: 3px solid #1cc88a !important;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="avatar avatar-md" style="width: 48px; height: 48px;">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->nama_tim) ?>&background=1cc88a&color=fff&size=48" 
                                                         alt="<?= htmlspecialchars($team->nama_tim) ?>"
                                                         class="avatar-img rounded-circle">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <small><strong><?= htmlspecialchars($team->nama_tim) ?></strong></small>
                                                <div class="my-0 text-muted small">
                                                    <i class="fe fe-user mr-1"></i><?= htmlspecialchars($team->leader_name) ?>
                                                </div>
                                            </div>
                                            <div class="col-auto text-right">
                                                <span class="badge badge-soft-info d-block"><?= $team->total_cs ?> CS</span>
                                            </div>
                                            <div class="col-auto">
                                                <a href="<?= base_url('supervisor/team/detail/' . $team->id_tim) ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-2">
                        <a href="<?= base_url('supervisor/team') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fe fe-eye"></i> Lihat Semua Tim
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-users fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Tim</h5>
                        <p class="text-muted">Tim akan ditampilkan di sini setelah ditambahkan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CS Performance Statistics -->
    <div class="col-md-12 col-lg-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Top 10 Performa CS</strong>
                <span class="small ml-2" style="opacity: 0.9;">Berdasarkan rata-rata nilai penilaian</span>
            </div>
            <div class="card-body">
                <?php if (!empty($cs_performance)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead >
                                <tr>
                                    <th>#</th>
                                    <th>CS</th>
                                    <th>Tim</th>
                                    <th>Penilaian</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cs_performance as $index => $cs): ?>
                                    <?php 
                                        $avgScore = floatval($cs->rata_rata_nilai);
                                        $performanceClass = $avgScore >= 4 ? 'success' : ($avgScore >= 3 ? 'info' : 'secondary');
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-<?= $performanceClass ?>">#<?= $index + 1 ?></span>
                                        </td>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar avatar-xs" style="width: 28px; height: 28px;">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=<?= $performanceClass == 'success' ? '1cc88a' : ($performanceClass == 'info' ? '36b9cc' : '858796') ?>&color=fff&size=28" 
                                                             alt="<?= htmlspecialchars($cs->nama_cs) ?>"
                                                             class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="col pl-0">
                                                    <small><strong><?= htmlspecialchars($cs->nama_cs) ?></strong></small>
                                                    <div class="my-0 text-muted" style="font-size: 10px;"><?= htmlspecialchars($cs->nik) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($cs->nama_tim) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-light"><?= $cs->total_penilaian ?>x</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $performanceClass ?>"><?= number_format($avgScore, 2) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-bar-chart fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Performa</h5>
                        <p class="text-muted">Data performa CS akan ditampilkan setelah ada penilaian</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Evaluations -->
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Penilaian Terbaru</strong>
                <span class="small ml-2" style="opacity: 0.9;">10 penilaian terakhir dari tim Anda</span>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_nilai)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead >
                                <tr>
                                    <th>Customer Service</th>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_nilai as $nilai): ?>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar avatar-xs" style="width: 32px; height: 32px;">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($nilai->nama_cs) ?>&background=858796&color=fff&size=32" 
                                                             alt="<?= htmlspecialchars($nilai->nama_cs) ?>"
                                                             class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="col pl-0">
                                                    <small><strong><?= htmlspecialchars($nilai->nama_cs) ?></strong></small>
                                                    <div class="my-0 text-muted small"><?= htmlspecialchars($nilai->nik) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small><strong><?= htmlspecialchars($nilai->nama_kriteria) ?></strong></small>
                                            <div class="my-0 text-muted small"><?= htmlspecialchars($nilai->nama_sub_kriteria) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge badge-success"><?= number_format($nilai->nilai, 2) ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-clock mr-1"></i><?= date('d M Y H:i', strtotime($nilai->created_at)) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-edit fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Penilaian</h5>
                        <p class="text-muted">Data penilaian akan ditampilkan di sini setelah proses evaluasi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

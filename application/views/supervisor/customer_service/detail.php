<div class="row justify-content-center">
    <div class="col-12">
        <div class="mb-4 row align-items-center">
            <div class="col">
                <h2 class="mb-2 page-title">Detail Customer Service</h2>
                <p class="text-muted card-text">Informasi lengkap dan histori penilaian customer service</p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('supervisor/customer-service') ?>" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <!-- CS Profile Card -->
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&size=128&background=random" 
                                 alt="Avatar" class="avatar-img rounded-circle">
                        </div>
                        <h4 class="mb-1 card-title"><?= htmlspecialchars($cs->nama_cs) ?></h4>
                        <p class="mb-3 text-muted small">NIK: <?= htmlspecialchars($cs->nik) ?></p>
                        
                        <div class="mb-4">
                            <span class="badge badge-soft-primary"><?= htmlspecialchars($cs->nama_produk) ?></span>
                            <span class="badge badge-soft-info"><?= htmlspecialchars($cs->nama_kanal) ?></span>
                        </div>

                        <hr>

                        <div class="text-left">
                            <div class="mb-2 row">
                                <div class="col-5">
                                    <strong class="small">Tim:</strong>
                                </div>
                                <div class="col-7">
                                    <span class="small"><?= htmlspecialchars($cs->nama_tim) ?></span>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5">
                                    <strong class="small">Total Penilaian:</strong>
                                </div>
                                <div class="col-7">
                                    <span class="badge badge-soft-success"><?= $stats->total_penilaian ?></span>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5">
                                    <strong class="small">Rata-rata Nilai:</strong>
                                </div>
                                <div class="col-7">
                                    <span class="badge badge-soft-warning"><?= number_format($stats->rata_rata_nilai, 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="mt-3 card shadow">
                    <div class="card-body">
                        <h5 class="mb-3 card-title">Statistik Penilaian</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Nilai Tertinggi</span>
                                <strong class="text-success"><?= number_format($stats->nilai_max, 2) ?></strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?= ($stats->nilai_max > 0) ? ($stats->nilai_max / 100 * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Nilai Rata-rata</span>
                                <strong class="text-warning"><?= number_format($stats->rata_rata_nilai, 2) ?></strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: <?= ($stats->rata_rata_nilai > 0) ? ($stats->rata_rata_nilai / 100 * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Nilai Terendah</span>
                                <strong class="text-danger"><?= number_format($stats->nilai_min, 2) ?></strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: <?= ($stats->nilai_min > 0) ? ($stats->nilai_min / 100 * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation History -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Histori Penilaian</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="evaluationTable">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Tanggal</th>
                                        <th>Kriteria</th>
                                        <th>Sub Kriteria</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($evaluations)): ?>
                                        <?php foreach ($evaluations as $index => $eval): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= date('d M Y', strtotime($eval->tanggal)) ?></td>
                                                <td><?= htmlspecialchars($eval->nama_kriteria) ?></td>
                                                <td><?= htmlspecialchars($eval->nama_sub_kriteria) ?></td>
                                                <td>
                                                    <?php
                                                    $badgeClass = 'badge-soft-secondary';
                                                    if ($eval->nilai >= 80) {
                                                        $badgeClass = 'badge-soft-success';
                                                    } elseif ($eval->nilai >= 60) {
                                                        $badgeClass = 'badge-soft-primary';
                                                    } elseif ($eval->nilai >= 40) {
                                                        $badgeClass = 'badge-soft-warning';
                                                    } else {
                                                        $badgeClass = 'badge-soft-danger';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>"><?= number_format($eval->nilai, 2) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="py-4 text-muted">
                                                    <i class="fe fe-inbox fe-24 mb-2"></i>
                                                    <p>Belum ada histori penilaian</p>
                                                </div>
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

<script>
$(document).ready(function() {
    $('#evaluationTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, "desc"]],
        "pageLength": 10
    });
});
</script>

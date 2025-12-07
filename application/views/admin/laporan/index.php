<!-- Laporan Performa CS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-gradient-info text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="fe fe-file-text"></i> Laporan Performa Customer Service</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Profile Matching -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info mb-0" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fe fe-info fe-24 mr-3"></i>
                                <div>
                                    <strong>Laporan ini menggunakan metode Profile Matching</strong><br>
                                    <small>Penilaian berdasarkan GAP (selisih nilai aktual dengan target sub kriteria), 
                                    konversi melalui tabel range dan konversi, 
                                    NCF (Core Factor - 60%), dan NSF (Secondary Factor - 40%). 
                                    Semakin kecil GAP dan semakin tinggi NCF/NSF, semakin baik performanya.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="filterPeriodeAwal" class="font-weight-bold">Periode Awal:</label>
                        <input type="month" class="form-control" id="filterPeriodeAwal" 
                               value="<?= date('Y-m', strtotime('-3 months')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterPeriodeAkhir" class="font-weight-bold">Periode Akhir:</label>
                        <input type="month" class="form-control" id="filterPeriodeAkhir" 
                               value="<?= date('Y-m') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterTimLaporan" class="font-weight-bold">Tim:</label>
                        <select class="form-control" id="filterTimLaporan">
                            <option value="">-- Semua Tim --</option>
                            <?php if (!empty($tim)): ?>
                                <?php foreach ($tim as $t): ?>
                                    <option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold d-block">&nbsp;</label>
                        <button class="btn btn-info btn-block" id="btnGenerate">
                            <i class="fe fe-refresh-cw"></i> Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                            Total CS Aktif
                                        </div>
                                        <div class="h4 mb-0 font-weight-bold">
                                            <?= isset($total_cs_aktif) ? $total_cs_aktif : 0 ?>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fe fe-users fe-32 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                            Rata-rata Skor
                                        </div>
                                        <div class="h4 mb-0 font-weight-bold">
                                            <?= isset($avg_score) ? number_format($avg_score, 2) : '0.00' ?>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fe fe-trending-up fe-32 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                            Top Performer
                                        </div>
                                        <div class="h4 mb-0 font-weight-bold">
                                            <?= isset($top_performer_count) ? $top_performer_count : 0 ?>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fe fe-award fe-32 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                            Perlu Improvement
                                        </div>
                                        <div class="h4 mb-0 font-weight-bold">
                                            <?= isset($need_improvement) ? $need_improvement : 0 ?>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fe fe-alert-triangle fe-32 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <strong><i class="fe fe-trending-up"></i> Trend Performa Per Periode</strong>
                            </div>
                            <div class="card-body">
                                <canvas id="chartPerformaTrend" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <strong><i class="fe fe-pie-chart"></i> Distribusi Kategori</strong>
                            </div>
                            <div class="card-body">
                                <canvas id="chartKategori"></canvas>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-success"><i class="fe fe-circle"></i> Excellent</span>
                                        <strong><?= isset($kategori['excellent']) ? $kategori['excellent'] : 0 ?>%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-info"><i class="fe fe-circle"></i> Good</span>
                                        <strong><?= isset($kategori['good']) ? $kategori['good'] : 0 ?>%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-warning"><i class="fe fe-circle"></i> Average</span>
                                        <strong><?= isset($kategori['average']) ? $kategori['average'] : 0 ?>%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-danger"><i class="fe fe-circle"></i> Poor</span>
                                        <strong><?= isset($kategori['poor']) ? $kategori['poor'] : 0 ?>%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance by Criteria -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <strong><i class="fe fe-bar-chart-2"></i> Performa Berdasarkan Kriteria</strong>
                            </div>
                            <div class="card-body">
                                <canvas id="chartPerKriteria" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top & Bottom Performers -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-left-success">
                            <div class="card-header bg-light">
                                <strong class="text-success"><i class="fe fe-trending-up"></i> Top 10 Performers</strong>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead >
                                            <tr>
                                                <th width="10%">Rank</th>
                                                <th>Nama CS</th>
                                                <th>Tim</th>
                                                <th>Avg Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($top_performers)): ?>
                                                <?php foreach ($top_performers as $index => $performer): ?>
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-success"><?= $index + 1 ?></span>
                                                        </td>
                                                        <td>
                                                            <small><strong><?= htmlspecialchars($performer->nama_cs) ?></strong></small>
                                                        </td>
                                                        <td>
                                                            <small><?= htmlspecialchars($performer->nama_tim) ?></small>
                                                        </td>
                                                        <td>
                                                            <strong><?= number_format($performer->avg_skor, 2) ?></strong>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">
                                                        Tidak ada data
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-left-danger">
                            <div class="card-header bg-light">
                                <strong class="text-danger"><i class="fe fe-trending-down"></i> Needs Improvement</strong>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead >
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama CS</th>
                                                <th>Tim</th>
                                                <th>Avg Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($bottom_performers)): ?>
                                                <?php foreach ($bottom_performers as $index => $performer): ?>
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-danger"><?= $index + 1 ?></span>
                                                        </td>
                                                        <td>
                                                            <small><strong><?= htmlspecialchars($performer->nama_cs) ?></strong></small>
                                                        </td>
                                                        <td>
                                                            <small><?= htmlspecialchars($performer->nama_tim) ?></small>
                                                        </td>
                                                        <td>
                                                            <strong><?= number_format($performer->avg_skor, 2) ?></strong>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">
                                                        Tidak ada data
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

                <!-- Export Buttons -->
                <div class="row">
                    <div class="col-12 text-center">
                        <hr class="my-4">
                        <button class="btn btn-success btn-lg" onclick="exportLaporanExcel()">
                            <i class="fe fe-download"></i> Export to Excel
                        </button>
                        <button class="btn btn-danger btn-lg" onclick="exportLaporanPDF()">
                            <i class="fe fe-file"></i> Export to PDF
                        </button>
                        <button class="btn btn-info btn-lg" onclick="window.print()">
                            <i class="fe fe-printer"></i> Print Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart configurations will be initialized when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart
    if (document.getElementById('chartPerformaTrend')) {
        const ctxTrend = document.getElementById('chartPerformaTrend').getContext('2d');
        const chartTrend = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: <?= json_encode($periode_labels ?? []) ?>,
                datasets: [{
                    label: 'Rata-rata Skor Akhir (NCF×60% + NSF×40%)',
                    data: <?= json_encode($trend_data ?? []) ?>,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // Kategori Chart
    if (document.getElementById('chartKategori')) {
        const ctxKategori = document.getElementById('chartKategori').getContext('2d');
        const chartKategori = new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: ['Excellent', 'Good', 'Average', 'Poor'],
                datasets: [{
                    data: [
                        <?= $kategori['excellent'] ?? 0 ?>,
                        <?= $kategori['good'] ?? 0 ?>,
                        <?= $kategori['average'] ?? 0 ?>,
                        <?= $kategori['poor'] ?? 0 ?>
                    ],
                    backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Per Kriteria Chart
    if (document.getElementById('chartPerKriteria')) {
        const ctxKriteria = document.getElementById('chartPerKriteria').getContext('2d');
        const chartKriteria = new Chart(ctxKriteria, {
            type: 'bar',
            data: {
                labels: <?= json_encode($kriteria_labels ?? []) ?>,
                datasets: [{
                    label: 'Rata-rata Bobot GAP (Profile Matching)',
                    data: <?= json_encode($kriteria_data ?? []) ?>,
                    backgroundColor: '#4e73df',
                    borderColor: '#4e73df',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});

function exportLaporanExcel() {
    const periode_awal = document.getElementById('filterPeriodeAwal').value;
    const periode_akhir = document.getElementById('filterPeriodeAkhir').value;
    const tim = document.getElementById('filterTimLaporan').value;
    
    window.location.href = `<?= base_url('admin/laporan/export-excel') ?>?periode_awal=${periode_awal}&periode_akhir=${periode_akhir}&id_tim=${tim}`;
}

function exportLaporanPDF() {
    const periode_awal = document.getElementById('filterPeriodeAwal').value;
    const periode_akhir = document.getElementById('filterPeriodeAkhir').value;
    const tim = document.getElementById('filterTimLaporan').value;
    
    window.open(`<?= base_url('admin/laporan/export-pdf') ?>?periode_awal=${periode_awal}&periode_akhir=${periode_akhir}&id_tim=${tim}`, '_blank');
}
</script>

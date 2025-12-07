<!-- Summary Cards Row -->
<div class="row mb-4">
    <!-- Total Users Card -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-primary" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Pengguna</span>
                        <span class="h3 font-weight-bold mb-0 text-primary"><?= isset($total_users) ? number_format($total_users) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-users text-primary mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total CS Card -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-success" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Customer Service</span>
                        <span class="h3 font-weight-bold mb-0 text-success"><?= isset($total_cs) ? number_format($total_cs) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-user-check text-success mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Kriteria Card -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-info" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Kriteria Penilaian</span>
                        <span class="h3 font-weight-bold mb-0 text-info"><?= isset($total_criteria) ? number_format($total_criteria) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-list text-info mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Rankings Card -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0 border-left-warning" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Ranking</span>
                        <span class="h3 font-weight-bold mb-0 text-warning"><?= isset($total_rankings) ? number_format($total_rankings) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-award text-warning mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Info Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0" style="border-left: 4px solid #6f42c1 !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Tim</span>
                        <span class="h3 font-weight-bold mb-0" style="color: #6f42c1;"><?= isset($total_teams) ? number_format($total_teams) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-briefcase mb-0" style="color: #6f42c1; opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0" style="border-left: 4px solid #e74a3b !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Produk</span>
                        <span class="h3 font-weight-bold mb-0 text-danger"><?= isset($total_produk) ? number_format($total_produk) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-package text-danger mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0" style="border-left: 4px solid #858796 !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Kanal</span>
                        <span class="h3 font-weight-bold mb-0 text-secondary"><?= isset($total_kanal) ? number_format($total_kanal) : '0' ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-message-circle text-secondary mb-0" style="opacity: 0.2;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="row">
    <!-- Quick Actions Card -->
    <div class="col-md-12 col-lg-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Akses Cepat</strong>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?= base_url('admin/customer-service') ?>" class="list-group-item list-group-item-action border-left-success" style="border-left: 3px solid #1cc88a !important;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-user-plus fe-24 text-success"></span>
                        </div>
                        <div class="col">
                            <small><strong>Kelola Customer Service</strong></small>
                            <div class="my-0 text-muted small">Tambah & edit data CS</div>
                        </div>
                        <div class="col-auto">
                            <span class="fe fe-chevron-right fe-16 text-muted"></span>
                        </div>
                    </div>
                </a>
                <a href="<?= base_url('admin/kriteria') ?>" class="list-group-item list-group-item-action border-left-info" style="border-left: 3px solid #36b9cc !important;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-sliders fe-24 text-info"></span>
                        </div>
                        <div class="col">
                            <small><strong>Kelola Kriteria</strong></small>
                            <div class="my-0 text-muted small">Atur kriteria penilaian</div>
                        </div>
                        <div class="col-auto">
                            <span class="fe fe-chevron-right fe-16 text-muted"></span>
                        </div>
                    </div>
                </a>
                <a href="<?= base_url('admin/nilai') ?>" class="list-group-item list-group-item-action" style="border-left: 3px solid #6f42c1 !important;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-edit fe-24" style="color: #6f42c1;"></span>
                        </div>
                        <div class="col">
                            <small><strong>Input Nilai</strong></small>
                            <div class="my-0 text-muted small">Masukkan nilai evaluasi</div>
                        </div>
                        <div class="col-auto">
                            <span class="fe fe-chevron-right fe-16 text-muted"></span>
                        </div>
                    </div>
                </a>
                <a href="<?= base_url('admin/ranking') ?>" class="list-group-item list-group-item-action border-left-warning" style="border-left: 3px solid #f6c23e !important;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-trending-up fe-24 text-warning"></span>
                        </div>
                        <div class="col">
                            <small><strong>Proses Ranking</strong></small>
                            <div class="my-0 text-muted small">Hitung ranking CS terbaik</div>
                        </div>
                        <div class="col-auto">
                            <span class="fe fe-chevron-right fe-16 text-muted"></span>
                        </div>
                    </div>
                </a>
                <a href="<?= base_url('admin/laporan') ?>" class="list-group-item list-group-item-action border-left-danger" style="border-left: 3px solid #e74a3b !important;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="fe fe-file-text fe-24 text-danger"></span>
                        </div>
                        <div class="col">
                            <small><strong>Laporan</strong></small>
                            <div class="my-0 text-muted small">Cetak & export laporan</div>
                        </div>
                        <div class="col-auto">
                            <span class="fe fe-chevron-right fe-16 text-muted"></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Overview Ranking CS Chart -->
    <div class="col-md-12 col-lg-8 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Overview Ranking Customer Service</strong>
                <?php if (!empty($current_periode)): ?>
                    <span class="small ml-2" style="opacity: 0.9;">(Periode: <?= date('F Y', strtotime($current_periode . '-01')) ?>)</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (!empty($top_cs)): ?>
                    <canvas id="csRankingChart" style="height: 400px;"></canvas>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-bar-chart-2 fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Ranking</h5>
                        <p class="text-muted">Data ranking akan ditampilkan setelah proses penilaian selesai</p>
                        <a href="<?= base_url('admin/ranking') ?>" class="btn btn-primary mt-2">
                            <i class="fe fe-plus"></i> Proses Ranking
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Profile Matching Method Info -->
<!-- <div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header">
                <strong class="card-title">Tentang Metode Profile Matching</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-3">
                            <strong>Profile Matching</strong> adalah metode yang digunakan untuk menentukan kandidat terbaik 
                            dengan membandingkan kompetensi individu ke dalam kompetensi yang dibutuhkan. 
                        </p>
                        <p class="mb-3">
                            Metode ini menggunakan konsep <strong>GAP</strong> (perbedaan) antara nilai profil ideal dengan 
                            nilai profil aktual dari kandidat. Semakin kecil gap-nya, semakin besar peluang kandidat untuk 
                            terpilih.
                        </p>
                        <h6 class="mb-2">Tahapan Perhitungan:</h6>
                        <ol class="mb-0">
                            <li>Pemetaan Gap Kompetensi</li>
                            <li>Pembobotan Gap</li>
                            <li>Penghitungan Core Factor & Secondary Factor</li>
                            <li>Penghitungan Nilai Total</li>
                            <li>Perankingan Kandidat</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Formula Perhitungan</h6>
                                <hr>
                                <p class="small mb-2"><strong>Core Factor:</strong></p>
                                <p class="small text-muted mb-3">NCF = ΣNC / ΣIC</p>
                                
                                <p class="small mb-2"><strong>Secondary Factor:</strong></p>
                                <p class="small text-muted mb-3">NSF = ΣNS / ΣIS</p>
                                
                                <p class="small mb-2"><strong>Nilai Total:</strong></p>
                                <p class="small text-muted mb-0">N = (x)% NCF + (y)% NSF</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

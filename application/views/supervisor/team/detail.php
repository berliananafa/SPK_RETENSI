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
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <!-- CS Members List -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong class="card-title">Anggota Customer Service</strong>
                    <?php if (!empty($selected_periode)): ?>
                        <span class="badge badge-soft-primary ml-2">Periode <?= htmlspecialchars($selected_periode) ?></span>
                    <?php endif; ?>
                </div>
                <span class="badge badge-soft-primary badge-pill"><?= count($cs_list) ?> CS</span>
            </div>
            <div class="card-body">
                <?php if (!empty($cs_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="dataTable-1">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIK</th>
                                    <th>Nama CS</th>
                                    <th>Produk</th>
                                    <th>Kanal</th>
                                    <th>Total Penilaian</th>
                                    <th>Ranking</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cs_list as $index => $cs): ?>
                                    <?php $csRanking = isset($rankings_by_cs[$cs->id_cs]) ? $rankings_by_cs[$cs->id_cs] : null; ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($cs->nik) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>" 
                                                         alt="Avatar" class="avatar-img rounded-circle">
                                                </div>
                                                <strong><?= htmlspecialchars($cs->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($cs->nama_produk) ?></span></td>
                                        <td><span class="badge badge-soft-info"><?= htmlspecialchars($cs->nama_kanal) ?></span></td>
                                        <td><span class="badge badge-soft-success"><?= (int)$cs->total_penilaian ?></span></td>
                                        <td>
                                            <?php if (!empty($csRanking)): ?>
                                                <span class="badge badge-primary">#<?= (int)$csRanking->peringkat ?></span>
                                                <span class="badge badge-soft-success"><?= number_format($csRanking->nilai_akhir, 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('supervisor/customer-service/detail/' . $cs->id_cs) ?>" 
                                               class="btn btn-sm btn-primary" title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
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

<!-- Supervisor Detail -->
<div class="row">
    <!-- Supervisor Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mb-3 mx-auto" style="width: 100px; height: 100px;">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($supervisor->nama_pengguna) ?>&background=00cfe8&color=fff&size=128" 
                         alt="<?= htmlspecialchars($supervisor->nama_pengguna) ?>"
                         class="avatar-img rounded-circle">
                </div>
                <h4 class="mb-2 font-weight-bold"><?= htmlspecialchars($supervisor->nama_pengguna) ?></h4>
                <p class="text-muted mb-3">
                    <strong>NIK: <?= htmlspecialchars($supervisor->nik) ?></strong>
                </p>
                
                <!-- Contact Info -->
                <div class="text-left px-3 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fe fe-mail text-muted mr-2"></i>
                        <small><?= htmlspecialchars($supervisor->email) ?></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fe fe-briefcase text-muted mr-2"></i>
                        <small>Supervisor</small>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <h3 class="mb-0 text-dark"><?= count($teams) ?></h3>
                            <small class="text-muted">Total Tim</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded border text-center">
                            <?php 
                                $totalCS = 0;
                                foreach ($teams as $team) {
                                    $totalCS += $team->total_cs;
                                }
                            ?>
                            <h3 class="mb-0 text-dark"><?= $totalCS ?></h3>
                            <small class="text-muted">Total CS</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisor Scope Card -->
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Scope Tanggung Jawab</strong>
            </div>
            <div class="card-body">
                <?php if (!empty($scopes)): ?>
                    <?php foreach ($scopes as $scope): ?>
                        <div class="mb-2 p-2 bg-light rounded border">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">
                                        <i class="fe fe-package mr-1"></i>Produk
                                    </small>
                                    <span class="badge badge-soft-info"><?= htmlspecialchars($scope->nama_produk) ?></span>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fe fe-message-circle mr-1"></i>Kanal
                                    </small>
                                    <span class="badge badge-soft-secondary"><?= htmlspecialchars($scope->nama_kanal) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fe fe-target text-muted" style="font-size: 32px;"></i>
                        <p class="text-muted mt-2 mb-0 small">Scope belum ditentukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <!-- Teams Overview Card -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title">Tim yang Dikelola</strong>
                <span class="badge badge-soft-primary badge-pill"><?= count($teams) ?> Tim</span>
            </div>
            <div class="card-body">
                <?php if (!empty($teams)): ?>
                    <div class="row">
                        <?php foreach ($teams as $team): ?>
                            <?php 
                                $teamSize = $team->total_cs;
                                $sizeClass = $teamSize >= 5 ? 'success' : ($teamSize >= 3 ? 'warning' : 'secondary');
                                $sizeStatus = $teamSize >= 5 ? 'Optimal' : ($teamSize >= 3 ? 'Normal' : 'Minimal');
                            ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 font-weight-bold">
                                                <i class="fe fe-users text-muted mr-1"></i><?= htmlspecialchars($team->nama_tim) ?>
                                            </h6>
                                            <span class="badge badge-soft-info"><?= $team->total_cs ?> CS</span>
                                        </div>
                                        <small class="text-muted d-block mb-2">
                                            <i class="fe fe-star mr-1"></i>Leader: <strong><?= htmlspecialchars($team->leader_name ?? 'Belum ditentukan') ?></strong>
                                        </small>
                                        <span class="badge badge-<?= $sizeClass ?>"><?= $sizeStatus ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fe fe-users text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada tim yang dikelola</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- CS List Card -->
        <div class="card shadow mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title">Daftar Customer Service</strong>
                <span class="badge badge-soft-primary badge-pill"><?= count($cs_list) ?> CS</span>
            </div>
            <div class="card-body">
                <?php if (!empty($cs_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm  table-striped datatable" id="dataTable-1">
                            <thead >
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama CS</th>
                                    <th>Tim</th>
                                    <th>Produk</th>
                                    <th>Kanal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cs_list as $index => $cs): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><span class="badge badge-light"><?= htmlspecialchars($cs->nik) ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs mr-2" style="width: 24px; height: 24px;">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=858796&color=fff&size=24" 
                                                         alt="<?= htmlspecialchars($cs->nama_cs) ?>"
                                                         class="avatar-img rounded-circle">
                                                </div>
                                                <strong><?= htmlspecialchars($cs->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($cs->nama_tim) ?></td>
                                        <td><span class="badge badge-soft-info"><?= htmlspecialchars($cs->nama_produk) ?></span></td>
                                        <td><span class="badge badge-soft-secondary"><?= htmlspecialchars($cs->nama_kanal) ?></span></td>
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

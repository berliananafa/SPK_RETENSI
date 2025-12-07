<!-- Detail Supervisor -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Detail Supervisor</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/supervisor/edit/' . $supervisor->id_user) ?>" class="btn btn-warning btn-sm">
                            <i class="fe fe-edit"></i> Edit Data
                        </a>
                        <a href="<?= base_url('admin/supervisor') ?>" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($supervisor->nama_pengguna) ?>&background=00cfe8&color=fff&size=128" 
                                 alt="<?= htmlspecialchars($supervisor->nama_pengguna) ?>"
                                 class="avatar-img rounded-circle">
                        </div>
                        <h4 class="mb-1"><?= htmlspecialchars($supervisor->nama_pengguna) ?></h4>
                        <p class="text-muted">
                            <span class="badge badge-info">Supervisor</span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%" class="text-muted">NIK</td>
                                <td><strong><?= htmlspecialchars($supervisor->nik ?? '-') ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td><strong><?= htmlspecialchars($supervisor->email ?? '-') ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Level</td>
                                <td><span class="badge badge-soft-info"><?= ucwords(str_replace('_', ' ', $supervisor->level)) ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Junior Manager</td>
                                <td>
                                    <?php if (!empty($junior_manager)): ?>
                                        <span class="badge badge-soft-primary">
                                            <i class="fe fe-user"></i> <?= htmlspecialchars($junior_manager->nama_pengguna) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fe fe-briefcase"></i> Scope Pekerjaan</h5>
                
                <?php if (!empty($scopes)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead >
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kanal</th>
                                    <th>Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scopes as $index => $scope): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <span class="badge badge-soft-info">
                                                <?= htmlspecialchars($scope->nama_kanal) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success">
                                                <?= htmlspecialchars($scope->nama_produk) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fe fe-info"></i> Belum ada scope pekerjaan yang ditugaskan
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detail Tim -->
<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Informasi Tim</strong>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-lg mx-auto mb-3" style="position: relative;">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->nama_tim) ?>&background=007bff&color=fff&size=128" 
                             alt="<?= htmlspecialchars($team->nama_tim) ?>"
                             class="avatar-img rounded-circle">
                    </div>
                    <h5 class="card-title mb-2"><?= htmlspecialchars($team->nama_tim) ?></h5>
                    <span class="badge badge-primary badge-pill">
                        <i class="fe fe-users"></i> <?= $members_count ?> Anggota
                    </span>
                </div>

                <hr>

                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td class="text-muted" width="40%">
                                <i class="fe fe-user-check"></i> Leader
                            </td>
                            <td>
                                <?php if (!empty($team->nama_leader)): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->nama_leader) ?>&background=28a745&color=fff&size=64" 
                                                 alt="<?= htmlspecialchars($team->nama_leader) ?>"
                                                 class="avatar-img rounded-circle">
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($team->nama_leader) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($team->email_leader) ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">
                                <i class="fe fe-briefcase"></i> Supervisor
                            </td>
                            <td>
                                <?php if (!empty($team->nama_supervisor)): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($team->nama_supervisor) ?>&background=00cfe8&color=fff&size=64" 
                                                 alt="<?= htmlspecialchars($team->nama_supervisor) ?>"
                                                 class="avatar-img rounded-circle">
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($team->nama_supervisor) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($team->email_supervisor) ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                <div class="btn-group btn-block">
                    <a href="<?= base_url('admin/tim/edit/' . $team->id_tim) ?>" 
                       class="btn btn-warning btn-sm">
                        <i class="fe fe-edit"></i> Edit
                    </a>
                    <a href="<?= base_url('admin/tim') ?>" 
                       class="btn btn-secondary btn-sm">
                        <i class="fe fe-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Customer Service</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/customer-service/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Anggota
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($members)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($members as $index => $member): ?>
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member->nama_cs) ?>&background=6c5ce7&color=fff&size=64" 
                                                 alt="<?= htmlspecialchars($member->nama_cs) ?>"
                                                 class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <strong><?= htmlspecialchars($member->nama_cs) ?></strong>
                                            <span class="badge badge-soft-secondary ml-2"><?= htmlspecialchars($member->nik) ?></span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge badge-soft-info mr-1">
                                                <i class="fe fe-inbox"></i> <?= htmlspecialchars($member->nama_kanal) ?>
                                            </span>
                                            <span class="badge badge-soft-success">
                                                <i class="fe fe-package"></i> <?= htmlspecialchars($member->nama_produk) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?= base_url('admin/customer-service/detail/' . $member->id_cs) ?>" 
                                           class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fe fe-user-x fe-48 mb-3"></i>
                        <p>Belum ada anggota Customer Service di tim ini</p>
                        <a href="<?= base_url('admin/customer-service/create') ?>" class="btn btn-sm btn-primary">
                            <i class="fe fe-plus"></i> Tambah Customer Service
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

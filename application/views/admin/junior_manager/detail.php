<!-- Detail Junior Manager -->
<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Informasi Junior Manager</strong>
            </div>
            <div class="card-body text-center">
                <div class="avatar avatar-lg mb-3">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($junior_manager->nama_pengguna) ?>&background=6c5ce7&color=fff&size=128" 
                         alt="<?= htmlspecialchars($junior_manager->nama_pengguna) ?>"
                         class="avatar-img rounded-circle">
                </div>
                <h5 class="card-title mb-0"><?= htmlspecialchars($junior_manager->nama_pengguna) ?></h5>
                <p class="text-muted mb-3">
                    <span class="badge badge-primary">Junior Manager</span>
                </p>

                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td class="text-left"><strong>NIK:</strong></td>
                            <td class="text-right"><?= htmlspecialchars($junior_manager->nik) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left"><strong>Email:</strong></td>
                            <td class="text-right"><?= htmlspecialchars($junior_manager->email) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left"><strong>Level:</strong></td>
                            <td class="text-right">
                                <span class="badge badge-soft-primary">Junior Manager</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                <div class="btn-group btn-block">
                    <a href="<?= base_url('admin/junior-manager/edit/' . $junior_manager->id_user) ?>" 
                       class="btn btn-warning btn-sm">
                        <i class="fe fe-edit"></i> Edit
                    </a>
                    <a href="<?= base_url('admin/junior-manager') ?>" 
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
                        <strong class="card-title">Daftar Supervisor</strong>
                    </div>
                    <div class="col-auto">
                        <span class="badge badge-info badge-pill">
                            <i class="fe fe-users"></i> <?= count($supervisors) ?> Supervisor
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($supervisors)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($supervisors as $supervisor): ?>
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($supervisor->nama_pengguna) ?>&background=00cfe8&color=fff&size=64" 
                                                 alt="<?= htmlspecialchars($supervisor->nama_pengguna) ?>"
                                                 class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <strong><?= htmlspecialchars($supervisor->nama_pengguna) ?></strong>
                                            <span class="badge badge-soft-info ml-2"><?= htmlspecialchars($supervisor->nik) ?></span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fe fe-mail"></i> <?= htmlspecialchars($supervisor->email) ?>
                                        </small>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?= base_url('admin/supervisor/detail/' . $supervisor->id_user) ?>" 
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
                        <i class="fe fe-users fe-48 mb-3"></i>
                        <p>Belum ada supervisor yang ditugaskan</p>
                        <a href="<?= base_url('admin/supervisor/create') ?>" class="btn btn-sm btn-primary">
                            <i class="fe fe-plus"></i> Tambah Supervisor
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

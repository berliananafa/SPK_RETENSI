<!-- Detail Leader -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Detail Leader</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/leader/edit/' . $leader->id_user) ?>" class="btn btn-warning btn-sm">
                            <i class="fe fe-edit"></i> Edit Data
                        </a>
                        <a href="<?= base_url('admin/leader') ?>" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($leader->nama_pengguna) ?>&background=28a745&color=fff&size=128" 
                                 alt="<?= htmlspecialchars($leader->nama_pengguna) ?>"
                                 class="avatar-img rounded-circle">
                        </div>
                        <h4 class="mb-1"><?= htmlspecialchars($leader->nama_pengguna) ?></h4>
                        <p class="text-muted">
                            <span class="badge badge-success">Leader</span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%" class="text-muted">NIK</td>
                                <td><strong><?= htmlspecialchars($leader->nik ?? '-') ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td><strong><?= htmlspecialchars($leader->email ?? '-') ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Level</td>
                                <td><span class="badge badge-soft-success"><?= ucwords(str_replace('_', ' ', $leader->level)) ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Team Information -->
                <h5 class="mb-3">
                    <i class="fe fe-users"></i> Tim yang Dipimpin
                </h5>
                <?php if ($team): ?>
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td width="40%" class="text-muted">Nama Tim</td>
                                            <td><strong><?= htmlspecialchars($team->nama_tim) ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Supervisor</td>
                                            <td>
                                                <?php if (!empty($team->nama_supervisor)): ?>
                                                    <span class="badge badge-soft-primary"><?= htmlspecialchars($team->nama_supervisor) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fe fe-info mr-2"></i>
                        Leader ini belum memimpin tim manapun.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

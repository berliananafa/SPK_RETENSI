<!-- Supervisor List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Supervisor</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/supervisor/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Supervisor
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable-1" class="table table-hover table-borderless">
                    <thead >
                        <tr>
                            <th width="5%">No</th>
                            <th>NIK</th>
                            <th>Nama Supervisor</th>
                            <th>Email</th>
                            <th>Junior Manager</th>
                            <th>Scope (Kanal × Produk)</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($supervisors)): ?>
                            <?php foreach ($supervisors as $index => $supervisor): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge badge-soft-info"><?= htmlspecialchars($supervisor->nik ?? '-') ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($supervisor->nama_pengguna) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($supervisor->email ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($supervisor->nama_atasan)): ?>
                                            <span class="badge badge-soft-primary">
                                                <i class="fe fe-user"></i> <?= htmlspecialchars($supervisor->nama_atasan) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $scopes = $this->SupervisorScopeModel->getBySupervisor($supervisor->id_user);
                                        if (!empty($scopes)): 
                                        ?>
                                            <span class="badge badge-info badge-pill" title="Total kombinasi kanal × produk">
                                                <i class="fe fe-briefcase"></i> <?= count($scopes) ?> Scope
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Belum ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/supervisor/detail/' . $supervisor->id_user) ?>" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/supervisor/edit/' . $supervisor->id_user) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/supervisor/delete/' . $supervisor->id_user) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Supervisor?"
                                               data-text="Data Supervisor akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data Supervisor</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

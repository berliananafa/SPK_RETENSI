<!-- Junior Manager List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Junior Manager</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/junior-manager/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Junior Manager
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
                            <th>Nama Junior Manager</th>
                            <th>Email</th>
                            <th class="text-center">Jumlah Supervisor</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($junior_managers)): ?>
                            <?php foreach ($junior_managers as $index => $jm): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge badge-soft-primary"><?= htmlspecialchars($jm->nik ?? '-') ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($jm->nama_pengguna) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($jm->email ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if (isset($jm->jumlah_supervisor) && $jm->jumlah_supervisor > 0): ?>
                                            <a href="<?= base_url('admin/junior-manager/detail/' . $jm->id_user) ?>" 
                                               class="badge badge-info badge-pill" 
                                               title="Lihat detail">
                                                <i class="fe fe-users"></i> <?= $jm->jumlah_supervisor ?> Supervisor
                                            </a>
                                        <?php else: ?>
                                            <span class="badge badge-secondary badge-pill">
                                                <i class="fe fe-users"></i> 0 Supervisor
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/junior-manager/detail/' . $jm->id_user) ?>" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/junior-manager/edit/' . $jm->id_user) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/junior-manager/delete/' . $jm->id_user) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Junior Manager?"
                                               data-text="Data Junior Manager akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data Junior Manager</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

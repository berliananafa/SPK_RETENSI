<!-- Leader List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Leader</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/leader/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Leader
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
                            <th>Nama Leader</th>
                            <th>Tim</th>
                            <th>Email</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leaders)): ?>
                            <?php foreach ($leaders as $index => $leader): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge badge-soft-success"><?= htmlspecialchars($leader->nik ?? '-') ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($leader->nama_pengguna) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($leader->id_tim)): ?>
                                            <span class="badge badge-soft-info">
                                                <i class="fe fe-users"></i> <?= htmlspecialchars($leader->nama_tim) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><i>Belum ada tim</i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($leader->email ?? '-') ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/leader/detail/' . $leader->id_user) ?>" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/leader/edit/' . $leader->id_user) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/leader/delete/' . $leader->id_user) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Leader?"
                                               data-text="Data Leader akan dihapus permanen!"
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
                                    <p>Belum ada data Leader</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


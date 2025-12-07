<!-- Data Table Card -->
<div class="row my-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title mb-0">Daftar Pengguna</strong>
                <a href="<?= base_url('admin/pengguna/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fe fe-plus"></i> Tambah Pengguna
                </a>
            </div>
            <div class="card-body">
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php $no = 1; foreach ($users as $user): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($user->nik) ?></td>
                                <td><?= htmlspecialchars($user->nama_pengguna) ?></td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td>
                                    <?php
                                    $badge_class = 'badge-secondary';
                                    $level_text = ucwords(str_replace('_', ' ', $user->level));
                                    
                                    switch($user->level) {
                                        case 'admin':
                                            $badge_class = 'badge-danger';
                                            break;
                                        case 'junior_manager':
                                            $badge_class = 'badge-primary';
                                            break;
                                        case 'supervisor':
                                            $badge_class = 'badge-info';
                                            break;
                                        case 'leader':
                                            $badge_class = 'badge-success';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= $level_text ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/pengguna/edit/'.$user->id_user) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <span class="fe fe-edit"></span>
                                    </a>
                                    <a href="<?= base_url('admin/pengguna/delete/'.$user->id_user) ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       data-title="Hapus Pengguna?"
                                       data-text="Data pengguna <?= htmlspecialchars($user->nama_pengguna) ?> akan dihapus!"
                                       title="Hapus">
                                        <span class="fe fe-trash"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <div class="py-5">
                                        <span class="fe fe-inbox fe-32 mb-3 d-block"></span>
                                        <p>Belum ada data pengguna</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

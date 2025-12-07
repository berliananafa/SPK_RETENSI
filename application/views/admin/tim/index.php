<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title mb-0">Daftar Tim</strong>
                <a href="<?= base_url('admin/tim/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fe fe-plus"></i> Tambah Tim
                </a>
            </div>
            <div class="card-body">
                <!-- Table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Tim</th>
                            <th>Leader</th>
                            <th>Supervisor</th>
                            <th>Jumlah CS</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($teams)): ?>
                            <?php foreach ($teams as $index => $team): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= esc($team->nama_tim) ?></strong></td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fe fe-user"></i> <?= esc($team->nama_leader) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="fe fe-user-check"></i> <?= esc($team->nama_supervisor) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $this->load->model('TimModel','Tim');
                                        $count = $this->Tim->getMembersCount($team->id_tim);
                                        ?>
                                        <span class="badge badge-light"><?= $count ?> CS</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/tim/detail/' . $team->id_tim) ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/tim/edit/' . $team->id_tim) ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/tim/delete/' . $team->id_tim) ?>"
                                                class="btn btn-sm btn-danger btn-delete" 
                                                data-id="<?= $team->id_tim ?>"
                                                data-name="<?= esc($team->nama_tim) ?>"
                                                title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data tim</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

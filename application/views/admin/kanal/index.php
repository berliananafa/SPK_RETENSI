<!-- Data Table Card -->
<div class="row my-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title mb-0">Daftar Kanal</strong>
                <a href="<?= base_url('admin/kanal/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fe fe-plus"></i> Tambah Kanal
                </a>
            </div>
            <div class="card-body">
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kanal</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($channels)): ?>
                            <?php $no = 1; foreach ($channels as $channel): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($channel->nama_kanal) ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($channel->created_at)) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($channel->updated_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/kanal/edit/'.$channel->id_kanal) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <span class="fe fe-edit"></span>
                                    </a>
                                    <a href="<?= base_url('admin/kanal/delete/'.$channel->id_kanal) ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       data-title="Hapus Kanal?"
                                       data-text="Kanal <?= htmlspecialchars($channel->nama_kanal) ?> akan dihapus!"
                                       title="Hapus">
                                        <span class="fe fe-trash"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <div class="py-5">
                                        <span class="fe fe-message-circle fe-32 mb-3 d-block"></span>
                                        <p>Belum ada data kanal</p>
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

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title mb-0">Daftar Customer Service</strong>
                <a href="<?= base_url('admin/customer-service/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fe fe-plus"></i> Tambah CS
                </a>
            </div>
            <div class="card-body">
                <!-- Table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>NIK</th>
                            <th>Nama CS</th>
                            <th>Tim</th>
                            <th>Produk</th>
                            <th>Kanal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customer_services)): ?>
                            <?php foreach ($customer_services as $index => $cs): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><code><?= esc($cs->nik) ?></code></td>
                                    <td><strong><?= esc($cs->nama_cs) ?></strong></td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <?= esc($cs->nama_tim) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= esc($cs->nama_produk) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?= esc($cs->nama_kanal) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/customer-service/detail/' . $cs->id_cs) ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/customer-service/edit/' . $cs->id_cs) ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/customer-service/delete/' . $cs->id_cs) ?>" 
                                                    class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="<?= $cs->id_cs ?>"
                                                    data-name="<?= esc($cs->nama_cs) ?>"
                                                    title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data customer service</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


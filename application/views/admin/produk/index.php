<!-- Data Table Card -->
<div class="row my-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title mb-0">Daftar Produk</strong>
                <a href="<?= base_url('admin/produk/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fe fe-plus"></i> Tambah Produk
                </a>
            </div>
            <div class="card-body">
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php $no = 1; foreach ($products as $product): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($product->sku_produk) ?></strong></td>
                                <td><?= htmlspecialchars($product->nama_produk) ?></td>
                                <td>
                                    <?php if (!empty($product->deskripsi)): ?>
                                        <?= character_limiter(htmlspecialchars($product->deskripsi), 50) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($product->gambar)): ?>
                                        <img src="<?= base_url('uploads/produk/'.$product->gambar) ?>" 
                                             alt="<?= htmlspecialchars($product->nama_produk) ?>" 
                                             class="avatar-img rounded"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($product->created_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/produk/edit/'.$product->id_produk) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <span class="fe fe-edit"></span>
                                    </a>
                                    <a href="<?= base_url('admin/produk/delete/'.$product->id_produk) ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       data-title="Hapus Produk?"
                                       data-text="Produk <?= htmlspecialchars($product->nama_produk) ?> akan dihapus!"
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
                                        <span class="fe fe-package fe-32 mb-3 d-block"></span>
                                        <p>Belum ada data produk</p>
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

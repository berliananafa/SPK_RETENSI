<!-- Form Card -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <?php if (empty($product)): ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Data produk tidak ditemukan.
                    </div>
                    <a href="<?= base_url('admin/produk') ?>" class="btn btn-secondary">
                        <span class="fe fe-arrow-left fe-16 mr-2"></span>Kembali
                    </a>
                <?php else: ?>
                <h5 class="card-title mb-4">Form Edit Produk</h5>
                
                <form action="<?= base_url('admin/produk/update/'.$product->id_produk) ?>" method="POST" enctype="multipart/form-data" id="formProduk">
                    
                    <div class="form-group">
                        <label for="sku_produk">SKU Produk <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('sku_produk') ? 'is-invalid' : '' ?>" 
                               id="sku_produk" 
                               name="sku_produk" 
                               value="<?= set_value('sku_produk', $product->sku_produk) ?>" 
                               placeholder="Contoh: PRD-001"
                               required>
                        <?php if (form_error('sku_produk')): ?>
                            <div class="invalid-feedback"><?= form_error('sku_produk') ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">SKU harus unik untuk setiap produk</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nama_produk') ? 'is-invalid' : '' ?>" 
                               id="nama_produk" 
                               name="nama_produk" 
                               value="<?= set_value('nama_produk', $product->nama_produk) ?>" 
                               placeholder="Masukkan nama produk"
                               required>
                        <?php if (form_error('nama_produk')): ?>
                            <div class="invalid-feedback"><?= form_error('nama_produk') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4"
                                  placeholder="Deskripsi produk (opsional)"><?= set_value('deskripsi', $product->deskripsi) ?></textarea>
                        <?php if (form_error('deskripsi')): ?>
                            <div class="invalid-feedback"><?= form_error('deskripsi') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar Produk</label>
                        <?php if (!empty($product->gambar)): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/produk/'.$product->gambar) ?>" 
                                     alt="<?= htmlspecialchars($product->nama_produk) ?>" 
                                     class="img-thumbnail"
                                     style="max-width: 200px;">
                                <p class="small text-muted mt-1">Gambar saat ini</p>
                            </div>
                        <?php endif; ?>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input" 
                                   id="gambar" 
                                   name="gambar"
                                   accept="image/*">
                            <label class="custom-file-label" for="gambar">Pilih gambar baru...</label>
                        </div>
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Max: 2MB. Kosongkan jika tidak ingin mengubah</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <span class="fe fe-save fe-16 mr-2"></span>Update
                        </button>
                        <a href="<?= base_url('admin/produk') ?>" class="btn btn-secondary">
                            <span class="fe fe-x fe-16 mr-2"></span>Batal
                        </a>
                    </div>

                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Information Card -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <?php if (!empty($product)): ?>
                <h5 class="card-title mb-3">Detail Produk</h5>
                <dl>
                    <dt>ID Produk</dt>
                    <dd class="text-muted"><?= $product->id_produk ?></dd>
                    
                    <dt>Tanggal Dibuat</dt>
                    <dd class="text-muted"><?= date('d F Y H:i', strtotime($product->created_at)) ?></dd>
                    
                    <dt>Terakhir Update</dt>
                    <dd class="text-muted"><?= date('d F Y H:i', strtotime($product->updated_at)) ?></dd>
                </dl>
                
                <hr>
                <?php endif; ?>
                
                <h6 class="mb-2">Tips:</h6>
                <ul class="small text-muted pl-3">
                    <li>Gunakan SKU yang mudah diingat</li>
                    <li>SKU harus unik</li>
                    <li>Nama produk harus jelas dan deskriptif</li>
                    <li>Kosongkan gambar jika tidak ingin mengubah</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Update custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>

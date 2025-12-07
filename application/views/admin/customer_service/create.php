<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Tambah Customer Service</strong>
            </div>
            <div class="card-body">
                <?= form_open('admin/customer-service/store', ['id' => 'formCS']) ?>
                    
                    <!-- NIK -->
                    <div class="form-group">
                        <label for="nik">NIK <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" 
                               id="nik" 
                               name="nik" 
                               value="<?= set_value('nik') ?>" 
                               placeholder="Masukkan NIK" 
                               required>
                        <?= form_error('nik', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Nama CS -->
                    <div class="form-group">
                        <label for="nama_cs">Nama Customer Service <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nama_cs') ? 'is-invalid' : '' ?>" 
                               id="nama_cs" 
                               name="nama_cs" 
                               value="<?= set_value('nama_cs') ?>" 
                               placeholder="Masukkan nama lengkap" 
                               required>
                        <?= form_error('nama_cs', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Tim -->
                    <div class="form-group">
                        <label for="id_tim">Tim <span class="text-danger">*</span></label>
                        <select name="id_tim" 
                                id="id_tim" 
                                class="form-control <?= form_error('id_tim') ? 'is-invalid' : '' ?>" 
                                required>
                            <option value="">-- Pilih Tim --</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= $team->id_tim ?>" <?= set_select('id_tim', $team->id_tim) ?>>
                                    <?= esc($team->nama_tim) ?> - Leader: <?= esc($team->nama_leader) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('id_tim', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Produk -->
                    <div class="form-group">
                        <label for="id_produk">Produk <span class="text-danger">*</span></label>
                        <select name="id_produk" 
                                id="id_produk" 
                                class="form-control <?= form_error('id_produk') ? 'is-invalid' : '' ?>" 
                                required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product->id_produk ?>" <?= set_select('id_produk', $product->id_produk) ?>>
                                    <?= esc($product->nama_produk) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('id_produk', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Kanal -->
                    <div class="form-group">
                        <label for="id_kanal">Kanal <span class="text-danger">*</span></label>
                        <select name="id_kanal" 
                                id="id_kanal" 
                                class="form-control <?= form_error('id_kanal') ? 'is-invalid' : '' ?>" 
                                required>
                            <option value="">-- Pilih Kanal --</option>
                            <?php foreach ($channels as $channel): ?>
                                <option value="<?= $channel->id_kanal ?>" <?= set_select('id_kanal', $channel->id_kanal) ?>>
                                    <?= esc($channel->nama_kanal) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('id_kanal', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/customer-service') ?>" class="btn btn-secondary">
                            <i class="fe fe-x"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Info Sidebar -->
<div class="row mt-3">
    <div class="col-md-8 offset-md-2">
        <div class="card border-left-primary shadow">
            <div class="card-body">
                <h6 class="font-weight-bold text-primary mb-2">
                    <i class="fe fe-info"></i> Informasi
                </h6>
                <ul class="mb-0 small">
                    <li>NIK harus unik untuk setiap Customer Service</li>
                    <li>Pilih tim yang sesuai dengan struktur organisasi</li>
                    <li>Produk menunjukkan jenis produk yang ditangani CS</li>
                    <li>Kanal menunjukkan saluran komunikasi yang digunakan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4">Form Tambah Kanal</h5>
                
                <form action="<?= base_url('admin/kanal/store') ?>" method="POST" id="formKanal">
                    
                    <div class="form-group">
                        <label for="nama_kanal">Nama Kanal <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nama_kanal') ? 'is-invalid' : '' ?>" 
                               id="nama_kanal" 
                               name="nama_kanal" 
                               value="<?= set_value('nama_kanal') ?>" 
                               placeholder="Contoh: Email, Telepon, Chat, Social Media"
                               required>
                        <?php if (form_error('nama_kanal')): ?>
                            <div class="invalid-feedback"><?= form_error('nama_kanal') ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Nama kanal komunikasi customer service</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <span class="fe fe-save fe-16 mr-2"></span>Simpan
                        </button>
                        <a href="<?= base_url('admin/kanal') ?>" class="btn btn-secondary">
                            <span class="fe fe-x fe-16 mr-2"></span>Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Information Card -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi</h5>
                
                <p class="text-muted small">
                    <strong>Kanal</strong> adalah media komunikasi yang digunakan untuk melayani customer.
                </p>
                
                <hr>
                
                <h6 class="mb-2">Contoh Kanal:</h6>
                <ul class="small text-muted pl-3">
                    <li>Email</li>
                    <li>Telepon / Call Center</li>
                    <li>Live Chat / WhatsApp</li>
                    <li>Social Media (Facebook, Twitter, Instagram)</li>
                    <li>Mobile App</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header">
                <strong class="card-title">Form Tambah Pengguna</strong>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/pengguna/store') ?>" method="POST" id="formPengguna">
                    
                    <div class="form-group">
                        <label for="nik">NIK <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" 
                               id="nik" 
                               name="nik" 
                               value="<?= set_value('nik') ?>" 
                               placeholder="Masukkan NIK"
                               required>
                        <?php if (form_error('nik')): ?>
                            <div class="invalid-feedback"><?= form_error('nik') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nama_pengguna">Nama Pengguna <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nama_pengguna') ? 'is-invalid' : '' ?>" 
                               id="nama_pengguna" 
                               name="nama_pengguna" 
                               value="<?= set_value('nama_pengguna') ?>" 
                               placeholder="Masukkan nama lengkap"
                               required>
                        <?php if (form_error('nama_pengguna')): ?>
                            <div class="invalid-feedback"><?= form_error('nama_pengguna') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= set_value('email') ?>" 
                               placeholder="contoh@email.com"
                               required>
                        <?php if (form_error('email')): ?>
                            <div class="invalid-feedback"><?= form_error('email') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               placeholder="Minimal 6 karakter"
                               required>
                        <?php if (form_error('password')): ?>
                            <div class="invalid-feedback"><?= form_error('password') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control <?= form_error('password_confirm') ? 'is-invalid' : '' ?>" 
                               id="password_confirm" 
                               name="password_confirm" 
                               placeholder="Ulangi password"
                               required>
                        <?php if (form_error('password_confirm')): ?>
                            <div class="invalid-feedback"><?= form_error('password_confirm') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="level">Level <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('level') ? 'is-invalid' : '' ?>" 
                                id="level" 
                                name="level" 
                                required>
                            <option value="">-- Pilih Level --</option>
                            <option value="admin" <?= set_select('level', 'admin') ?>>Admin</option>
                            <option value="junior_manager" <?= set_select('level', 'junior_manager') ?>>Junior Manager</option>
                            <option value="supervisor" <?= set_select('level', 'supervisor') ?>>Supervisor</option>
                            <option value="leader" <?= set_select('level', 'leader') ?>>Leader</option>
                        </select>
                        <?php if (form_error('level')): ?>
                            <div class="invalid-feedback"><?= form_error('level') ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <span class="fe fe-save fe-16 mr-2"></span>Simpan
                        </button>
                        <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-secondary">
                            <span class="fe fe-x fe-16 mr-2"></span>Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Information Card -->
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-header">
                <strong class="card-title">Informasi</strong>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Level Pengguna:</h6>
                <dl>
                    <dt class="text-danger">Admin</dt>
                    <dd class="small text-muted mb-3">Akses penuh ke semua fitur sistem</dd>
                    
                    <dt class="text-primary">Junior Manager</dt>
                    <dd class="small text-muted mb-3">Mengelola laporan dan monitoring</dd>
                    
                    <dt class="text-info">Supervisor</dt>
                    <dd class="small text-muted mb-3">Mengelola tim dan evaluasi CS</dd>
                    
                    <dt class="text-success">Leader</dt>
                    <dd class="small text-muted mb-0">Mengelola anggota tim</dd>
                </dl>
                
                <hr class="my-3">
                
                <h6 class="mb-2">Catatan:</h6>
                <ul class="small text-muted pl-3">
                    <li>NIK harus unik</li>
                    <li>Email harus valid dan unik</li>
                    <li>Password minimal 6 karakter</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('#formPengguna').on('submit', function(e) {
        var password = $('#password').val();
        var password_confirm = $('#password_confirm').val();
        
        if (password !== password_confirm) {
            e.preventDefault();
            alert('Password dan Konfirmasi Password tidak sama!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
    });
});
</script>

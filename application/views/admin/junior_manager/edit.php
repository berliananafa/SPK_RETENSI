<!-- Form Edit Junior Manager -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Form Edit Junior Manager</strong>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle"></i> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/junior-manager/update/' . $junior_manager->id_user) ?>" method="POST">
                    <div class="form-group">
                        <label for="nik">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" 
                               id="nik" name="nik" value="<?= set_value('nik', $junior_manager->nik) ?>" required
                               placeholder="Masukkan NIK">
                        <?= form_error('nik', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="form-group">
                        <label for="nama_pengguna">Nama Junior Manager <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= form_error('nama_pengguna') ? 'is-invalid' : '' ?>" 
                               id="nama_pengguna" name="nama_pengguna" value="<?= set_value('nama_pengguna', $junior_manager->nama_pengguna) ?>" required
                               placeholder="Masukkan nama lengkap">
                        <?= form_error('nama_pengguna', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= set_value('email', $junior_manager->email) ?>" required
                               placeholder="contoh@email.com">
                        <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" 
                               id="password" name="password" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        <?= form_error('password', '<div class="invalid-feedback">', '</div>') ?>
                        <small class="form-text text-muted">
                            <i class="fe fe-info"></i> Kosongkan jika tidak ingin mengubah password
                        </small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group text-right mb-0">
                        <a href="<?= base_url('admin/junior-manager') ?>" class="btn btn-secondary">
                            <i class="fe fe-x"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

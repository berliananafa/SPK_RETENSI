<!-- Form Edit Supervisor -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Form Edit Supervisor</strong>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle mr-2"></i>
                        <?= validation_errors() ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle mr-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/supervisor/update/' . $supervisor->id_user) ?>" method="POST">
                    <div class="form-group">
                        <label for="id_atasan">Junior Manager <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('id_atasan') ? 'is-invalid' : '' ?>" 
                                id="id_atasan" name="id_atasan" required>
                            <option value="">-- Pilih Junior Manager --</option>
                            <?php if (!empty($junior_managers)): ?>
                                <?php foreach ($junior_managers as $jm): ?>
                                    <option value="<?= $jm->id_user ?>" 
                                        <?= set_select('id_atasan', $jm->id_user, $jm->id_user == ($supervisor->id_atasan ?? '')) ?>>
                                        <?= htmlspecialchars($jm->nama_pengguna) ?> (<?= htmlspecialchars($jm->nik) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?= form_error('id_atasan', '<div class="invalid-feedback">', '</div>') ?>
                        <small class="form-text text-muted">Pilih Junior Manager yang akan membawahi supervisor ini</small>
                    </div>

                    <div class="form-group">
                        <label for="nik">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" 
                               id="nik" name="nik" value="<?= set_value('nik', $supervisor->nik) ?>" 
                               placeholder="Masukkan NIK" required>
                        <?= form_error('nik', '<div class="invalid-feedback">', '</div>') ?>
                        <small class="form-text text-muted">NIK harus unik di sistem</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_pengguna">Nama Supervisor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= form_error('nama_pengguna') ? 'is-invalid' : '' ?>\" 
                               id="nama_pengguna" name="nama_pengguna" value="<?= set_value('nama_pengguna', $supervisor->nama_pengguna) ?>" 
                               placeholder="Masukkan nama lengkap" required>
                        <?= form_error('nama_pengguna', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= set_value('email', $supervisor->email) ?>" 
                               placeholder="contoh@email.com" required>
                        <?= form_error('email', '<div class="invalid-feedback">', '</div>') ?>
                        <small class="form-text text-muted">Email harus unik dan akan digunakan untuk login</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" 
                               id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <?= form_error('password', '<div class="invalid-feedback">', '</div>') ?>
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fe fe-briefcase"></i> Scope SPV</h5>

                    <div class="form-group">
                        <label>Kanal <span class="text-danger">*</span></label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <?php if (!empty($kanals)): ?>
                                <?php foreach ($kanals as $kanal): ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="kanal_<?= $kanal->id_kanal ?>" 
                                               name="id_kanal[]" 
                                               value="<?= $kanal->id_kanal ?>"
                                               <?= (in_array($kanal->id_kanal, $selected_kanals)) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="kanal_<?= $kanal->id_kanal ?>">
                                            <?= htmlspecialchars($kanal->nama_kanal) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">Belum ada data kanal</p>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted">Pilih kanal yang akan ditangani supervisor ini</small>
                    </div>

                    <div class="form-group">
                        <label>Produk <span class="text-danger">*</span></label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <?php if (!empty($produks)): ?>
                                <?php foreach ($produks as $produk): ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="produk_<?= $produk->id_produk ?>" 
                                               name="id_produk[]" 
                                               value="<?= $produk->id_produk ?>"
                                               <?= (in_array($produk->id_produk, $selected_produks)) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="produk_<?= $produk->id_produk ?>">
                                            <?= htmlspecialchars($produk->nama_produk) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">Belum ada data produk</p>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted">Pilih produk yang akan ditangani supervisor ini</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group text-right mb-0">
                        <a href="<?= base_url('admin/supervisor') ?>" class="btn btn-secondary">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

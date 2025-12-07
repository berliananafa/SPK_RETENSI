<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Edit Tim</strong>
            </div>
            <div class="card-body">
                <?= form_open('admin/tim/update/' . $team->id_tim, ['id' => 'formTim']) ?>
                    
                    <!-- Nama Tim -->
                    <div class="form-group">
                        <label for="nama_tim">Nama Tim <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control <?= form_error('nama_tim') ? 'is-invalid' : '' ?>" 
                               id="nama_tim" 
                               name="nama_tim" 
                               value="<?= set_value('nama_tim', $team->nama_tim) ?>" 
                               placeholder="Contoh: Tim Alpha" 
                               required>
                        <?= form_error('nama_tim', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Leader -->
                    <div class="form-group">
                        <label for="id_leader">Leader <span class="text-danger">*</span></label>
                        <select name="id_leader" 
                                id="id_leader" 
                                class="form-control <?= form_error('id_leader') ? 'is-invalid' : '' ?>" 
                                required>
                            <option value="">-- Pilih Leader --</option>
                            <?php foreach ($leaders as $leader): ?>
                                <option value="<?= $leader->id_user ?>" 
                                    <?= set_select('id_leader', $leader->id_user, ($leader->id_user == $team->id_leader)) ?>>
                                    <?= esc($leader->nama_pengguna) ?> - <?= esc($leader->email) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('id_leader', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Supervisor -->
                    <div class="form-group">
                        <label for="id_supervisor">Supervisor <span class="text-danger">*</span></label>
                        <select name="id_supervisor" 
                                id="id_supervisor" 
                                class="form-control <?= form_error('id_supervisor') ? 'is-invalid' : '' ?>" 
                                required>
                            <option value="">-- Pilih Supervisor --</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?= $supervisor->id_user ?>" 
                                    <?= set_select('id_supervisor', $supervisor->id_user, ($supervisor->id_user == $team->id_supervisor)) ?>>
                                    <?= esc($supervisor->nama_pengguna) ?> - <?= esc($supervisor->email) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('id_supervisor', '<div class="invalid-feedback">', '</div>') ?>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Perbarui
                        </button>
                        <a href="<?= base_url('admin/tim') ?>" class="btn btn-secondary">
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
        <div class="card border-left-warning shadow">
            <div class="card-body">
                <h6 class="font-weight-bold text-warning mb-2">
                    <i class="fe fe-alert-triangle"></i> Perhatian
                </h6>
                <ul class="mb-0 small">
                    <li>Perubahan Leader atau Supervisor akan mempengaruhi struktur organisasi</li>
                    <li>Pastikan Leader dan Supervisor yang dipilih tersedia dan aktif</li>
                    <li>Tim yang sudah memiliki anggota CS sebaiknya tidak diubah sembarangan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

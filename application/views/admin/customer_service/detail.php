<!-- Detail Customer Service -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Detail Customer Service</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/customer-service/edit/' . $cs->id_cs) ?>" class="btn btn-warning btn-sm">
                            <i class="fe fe-edit"></i> Edit Data
                        </a>
                        <a href="<?= base_url('admin/customer-service') ?>" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=6c5ce7&color=fff&size=128" 
                                 alt="<?= htmlspecialchars($cs->nama_cs) ?>"
                                 class="avatar-img rounded-circle">
                        </div>
                        <h4 class="mb-1"><?= htmlspecialchars($cs->nama_cs) ?></h4>
                        <p class="text-muted">
                            <span class="badge badge-purple">Customer Service</span>
                        </p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fe fe-user"></i> Informasi Personal</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%" class="text-muted">NIK</td>
                                <td><strong><?= htmlspecialchars($cs->nik) ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Lengkap</td>
                                <td><strong><?= htmlspecialchars($cs->nama_cs) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fe fe-briefcase"></i> Informasi Pekerjaan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fe fe-users"></i> Tim
                                </h6>
                                <h5 class="card-title mb-0">
                                    <?php if (!empty($cs->nama_tim)): ?>
                                        <span class="badge badge-soft-primary badge-lg">
                                            <?= htmlspecialchars($cs->nama_tim) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fe fe-inbox"></i> Kanal
                                </h6>
                                <h5 class="card-title mb-0">
                                    <?php if (!empty($cs->nama_kanal)): ?>
                                        <span class="badge badge-soft-info badge-lg">
                                            <?= htmlspecialchars($cs->nama_kanal) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fe fe-package"></i> Produk
                                </h6>
                                <h5 class="card-title mb-0">
                                    <?php if (!empty($cs->nama_produk)): ?>
                                        <span class="badge badge-soft-success badge-lg">
                                            <?= htmlspecialchars($cs->nama_produk) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fe fe-clock"></i> Dibuat: <?= date('d M Y H:i', strtotime($cs->created_at)) ?>
                        </small>
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-muted">
                            <i class="fe fe-refresh-cw"></i> Update: <?= date('d M Y H:i', strtotime($cs->updated_at)) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

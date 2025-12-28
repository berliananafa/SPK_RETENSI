<!-- Team Info Banner -->
<?php if ($team): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fe fe-info"></i> <strong>Tim: <?= htmlspecialchars($team->nama_tim) ?></strong>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fe fe-alert-triangle"></i> Anda belum memimpin tim. Silakan hubungi administrator.
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-12">
        <!-- Stats Cards -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <span class="mb-1 text-muted text-uppercase small">Total CS</span>
                                <h3 class="mb-0 card-title"><?= $total_cs ?></h3>
                            </div>
                            <div class="col-auto">
                                <span class="text-primary fe fe-users fe-32"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable-1">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>NIK</th>
                                <th>Nama CS</th>
                                <th>Produk</th>
                                <th>Kanal</th>
                                <th>Total Penilaian</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($customer_services)): ?>
                                <?php foreach ($customer_services as $index => $cs): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($cs->nik) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&background=random"
                                                         alt="Avatar" class="avatar-img rounded-circle">
                                                </div>
                                                <strong><?= htmlspecialchars($cs->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($cs->nama_produk) ?></span></td>
                                        <td><span class="badge badge-soft-info"><?= htmlspecialchars($cs->nama_kanal) ?></span></td>
                                        <td>
                                            <?php
                                            // Get evaluation count
                                            $this->load->model('NilaiModel');
                                            $stats = $this->NilaiModel->getStatsByCustomerService($cs->id_cs);
                                            ?>
                                            <span class="badge badge-soft-success"><?= $stats->total_penilaian ?> penilaian</span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('leader/customer-service/detail/' . $cs->id_cs) ?>"
                                               class="btn btn-sm btn-primary" title="Detail">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4 text-muted">
                                            <i class="fe fe-inbox fe-32 mb-3"></i>
                                            <p>Belum ada data customer service</p>
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
</div>

<script>
$(document).ready(function() {
    $('#dataTable-1').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, "asc"]]
    });
});
</script>

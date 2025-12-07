<!-- Leader List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Daftar Leader</strong>
                <span class="small ml-2" style="opacity: 0.9;">Leader yang berada di bawah tanggung jawab Anda</span>
            </div>
            <div class="card-body">
                <?php if (!empty($leaders)): ?>
                    <div class="table-responsive">
                        <table class="table datatables" id="dataTable-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Leader</th>
                                    <th>Tim</th>
                                    <th>Total CS</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaders as $index => $leader): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <small><?= htmlspecialchars($leader->nik) ?></small>
                                        </td>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col pl-0">
                                                    <strong><?= htmlspecialchars($leader->nama_pengguna) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success">
                                                <i class="fe fe-users mr-1"></i><?= htmlspecialchars($leader->nama_tim) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?= $leader->total_cs ?> CS</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-mail mr-1"></i><?= htmlspecialchars($leader->email) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('supervisor/leader/detail/' . $leader->id_user) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fe fe-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-user-x fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Leader</h5>
                        <p class="text-muted">Leader akan ditampilkan di sini setelah ditambahkan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-leaders').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>

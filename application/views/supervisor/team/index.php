<!-- Team & CS List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Daftar Tim & Customer Service</strong>
                <span class="small ml-2" style="opacity: 0.9;">Tim dan CS yang berada di bawah tanggung jawab Anda</span>
            </div>
            <div class="card-body">
                <?php if (!empty($teams)): ?>
                    <div class="table-responsive">
                        <table class="table datatables" id="dataTable-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tim</th>
                                    <th>Leader</th>
                                    <th>Total CS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teams as $index => $team): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col pl-0">
                                                    <strong><?= htmlspecialchars($team->nama_tim) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-user mr-1"></i><?= htmlspecialchars($team->leader_name) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?= $team->total_cs ?> CS</span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('supervisor/team/detail/' . $team->id_tim) ?>" 
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
                        <span class="fe fe-users fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Tim</h5>
                        <p class="text-muted">Tim akan ditampilkan di sini setelah ditambahkan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-teams').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>

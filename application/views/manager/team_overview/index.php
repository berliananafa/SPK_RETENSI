<!-- Team Overview -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Daftar Tim</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless datatable" id="dataTable-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tim</th>
                                <th>Supervisor</th>
                                <th>Leader</th>
                                <th>Total CS</th>
                                <th>Total Penilaian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($teams)): ?>
                                <?php foreach ($teams as $index => $team): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <strong><?= htmlspecialchars($team->nama_tim) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($team->supervisor_name ?? '-') ?></td>
                                        <td><?= htmlspecialchars($team->leader_name ?? '-') ?></td>
                                        <td><span class="badge badge-soft-info"><?= $team->total_cs ?> CS</span></td>
                                        <td><span class="badge badge-soft-success"><?= $team->total_penilaian ?></span></td>
                                        <td>
                                            <a href="<?= base_url('junior-manager/team-overview/detail/' . $team->id_tim) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fe fe-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data tim</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

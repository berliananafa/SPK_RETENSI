<!-- History Penilaian -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">History Penilaian</strong>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url('junior-manager/nilai/input') ?>" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Input Penilaian
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fe fe-check-circle"></i> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-x-circle"></i> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIK</th>
                                <th>Nama CS</th>
                                <th>Tim</th>
                                <th>Kriteria</th>
                                <th>Sub Kriteria</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($nilai_list)): ?>
                                <?php foreach ($nilai_list as $index => $nilai): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($nilai->created_at)) ?></td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($nilai->nik) ?></span></td>
                                        <td><?= htmlspecialchars($nilai->nama_cs) ?></td>
                                        <td><?= htmlspecialchars($nilai->nama_tim) ?></td>
                                        <td><?= htmlspecialchars($nilai->nama_kriteria) ?></td>
                                        <td><?= htmlspecialchars($nilai->nama_sub_kriteria) ?></td>
                                        <td><span class="badge badge-success"><?= number_format($nilai->nilai, 2) ?></span></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete(<?= $nilai->id_nilai ?>)">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data penilaian</td>
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
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data penilaian ini?')) {
        window.location.href = '<?= base_url('junior-manager/nilai/delete/') ?>' + id;
    }
}
</script>

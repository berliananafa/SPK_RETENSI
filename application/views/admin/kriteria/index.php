<!-- Kriteria List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Kriteria Penilaian</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/kriteria/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Kriteria
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable-1" class="table table-hover table-borderless">
                    <thead >
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Jenis Kriteria</th>
                            <th>Bobot (%)</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kriteria)): ?>
                            <?php foreach ($kriteria as $index => $krt): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge badge-soft-primary"><?= htmlspecialchars($krt->kode_kriteria) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($krt->nama_kriteria) ?></strong>
                                                <?php if (!empty($krt->deskripsi)): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($krt->deskripsi) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (isset($krt->jenis_kriteria) && $krt->jenis_kriteria == 'core_factor'): ?>
                                            <span class="badge badge-danger"><i class="fe fe-star"></i> Core Factor</span>
                                        <?php else: ?>
                                            <span class="badge badge-info"><i class="fe fe-circle"></i> Secondary Factor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: <?= $krt->bobot ?>%;" aria-valuenow="<?= $krt->bobot ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?= number_format($krt->bobot, 2) ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/kriteria/edit/' . $krt->id_kriteria) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/kriteria/delete/' . $krt->id_kriteria) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Kriteria?"
                                               data-text="Data kriteria dan sub kriteria akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data kriteria</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-left-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-primary"></i>
                    </div>
                    <div class="col">
                                                <h6 class="mb-1">Tentang Profile Matching</h6>
                        <p class="mb-0 text-muted small">
                            Kriteria dibagi menjadi <strong>Core Factor (60%)</strong> dan <strong>Secondary Factor (40%)</strong>. 
                            Setiap kriteria memiliki <strong>Bobot</strong> yang menunjukkan tingkat kepentingan. 
                            <strong>Nilai Target</strong> diatur per <strong>Sub Kriteria</strong>. <strong>GAP</strong> dihitung dari selisih nilai aktual CS dengan target sub kriteria.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const title = $(this).data('title') || 'Hapus Data?';
        const text = $(this).data('text') || 'Data akan dihapus permanen!';

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>

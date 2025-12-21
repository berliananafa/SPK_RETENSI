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
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kriteria)): ?>
                            <?php foreach ($kriteria as $index => $krt): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($krt->kode_kriteria) ?></strong></td>
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
                                            <strong class="text-danger">Core Factor</strong> <small class="text-muted">(90%)</small>
                                        <?php else: ?>
                                            <strong class="text-info">Secondary Factor</strong> <small class="text-muted">(10%)</small>
                                        <?php endif; ?>
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
                                <td colspan="5" class="text-center text-muted py-4">
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
                            Kriteria dibagi menjadi <strong>Core Factor (90%)</strong> dan <strong>Secondary Factor (10%)</strong>. 
                            Bobot otomatis sesuai jenis kriteria. <strong>Bobot detail</strong> diatur di <strong>Sub Kriteria</strong> yang merepresentasikan persentase dari total keseluruhan. 
                            <strong>GAP</strong> dihitung dari selisih nilai aktual CS dengan target sub kriteria.
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

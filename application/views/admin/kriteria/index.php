<!-- Statistik Approval Cards -->
<div class="row mb-4">
	<div class="col-md-3">
		<div class="card shadow-sm border-left-primary">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kriteria</div>
						<div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_kriteria ?></div>
					</div>
					<div class="col-auto">
						<i class="fe fe-list fe-32 text-muted"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card shadow-sm border-left-warning">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
						<div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_pending ?></div>
					</div>
					<div class="col-auto">
						<i class="fe fe-clock fe-32 text-warning"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card shadow-sm border-left-success">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
						<div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_approved ?></div>
					</div>
					<div class="col-auto">
						<i class="fe fe-check-circle fe-32 text-success"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card shadow-sm border-left-danger">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
						<div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_rejected ?></div>
					</div>
					<div class="col-auto">
						<i class="fe fe-x-circle fe-32 text-danger"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Kriteria List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Kriteria Penilaian</strong>
                        <p class="mb-0 text-muted small">Kelola kriteria penilaian - Status approval ditentukan oleh Manager</p>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/kriteria/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Kriteria
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable-1" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode</th>
                            <th>Nama Kriteria</th>
                            <th width="18%">Jenis Kriteria</th>
                            <th width="12%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kriteria)): ?>
                            <?php foreach ($kriteria as $index => $krt): ?>
                                <?php
                                    $status = $krt->status_approval ?? 'pending';
                                    $badge_class = 'secondary';
                                    if ($status === 'approved') $badge_class = 'success';
                                    elseif ($status === 'rejected') $badge_class = 'danger';
                                    elseif ($status === 'pending') $badge_class = 'warning';
                                ?>
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
                                            <span class="badge badge-primary">Core Factor</span>
                                            <small class="text-muted">(90%)</small>
                                        <?php else: ?>
                                            <span class="badge badge-info">Secondary Factor</span>
                                            <small class="text-muted">(10%)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?= $badge_class ?>">
                                            <?= htmlspecialchars(ucfirst($status)) ?>
                                        </span>
                                        <?php if ($status === 'rejected' && !empty($krt->rejection_note)): ?>
                                            <br><small class="text-muted" title="<?= htmlspecialchars($krt->rejection_note) ?>">
                                                <i class="fe fe-info"></i> Lihat catatan
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/kriteria/edit/' . $krt->id_kriteria) ?>"
                                               class="btn btn-sm btn-warning"
                                               title="Edit <?= $status === 'approved' ? '(akan reset approval)' : '' ?>">
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
        <div class="card shadow-sm border-left-info">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Tentang Approval Kriteria</h6>
                        <p class="mb-0 text-muted small">
                            Kriteria yang dibuat akan berstatus <strong class="text-warning">Pending</strong> menunggu persetujuan Manager.
                            Hanya kriteria dengan status <strong class="text-success">Approved</strong> yang dapat digunakan untuk penilaian.
                            Jika kriteria di-edit, status approval akan direset ke <strong class="text-warning">Pending</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-2">
        <div class="card shadow-sm border-left-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-book fe-24 text-primary"></i>
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

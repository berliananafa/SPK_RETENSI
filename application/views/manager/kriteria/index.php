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
<div class="card shadow">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<strong class="card-title">Data Kriteria Penilaian</strong>
				<p class="mb-0 text-muted small">Kelola dan setujui kriteria penilaian</p>
			</div>
		</div>
	</div>
	<div class="card-body">
		<?php if (!empty($kriteria)): ?>
		<div class="table-responsive">
			<table class="table table-hover table-striped" id="dataTable-1">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th>Kode</th>
						<th>Nama Kriteria</th>
						<th>Jenis Kriteria</th>
						<th>Status</th>
						<th width="20%" class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($kriteria as $i => $k): ?>
					<?php 
                        $status = $k->status_approval ?? 'pending';
                        $badge_class = 'secondary';
                        if ($status === 'approved') $badge_class = 'success';
                        elseif ($status === 'rejected') $badge_class = 'danger';
                        elseif ($status === 'pending') $badge_class = 'warning';
                    ?>
					<tr>
						<td><?= $i + 1 ?></td>
						<td><strong><?= htmlspecialchars($k->kode_kriteria) ?></strong></td>
						<td>
							<div class="d-flex align-items-center">
								<div>
									<strong><?= htmlspecialchars($k->nama_kriteria) ?></strong>
									<?php if (!empty($k->deskripsi)): ?>
									<br><small class="text-muted"><?= htmlspecialchars($k->deskripsi) ?></small>
									<?php endif; ?>
								</div>
							</div>
						</td>
						<td>
							<?php if (isset($k->jenis_kriteria) && $k->jenis_kriteria == 'core_factor'): ?>
							<span class="badge badge-primary">Core Factor</span>
							<small class="text-muted">(90%)</small>
							<?php else: ?>
							<span class="badge badge-info">Secondary Factor</span>
							<small class="text-muted">(10%)</small>
							<?php endif; ?>
						</td>
						<td>
							<span class="badge badge-<?= $badge_class ?>">
								<?= htmlspecialchars(ucfirst($status)) ?>
							</span>
						</td>
						<td class="text-center">
							<div class="btn-group" role="group">
								<a href="<?= base_url('junior-manager/kriteria/detail/' . $k->id_kriteria) ?>"
									class="btn btn-sm btn-info" title="Lihat Detail">
									<i class="fe fe-eye"></i>
								</a>
								<?php if ($status === 'pending'): ?>
								<button data-id="<?= $k->id_kriteria ?>"
									data-url="<?= base_url('junior-manager/kriteria/approve/' . $k->id_kriteria) ?>"
									class="btn btn-sm btn-success btn-approve" title="Setujui">
									<i class="fe fe-check"></i>
								</button>
								<button data-id="<?= $k->id_kriteria ?>"
									data-url="<?= base_url('junior-manager/kriteria/reject/' . $k->id_kriteria) ?>"
									class="btn btn-sm btn-danger btn-reject" title="Tolak">
									<i class="fe fe-x"></i>
								</button>
								<?php elseif ($status === 'approved'): ?>
								<button class="btn btn-sm btn-outline-success" disabled title="Sudah Disetujui">
									<i class="fe fe-check-circle"></i>
								</button>
								<?php elseif ($status === 'rejected'): ?>
								<button class="btn btn-sm btn-outline-danger" disabled title="Sudah Ditolak">
									<i class="fe fe-x-circle"></i>
								</button>
								<?php endif; ?>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php else: ?>
		<div class="text-center py-4">
			<i class="fe fe-inbox fe-24 mb-3 text-muted"></i>
			<p class="text-muted">Belum ada data kriteria</p>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php
ob_start();
?>
<script>
	document.addEventListener('click', function (e) {
		// Handle Approve Button
		if (e.target && (e.target.classList.contains('btn-approve') || e.target.closest('.btn-approve'))) {
			var btn = e.target.classList.contains('btn-approve') ? e.target : e.target.closest('.btn-approve');
			var url = btn.getAttribute('data-url');
			if (!url) return;

			Swal.fire({
				title: 'Setujui Kriteria?',
				text: 'Kriteria yang disetujui akan masuk dalam perhitungan penilaian',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Setujui!',
				cancelButtonText: 'Batal'
			}).then(function (result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					Swal.fire({
						title: 'Memproses...',
						text: 'Mohon tunggu sebentar',
						icon: 'info',
						showConfirmButton: false,
						allowOutsideClick: false
					});

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					}).then(function (res) {
						return res.json();
					}).then(function (json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Kriteria berhasil disetujui',
								icon: 'success'
							}).then(function () {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menyetujui kriteria',
								icon: 'error'
							});
							btn.disabled = false;
						}
					}).catch(function () {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
					});
				}
			});
		}

		// Handle Reject Button
		if (e.target && (e.target.classList.contains('btn-reject') || e.target.closest('.btn-reject'))) {
			var btn = e.target.classList.contains('btn-reject') ? e.target : e.target.closest('.btn-reject');
			var url = btn.getAttribute('data-url');
			if (!url) return;

			Swal.fire({
				title: 'Tolak Kriteria?',
				text: 'Masukkan alasan penolakan (opsional)',
				input: 'textarea',
				inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
				inputAttributes: {
					'aria-label': 'Alasan penolakan'
				},
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Tolak!',
				cancelButtonText: 'Batal'
			}).then(function (result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					Swal.fire({
						title: 'Memproses...',
						text: 'Mohon tunggu sebentar',
						icon: 'info',
						showConfirmButton: false,
						allowOutsideClick: false
					});

					var formData = new FormData();
					formData.append('keterangan', result.value || '');

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					}).then(function (res) {
						return res.json();
					}).then(function (json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Kriteria berhasil ditolak',
								icon: 'success'
							}).then(function () {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menolak kriteria',
								icon: 'error'
							});
							btn.disabled = false;
						}
					}).catch(function () {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
					});
				}
			});
		}
	});

</script>
<?php
add_js(ob_get_clean());
?>

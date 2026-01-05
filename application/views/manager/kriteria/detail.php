<!-- Info Cards -->
<div class="row mb-4">
	<div class="col-md-4">
		<div class="card shadow-sm border-left-primary">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jenis Kriteria</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800">
							<?php if (isset($kriteria->jenis_kriteria) && $kriteria->jenis_kriteria == 'core_factor'): ?>
								Core Factor (90%)
							<?php else: ?>
								Secondary Factor (10%)
							<?php endif; ?>
						</div>
					</div>
					<div class="col-auto">
						<i class="fe fe-tag fe-32 text-muted"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-left-info">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Bobot Kriteria</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($kriteria->bobot) ?>%
						</div>
					</div>
					<div class="col-auto">
						<i class="fe fe-percent fe-32 text-muted"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-left-success">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sub Kriteria</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800"><?= $total_sub_kriteria ?> Item</div>
					</div>
					<div class="col-auto">
						<i class="fe fe-layers fe-32 text-muted"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="card shadow mb-4">
			<div class="card-header">
				<strong class="card-title">Detail Kriteria</strong>
			</div>
			<div class="card-body">
				<table class="table table-borderless">
					<tbody>
						<tr>
							<th width="200">Kode Kriteria</th>
							<td><?= htmlspecialchars($kriteria->kode_kriteria) ?></td>
						</tr>
						<tr>
							<th>Nama Kriteria</th>
							<td><?= htmlspecialchars($kriteria->nama_kriteria) ?></td>
						</tr>
						<tr>
							<th>Bobot</th>
							<td><?= htmlspecialchars($kriteria->bobot) ?></td>
						</tr>
						<tr>
							<th>Jenis Kriteria</th>
							<td>
								<?php
								$badgeConfig = [
									'core_factor' => ['label' => 'Core Factor', 'class' => 'primary'],
									'secondary_factor' => ['label' => 'Secondary Factor', 'class' => 'info'],
								];

								$config = $badgeConfig[$kriteria->jenis_kriteria] ?? [
									'label' => ucwords(str_replace('_', ' ', $kriteria->jenis_kriteria)),
									'class' => 'secondary'
								];
								?>
								<span class="badge badge-<?= $config['class']; ?>">
									<?= htmlspecialchars($config['label']); ?>
								</span>
							</td>
						</tr>
						<tr>
							<th>Deskripsi</th>
							<td><?= htmlspecialchars($kriteria->deskripsi ?? '-') ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Sub Kriteria -->
		<div class="card shadow">
			<div class="card-header">
				<strong class="card-title">Sub Kriteria</strong>
			</div>
			<div class="card-body">
				<?php if (!empty($sub_kriteria)): ?>
					<div class="table-responsive">
						<table class="table table-hover table-striped table-sm" id="dataTable-1">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Sub Kriteria</th>
									<th>Bobot</th>
									<th>Keterangan</th>
									<th>Status</th>
									<th width="150">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($sub_kriteria as $i => $sk): ?>
									<?php
									$status_sub = $sk->status_approval ?? 'pending';
									$badge_class_sub = 'secondary';
									if ($status_sub === 'approved') $badge_class_sub = 'success';
									elseif ($status_sub === 'rejected') $badge_class_sub = 'danger';
									elseif ($status_sub === 'pending') $badge_class_sub = 'warning';
									?>
									<tr>
										<td><?= $i + 1 ?></td>
										<td><?= htmlspecialchars($sk->nama_sub_kriteria) ?></td>
										<td><?= htmlspecialchars($sk->bobot_sub) ?></td>
										<td><?= htmlspecialchars($sk->keterangan ?? '-') ?></td>
										<td>
											<span class="badge badge-<?= $badge_class_sub ?>">
												<?= htmlspecialchars(ucfirst($status_sub)) ?>
											</span>
											<?php if ($status_sub === 'rejected' && !empty($sk->rejection_note)): ?>
												<a href="#" class="text-danger ml-1"
													data-toggle="tooltip"
													title="<?= htmlspecialchars($sk->rejection_note) ?>">
													<i class="fe fe-info"></i>
												</a>
											<?php endif; ?>
										</td>
										<td>
											<?php if ($status_sub === 'pending'): ?>
												<button class="btn btn-sm btn-success btn-approve-sub"
													data-id="<?= $sk->id_sub_kriteria ?>"
													data-url="<?= base_url('junior-manager/kriteria/approve_sub/' . $sk->id_sub_kriteria) ?>"
													title="Setujui">
													<i class="fe fe-check"></i>
												</button>
												<button class="btn btn-sm btn-danger btn-reject-sub"
													data-id="<?= $sk->id_sub_kriteria ?>"
													data-url="<?= base_url('junior-manager/kriteria/reject_sub/' . $sk->id_sub_kriteria) ?>"
													title="Tolak">
													<i class="fe fe-x"></i>
												</button>
											<?php else: ?>
												<span class="text-muted small">
													<?php if ($status_sub === 'approved'): ?>
														<i class="fe fe-check-circle text-success"></i> Disetujui
													<?php else: ?>
														<i class="fe fe-x-circle text-danger"></i> Ditolak
													<?php endif; ?>
												</span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="text-center py-4">
						<i class="fe fe-inbox text-muted" style="font-size: 36px;"></i>
						<p class="text-muted mt-2">Belum ada sub kriteria</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<!-- Status Approval -->
		<div class="card shadow mb-4">
			<div class="card-header">
				<strong class="card-title">Status Approval</strong>
			</div>
			<div class="card-body">
				<?php
				$status = $kriteria->status_approval ?? 'pending';
				$badge_class = 'secondary';
				if ($status === 'approved') $badge_class = 'success';
				elseif ($status === 'rejected') $badge_class = 'danger';
				elseif ($status === 'pending') $badge_class = 'warning';
				?>

				<div class="mb-3">
					<label class="text-muted small">Status</label>
					<div>
						<span class="badge badge-<?= $badge_class ?> badge-lg">
							<?= htmlspecialchars(ucfirst($status)) ?>
						</span>
					</div>
				</div>

				<?php if ($status === 'approved' || $status === 'rejected'): ?>
					<?php if (isset($approved_by_user) && $approved_by_user): ?>
						<div class="mb-3">
							<label class="text-muted small">Oleh</label>
							<div><?= htmlspecialchars($approved_by_user->nama_pengguna) ?></div>
						</div>
					<?php endif; ?>

					<?php if (!empty($kriteria->approved_at)): ?>
						<div class="mb-3">
							<label class="text-muted small">Tanggal</label>
							<div><?= date('d M Y H:i', strtotime($kriteria->approved_at)) ?></div>
						</div>
					<?php endif; ?>

					<?php if ($status === 'rejected' && !empty($kriteria->rejection_note)): ?>
						<div class="mb-3">
							<label class="text-muted small">Alasan Penolakan</label>
							<div class="alert alert-danger">
								<?= htmlspecialchars($kriteria->rejection_note) ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ($status === 'pending'): ?>
					<div class="mt-3">
						<button class="btn btn-success btn-block btn-approve" data-id="<?= $kriteria->id_kriteria ?>"
							data-url="<?= base_url('junior-manager/kriteria/approve/' . $kriteria->id_kriteria) ?>">
							<i class="fe fe-check"></i> Setujui Kriteria
						</button>
						<button class="btn btn-danger btn-block btn-reject" data-id="<?= $kriteria->id_kriteria ?>"
							data-url="<?= base_url('junior-manager/kriteria/reject/' . $kriteria->id_kriteria) ?>">
							<i class="fe fe-x"></i> Tolak Kriteria
						</button>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Navigation -->
		<div class="card shadow">
			<div class="card-body">
				<a href="<?= base_url('junior-manager/kriteria') ?>" class="btn btn-secondary btn-block">
					<i class="fe fe-arrow-left"></i> Kembali ke Daftar
				</a>
			</div>
		</div>
	</div>
</div>

<?php
ob_start();
?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Initialize tooltips
		$('[data-toggle="tooltip"]').tooltip();
	});

	document.addEventListener('click', function(e) {
		// Handle Approve Button (Kriteria)
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
			}).then(function(result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					btn.innerHTML = '<i class="fe fe-loader"></i> Memproses...';

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					}).then(function(res) {
						return res.json();
					}).then(function(json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Kriteria berhasil disetujui',
								icon: 'success'
							}).then(function() {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menyetujui kriteria',
								icon: 'error'
							});
							btn.disabled = false;
							btn.innerHTML = '<i class="fe fe-check"></i> Setujui Kriteria';
						}
					}).catch(function() {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
						btn.innerHTML = '<i class="fe fe-check"></i> Setujui Kriteria';
					});
				}
			});
		}

		// Handle Reject Button (Kriteria)
		if (e.target && (e.target.classList.contains('btn-reject') || e.target.closest('.btn-reject'))) {
			var btn = e.target.classList.contains('btn-reject') ? e.target : e.target.closest('.btn-reject');
			var url = btn.getAttribute('data-url');
			if (!url) return;

			Swal.fire({
				title: 'Tolak Kriteria?',
				text: 'Masukkan alasan penolakan',
				input: 'textarea',
				inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
				inputAttributes: {
					'aria-label': 'Alasan penolakan'
				},
				inputValidator: function(value) {
					if (!value || !value.trim()) {
						return 'Alasan penolakan harus diisi!';
					}
				},
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Tolak!',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					btn.innerHTML = '<i class="fe fe-loader"></i> Memproses...';

					var formData = new FormData();
					formData.append('keterangan', result.value);

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					}).then(function(res) {
						return res.json();
					}).then(function(json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Kriteria berhasil ditolak',
								icon: 'success'
							}).then(function() {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menolak kriteria',
								icon: 'error'
							});
							btn.disabled = false;
							btn.innerHTML = '<i class="fe fe-x"></i> Tolak Kriteria';
						}
					}).catch(function() {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
						btn.innerHTML = '<i class="fe fe-x"></i> Tolak Kriteria';
					});
				}
			});
		}

		// Handle Approve Button (Sub Kriteria)
		if (e.target && (e.target.classList.contains('btn-approve-sub') || e.target.closest('.btn-approve-sub'))) {
			var btn = e.target.classList.contains('btn-approve-sub') ? e.target : e.target.closest('.btn-approve-sub');
			var url = btn.getAttribute('data-url');
			if (!url) return;

			Swal.fire({
				title: 'Setujui Sub Kriteria?',
				text: 'Sub kriteria yang disetujui akan digunakan dalam penilaian',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Setujui!',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					var originalHtml = btn.innerHTML;
					btn.innerHTML = '<i class="fe fe-loader"></i>';

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					}).then(function(res) {
						return res.json();
					}).then(function(json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Sub kriteria berhasil disetujui',
								icon: 'success',
								timer: 1500,
								showConfirmButton: false
							}).then(function() {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menyetujui sub kriteria',
								icon: 'error'
							});
							btn.disabled = false;
							btn.innerHTML = originalHtml;
						}
					}).catch(function() {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
						btn.innerHTML = originalHtml;
					});
				}
			});
		}

		// Handle Reject Button (Sub Kriteria)
		if (e.target && (e.target.classList.contains('btn-reject-sub') || e.target.closest('.btn-reject-sub'))) {
			var btn = e.target.classList.contains('btn-reject-sub') ? e.target : e.target.closest('.btn-reject-sub');
			var url = btn.getAttribute('data-url');
			if (!url) return;

			Swal.fire({
				title: 'Tolak Sub Kriteria?',
				text: 'Masukkan alasan penolakan',
				input: 'textarea',
				inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
				inputAttributes: {
					'aria-label': 'Alasan penolakan'
				},
				inputValidator: function(value) {
					if (!value || !value.trim()) {
						return 'Alasan penolakan harus diisi!';
					}
				},
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Tolak!',
				cancelButtonText: 'Batal'
			}).then(function(result) {
				if (result.isConfirmed) {
					btn.disabled = true;
					var originalHtml = btn.innerHTML;
					btn.innerHTML = '<i class="fe fe-loader"></i>';

					var formData = new FormData();
					formData.append('keterangan', result.value);

					fetch(url, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					}).then(function(res) {
						return res.json();
					}).then(function(json) {
						if (json.status === 'success') {
							Swal.fire({
								title: 'Berhasil!',
								text: json.message || 'Sub kriteria berhasil ditolak',
								icon: 'success',
								timer: 1500,
								showConfirmButton: false
							}).then(function() {
								location.reload();
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: json.message || 'Gagal menolak sub kriteria',
								icon: 'error'
							});
							btn.disabled = false;
							btn.innerHTML = originalHtml;
						}
					}).catch(function() {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan pada server',
							icon: 'error'
						});
						btn.disabled = false;
						btn.innerHTML = originalHtml;
					});
				}
			});
		}
	});
</script>
<?php
add_js(ob_get_clean());
?>

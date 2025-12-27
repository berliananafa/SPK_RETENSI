<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-primary text-white">
				<div class="row align-items-center">
					<div class="col">
						<h5 class="mb-0"><i class="fe fe-award"></i> Hasil Ranking Customer Service</h5>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-3">
						<label for="filterPeriode" class="font-weight-bold">Periode:</label>
						<input type="month" class="form-control form-control-sm" id="filterPeriode"
							value="<?= date('Y-m') ?>">
					</div>
					<div class="col-md-2">
						<label for="filterTim" class="font-weight-bold">Tim:</label>
						<select class="form-control form-control-sm" id="filterTim">
							<option value="">-- Semua Tim --</option>
							<?php if (!empty($teams)): ?>
								<?php foreach ($teams as $t): ?>
									<option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-2">
						<label for="filterProduk" class="font-weight-bold">Produk:</label>
						<select class="form-control form-control-sm" id="filterProduk">
							<option value="">-- Semua Produk --</option>
							<?php if (!empty($produks)): ?>
								<?php foreach ($produks as $p): ?>
									<option value="<?= $p->id_produk ?>"><?= htmlspecialchars($p->nama_produk) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold d-block">&nbsp;</label>
						<button class="btn btn-info btn-sm btn-block" id="btnFilter">
							<i class="fe fe-filter"></i> Filter Data
						</button>
					</div>
					<div class="col-md-2">
						<label class="font-weight-bold d-block">&nbsp;</label>
						<button class="btn btn-secondary btn-sm btn-block" id="btnClearFilter">
							<i class="fe fe-x-circle"></i> Clear
						</button>
					</div>
				</div>

				<!-- Rankings Table -->
				<?php if (!empty($rankings)): ?>
					<div class="table-responsive">
						<table id="dataTable-1" class="table table-hover table-striped datatables">
							<thead>
								<tr>
									<th width="5%">Rank</th>
									<th>NIK</th>
									<th>Nama CS</th>
									<th>Produk</th>
									<th>Tim</th>
									<th>Kanal</th>
									<th>Nilai Akhir</th>
									<th>Status</th>
									<th width="12%" class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($rankings as $index => $rank): ?>
									<tr>
										<td>
											<?php if ($index == 0): ?>
												<span style="display:inline-block; min-width:36px; padding:6px 8px; height:36px; line-height:24px; font-size:0.9rem; text-align:center; border-radius:18px; background:#FFD700; color:#222;">
													<?= $index + 1 ?>
												</span>
											<?php elseif ($index == 1): ?>
												<span style="display:inline-block; min-width:36px; padding:6px 8px; height:36px; line-height:24px; font-size:0.9rem; text-align:center; border-radius:18px; background:#C0C0C0; color:#222;">
													<?= $index + 1 ?>
												</span>
											<?php elseif ($index == 2): ?>
												<span style="display:inline-block; min-width:36px; padding:6px 8px; height:36px; line-height:24px; font-size:0.9rem; text-align:center; border-radius:18px; background:#CD7F32; color:#fff;">
													<?= $index + 1 ?>
												</span>
											<?php else: ?>
												<strong><?= $index + 1 ?></strong>
											<?php endif; ?>
										</td>
										<td><?= htmlspecialchars($rank->nik) ?></td>
										<td>
											<div class="d-flex align-items-center">
												<strong><?= htmlspecialchars($rank->nama_cs) ?></strong>
											</div>
										</td>
										<td><?= htmlspecialchars($rank->nama_produk ?? '-') ?></td>
										<td><?= htmlspecialchars($rank->nama_tim ?? '-') ?></td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($rank->nama_kanal ?? '-') ?></small>
										</td>
										<td>
											<strong><?= number_format($rank->nilai_akhir, 2, ',', '.') ?></strong>
										</td>
										<td>
											<?php if ($rank->status === 'pending_supervisor'): ?>
												<span class="badge badge-warning">
													<i class="fe fe-clock"></i> Menunggu Approval
												</span>
											<?php elseif ($rank->status === 'pending_leader'): ?>
												<span class="badge badge-info">
													<i class="fe fe-arrow-down"></i> Di Leader
												</span>
											<?php elseif ($rank->status === 'rejected_supervisor'): ?>
												<span class="badge badge-danger">
													<i class="fe fe-x"></i> Ditolak
												</span>
											<?php elseif ($rank->status === 'published'): ?>
												<span class="badge badge-success">
													<i class="fe fe-check"></i> Published
												</span>
											<?php else: ?>
												<span class="badge badge-secondary"><?= ucfirst($rank->status ?? 'draft') ?></span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<?php if ($rank->status === 'pending_supervisor'): ?>
												<button class="btn btn-sm btn-success btn-approve" data-id="<?= $rank->id_ranking ?? $rank->id ?>">
													<i class="fe fe-check"></i> Setujui
												</button>
												<button class="btn btn-sm btn-danger btn-reject" data-id="<?= $rank->id_ranking ?? $rank->id ?>">
													<i class="fe fe-x"></i> Tolak
												</button>
											<?php elseif (!empty($rank->approved_by_supervisor)): ?>
												<span class="text-success"><i class="fe fe-check-circle"></i> Approved</span>
											<?php else: ?>
												<span class="text-muted">-</span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="alert alert-info">
						<i class="fe fe-alert-circle"></i> Belum ada data ranking untuk periode ini.
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php
ob_start();
?>
<script>
	$(document).ready(function() {
		// Filter button click handler
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var url = '<?= base_url('supervisor/ranking') ?>?';
			var params = [];

			if (periode) params.push('periode=' + periode);
			if (tim) params.push('id_tim=' + tim);
			if (produk) params.push('id_produk=' + produk);

			window.location.href = url + params.join('&');
		});

		// Clear filter button click handler
		$('#btnClearFilter').on('click', function() {
			$('#filterPeriode').val('<?= date('Y-m') ?>');
			$('#filterTim').val('');
			$('#filterProduk').val('');

			window.location.href = '<?= base_url('supervisor/ranking') ?>';
		});

		let currentRankingId = null;

		// Approve button
		$('.btn-approve').on('click', function(e) {
			e.preventDefault();
			const id = $(this).data('id');

			Swal.fire({
				title: 'Konfirmasi Approval',
				text: 'Apakah Anda yakin ingin menyetujui dan mempublikasikan ranking ini?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Setujui & Publish',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url('supervisor/ranking/approve') ?>/' + id,
						method: 'POST',
						dataType: 'json',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						success: function(response) {
							if (response.status === 'success') {
								Swal.fire('Berhasil!', response.message, 'success').then(() => {
									location.reload();
								});
							} else {
								Swal.fire('Error', response.message, 'error');
							}
						},
						error: function(xhr) {
							const response = xhr.responseJSON || {};
							Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
						}
					});
				}
			});
		});

		// Reject button
		$('.btn-reject').on('click', function(e) {
			e.preventDefault();
			currentRankingId = $(this).data('id');

			Swal.fire({
				title: 'Tolak Ranking',
				input: 'textarea',
				inputLabel: 'Catatan Penolakan',
				inputPlaceholder: 'Masukkan alasan penolakan...',
				inputAttributes: {
					'aria-label': 'Catatan penolakan'
				},
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Tolak',
				cancelButtonText: 'Batal',
				inputValidator: (value) => {
					if (!value) {
						return 'Catatan penolakan harus diisi!'
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url('supervisor/ranking/reject') ?>/' + currentRankingId,
						method: 'POST',
						data: { note: result.value },
						dataType: 'json',
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						success: function(response) {
							if (response.status === 'success') {
								Swal.fire('Berhasil!', response.message, 'success').then(() => {
									location.reload();
								});
							} else {
								Swal.fire('Error', response.message, 'error');
							}
						},
						error: function(xhr) {
							const response = xhr.responseJSON || {};
							Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
						}
					});
				}
			});
		});


	});
</script>
<?php
add_js(ob_get_clean());
?>

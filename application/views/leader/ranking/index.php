<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-primary text-white">
				<div class="row align-items-center">
					<div class="col">
						<h5 class="mb-0"><i class="fe fe-award"></i> Hasil Ranking Customer Service</h5>
						<small class="text-white-50">Approval ranking untuk tim Anda</small>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-4">
						<label for="filterPeriode" class="font-weight-bold">Periode:</label>
						<input type="month" class="form-control form-control-sm" id="filterPeriode"
							value="<?= $selected_periode ?? date('Y-m') ?>">
					</div>
					<div class="col-md-4">
						<label class="font-weight-bold d-block">&nbsp;</label>
						<button class="btn btn-info btn-sm btn-block" id="btnFilter">
							<i class="fe fe-filter"></i> Filter Data
						</button>
					</div>
					<div class="col-md-4">
						<label class="font-weight-bold d-block">&nbsp;</label>
						<button class="btn btn-secondary btn-sm btn-block" id="btnClearFilter">
							<i class="fe fe-x-circle"></i> Clear
						</button>
					</div>
				</div>

				<!-- Info Tim -->
				<?php if (!empty($team)): ?>
					<div class="alert alert-info mb-3">
						<i class="fe fe-users"></i> <strong>Tim Anda:</strong> <?= htmlspecialchars($team->nama_tim) ?>
					</div>
				<?php endif; ?>

				<!-- Rankings Table -->
				<?php if (!empty($rankings)): ?>
					<div class="table-responsive">
						<table id="dataTable-1" class="table table-hover table-striped datatables">
							<thead class="thead-light">
								<tr>
									<th width="5%">Rank</th>
									<th>NIK</th>
									<th>Nama CS</th>
									<th>Produk</th>
									<th>Kanal</th>
									<th>Nilai Akhir</th>
									<th>Status</th>
									<th width="15%" class="text-center">Aksi</th>
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
										<td><span class="badge badge-primary"><?= htmlspecialchars($rank->nik) ?></span></td>
										<td>
											<div class="d-flex align-items-center">
												<strong><?= htmlspecialchars($rank->nama_cs) ?></strong>
											</div>
										</td>
										<td><?= htmlspecialchars($rank->nama_produk ?? '-') ?></td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($rank->nama_kanal ?? '-') ?></small>
										</td>
										<td>
											<strong><?= number_format($rank->nilai_akhir, 2, ',', '.') ?></strong>
										</td>
										<td>
											<?php if ($rank->status === 'pending_leader'): ?>
												<span class="badge badge-warning">
													<i class="fe fe-clock"></i> Menunggu Approval Leader
												</span>
											<?php elseif ($rank->status === 'pending_supervisor'): ?>
												<span class="badge badge-info">
													<i class="fe fe-arrow-up"></i> Di Supervisor
												</span>
											<?php elseif ($rank->status === 'rejected_leader'): ?>
												<span class="badge badge-danger">
													<i class="fe fe-x"></i> Ditolak Leader
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
											<?php if ($rank->status === 'pending_leader'): ?>
												<button class="btn btn-sm btn-success btn-approve" data-id="<?= $rank->id_ranking ?>">
													<i class="fe fe-check"></i> Setujui
												</button>
												<button class="btn btn-sm btn-danger btn-reject" data-id="<?= $rank->id_ranking ?>">
													<i class="fe fe-x"></i> Tolak
												</button>
											<?php elseif (!empty($rank->approved_by_leader)): ?>
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

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tolak Ranking</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="rejectNote">Catatan Penolakan <span class="text-danger">*</span></label>
					<textarea class="form-control" id="rejectNote" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-danger" id="btnConfirmReject">
					<i class="fe fe-x"></i> Tolak Ranking
				</button>
			</div>
		</div>
	</div>
</div>

<?php
ob_start();
?>
<script>
	$(document).ready(function() {
		let currentRankingId = null;

		// Filter button
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var url = '<?= base_url('leader/ranking') ?>?';
			if (periode) url += 'periode=' + periode;
			window.location.href = url;
		});

		// Clear filter
		$('#btnClearFilter').on('click', function() {
			window.location.href = '<?= base_url('leader/ranking') ?>';
		});

		// Approve button
		$('.btn-approve').on('click', function(e) {
			e.preventDefault();
			const id = $(this).data('id');

			Swal.fire({
				title: 'Konfirmasi Approval',
				text: 'Apakah Anda yakin ingin menyetujui ranking ini?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Setujui',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url('leader/ranking/approve') ?>/' + id,
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
			$('#rejectNote').val('');
			$('#modalReject').modal('show');
		});

		// Confirm reject
		$('#btnConfirmReject').on('click', function() {
			const note = $('#rejectNote').val().trim();

			if (!note) {
				Swal.fire('Peringatan', 'Catatan penolakan harus diisi', 'warning');
				return;
			}

			$.ajax({
				url: '<?= base_url('leader/ranking/reject') ?>/' + currentRankingId,
				method: 'POST',
				data: { note: note },
				dataType: 'json',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				success: function(response) {
					$('#modalReject').modal('hide');
					if (response.status === 'success') {
						Swal.fire('Berhasil!', response.message, 'success').then(() => {
							location.reload();
						});
					} else {
						Swal.fire('Error', response.message, 'error');
					}
				},
				error: function(xhr) {
					$('#modalReject').modal('hide');
					const response = xhr.responseJSON || {};
					Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
				}
			});
		});
	});
</script>
<?php
add_js(ob_get_clean());
?>

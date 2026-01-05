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
						<label for="filterProduk" class="font-weight-bold">Produk:</label>
						<select class="form-control form-control-sm" id="filterProduk" name="id_produk">
							<option value="">Semua Produk</option>
							<?php if (!empty($produk_list)): ?>
								<?php foreach ($produk_list as $produk): ?>
									<option value="<?= htmlspecialchars($produk->id_produk) ?>" <?= ($selected_produk == $produk->id_produk) ? 'selected' : '' ?>>
										<?= htmlspecialchars($produk->nama_produk) ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-2">
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
								<th width="20%" class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($rankings)): ?>
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
										<td><strong><?= htmlspecialchars($rank->nik) ?></strong></td>
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
													<i class="fe fe-arrow-up"></i> Menunggu Approval Supervisor
												</span>
											<?php elseif ($rank->status === 'rejected_leader'): ?>
												<span class="badge badge-danger">
													<i class="fe fe-x"></i> Ditolak Leader
												</span>
											<?php elseif ($rank->status === 'rejected_supervisor'): ?>
												<span class="badge badge-danger">
													<i class="fe fe-x"></i> Ditolak Supervisor
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
											<!-- Tombol Detail - Selalu tampil -->
											<button class="btn btn-sm btn-info btn-detail mb-1"
												data-id="<?= $rank->id_cs ?>"
												title="Detail Penilaian">
												<i class="fe fe-eye"></i>
											</button>

											<?php if ($rank->status === 'pending_leader'): ?>
												<!-- Tombol Approve & Reject - Hanya untuk status pending_leader -->
												<button class="btn btn-sm btn-success btn-approve mb-1"
													data-id="<?= $rank->id_ranking ?>"
													title="Setujui">
													<i class="fe fe-check"></i>
												</button>
												<button class="btn btn-sm btn-danger btn-reject mb-1"
													data-id="<?= $rank->id_ranking ?>"
													title="Tolak">
													<i class="fe fe-x"></i>
												</button>
											<?php elseif ($rank->status === 'pending_supervisor'): ?>
												<!-- Status: Menunggu Supervisor -->
												<br>
												<span class="badge badge-info">
													<i class="fe fe-check-circle"></i> Disetujui Leader
												</span>
											<?php elseif ($rank->status === 'rejected_leader'): ?>
												<!-- Status: Ditolak oleh Leader -->
												<br>
												<span class="badge badge-danger">
													<i class="fe fe-x-circle"></i> Ditolak
												</span>
												<?php if (!empty($rank->leader_note)): ?>
													<button class="btn btn-sm btn-outline-secondary mt-1"
														data-toggle="tooltip"
														title="<?= htmlspecialchars($rank->leader_note) ?>">
														<i class="fe fe-info"></i>
													</button>
												<?php endif; ?>
											<?php elseif ($rank->status === 'rejected_supervisor'): ?>
												<!-- Status: Ditolak oleh Supervisor -->
												<br>
												<span class="badge badge-danger">
													<i class="fe fe-x-circle"></i> Ditolak Supervisor
												</span>
												<?php if (!empty($rank->supervisor_note)): ?>
													<button class="btn btn-sm btn-outline-secondary mt-1"
														data-toggle="tooltip"
														title="<?= htmlspecialchars($rank->supervisor_note) ?>">
														<i class="fe fe-info"></i>
													</button>
												<?php endif; ?>
											<?php elseif ($rank->status === 'published'): ?>
												<!-- Status: Published -->
												<br>
												<span class="badge badge-success">
													<i class="fe fe-check-circle"></i> Published
												</span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="8" class="text-center py-4">
										<i class="fe fe-inbox text-muted" style="font-size: 48px;"></i>
										<p class="text-muted mt-2 mb-0">Belum ada data ranking untuk periode ini.</p>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
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
				<button type="button" class="btn btn-danger" id="btnConfirmReject" data-url="<?= base_url('leader/ranking/reject/') ?>">
					<i class="fe fe-x"></i> Tolak Ranking
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Detail Ranking -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title">
					<i class="fe fe-bar-chart-2"></i> Detail Perhitungan Ranking
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detailContent">
				<div class="text-center py-5">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
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
			var id_produk = $('#filterProduk').val();
			var url = '<?= base_url('leader/ranking') ?>?';
			var params = [];
			if (periode) params.push('periode=' + encodeURIComponent(periode));
			if (id_produk) params.push('id_produk=' + encodeURIComponent(id_produk));
			if (params.length > 0) url += params.join('&');
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
			const url = $(this).data('url');

			if (!note) {
				Swal.fire('Peringatan', 'Catatan penolakan harus diisi', 'warning');
				return;
			}

			$.ajax({
				url: url + currentRankingId,
				method: 'POST',
				data: {
					note: note
				},
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

		// Detail button - Load detail modal
		$('.btn-detail').on('click', function(e) {
			e.preventDefault();
			const id = $(this).data('id');
			const periode = '<?= htmlspecialchars($selected_periode ?? date('Y-m')) ?>';

			// Show modal
			$('#modalDetail').modal('show');

			// Load content via AJAX
			$('#detailContent').html(`
			<div class="text-center py-5">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
		`);

			$.ajax({
				url: '<?= base_url('leader/ranking/detail') ?>',
				method: 'GET',
				data: {
					id: id,
					periode: periode
				},
				success: function(response) {
					$('#detailContent').html(response);
				},
				error: function(xhr) {
					$('#detailContent').html(`
					<div class="alert alert-danger">
						<i class="fe fe-alert-circle"></i> Gagal memuat data detail.
					</div>
				`);
				}
			});
		});
	});
</script>
<?php
add_js(ob_get_clean());
?>

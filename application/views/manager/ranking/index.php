<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-primary text-white">
				<div class="row align-items-center">
					<div class="col">
						<h5 class="mb-0"><i class="fe fe-award"></i> Hasil Ranking Customer Service</h5>
						<small class="text-white-50">Monitoring hasil ranking di area Junior Manager (Read-Only)</small>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-3">
						<label for="filterPeriode" class="font-weight-bold">Periode:</label>
						<input type="month" class="form-control form-control-sm" id="filterPeriode"
							value="<?= $selected_periode ?? date('Y-m') ?>">
					</div>
					<div class="col-md-2">
						<label for="filterTim" class="font-weight-bold">Tim:</label>
						<select class="form-control form-control-sm" id="filterTim">
							<option value="">-- Semua Tim --</option>
							<?php if (!empty($teams)): ?>
								<?php foreach ($teams as $t): ?>
									<option value="<?= $t->id_tim ?>" <?= ($selected_tim == $t->id_tim) ? 'selected' : '' ?>>
										<?= htmlspecialchars($t->nama_tim) ?>
									</option>
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
									<option value="<?= $p->id_produk ?>" <?= ($selected_produk == $p->id_produk) ? 'selected' : '' ?>>
										<?= htmlspecialchars($p->nama_produk) ?>
									</option>
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
				<div class="table-responsive">
					<table id="dataTable-1" class="table table-hover table-striped datatables">
						<thead>
							<tr>
								<th width="5%">Rank</th>
								<th>NIK</th>
								<th>Nama CS</th>
								<th>Produk</th>
								<th>Tim</th>
								<th>NCF (90%)</th>
								<th>NSF (10%)</th>
								<th>Skor Akhir</th>
								<th width="10%" class="text-center">Approval Leader</th>
								<th width="10%" class="text-center">Approval SPV</th>
								<th width="8%" class="text-center">Aksi</th>
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
										<td><?= htmlspecialchars($rank->nik) ?></td>
										<td>
											<div class="d-flex align-items-center">
												<strong><?= htmlspecialchars($rank->nama_cs) ?></strong>
											</div>
										</td>
										<td><?= htmlspecialchars($rank->nama_produk ?? '-') ?></td>
										<td><?= htmlspecialchars($rank->nama_tim ?? '-') ?></td>
										<td><small class="text-danger"><?= number_format($rank->ncf ?? 0, 2, ',', '.') ?></small></td>
										<td><small class="text-primary"><?= number_format($rank->nsf ?? 0, 2, ',', '.') ?></small></td>
										<td>
											<div class="progress" style="height: 25px;">
												<?php
												$max_score = !empty($rankings) ? max(array_column($rankings, 'skor_akhir') ?: array_column($rankings, 'nilai_akhir')) : 1;
												$current_score = $rank->skor_akhir ?? $rank->nilai_akhir ?? 0;
												$percentage = ($current_score / $max_score) * 100;
												?>
												<div class="progress-bar bg-success" role="progressbar"
													style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>"
													aria-valuemin="0" aria-valuemax="100">
													<strong><?= number_format($current_score, 2, ',', '.') ?></strong>
												</div>
											</div>
										</td>
										<td class="text-center">
											<?php
											$rankStatus = $rank->status ?? 'draft';
											if ($rankStatus === 'rejected_leader'): ?>
												<span class="badge badge-danger"
													data-toggle="tooltip"
													title="Ditolak oleh <?= htmlspecialchars($rank->approved_by_leader_name ?? '') ?>">
													<i class="fe fe-x"></i> Ditolak
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_leader) ? date('d/m/Y', strtotime($rank->approved_at_leader)) : '' ?></small>
												<?php if (!empty($rank->leader_note)): ?>
													<br>
													<button class="btn btn-xs btn-outline-secondary mt-1"
														data-toggle="tooltip"
														title="<?= htmlspecialchars($rank->leader_note) ?>">
														<i class="fe fe-info"></i>
													</button>
												<?php endif; ?>
											<?php elseif (in_array($rankStatus, ['pending_supervisor', 'rejected_supervisor', 'published'])): ?>
												<span class="badge badge-success"
													data-toggle="tooltip"
													title="Disetujui oleh <?= htmlspecialchars($rank->approved_by_leader_name ?? '') ?>">
													<i class="fe fe-check"></i> Approved
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_leader) ? date('d/m/Y', strtotime($rank->approved_at_leader)) : '' ?></small>
											<?php else: ?>
												<span class="badge badge-warning">
													<i class="fe fe-clock"></i> Pending
												</span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<?php
											if ($rankStatus === 'rejected_supervisor'): ?>
												<span class="badge badge-danger"
													data-toggle="tooltip"
													title="Ditolak oleh <?= htmlspecialchars($rank->approved_by_supervisor_name ?? '') ?>">
													<i class="fe fe-x"></i> Ditolak
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_supervisor) ? date('d/m/Y', strtotime($rank->approved_at_supervisor)) : '' ?></small>
												<?php if (!empty($rank->supervisor_note)): ?>
													<br>
													<button class="btn btn-xs btn-outline-secondary mt-1"
														data-toggle="tooltip"
														title="<?= htmlspecialchars($rank->supervisor_note) ?>">
														<i class="fe fe-info"></i>
													</button>
												<?php endif; ?>
											<?php elseif ($rankStatus === 'published'): ?>
												<span class="badge badge-success"
													data-toggle="tooltip"
													title="Disetujui oleh <?= htmlspecialchars($rank->approved_by_supervisor_name ?? '') ?>">
													<i class="fe fe-check"></i> Approved
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_supervisor) ? date('d/m/Y', strtotime($rank->approved_at_supervisor)) : '' ?></small>
											<?php else: ?>
												<span class="badge badge-warning">
													<i class="fe fe-clock"></i> Pending
												</span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-info btn-detail"
												data-id="<?= $rank->id_cs ?>"
												title="Detail">
												<i class="fe fe-eye"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="11" class="text-center py-4">
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
		// Filter button click handler
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var url = '<?= base_url('junior-manager/ranking') ?>?';
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

			window.location.href = '<?= base_url('junior-manager/ranking') ?>';
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
				url: '<?= base_url('junior-manager/ranking/detail') ?>',
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

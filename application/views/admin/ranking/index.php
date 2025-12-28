<!-- Hasil Ranking CS -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-primary text-white">
				<div class="row align-items-center">
					<div class="col">
						<h5 class="mb-0"><i class="fe fe-award"></i> Hasil Ranking Customer Service</h5>
					</div>
					<div class="col-auto">
						<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalProcess">
							<i class="fe fe-refresh-cw"></i> Proses Ranking
						</button>
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
							<?php if (!empty($tim)): ?>
								<?php foreach ($tim as $t): ?>
									<option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-2">
						<label for="filterProduk" class="font-weight-bold">Produk:</label>
						<select class="form-control form-control-sm" id="filterProduk">
							<option value="">-- Semua Produk --</option>
							<?php if (!empty($produk)): ?>
								<?php foreach ($produk as $p): ?>
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
							<i class="fe fe-x-circle"></i> Clear Filter
						</button>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="mb-3">
					<a href="<?= base_url('admin/ranking/export') ?>?periode=<?= urlencode($filter_periode) ?><?= $filter_tim ? '&tim=' . urlencode($filter_tim) : '' ?><?= $filter_produk ? '&produk=' . urlencode($filter_produk) : '' ?>" class="btn btn-success btn-sm" id="btnExport">
						<i class="fe fe-download"></i> Export Excel
					</a>
					<button class="btn btn-info btn-sm" onclick="window.location.reload()">
						<i class="fe fe-refresh-ccw"></i> Refresh
					</button>
				</div>

				<!-- Top 3 Ranking - Podium Style -->
				<?php if (!empty($rankings) && count($rankings) >= 3): ?>
					<div class="row mb-4">
						<div class="col-12">
							<div class="text-center mb-3">
								<h4 class="font-weight-bold">🏆 Top 3 Customer Service Terbaik</h4>
							</div>
						</div>

						<!-- Rank 2 (Kiri - Lebih Pendek) -->
						<div class="col-md-4 text-center">
							<div class="card shadow-sm" style="margin-top: 40px;">
								<div class="card-body">
									<div class="mb-2">
										<span style="display:inline-block; width:50px; height:50px; line-height:50px; font-size:2rem; text-align:center; border-radius:50%; background:#C0C0C0; color:#222;">
											2
										</span>
									</div>
									<h6 class="mb-1 font-weight-bold"><?= $rankings[1]->nama_cs ?></h6>
									<small class="text-muted d-block mb-2">NIK: <?= $rankings[1]->nik ?></small>
									<h4 class="text-secondary mb-0 font-weight-bold">
										<?= number_format($rankings[1]->skor_akhir ?? $rankings[1]->nilai_akhir ?? 0, 2, ',', '.') ?></h4>
								</div>
							</div>
						</div>

						<!-- Rank 1 (Tengah - Paling Tinggi) -->
						<div class="col-md-4 text-center">
							<div class="card shadow-lg border-warning" style="border-width: 3px;">
								<div class="card-body">
									<div class="mb-2">
										<span style="display:inline-block; width:60px; height:60px; line-height:60px; font-size:2.5rem; text-align:center; border-radius:50%; background:#FFD700; color:#222;">
											1
										</span>
									</div>
									<h5 class="mb-1 font-weight-bold text-warning"><?= $rankings[0]->nama_cs ?></h5>
									<small class="text-muted d-block mb-2">NIK: <?= $rankings[0]->nik ?></small>
									<h3 class="text-warning mb-0 font-weight-bold">
										<?= number_format($rankings[0]->skor_akhir ?? $rankings[0]->nilai_akhir ?? 0, 2, ',', '.') ?></h3>
								</div>
							</div>
						</div>

						<!-- Rank 3 (Kanan - Lebih Pendek) -->
						<div class="col-md-4 text-center">
							<div class="card shadow-sm" style="margin-top: 40px;">
								<div class="card-body">
									<div class="mb-2">
										<span style="display:inline-block; width:50px; height:50px; line-height:50px; font-size:2rem; text-align:center; border-radius:50%; background:#CD7F32; color:#fff;">
											3
										</span>
									</div>
									<h6 class="mb-1 font-weight-bold"><?= $rankings[2]->nama_cs ?></h6>
									<small class="text-muted d-block mb-2">NIK: <?= $rankings[2]->nik ?></small>
									<h4 class="text-danger mb-0 font-weight-bold">
										<?= number_format($rankings[2]->skor_akhir ?? $rankings[2]->nilai_akhir ?? 0, 2, ',', '.') ?></h4>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<!-- Full Ranking Table -->
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
								<?php if (!empty($is_saved)): ?>
								<th width="10%" class="text-center">Approval Leader</th>
								<th width="10%" class="text-center">Approval SPV</th>
								<?php endif; ?>
								<th width="10%" class="text-center">Aksi</th>
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
										<td>
											<?= htmlspecialchars($rank->nama_produk ?? '-'); ?>
										</td>
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
										<?php if (!empty($is_saved)): ?>
										<td class="text-center">
											<?php
											$rankStatus = $rank->status ?? 'draft';
											if ($rankStatus === 'rejected_leader'): ?>
												<span class="badge badge-danger" title="Ditolak oleh <?= htmlspecialchars($rank->approved_by_leader_name ?? '') ?>">
													<i class="fe fe-x"></i> Ditolak
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_leader) ? date('d/m/Y', strtotime($rank->approved_at_leader)) : '' ?></small>
											<?php elseif (in_array($rankStatus, ['pending_supervisor', 'rejected_supervisor', 'published'])): ?>
												<span class="badge badge-success" title="Disetujui oleh <?= htmlspecialchars($rank->approved_by_leader_name ?? '') ?>">
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
												<span class="badge badge-danger" title="Ditolak oleh <?= htmlspecialchars($rank->approved_by_supervisor_name ?? '') ?>">
													<i class="fe fe-x"></i> Ditolak
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_supervisor) ? date('d/m/Y', strtotime($rank->approved_at_supervisor)) : '' ?></small>
											<?php elseif ($rankStatus === 'published'): ?>
												<span class="badge badge-success" title="Disetujui oleh <?= htmlspecialchars($rank->approved_by_supervisor_name ?? '') ?>">
													<i class="fe fe-check"></i> Approved
												</span>
												<br><small class="text-muted"><?= !empty($rank->approved_at_supervisor) ? date('d/m/Y', strtotime($rank->approved_at_supervisor)) : '' ?></small>
											<?php else: ?>
												<span class="badge badge-warning">
													<i class="fe fe-clock"></i> Pending
												</span>
											<?php endif; ?>
										</td>
										<?php endif; ?>
										<td class="text-center">
											<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalDetail"
												data-id="<?= $rank->id_cs ?>" title="Detail Nilai">
												<i class="fe fe-eye"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="<?= !empty($is_saved) ? '12' : '10' ?>" class="text-center text-muted py-4">
										<i class="fe fe-inbox fe-24 mb-3"></i>
										<p>Belum ada data ranking untuk periode ini</p>
										<button class="btn btn-primary" data-toggle="modal" data-target="#modalProcess">
											<i class="fe fe-refresh-cw"></i> Proses Ranking Sekarang
										</button>
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

<!-- Modal Proses Ranking -->
<div class="modal fade" id="modalProcess" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fe fe-refresh-cw"></i> Proses Ranking</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('admin/ranking/process') ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="periode_ranking">Periode <span class="text-danger">*</span></label>
					<input type="month" class="form-control" id="periode_ranking" name="periode"
						value="<?= date('Y-m') ?>" required>
				</div>
				<div class="alert alert-info">
					<i class="fe fe-info"></i> <strong>Proses ranking menggunakan metode Profile Matching:</strong>
					<ul class="mb-0 mt-2 small">
						<li><strong>GAP:</strong> Selisih nilai aktual dengan nilai target per sub kriteria</li>
						<li><strong>Mapping GAP:</strong> Konversi GAP ke nilai range sesuai tabel range dan konversi
						</li>
						<li><strong>NCF (Core Factor):</strong> Rata-rata nilai konversi kriteria core_factor (60%)</li>
						<li><strong>NSF (Secondary Factor):</strong> Rata-rata nilai konversi kriteria secondary_factor
							(40%)</li>
						<li><strong>Skor Akhir:</strong> (NCF × 0.6) + (NSF × 0.4)</li>
					</ul>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">
					<i class="fe fe-refresh-cw"></i> Proses Sekarang
				</button>
			</div>
			<?= form_close() ?>
		</div>
	</div>
</div>

<!-- Modal Detail Nilai -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fe fe-eye"></i> Detail Nilai & Perhitungan</h5>
				<button type="button" class="close" data-dismiss="modal">
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
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<?php
ob_start();
?>
<script>
	$(document).ready(function() {
		// Load modal details via AJAX when shown
		$('#modalDetail').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var periode = $('#filterPeriode').val() || '<?= isset($filter_periode) ? $filter_periode : date('Y-m') ?>';

			var $content = $('#detailContent');
			$content.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

			if (!id) {
				$content.html('<div class="p-3 text-danger">ID tidak ditemukan.</div>');
				return;
			}

			$.get('<?= base_url('admin/ranking/detail') ?>', {
					id: id,
					periode: periode
				})
				.done(function(html) {
					$content.html(html);
				})
				.fail(function() {
					$content.html('<div class="p-3 text-danger">Gagal memuat detail. Coba lagi.</div>');
				});
		});

		// Clear content when modal hidden
		$('#modalDetail').on('hidden.bs.modal', function() {
			$('#detailContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
		});

		// Initialize filter inputs from server-side values if provided
		var initialPeriode = '<?= isset($filter_periode) ? $filter_periode : '' ?>';
		var initialTim = '<?= isset($filter_tim) ? $filter_tim : '' ?>';
		var initialProduk = '<?= isset($filter_produk) ? $filter_produk : '' ?>';

		if (initialPeriode) {
			$('#filterPeriode').val(initialPeriode);
		}
		if (initialTim) {
			$('#filterTim').val(initialTim);
		}
		if (initialProduk) {
			$('#filterProduk').val(initialProduk);
		}

		// Filter button handler: redirect with query params
		$('#btnFilter').on('click', function(e) {
			e.preventDefault();
			var periode = $('#filterPeriode').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var params = {};
			if (periode) params.periode = periode;
			if (tim) params.tim = tim;
			if (produk) params.produk = produk;

			var query = $.param(params);
			var url = '<?= base_url('admin/ranking') ?>' + (query ? ('?' + query) : '');
			window.location.href = url;
		});

		// Clear Filter button handler
		$('#btnClearFilter').on('click', function(e) {
			e.preventDefault();

			// Reset form inputs to default
			$('#filterPeriode').val('<?= date('Y-m') ?>');
			$('#filterTim').val('');
			$('#filterProduk').val('');

			// Redirect to base URL without query params
			window.location.href = '<?= base_url('admin/ranking') ?>';
		});

		// Update export link when filters change
		function updateExportLink() {
			var periode = $('#filterPeriode').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var params = {};
			if (periode) params.periode = periode;
			if (tim) params.tim = tim;
			if (produk) params.produk = produk;

			var query = $.param(params);
			var exportUrl = '<?= base_url('admin/ranking/export') ?>' + (query ? ('?' + query) : '');
			$('#btnExport').attr('href', exportUrl);
		}

		// Update export link on filter change
		$('#filterPeriode, #filterTim, #filterProduk').on('change', updateExportLink);

		// Initial update
		updateExportLink();
	});
</script>
<?php
add_js(ob_get_clean());
?>

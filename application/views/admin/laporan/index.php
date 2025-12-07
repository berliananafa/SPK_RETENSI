<!-- Laporan Performa CS -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-info text-white">
				<h5 class="mb-0"><i class="fe fe-file-text"></i> Laporan Performa Customer Service</h5>
			</div>
			<div class="card-body">
				<!-- Info Profile Matching -->
				<div class="alert alert-info mb-4">
					<strong><i class="fe fe-info"></i> Metode Profile Matching</strong><br>
					<small>Penilaian berdasarkan selisih (GAP) nilai aktual dengan target,
						dengan pembobotan NCF (Core Factor 60%) dan NSF (Secondary Factor 40%).</small>
				</div>

				<!-- Filter Section -->
				<div class="row mb-4">
					<div class="col-md-3">
						<label class="font-weight-bold">Periode:</label>
						<input type="month" class="form-control form-control-sm" id="filterPeriode"
							value="<?= $filter_periode ?>">
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold">Tim:</label>
						<select class="form-control form-control-sm" id="filterTim">
							<option value="">-- Semua Tim --</option>
							<?php foreach ($tim as $t): ?>
								<option value="<?= $t->id_tim ?>" <?= $filter_tim == $t->id_tim ? 'selected' : '' ?>>
									<?= htmlspecialchars($t->nama_tim) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="font-weight-bold">Produk:</label>
						<select class="form-control form-control-sm" id="filterProduk">
							<option value="">-- Semua Produk --</option>
							<?php foreach ($produk as $p): ?>
								<option value="<?= $p->id_produk ?>" <?= $filter_produk == $p->id_produk ? 'selected' : '' ?>>
									<?= htmlspecialchars($p->nama_produk) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="d-block">&nbsp;</label>
						<button class="btn btn-info btn-sm btn-block" id="btnFilter">
							<i class="fe fe-filter"></i> Tampilkan
						</button>
					</div>
				</div>

				<!-- Summary Statistics -->
				<div class="row mb-4">
					<div class="col-md-3">
						<div class="card bg-primary text-white">
							<div class="card-body text-center">
								<h2 class="mb-0"><?= $statistik['total_cs'] ?></h2>
								<small>Total CS</small>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card bg-success text-white">
							<div class="card-body text-center">
								<h2 class="mb-0"><?= number_format($statistik['avg_skor'], 2) ?></h2>
								<small>Rata-rata Skor</small>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card bg-warning text-white">
							<div class="card-body text-center">
								<h2 class="mb-0"><?= $statistik['excellent'] ?></h2>
								<small>Excellent (≥4.0)</small>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card bg-danger text-white">
							<div class="card-body text-center">
								<h2 class="mb-0"><?= $statistik['poor'] ?></h2>
								<small>Perlu Perbaikan (&lt;2.5)</small>
							</div>
						</div>
					</div>
				</div>

				<!-- Charts -->
				<div class="row mb-4">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<strong><i class="fe fe-pie-chart"></i> Distribusi Kategori Performa</strong>
							</div>
							<div class="card-body">
								<canvas id="chartKategori" height="200"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<strong><i class="fe fe-bar-chart-2"></i> Rata-rata Per Kriteria</strong>
							</div>
							<div class="card-body">
								<canvas id="chartKriteria" height="200"></canvas>
							</div>
						</div>
					</div>
				</div>

				<!-- Top & Bottom Performers -->
				<div class="row mb-4">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header bg-success text-white">
								<strong><i class="fe fe-award"></i> Top 5 Performers</strong>
							</div>
							<div class="card-body p-0">
								<table class="table table-sm table-hover mb-0">
									<thead>
										<tr>
											<th width="10%">Rank</th>
											<th>Nama CS</th>
											<th>Produk</th>
											<th>Tim</th>
											<th width="15%">Skor</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($top_performers)): ?>
											<?php foreach ($top_performers as $i => $p): ?>
												<tr>
													<td><?= $i + 1 ?></td>
													<td><?= htmlspecialchars($p->nama_cs) ?></td>
													<td><?= htmlspecialchars($p->nama_produk) ?></td>
													<td><small><?= htmlspecialchars($p->nama_tim) ?></small></td>
													<td><strong><?= number_format($p->avg_skor, 2) ?></strong></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="4" class="text-center text-muted">Tidak ada data</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header bg-danger text-white">
								<strong><i class="fe fe-alert-triangle"></i> Perlu Perbaikan</strong>
							</div>
							<div class="card-body p-0">
								<table class="table table-sm table-hover mb-0">
									<thead>
										<tr>
											<th width="10%">No</th>
											<th>Nama CS</th>
											<th>Produk</th>
											<th>Tim</th>
											<th width="15%">Skor</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($bottom_performers)): ?>
											<?php foreach ($bottom_performers as $i => $p): ?>
												<tr>
													<td><?= $i + 1 ?></td>
													<td><?= htmlspecialchars($p->nama_cs) ?></td>
													<td><?= htmlspecialchars($p->nama_produk) ?></td>
													<td><small><?= htmlspecialchars($p->nama_tim) ?></small></td>
													<td><strong><?= number_format($p->avg_skor, 2) ?></strong></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="4" class="text-center text-muted">Tidak ada data</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="text-center">
					<hr>
					<a href="<?= base_url('admin/laporan/export?periode=' . $filter_periode) ?>" class="btn btn-primary">
						<i class="fe fe-download"></i> Export Excel
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php ob_start(); ?>
<script>
	$(document).ready(function() {
		// Filter handler
		$('#btnFilter').click(function() {
			var periode = $('#filterPeriode').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var params = [];
			if (periode) params.push('periode=' + periode);
			if (tim) params.push('id_tim=' + tim);
			if (produk) params.push('id_produk=' + produk);

			window.location.href = '<?= base_url('admin/laporan') ?>?' + params.join('&');
		});

		// Chart Kategori
		new Chart(document.getElementById('chartKategori'), {
			type: 'doughnut',
			data: {
				labels: ['Excellent (≥4)', 'Good (3-4)', 'Average (2.5-3)', 'Poor (<2.5)'],
				datasets: [{
					data: [
						<?= $chart_kategori['excellent'] ?>,
						<?= $chart_kategori['good'] ?>,
						<?= $chart_kategori['average'] ?>,
						<?= $chart_kategori['poor'] ?>
					],
					backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545']
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						position: 'bottom'
					}
				}
			}
		});

		// Chart Kriteria
		new Chart(document.getElementById('chartKriteria'), {
			type: 'bar',
			data: {
				labels: <?= json_encode($chart_kriteria['labels']) ?>,
				datasets: [{
					label: 'Rata-rata Nilai',
					data: <?= json_encode($chart_kriteria['data']) ?>,
					backgroundColor: '#4e73df'
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						max: 100
					}
				}
			}
		});
	});
</script>
<?php add_js(ob_get_clean()); ?>

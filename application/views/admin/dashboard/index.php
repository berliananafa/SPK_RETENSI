<!-- Summary Cards Row -->
<div class="row mb-2">
	<!-- Total Users Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-primary" style="border-left: 4px solid #4e73df !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Pengguna</span>
						<span class="h3 font-weight-bold mb-0 text-primary"><?= number_format($total_users ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-users text-primary mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total CS Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-success" style="border-left: 4px solid #1cc88a !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Customer Service</span>
						<span class="h3 font-weight-bold mb-0 text-success"><?= number_format($total_cs ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-user-check text-success mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Kriteria Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-info" style="border-left: 4px solid #36b9cc !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Kriteria</span>
						<span class="h3 font-weight-bold mb-0 text-info"><?= number_format($total_criteria ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-list text-info mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Rankings Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-warning" style="border-left: 4px solid #f6c23e !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Ranking</span>
						<span class="h3 font-weight-bold mb-0 text-warning"><?= number_format($total_rankings ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-award text-warning mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Additional Cards Row -->
<div class="row mb-4">
	<!-- Total Tim Card -->
	<div class="col-md-6 col-xl-4 mb-4">
		<div class="card shadow border-0 border-left-danger" style="border-left: 4px solid #e74a3b !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Tim</span>
						<span class="h3 font-weight-bold mb-0 text-danger"><?= number_format($total_teams ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-users text-danger mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Produk Card -->
	<div class="col-md-6 col-xl-4 mb-4">
		<div class="card shadow border-0" style="border-left: 4px solid #5a5c69 !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Produk</span>
						<span class="h3 font-weight-bold mb-0" style="color: #5a5c69;"><?= number_format($total_produk ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-package mb-0" style="opacity: 0.2; color: #5a5c69;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Kanal Card -->
	<div class="col-md-6 col-xl-4 mb-4">
		<div class="card shadow border-0" style="border-left: 4px solid #858796 !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Kanal</span>
						<span class="h3 font-weight-bold mb-0" style="color: #858796;"><?= number_format($total_kanal ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-hash mb-0" style="opacity: 0.2; color: #858796;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Main Content Row -->
<div class="row">
	<!-- Quick Actions -->
	<div class="col-lg-4 mb-4">
		<div class="card shadow">
			<div class="card-header text-white">
				<strong><i class="fe fe-zap"></i> Menu Cepat</strong>
			</div>
			<div class="list-group list-group-flush">
				<a href="<?= base_url('admin/customer-service') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-user-plus text-success mr-3 fe-20"></i>
					<div>
						<strong>Kelola CS</strong>
						<div class="small text-muted">Tambah & edit data CS</div>
					</div>
				</a>
				<a href="<?= base_url('admin/kriteria') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-sliders text-info mr-3 fe-20"></i>
					<div>
						<strong>Kelola Kriteria</strong>
						<div class="small text-muted">Atur kriteria penilaian</div>
					</div>
				</a>
				<a href="<?= base_url('admin/nilai') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-edit text-primary mr-3 fe-20"></i>
					<div>
						<strong>Input Nilai</strong>
						<div class="small text-muted">Masukkan nilai evaluasi</div>
					</div>
				</a>
				<a href="<?= base_url('admin/ranking') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-trending-up text-warning mr-3 fe-20"></i>
					<div>
						<strong>Proses Ranking</strong>
						<div class="small text-muted">Hitung ranking CS</div>
					</div>
				</a>
				<a href="<?= base_url('admin/laporan') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-file-text text-danger mr-3 fe-20"></i>
					<div>
						<strong>Laporan</strong>
						<div class="small text-muted">Export laporan</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<!-- Top 10 Ranking Table -->
	<div class="col-lg-8 mb-4">
		<div class="card shadow">
			<div class="card-header  d-flex justify-content-between align-items-center">
				<strong><i class="fe fe-award"></i> Top 5 Customer Service Terbaik</strong>
				<?php if (!empty($current_periode)): ?>
					<span class="badge badge-light">
						<?= date('F Y', strtotime($current_periode . '-01')) ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="card-body p-0">
				<?php if (!empty($top_cs)): ?>
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="bg-light">
								<tr>
									<th width="8%" class="text-center">Rank</th>
									<th width="15%">NIK</th>
									<th>Nama CS</th>
									<th width="20%">Tim</th>
									<th width="15%" class="text-center">Skor</th>
									<th width="12%" class="text-center">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($top_cs as $i => $cs): ?>
									<?php
									$badgeClass = 'primary';
									$statusText = 'Baik';
									$statusClass = 'info';

									// Special styling untuk top 3
									if ($i == 0) {
										$badgeClass = 'warning';
										$statusText = 'Excellent';
										$statusClass = 'warning';
									} elseif ($i == 1) {
										$badgeClass = 'secondary';
										$statusText = 'Sangat Baik';
										$statusClass = 'success';
									} elseif ($i == 2) {
										$badgeClass = 'danger';
										$statusText = 'Sangat Baik';
										$statusClass = 'success';
									}
									
									// Tentukan status berdasarkan nilai
									if ($cs->nilai_akhir >= 4.5) {
										$statusText = 'Excellent';
										$statusClass = 'success';
									} elseif ($cs->nilai_akhir >= 4.0) {
										$statusText = 'Sangat Baik';
										$statusClass = 'success';
									} elseif ($cs->nilai_akhir >= 3.5) {
										$statusText = 'Baik';
										$statusClass = 'info';
									} elseif ($cs->nilai_akhir >= 3.0) {
										$statusText = 'Cukup';
										$statusClass = 'warning';
									} else {
										$statusText = 'Kurang';
										$statusClass = 'danger';
									}
									?>
									<tr>
										<td class="text-center">
											<?php if ($i < 3): ?>
												<span class="badge badge-<?= $badgeClass ?> badge-lg" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
													<?= $i + 1 ?>
												</span>
											<?php else: ?>
												<strong style="font-size: 1.1rem;"><?= $i + 1 ?></strong>
											<?php endif; ?>
										</td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($cs->nik) ?></small>
										</td>
										<td>
											<strong><?= htmlspecialchars($cs->nama_cs) ?></strong>
										</td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($cs->nama_tim ?? '-') ?></small>
										</td>
										<td class="text-center">
											<span class="badge badge-light" style="font-size: 0.95rem; padding: 0.4rem 0.8rem;">
												<i class="fe fe-star text-warning"></i>
												<strong><?= number_format($cs->nilai_akhir, 2, ',', '.') ?></strong>
											</span>
										</td>
										<td class="text-center">
											<span class="badge badge-<?= $statusClass ?>" style="font-size: 0.85rem; padding: 0.35rem 0.7rem;">
												<?= $statusText ?>
											</span>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<!-- Footer dengan link -->
					<div class="card-footer bg-light text-center">
						<a href="<?= base_url('admin/ranking?periode=' . ($current_periode ?? '')) ?>"
							class="btn btn-primary btn-sm">
							<i class="fe fe-arrow-right"></i> Lihat Semua Ranking
						</a>
					</div>

				<?php else: ?>
					<div class="text-center py-5">
						<i class="fe fe-inbox fe-48 text-muted mb-3"></i>
						<h6 class="text-muted">Belum Ada Data Ranking</h6>
						<p class="text-muted small">Data ranking akan ditampilkan setelah proses penilaian selesai</p>
						<a href="<?= base_url('admin/ranking') ?>" class="btn btn-primary btn-sm mt-2">
							<i class="fe fe-plus"></i> Proses Ranking Sekarang
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

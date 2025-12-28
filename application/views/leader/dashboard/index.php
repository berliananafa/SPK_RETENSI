<!-- Team Info Banner -->
<?php if ($team): ?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="card shadow border-0" style="border-left: 4px solid #4e73df !important;">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col-auto">
							<span class="h1 fe fe-users text-primary mb-0" style="opacity: 0.3;"></span>
						</div>
						<div class="col">
							<h4 class="mb-1 font-weight-bold text-primary"><?= htmlspecialchars($team->nama_tim) ?></h4>
							<p class="text-muted mb-0">
								<i class="fe fe-user"></i> Leader: <strong><?= htmlspecialchars($team->nama_leader ?? '-') ?></strong>
								<?php if (!empty($team->nama_supervisor)): ?>
									| <i class="fe fe-user-check"></i> Supervisor: <strong><?= htmlspecialchars($team->nama_supervisor) ?></strong>
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="alert alert-warning">
				<i class="fe fe-alert-triangle"></i> Anda belum memimpin tim. Silakan hubungi administrator.
			</div>
		</div>
	</div>
<?php endif; ?>

<!-- Summary Cards Row -->
<div class="row mb-2">
	<!-- Total CS Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-success" style="border-left: 4px solid #1cc88a !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Anggota Tim</span>
						<span class="h3 font-weight-bold mb-0 text-success"><?= number_format($total_cs ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-user text-success mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Penilaian Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-warning" style="border-left: 4px solid #f6c23e !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Penilaian</span>
						<span class="h3 font-weight-bold mb-0 text-warning"><?= number_format($total_penilaian ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-edit text-warning mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Total Ranking Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-info" style="border-left: 4px solid #36b9cc !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Total Ranking</span>
						<span class="h3 font-weight-bold mb-0 text-info"><?= number_format($total_rankings ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-award text-info mb-0" style="opacity: 0.2;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Pending Approvals Card -->
	<div class="col-md-6 col-xl-3 mb-4">
		<div class="card shadow border-0 border-left-danger" style="border-left: 4px solid #e74a3b !important;">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<span class="h6 font-weight-bold text-muted text-uppercase d-block mb-2">Perlu Approval</span>
						<span class="h3 font-weight-bold mb-0 text-danger"><?= number_format($pending_approvals ?? 0) ?></span>
					</div>
					<div class="col-auto">
						<span class="h2 fe fe-alert-circle text-danger mb-0" style="opacity: 0.2;"></span>
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
				<a href="<?= base_url('leader/ranking') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-trending-up text-primary mr-3 fe-20"></i>
					<div>
						<strong>Ranking Tim</strong>
						<div class="small text-muted">Lihat peringkat tim Anda</div>
						<?php if ($pending_approvals > 0): ?>
							<span class="badge badge-danger badge-sm"><?= $pending_approvals ?> Pending</span>
						<?php endif; ?>
					</div>
				</a>
				<a href="<?= base_url('leader/customer-service') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-users text-success mr-3 fe-20"></i>
					<div>
						<strong>Anggota Tim</strong>
						<div class="small text-muted">Lihat anggota tim Anda</div>
					</div>
				</a>
				<a href="<?= base_url('leader/nilai') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-edit text-warning mr-3 fe-20"></i>
					<div>
						<strong>Penilaian</strong>
						<div class="small text-muted">Lihat data penilaian</div>
					</div>
				</a>
				<a href="<?= base_url('leader/laporan') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
					<i class="fe fe-file-text text-info mr-3 fe-20"></i>
					<div>
						<strong>Laporan</strong>
						<div class="small text-muted">Export laporan tim</div>
					</div>
				</a>
			</div>
		</div>

		<!-- Recent Evaluations Card -->
		<div class="card shadow mt-4">
			<div class="card-header">
				<strong><i class="fe fe-activity"></i> Penilaian Terbaru</strong>
			</div>
			<div class="card-body p-0">
				<?php if (!empty($recent_nilai)): ?>
					<div class="list-group list-group-flush">
						<?php foreach ($recent_nilai as $nilai): ?>
							<div class="list-group-item">
								<div class="d-flex justify-content-between align-items-start">
									<div class="flex-grow-1">
										<h6 class="mb-1 font-weight-bold"><?= htmlspecialchars($nilai->nama_cs) ?></h6>
										<p class="mb-1 small text-muted">
											<i class="fe fe-list"></i> <?= htmlspecialchars($nilai->nama_kriteria) ?>
											- <?= htmlspecialchars($nilai->nama_sub_kriteria) ?>
										</p>
										<small class="text-muted">
											<i class="fe fe-clock"></i> <?= date('d M Y H:i', strtotime($nilai->created_at)) ?>
										</small>
									</div>
									<div>
										<span class="badge badge-primary badge-lg">
											<?= number_format($nilai->nilai, 0, ',', '.') ?>
										</span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="text-center py-4">
						<i class="fe fe-inbox fe-32 text-muted mb-2"></i>
						<p class="text-muted small mb-0">Belum ada penilaian</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Top 5 Ranking Table -->
	<div class="col-lg-8 mb-4">
		<div class="card shadow">
			<div class="card-header d-flex justify-content-between align-items-center">
				<strong><i class="fe fe-award"></i> Top 5 Customer Service Terbaik</strong>
				<?php if (!empty($current_periode)): ?>
					<span class="badge badge-light">
						<?php
						$periodeStr = is_object($current_periode) ? $current_periode->periode : $current_periode;
						echo date('F Y', strtotime($periodeStr . '-01'));
						?>
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
									<th width="20%">Produk</th>
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
											<small class="text-muted"><?= htmlspecialchars($cs->nama_produk ?? '-') ?></small>
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
						<a href="<?= base_url('leader/ranking' . (!empty($current_periode) ? '?periode=' . (is_object($current_periode) ? $current_periode->periode : $current_periode) : '')) ?>"
							class="btn btn-primary btn-sm">
							<i class="fe fe-arrow-right"></i> Lihat Semua Ranking
						</a>
					</div>

				<?php else: ?>
					<div class="text-center py-5">
						<i class="fe fe-inbox fe-48 text-muted mb-3"></i>
						<h6 class="text-muted">Belum Ada Data Ranking</h6>
						<p class="text-muted small">Data ranking akan ditampilkan setelah proses penilaian selesai</p>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Team Members Table -->
		<div class="card shadow mt-4">
			<div class="card-header">
				<strong><i class="fe fe-users"></i> Anggota Tim</strong>
			</div>
			<div class="card-body">
				<?php if (!empty($team_members)): ?>
					<div class="table-responsive">
						<table class="table table-hover mb-0" id="dataTable-1">
							<thead class="bg-light">
								<tr>
									<th>NIK</th>
									<th>Nama CS</th>
									<th>Produk</th>
									<th>Kanal</th>
									<th class="text-center">Total Penilaian</th>
									<th class="text-center">Ranking</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($team_members as $member): ?>
									<tr>
										<td><small class="text-muted"><?= htmlspecialchars($member->nik) ?></small></td>
										<td><strong><?= htmlspecialchars($member->nama_cs) ?></strong></td>
										<td><small class="text-muted"><?= htmlspecialchars($member->nama_produk ?? '-') ?></small></td>
										<td><small class="text-muted"><?= htmlspecialchars($member->nama_kanal ?? '-') ?></small></td>
										<td class="text-center">
											<span class="badge badge-info"><?= number_format($member->total_penilaian ?? 0) ?></span>
										</td>
										<td class="text-center">
											<?php if (!empty($member->peringkat)): ?>
												<span class="badge badge-primary">
													#<?= $member->peringkat ?>
													<?php if (!empty($member->nilai_akhir)): ?>
														(<?= number_format($member->nilai_akhir, 2, ',', '.') ?>)
													<?php endif; ?>
												</span>
											<?php else: ?>
												<span class="text-muted small">-</span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="text-center py-4">
						<i class="fe fe-inbox fe-32 text-muted mb-2"></i>
						<p class="text-muted small mb-0">Belum ada anggota tim</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

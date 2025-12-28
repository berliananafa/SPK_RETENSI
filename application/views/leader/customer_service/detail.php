<div class="row justify-content-center">
	<div class="col-12">
		<div class="row">
			<!-- CS Profile Card -->
			<div class="col-md-4">
				<div class="card shadow">
					<div class="card-body text-center">
						<div class="avatar avatar-xl mx-auto mb-3">
							<img src="https://ui-avatars.com/api/?name=<?= urlencode($cs->nama_cs) ?>&size=128&background=random"
								alt="Avatar" class="avatar-img rounded-circle">
						</div>
						<h4 class="mb-1 card-title"><?= htmlspecialchars($cs->nama_cs) ?></h4>
						<p class="mb-3 text-muted small">NIK: <?= htmlspecialchars($cs->nik) ?></p>

						<div class="mb-4">
							<span class="badge badge-soft-primary"><?= htmlspecialchars($cs->nama_produk) ?></span>
							<span class="badge badge-soft-info"><?= htmlspecialchars($cs->nama_kanal) ?></span>
						</div>

						<hr>

						<div class="text-left">
							<div class="mb-2 row">
								<div class="col-5">
									<strong class="small">Tim:</strong>
								</div>
								<div class="col-7">
									<span class="small"><?= htmlspecialchars($cs->nama_tim) ?></span>
								</div>
							</div>
							<div class="mb-2 row">
								<div class="col-5">
									<strong class="small">Total Penilaian:</strong>
								</div>
								<div class="col-7">
									<span class="badge badge-soft-success"><?= $stats->total_penilaian ?></span>
								</div>
							</div>
							<div class="mb-2 row">
								<div class="col-5">
									<strong class="small">Rata-rata Nilai:</strong>
								</div>
								<div class="col-7">
									<span class="badge badge-soft-warning"><?= number_format($stats->rata_rata_nilai, 2) ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Statistics Card -->
				<div class="mt-3 card shadow">
					<div class="card-body">
						<h5 class="mb-3 card-title">Statistik Penilaian</h5>
						<div class="mb-3">
							<div class="d-flex justify-content-between mb-1">
								<span class="text-muted small">Nilai Tertinggi</span>
								<strong class="text-success"><?= number_format($stats->nilai_max, 0, ',', '.') ?></strong>
							</div>
							<div class="progress" style="height: 5px;">
								<div class="progress-bar bg-success" role="progressbar"
									style="width: <?= ($stats->nilai_max > 0) ? ($stats->nilai_max / 100 * 100) : 0 ?>%"></div>
							</div>
						</div>
						<div class="mb-3">
							<div class="d-flex justify-content-between mb-1">
								<span class="text-muted small">Nilai Rata-rata</span>
								<strong class="text-warning"><?= number_format($stats->rata_rata_nilai, 0, ',', '.') ?></strong>
							</div>
							<div class="progress" style="height: 5px;">
								<div class="progress-bar bg-warning" role="progressbar"
									style="width: <?= ($stats->rata_rata_nilai > 0) ? ($stats->rata_rata_nilai / 100 * 100) : 0 ?>%"></div>
							</div>
						</div>
						<div>
							<div class="d-flex justify-content-between mb-1">
								<span class="text-muted small">Nilai Terendah</span>
								<strong class="text-danger"><?= number_format($stats->nilai_min, 0, ',', '.') ?></strong>
							</div>
							<div class="progress" style="height: 5px;">
								<div class="progress-bar bg-danger" role="progressbar"
									style="width: <?= ($stats->nilai_min > 0) ? ($stats->nilai_min / 100 * 100) : 0 ?>%"></div>
							</div>
						</div>
					</div>
				</div>

				<!-- Latest Ranking Overview -->
				<div class="mt-3 card shadow">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<h5 class="mb-0 card-title">Hasil Ranking Terbaru</h5>
							<?php if (!empty($selected_periode)): ?>
								<span class="badge badge-soft-primary">Periode <?= htmlspecialchars($selected_periode) ?></span>
							<?php endif; ?>
						</div>
						<?php if (!empty($ranking)): ?>
							<div class="row align-items-center">
								<div class="col-auto text-center pr-0">
									<div class="display-4 mb-0 font-weight-bold text-primary">#<?= (int)$ranking->peringkat ?></div>
									<small class="text-muted">Peringkat</small>
								</div>
								<div class="col">
									<div class="p-3 bg-light rounded border">
										<div class="d-flex justify-content-between">
											<span class="text-muted">Nilai Akhir</span>
											<strong><?= number_format($ranking->nilai_akhir, 2) ?></strong>
										</div>
										<div class="progress mt-2" style="height: 6px;">
											<div class="progress-bar bg-primary" role="progressbar" style="width: <?= min(max($ranking->nilai_akhir, 0), 100) ?>%"></div>
										</div>
									</div>
								</div>
							</div>
							<?php if (!empty($ranking->status)): ?>
								<span class="badge badge-soft-success mt-2">Status: <?= htmlspecialchars($ranking->status) ?></span>
							<?php endif; ?>
						<?php else: ?>
							<div class="text-center py-3 text-muted">
								<i class="fe fe-bar-chart-2 fe-24 mb-2"></i>
								<div>Belum ada hasil ranking untuk periode ini</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Evaluation History -->
			<div class="col-md-8">
				<div class="card shadow">
					<div class="card-header">
						<h5 class="mb-0 card-title">Histori Penilaian</h5>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped" id="dataTable-1">
								<thead>
									<tr>
										<th width="50">No</th>
										<th>Tanggal</th>
										<th>Kriteria</th>
										<th>Sub Kriteria</th>
										<th>Nilai</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($evaluations)): ?>
										<?php foreach ($evaluations as $index => $eval): ?>
											<tr>
												<td><?= $index + 1 ?></td>
												<td><?= date('d M Y', strtotime($eval->tanggal)) ?></td>
												<td><?= htmlspecialchars($eval->nama_kriteria) ?></td>
												<td><?= htmlspecialchars($eval->nama_sub_kriteria) ?></td>
												<td>
													<?php
													$badgeClass = 'badge-soft-secondary';
													if ($eval->nilai >= 80) {
														$badgeClass = 'badge-soft-success';
													} elseif ($eval->nilai >= 60) {
														$badgeClass = 'badge-soft-primary';
													} elseif ($eval->nilai >= 40) {
														$badgeClass = 'badge-soft-warning';
													} else {
														$badgeClass = 'badge-soft-danger';
													}
													?>
													<span class="badge <?= $badgeClass ?>"><?= number_format($eval->nilai, 0, ',', '.') ?></span>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="5" class="text-center">
												<div class="py-4 text-muted">
													<i class="fe fe-inbox fe-24 mb-2"></i>
													<p>Belum ada histori penilaian</p>
												</div>
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
	</div>
</div>

<!-- Header Section -->
<div class="bg-light border-bottom p-3">
	<div class="d-flex justify-content-between align-items-start">
		<div>
			<h5 class="mb-1 text-dark">
				<i class="fas fa-user-circle me-2"></i>
				<?= htmlspecialchars($cs->nama_cs ?? '-') ?>
			</h5>
			<div class="text-muted small">
				<span class="badge bg-secondary me-2">
					<i class="fas fa-id-card me-1"></i>
					<?= htmlspecialchars($cs->nik ?? '-') ?>
				</span>
				<span class="badge bg-info">
					<i class="far fa-calendar-alt me-1"></i>
					<?= htmlspecialchars($periode) ?>
				</span>
			</div>
		</div>
	</div>

	<!-- Info Tambahan -->
	<div class="mt-3 pt-3 border-top">
		<div class="row g-2">
			<?php if (!empty($cs->nama_tim)): ?>
				<div class="col-md-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-users text-primary me-2"></i>
						<div>
							<div class="small text-muted">Tim</div>
							<strong class="text-dark"><?= htmlspecialchars($cs->nama_tim) ?></strong>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($cs->nama_produk)): ?>
				<div class="col-md-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-box text-success me-2"></i>
						<div>
							<div class="small text-muted">Produk</div>
							<strong class="text-dark"><?= htmlspecialchars($cs->nama_produk) ?></strong>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($cs->nama_leader)): ?>
				<div class="col-md-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-user-tie text-warning me-2"></i>
						<div>
							<div class="small text-muted">Leader</div>
							<strong class="text-dark"><?= htmlspecialchars($cs->nama_leader) ?></strong>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Score Cards -->
<div class="p-3 bg-white border-bottom">
	<div class="row g-3">
		<div class="col-md-4">
			<div class="card border-danger shadow-sm h-100">
				<div class="card-body text-center p-3">
					<div class="text-danger mb-1">
						<i class="fas fa-star fa-lg"></i>
					</div>
					<div class="small text-muted mb-1">NCF (Core Factor)</div>
					<h4 class="mb-0 text-danger fw-bold"><?= number_format($ncf, 2) ?></h4>
					<div class="progress mt-2" style="height: 4px;">
						<div class="progress-bar bg-danger" style="width: <?= ($ncf / 5) * 100 ?>%"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card border-primary shadow-sm h-100">
				<div class="card-body text-center p-3">
					<div class="text-primary mb-1">
						<i class="fas fa-certificate fa-lg"></i>
					</div>
					<div class="small text-muted mb-1">NSF (Secondary Factor)</div>
					<h4 class="mb-0 text-primary fw-bold"><?= number_format($nsf, 2) ?></h4>
					<div class="progress mt-2" style="height: 4px;">
						<div class="progress-bar bg-primary" style="width: <?= ($nsf / 5) * 100 ?>%"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card border-success shadow-sm h-100">
				<div class="card-body text-center p-3">
					<div class="text-success mb-1">
						<i class="fas fa-trophy fa-lg"></i>
					</div>
					<div class="small text-muted mb-1">Skor Akhir</div>
					<h4 class="mb-0 text-success fw-bold"><?= number_format($skor, 2) ?></h4>
					<div class="progress mt-2" style="height: 4px;">
						<div class="progress-bar bg-success" style="width: <?= ($skor / 5) * 100 ?>%"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Formula Info -->
	<div class="alert alert-info mt-3 mb-0 py-2">
		<small>
			<i class="fas fa-info-circle me-1"></i>
			<strong>Formula:</strong> Skor Akhir = (NCF × 60%) + (NSF × 40%) = (<?= number_format($ncf, 2) ?> × 0.6)
			+ (<?= number_format($nsf, 2) ?> × 0.4) = <strong><?= number_format($skor, 2) ?></strong>
		</small>
	</div>
</div>

<!-- Detail Breakdown Table -->
<div class="p-3">
	<h6 class="mb-3">
		<i class="fas fa-table me-2"></i>
		Detail Breakdown Per Kriteria
	</h6>

	<div class="table-responsive">
		<table class="table table-sm table-hover table-bordered mb-0">
			<thead>
				<tr>
					<th width="8%" class="text-center">Kode</th>
					<th>Sub Kriteria</th>
					<th width="9%" class="text-center">Nilai</th>
					<th width="9%" class="text-center">Target</th>
					<th width="9%" class="text-center">Gap</th>
					<th width="11%" class="text-center">Konversi</th>
					<th width="9%" class="text-center">Bobot</th>
					<th width="9%" class="text-center">Factor</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$cfRows = array_filter($rows, fn($r) => strtolower($r['jenis']) === 'core_factor');
				$sfRows = array_filter($rows, fn($r) => strtolower($r['jenis']) !== 'core_factor');

				if (!empty($cfRows)):
				?>
					<tr class="table-danger">
						<td colspan="8" class="fw-bold py-2">
							<i class="fas fa-star me-2"></i>Core Factor (CF)
						</td>
					</tr>
					<?php foreach ($cfRows as $r): ?>
						<tr>
							<td class="text-center">
								<strong><?= htmlspecialchars($r['kode_kriteria'] ?? '-') ?></strong>
							</td>
							<td>
								<strong><?= htmlspecialchars($r['nama_sub'] ?? '-') ?></strong>
								<div class="small text-muted"><?= htmlspecialchars($r['nama_kriteria'] ?? '-') ?></div>
							</td>
							<td class="text-center"><?= number_format($r['nilai_asli'], 2) ?></td>
							<td class="text-center"><?= number_format($r['target'], 2) ?></td>
							<td class="text-center">
								<span
									class="<?= $r['gap'] == 0 ? 'text-success' : ($r['gap'] > 0 ? 'text-warning' : 'text-muted') ?>">
									<?= number_format($r['gap'], 2) ?>
								</span>
							</td>
							<td class="text-center">
								<strong class="text-danger"><?= number_format($r['nilai_konversi'], 2) ?></strong>
								<?php if (!empty($r['id_range'])): ?>
									<div class="small text-muted">(R-<?= htmlspecialchars($r['id_range']) ?>)</div>
								<?php endif; ?>
							</td>
							<td class="text-center"><?= number_format($r['bobot'], 2) ?></td>
							<td class="text-center">
								<small class="text-danger">CF</small>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if (!empty($sfRows)): ?>
					<tr class="table-primary">
						<td colspan="8" class="fw-bold py-2">
							<i class="fas fa-certificate me-2"></i>Secondary Factor (SF)
						</td>
					</tr>
					<?php foreach ($sfRows as $r): ?>
						<tr>
							<td class="text-center">
								<strong class="text-primary"><?= htmlspecialchars($r['kode_kriteria'] ?? '-') ?></strong>
							</td>
							<td>
								<strong><?= htmlspecialchars($r['nama_sub'] ?? '-') ?></strong>
								<div class="small text-muted"><?= htmlspecialchars($r['nama_kriteria'] ?? '-') ?></div>
							</td>
							<td class="text-center"><?= number_format($r['nilai_asli'], 2) ?></td>
							<td class="text-center"><?= number_format($r['target'], 2) ?></td>
							<td class="text-center">
								<span
									class="<?= $r['gap'] == 0 ? 'text-success' : ($r['gap'] > 0 ? 'text-warning' : 'text-muted') ?>">
									<?= number_format($r['gap'], 2) ?>
								</span>
							</td>
							<td class="text-center">
								<strong class="text-primary"><?= number_format($r['nilai_konversi'], 2) ?></strong>
								<?php if (!empty($r['id_range'])): ?>
									<div class="small text-muted">(R-<?= htmlspecialchars($r['id_range']) ?>)</div>
								<?php endif; ?>
							</td>
							<td class="text-center"><?= number_format($r['bobot'], 2) ?></td>
							<td class="text-center">
								<small class="text-primary">SF</small>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

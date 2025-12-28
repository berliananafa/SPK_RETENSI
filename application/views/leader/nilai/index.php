<!-- Nilai Sub Kriteria CS -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header bg-gradient-primary text-white">
				<strong class="card-title mb-0">Monitoring Data Penilaian Customer Service</strong>
				<span class="small ml-2" style="opacity: 0.9;">Validasi nilai penilaian CS di tim Anda (Read-Only)</span>
			</div>
			<div class="card-body">
				<!-- Team Info -->
				<?php if ($team): ?>
				<div class="alert alert-info mb-3">
					<i class="fe fe-info"></i> <strong>Tim: <?= htmlspecialchars($team->nama_tim) ?></strong>
				</div>
				<?php endif; ?>

				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-3">
						<label>Periode:</label>
						<input type="month" class="form-control" id="filterPeriode"
							value="<?= $selected_periode ?? date('Y-m') ?>">
					</div>
					<div class="col-md-3">
						<label>Kriteria:</label>
						<select class="form-control" id="filterKriteria">
							<option value="">-- Semua Kriteria --</option>
							<?php if (!empty($kriteria_list)): ?>
								<?php foreach ($kriteria_list as $krt): ?>
									<option value="<?= $krt->id_kriteria ?>"
										<?= ($selected_kriteria == $krt->id_kriteria) ? 'selected' : '' ?>>
										<?= htmlspecialchars($krt->kode_kriteria . ' - ' . $krt->nama_kriteria) ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label>CS:</label>
						<select class="form-control" id="filterCS">
							<option value="">-- Semua CS --</option>
							<?php if (!empty($cs_list)): ?>
								<?php foreach ($cs_list as $cs): ?>
									<option value="<?= $cs->id_cs ?>" <?= ($selected_cs == $cs->id_cs) ? 'selected' : '' ?>>
										<?= htmlspecialchars($cs->nama_cs) ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-2">
						<label>&nbsp;</label>
						<button class="btn btn-info btn-block" id="btnFilter">
							<i class="fe fe-filter"></i> Filter
						</button>
					</div>
				</div>

				<!-- Summary Cards -->
				<div class="row mb-3">
					<div class="col-md-3">
						<div class="card border-left-primary shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Total Penilaian</small>
								<h4 class="mb-0"><?= $total_penilaian ?? 0 ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-success shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">CS Dinilai</small>
								<h4 class="mb-0"><?= $total_cs ?? 0 ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-info shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Rata-rata Nilai</small>
								<h4 class="mb-0"><?= number_format($rata_rata ?? 0, 2, ',', '.') ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-warning shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Sub Kriteria</small>
								<h4 class="mb-0"><?= $total_kriteria ?? 0 ?></h4>
							</div>
						</div>
					</div>
				</div>

				<!-- Nilai Table -->
				<?php if (!empty($nilai_data)): ?>
					<div class="table-responsive">
						<table class="table table-hover table-striped datatables" id="dataTable-1">
							<thead class="thead-light">
								<tr>
									<th width="5%">No</th>
									<th>NIK</th>
									<th>Nama CS</th>
									<th>Produk</th>
									<th>Kriteria</th>
									<th>Sub Kriteria</th>
									<th>Nilai</th>
									<th>Periode</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($nilai_data as $index => $nilai): ?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td>
											<span class="badge badge-primary"><?= htmlspecialchars($nilai->nik ?? '-') ?></span>
										</td>
										<td>
											<div class="d-flex align-items-center">
												<strong><?= htmlspecialchars($nilai->nama_cs) ?></strong>
											</div>
										</td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($nilai->nama_produk ?? '-') ?></small>
										</td>
										<td>
											<span class="badge badge-info"><?= htmlspecialchars($nilai->kode_kriteria ?? '') ?></span>
											<small><?= htmlspecialchars($nilai->nama_kriteria) ?></small>
										</td>
										<td>
											<small class="text-muted"><?= htmlspecialchars($nilai->nama_sub_kriteria ?? '-') ?></small>
										</td>
										<td>
											<strong><?= number_format($nilai->nilai, 0, ',', '.') ?></strong>
										</td>
										<td>
											<small class="text-muted">
												<?php if (!empty($nilai->periode) && preg_match('/^\d{4}-\d{2}$/', $nilai->periode)): ?>
													<?php
													$bulan_map = [
														'01' => 'Januari',
														'02' => 'Februari',
														'03' => 'Maret',
														'04' => 'April',
														'05' => 'Mei',
														'06' => 'Juni',
														'07' => 'Juli',
														'08' => 'Agustus',
														'09' => 'September',
														'10' => 'Oktober',
														'11' => 'November',
														'12' => 'Desember'
													];
													list($yy, $mm) = explode('-', $nilai->periode);
													echo (isset($bulan_map[$mm]) ? $bulan_map[$mm] : $mm) . ' ' . $yy;
													?>
												<?php else: ?>
													<?= htmlspecialchars($nilai->periode ?? '-') ?>
												<?php endif; ?>
											</small>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="text-center py-5">
						<span class="fe fe-bar-chart-2 fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
						<h5 class="text-muted">Belum Ada Data Nilai</h5>
						<p class="text-muted">Silakan pilih periode dan filter untuk melihat nilai sub kriteria</p>
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
		// Filter button click
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var kriteria = $('#filterKriteria').val();
			var cs = $('#filterCS').val();

			var url = '<?= base_url('leader/nilai') ?>?';
			var params = [];

			if (periode) params.push('periode=' + periode);
			if (kriteria) params.push('id_kriteria=' + kriteria);
			if (cs) params.push('id_cs=' + cs);

			window.location.href = url + params.join('&');
		});
	});
</script>
<?php
add_js(ob_get_clean());
?>

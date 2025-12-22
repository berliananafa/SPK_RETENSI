<!-- Monitoring Penilaian CS -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<strong class="card-title">Monitoring Data Penilaian Customer Service</strong>
					</div>
					<div class="col-auto">
						<a href="<?= base_url('admin/nilai/input') ?>" class="btn btn-primary btn-sm">
							<i class="fe fe-upload"></i> Upload Penilaian
						</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="row mb-3">
					<div class="col-md-4">
						<label>Periode:</label>
						<input type="month" class="form-control" id="filterPeriode"
							value="<?= $filter_periode ?? date('Y-m') ?>">
					</div>
					<div class="col-md-3">
						<label>Kriteria:</label>
						<select class="form-control" id="filterKriteria">
							<option value="">-- Semua Kriteria --</option>
							<?php if (!empty($kriteria)): ?>
								<?php foreach ($kriteria as $krt): ?>
									<option value="<?= $krt->id_kriteria ?>"
										<?= ($filter_kriteria == $krt->id_kriteria) ? 'selected' : '' ?>>
										<?= $krt->kode_kriteria . ' - ' . $krt->nama_kriteria ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Tim:</label>
						<select class="form-control" id="filterTim">
							<option value="">-- Semua Tim --</option>
							<?php if (!empty($tim)): ?>
								<?php foreach ($tim as $t): ?>
									<option value="<?= $t->id_tim ?>" <?= ($filter_tim == $t->id_tim) ? 'selected' : '' ?>>
										<?= $t->nama_tim ?>
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
								<h4 class="mb-0"><?= $total_penilaian ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-success shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">CS Dinilai</small>
								<h4 class="mb-0"><?= $total_cs ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-info shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Rata-rata Nilai</small>
								<h4 class="mb-0"><?= number_format($rata_rata, 2, ',', '.') ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-warning shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Sub Kriteria</small>
								<h4 class="mb-0"><?= $total_kriteria ?></h4>
							</div>
						</div>
					</div>
				</div>

				<!-- Data Table -->
				<div class="table-responsive">
					<table id="dataTable-1" class="table table-hover ">
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
								<th width="10%" class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($penilaian)): ?>
								<?php foreach ($penilaian as $index => $nilai): ?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td>
											<span class="badge badge-primary"><?= $nilai->nik ?? '-' ?></span>
										</td>
										<td>
											<div class="d-flex align-items-center">
												<strong><?= htmlspecialchars($nilai->nama_cs) ?></strong>
											</div>
										</td>
										<td><?= $nilai->nama_produk ?></td>
										<td>
											<span class="badge badge-info"><?= $nilai->kode_kriteria ?></span>
											<?= $nilai->nama_kriteria ?>
										</td>
										<td><?= $nilai->nama_sub_kriteria ?></td>
										<td>
											<strong><?= number_format($nilai->nilai, 0, ',', '.') ?></strong>
										</td>
										<td>
											<small>
												<?php if (!empty($nilai->periode) && preg_match('/^\d{4}-\d{2}$/', $nilai->periode)): ?>
													<?php {
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
													}
													?>
												<?php else: ?>
													<?= $nilai->periode ?? '-' ?>
												<?php endif; ?>
											</small>
										</td>
										<td class="text-center">
											<a href="<?= base_url('admin/nilai/delete/' . $nilai->id_nilai) ?>"
												class="btn btn-sm btn-danger btn-delete" data-title="Hapus Penilaian?"
												data-text="Data akan dihapus permanen!" title="Hapus">
												<i class="fe fe-trash-2"></i>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="8" class="text-center text-muted py-4">
										<i class="fe fe-inbox fe-24"></i>
										<p>Belum ada data penilaian</p>
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


<?php
ob_start();
?>
<style>
	.border-left-primary {
		border-left: 4px solid #4e73df !important;
	}

	.border-left-success {
		border-left: 4px solid #1cc88a !important;
	}

	.border-left-info {
		border-left: 4px solid #36b9cc !important;
	}

	.border-left-warning {
		border-left: 4px solid #f6c23e !important;
	}
</style>
<?php
add_css(ob_get_clean());

ob_start();
?>
<script>
	$(document).ready(function() {
		// Filter button click
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var kriteria = $('#filterKriteria').val();
			var tim = $('#filterTim').val();

			var url = `<?= base_url('admin/nilai') ?>` + '?';
			var params = [];

			if (periode) params.push('periode=' + periode);
			if (kriteria) params.push('kriteria=' + kriteria);
			if (tim) params.push('tim=' + tim);

			window.location.href = url + params.join('&');
		});
	});
</script>
<?php
add_js(ob_get_clean());
?>

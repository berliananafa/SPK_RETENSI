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
				<div class="card mb-3 border">
					<div class="card-header bg-light py-2">
						<strong><i class="fe fe-filter"></i> Filter Data Penilaian</strong>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mb-2">
									<label class="small font-weight-bold">Periode:</label>
									<input type="month" class="form-control form-control-sm" id="filterPeriode"
										value="<?= $filter_periode ?? date('Y-m') ?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-2">
									<label class="small font-weight-bold">Kriteria:</label>
									<select class="form-control form-control-sm" id="filterKriteria">
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
							</div>
							<div class="col-md-2">
								<div class="form-group mb-2">
									<label class="small font-weight-bold">Tim:</label>
									<select class="form-control form-control-sm" id="filterTim">
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
							</div>
							<div class="col-md-2">
								<div class="form-group mb-2">
									<label class="small font-weight-bold">Produk:</label>
									<select class="form-control form-control-sm" id="filterProduk">
										<option value="">-- Semua Produk --</option>
										<?php if (!empty($produk)): ?>
											<?php foreach ($produk as $p): ?>
												<option value="<?= $p->id_produk ?>" <?= ($filter_produk == $p->id_produk) ? 'selected' : '' ?>>
													<?= $p->nama_produk ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label class="small d-block">&nbsp;</label>
								<button class="btn btn-primary btn-sm" id="btnFilter">
									<i class="fe fe-check"></i> Filter
								</button>
								<button class="btn btn-secondary btn-sm" id="btnReset">
									<i class="fe fe-refresh-cw"></i> Reset
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Summary Cards -->
				<div class="row mb-3">
					<div class="col-md-3">
						<div class="card border-left-primary shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">Total Penilaian</small>
								<h4 class="mb-0"><?= number_format($total_penilaian, 0, ',', '.') ?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card border-left-success shadow-sm">
							<div class="card-body py-3">
								<small class="text-muted">CS Dinilai</small>
								<h4 class="mb-0"><?= number_format($total_cs, 0, ',', '.') ?></h4>
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
								<h4 class="mb-0"><?= number_format($total_kriteria, 0, ',', '.') ?></h4>
							</div>
						</div>
					</div>
				</div>

				<!-- Data Table -->
				<div class="table-responsive">
					<table id="dataTable-1" class="table table-hover">
						<thead class="thead-light">
							<tr>
								<th width="5%" class="text-center">No</th>
								<th>NIK</th>
								<th>Nama CS</th>
								<th>Tim</th>
								<th>Produk</th>
								<th>Kriteria</th>
								<th>Sub Kriteria</th>
								<th class="text-center">Nilai</th>
								<th>Periode</th>
								<th width="10%" class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($penilaian)): ?>
								<?php foreach ($penilaian as $index => $nilai): ?>
									<tr>
										<td class="text-center"><?= $index + 1 ?></td>
										<td>
											<code><?= $nilai->nik ?? '-' ?></code>
										</td>
										<td>
											<strong><?= htmlspecialchars($nilai->nama_cs) ?></strong>
										</td>
										<td>
											<span class="badge badge-primary"><?= $nilai->nama_tim ?></span>
										</td>
										<td>
											<span class="badge badge-success"><?= $nilai->nama_produk ?></span>
										</td>
										<td>
											<span class="badge badge-info"><?= $nilai->kode_kriteria ?></span>
											<?= $nilai->nama_kriteria ?>
										</td>
										<td><?= $nilai->nama_sub_kriteria ?></td>
										<td class="text-center">
											<strong class="text-primary"><?= number_format($nilai->nilai, 0, ',', '.') ?></strong>
										</td>
										<td>
											<small>
												<?php if (!empty($nilai->periode) && preg_match('/^\d{4}-\d{2}$/', $nilai->periode)): ?>
													<?php {
														$bulan_map = [
															'01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
															'05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
															'09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
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
												class="btn btn-sm btn-danger btn-delete"
												data-title="Hapus Penilaian?"
												data-text="Data akan dihapus permanen!"
												title="Hapus"
												data-toggle="tooltip">
												<i class="fe fe-trash-2"></i>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="10" class="text-center text-muted py-4">
										<i class="fe fe-inbox fe-24"></i>
										<p class="mt-2">
											<?php if (!empty($active_filters)): ?>
												Tidak ada data penilaian dengan filter yang dipilih
											<?php else: ?>
												Belum ada data penilaian
											<?php endif; ?>
										</p>
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

<?php ob_start(); ?>
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
<?php add_css(ob_get_clean()); ?>

<?php ob_start(); ?>
<script>
	$(document).ready(function() {
		// Initialize tooltip
		$('[data-toggle="tooltip"]').tooltip();

		// Apply Filter
		$('#btnFilter').on('click', function() {
			var periode = $('#filterPeriode').val();
			var kriteria = $('#filterKriteria').val();
			var tim = $('#filterTim').val();
			var produk = $('#filterProduk').val();

			var url = '<?= base_url('admin/nilai') ?>';
			var params = [];

			if (periode) params.push('periode=' + periode);
			if (kriteria) params.push('kriteria=' + kriteria);
			if (tim) params.push('tim=' + tim);
			if (produk) params.push('produk=' + produk);

			if (params.length > 0) {
				url += '?' + params.join('&');
			}

			window.location.href = url;
		});

		// Reset Filter
		$('#btnReset').on('click', function() {
			window.location.href = '<?= base_url('admin/nilai') ?>';
		});

		// Allow pressing Enter to apply filter
		$('#filterPeriode, #filterKriteria, #filterTim, #filterProduk').on('keypress', function(e) {
			if (e.which === 13) {
				$('#btnFilter').click();
			}
		});

		// Konfirmasi hapus dengan SweetAlert
		$(document).on('click', '.btn-delete', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			var title = $(this).data('title') || 'Hapus Data?';
			var text = $(this).data('text') || 'Data akan dihapus permanen!';

			Swal.fire({
				title: title,
				text: text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: '<i class="fe fe-trash-2"></i> Ya, Hapus!',
				cancelButtonText: '<i class="fe fe-x"></i> Batal',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = url;
				}
			});
		});
	});
</script>
<?php add_js(ob_get_clean()); ?>

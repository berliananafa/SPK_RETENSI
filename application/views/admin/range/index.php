<!-- Range Nilai List -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<strong class="card-title">Data Range Nilai</strong>
					</div>
					<div class="col-auto">
						<a href="<?= base_url('admin/range/create') ?>" class="btn btn-primary btn-sm">
							<i class="fe fe-plus"></i> Tambah Range
						</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter -->
				<div class="row mb-3">
					<div class="col-md-6">
						<label for="filterKriteria" class="font-weight-bold">Filter Kriteria:</label>
						<select class="form-control form-control-sm" id="filterKriteria">
							<option value="">-- Semua Kriteria --</option>
							<?php if (!empty($all_kriteria)): ?>
								<?php foreach ($all_kriteria as $k): ?>
									<option value="<?= $k->id_kriteria ?>"><?= htmlspecialchars($k->kode_kriteria . ' - ' . $k->nama_kriteria) ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-6">
						<label for="filterSubKriteria" class="font-weight-bold">Filter Sub Kriteria:</label>
						<select class="form-control form-control-sm" id="filterSubKriteria">
							<option value="">-- Semua Sub Kriteria --</option>
							<?php if (!empty($all_sub_kriteria)): ?>
								<?php foreach ($all_sub_kriteria as $sk): ?>
									<option value="<?= $sk->id_sub_kriteria ?>" data-kriteria="<?= $sk->id_kriteria ?>">
										<?= htmlspecialchars($sk->kode_kriteria . ' - ' . $sk->nama_sub_kriteria) ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>

				<table id="dataTable-1" class="table table-hover table-striped">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th>Sub Kriteria</th>
							<th>Range Nilai</th>
							<th width="10%" class="text-center">Poin</th>
							<th>Keterangan</th>
							<th width="15%" class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($ranges)): ?>
							<?php foreach ($ranges as $index => $range): ?>
								<tr data-kriteria="<?= $range->id_sub_kriteria ?>">
									<td><?= $index + 1 ?></td>
									<td>
										<strong><?= htmlspecialchars($range->kode_kriteria ?? '-') ?></strong> - <?= htmlspecialchars($range->nama_kriteria ?? '-') ?>
										<br><strong class="text-info"><?= htmlspecialchars($range->nama_sub_kriteria ?? '-') ?></strong>
									</td>
									<td>
										<?php if ($range->batas_bawah === null || $range->batas_bawah === ''): ?>
											<span class="text-muted">≤ <?= number_format($range->batas_atas, 0) ?></span>
										<?php elseif ($range->batas_atas === null || $range->batas_atas === ''): ?>
											<span class="text-muted">≥ <?= number_format($range->batas_bawah, 0) ?></span>
										<?php else: ?>
											<?= number_format($range->batas_bawah, 0) ?> - <?= number_format($range->batas_atas, 0) ?>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<strong class="text-success"><?= number_format($range->nilai_range, 0) ?></strong>
									</td>
									<td>
										<?= htmlspecialchars($range->keterangan ?? '-') ?>
									</td>
									<td class="text-center">
										<div class="btn-group" role="group">
											<a href="<?= base_url('admin/range/edit/' . $range->id_range) ?>"
												class="btn btn-sm btn-warning" title="Edit">
												<i class="fe fe-edit"></i>
											</a>
											<a href="<?= base_url('admin/range/delete/' . $range->id_range) ?>"
												class="btn btn-sm btn-danger btn-delete"
												data-title="Hapus Range?"
												data-text="Data range nilai akan dihapus permanen!"
												title="Hapus">
												<i class="fe fe-trash-2"></i>
											</a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted py-4">
									<i class="fe fe-inbox fe-24 mb-3"></i>
									<p>Belum ada data Range Nilai</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Info Card -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card shadow-sm border-left-warning">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-auto">
						<i class="fe fe-info fe-24 text-warning"></i>
					</div>
					<div class="col">
						<h6 class="mb-1">Informasi Range Nilai</h6>
						<p class="mb-0 text-muted small">
							Range nilai digunakan untuk mengkonversi nilai mentah menjadi nilai standar (normalized).
							Setiap range memiliki batas minimum dan maksimum yang tidak boleh tumpang tindih.
							<br><strong>Contoh:</strong> Produktivitas 0-50 = 1, 51-100 = 2, 101-150 = 3, dst.
						</p>
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
	document.addEventListener('DOMContentLoaded', function() {
		// Filter functionality
		const filterKriteria = document.getElementById('filterKriteria');
		const filterSubKriteria = document.getElementById('filterSubKriteria');
		const subKriteriaOptions = filterSubKriteria.querySelectorAll('option[data-kriteria]');

		// Custom search function for DataTable
		$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
			const selectedSubKriteria = filterSubKriteria.value;
			
			if (selectedSubKriteria === '') {
				return true;
			}

			const row = $(settings.aoData[dataIndex].nTr);
			const rowSubKriteria = row.attr('data-kriteria');
			return rowSubKriteria === selectedSubKriteria;
		});

		// Filter by Kriteria
		filterKriteria.addEventListener('change', function() {
			const selectedKriteria = this.value;

			// Reset sub kriteria filter
			filterSubKriteria.value = '';

			// Show/hide sub kriteria options based on kriteria
			subKriteriaOptions.forEach(function(option) {
				if (selectedKriteria === '' || option.getAttribute('data-kriteria') === selectedKriteria) {
					option.style.display = '';
				} else {
					option.style.display = 'none';
				}
			});

			// Redraw table with reset pagination
			if (window.dataTable) {
				window.dataTable.page(0).draw();
			}
		});

		// Filter by Sub Kriteria
		filterSubKriteria.addEventListener('change', function() {
			// Redraw table with reset pagination
			if (window.dataTable) {
				window.dataTable.page(0).draw();
			}
		});

		// Delete confirmation
		$(document).on('click', '.btn-delete', function(e) {
			e.preventDefault();
			const url = $(this).attr('href');
			const title = $(this).data('title') || 'Hapus Data?';
			const text = $(this).data('text') || 'Data akan dihapus permanen!';

			Swal.fire({
				title: title,
				text: text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = url;
				}
			});
		});
	});
</script>
<?php
add_js(ob_get_clean());
?>

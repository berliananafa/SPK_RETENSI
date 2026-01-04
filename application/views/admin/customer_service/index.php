<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header d-flex justify-content-between align-items-center">
				<strong class="card-title mb-0">Daftar Customer Service</strong>
				<div class="btn-group">
					<a href="<?= base_url('admin/customer-service/create') ?>" class="btn btn-primary btn-sm">
						<i class="fe fe-plus"></i> Tambah CS
					</a>
					<button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fe fe-download"></i> Import/Export
					</button>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="<?= base_url('admin/customer-service/import') ?>">
							<i class="fe fe-upload"></i> Import Data
						</a>
						<a class="dropdown-item" href="<?= base_url('admin/customer-service/download-template') ?>">
							<i class="fe fe-file"></i> Download Template
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#" id="btnExport">
							<i class="fe fe-download"></i> Export ke Excel
						</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<!-- Filter Section -->
				<div class="card mb-3 border">
					<div class="card-header bg-light py-2">
						<strong><i class="fe fe-filter"></i> Filter Data</strong>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mb-2">
									<label for="filterTim" class="small font-weight-bold">Tim</label>
									<select class="form-control form-control-sm" id="filterTim">
										<option value="">-- Semua Tim --</option>
										<?php if (!empty($teams)): ?>
											<?php foreach ($teams as $team): ?>
												<option value="<?= $team->id_tim ?>" <?= (isset($selected_tim) && $selected_tim == $team->id_tim) ? 'selected' : '' ?>>
													<?= esc($team->nama_tim) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-2">
									<label for="filterProduk" class="small font-weight-bold">Produk</label>
									<select class="form-control form-control-sm" id="filterProduk">
										<option value="">-- Semua Produk --</option>
										<?php if (!empty($products)): ?>
											<?php foreach ($products as $product): ?>
												<option value="<?= $product->id_produk ?>" <?= (isset($selected_produk) && $selected_produk == $product->id_produk) ? 'selected' : '' ?>>
													<?= esc($product->nama_produk) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-2">
									<label for="filterKanal" class="small font-weight-bold">Kanal</label>
									<select class="form-control form-control-sm" id="filterKanal">
										<option value="">-- Semua Kanal --</option>
										<?php if (!empty($channels)): ?>
											<?php foreach ($channels as $channel): ?>
												<option value="<?= $channel->id_kanal ?>" <?= (isset($selected_kanal) && $selected_kanal == $channel->id_kanal) ? 'selected' : '' ?>>
													<?= esc($channel->nama_kanal) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<label class="small d-block">&nbsp;</label>
								<button type="button" class="btn btn-primary btn-sm" id="btnApplyFilter">
									<i class="fe fe-check"></i> Terapkan Filter
								</button>
								<button type="button" class="btn btn-secondary btn-sm" id="btnResetFilter">
									<i class="fe fe-refresh-cw"></i> Reset Filter
								</button>
							</div>
						</div>					
					</div>
				</div>

				<!-- Table -->
				<div class="table-responsive">
					<table class="table table-hover datatables" id="dataTable-1">
						<thead class="thead-light">
							<tr>
								<th width="5%" class="text-center">No</th>
								<th>NIK</th>
								<th>Nama CS</th>
								<th>Tim</th>
								<th>Produk</th>
								<th>Kanal</th>
								<th width="15%" class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($customer_services)): ?>
								<?php foreach ($customer_services as $index => $cs): ?>
									<tr>
										<td class="text-center"><?= $index + 1 ?></td>
										<td><code><?= esc($cs->nik) ?></code></td>
										<td><strong><?= esc($cs->nama_cs) ?></strong></td>
										<td>
											<span class="badge badge-primary">
												<?= esc($cs->nama_tim) ?>
											</span>
										</td>
										<td>
											<span class="badge badge-info">
												<?= esc($cs->nama_produk) ?>
											</span>
										</td>
										<td>
											<span class="badge badge-success">
												<?= esc($cs->nama_kanal) ?>
											</span>
										</td>
										<td class="text-center">
											<div class="btn-group" role="group">
												<a href="<?= base_url('admin/customer-service/detail/' . $cs->id_cs) ?>"
													class="btn btn-sm btn-info"
													title="Detail"
													data-toggle="tooltip">
													<i class="fe fe-eye"></i>
												</a>
												<a href="<?= base_url('admin/customer-service/edit/' . $cs->id_cs) ?>"
													class="btn btn-sm btn-warning"
													title="Edit"
													data-toggle="tooltip">
													<i class="fe fe-edit"></i>
												</a>
												<a href="<?= base_url('admin/customer-service/delete/' . $cs->id_cs) ?>"
													class="btn btn-sm btn-danger btn-delete"
													data-id="<?= $cs->id_cs ?>"
													data-name="<?= esc($cs->nama_cs) ?>"
													title="Hapus"
													data-toggle="tooltip">
													<i class="fe fe-trash-2"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="7" class="text-center py-4">
										<div class="text-muted">
											<i class="fe fe-inbox" style="font-size: 48px;"></i>
											<p class="mt-2 mb-0">
												<?php if (!empty($active_filters)): ?>
													Tidak ada data customer service dengan filter yang dipilih
												<?php else: ?>
													Belum ada data customer service
												<?php endif; ?>
											</p>
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

<?php ob_start(); ?>
<script>
	$(document).ready(function() {
		// Initialize tooltip
		$('[data-toggle="tooltip"]').tooltip();

		// Apply Filter
		$('#btnApplyFilter').on('click', function() {
			var timId = $('#filterTim').val();
			var produkId = $('#filterProduk').val();
			var kanalId = $('#filterKanal').val();
			var url = '<?= base_url('admin/customer-service') ?>';
			var params = [];

			if (timId) params.push('tim=' + timId);
			if (produkId) params.push('produk=' + produkId);
			if (kanalId) params.push('kanal=' + kanalId);

			if (params.length > 0) {
				url += '?' + params.join('&');
			}

			window.location.href = url;
		});

		// Reset Filter
		$('#btnResetFilter').on('click', function() {
			window.location.href = '<?= base_url('admin/customer-service') ?>';
		});

		// Allow pressing Enter to apply filter
		$('#filterTim, #filterProduk, #filterKanal').on('keypress', function(e) {
			if (e.which === 13) {
				$('#btnApplyFilter').click();
			}
		});

		// Export dengan filter
		$('#btnExport').on('click', function(e) {
			e.preventDefault();
			var timId = $('#filterTim').val();
			var produkId = $('#filterProduk').val();
			var kanalId = $('#filterKanal').val();
			var url = '<?= base_url('admin/customer-service/export') ?>';
			var params = [];

			if (timId) params.push('tim=' + timId);
			if (produkId) params.push('produk=' + produkId);
			if (kanalId) params.push('kanal=' + kanalId);

			if (params.length > 0) {
				url += '?' + params.join('&');
			}

			window.location.href = url;
		});

		// Konfirmasi hapus dengan SweetAlert
		$(document).on('click', '.btn-delete', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			var name = $(this).data('name');

			Swal.fire({
				title: 'Konfirmasi Hapus',
				html: 'Apakah Anda yakin ingin menghapus CS:<br><strong>' + name + '</strong>?',
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

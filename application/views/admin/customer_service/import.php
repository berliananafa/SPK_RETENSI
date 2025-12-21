<div class="row justify-content-center">
	<div class="col-12 col-lg-10 col-xl-8">
		<!-- Download Template Card -->
		<div class="card shadow mb-4 border-primary">
			<div class="card-body text-center py-5">
				<i class="fe fe-download fe-48 text-primary mb-3"></i>
				<h5 class="mb-3">Download Template Excel</h5>
				<p class="text-muted mb-4">Download template untuk memudahkan proses import data customer service</p>
				<a href="<?= base_url('admin/customer-service/download-template') ?>"
					class="btn btn-primary btn-lg">
					<i class="fe fe-download"></i> Download Template
				</a>
			</div>
		</div>

		<!-- Upload Form Card -->
		<div class="card shadow">
			<div class="card-header">
				<strong class="card-title"><i class="fe fe-upload"></i> Upload File Import</strong>
			</div>
			<div class="card-body">
				<?= form_open_multipart('admin/customer-service/process-import') ?>
				<div class="form-group">
					<label for="file">Pilih File Excel <span class="text-danger">*</span></label>
					<div class="custom-file">
						<input type="file"
							class="custom-file-input"
							id="file"
							name="file"
							accept=".xlsx,.xls"
							required>
						<label class="custom-file-label" for="file">Pilih file...</label>
					</div>
					<small class="form-text text-muted">
						Format: .xlsx atau .xls | Maksimal: 2MB
					</small>
				</div>

				<div class="alert alert-warning" role="alert">
					<i class="fe fe-alert-triangle"></i>
					<strong>Perhatian!</strong> Proses import akan:
					<ul class="mb-0 mt-2">
						<li>Melewati data dengan NIK yang sudah terdaftar</li>
						<li>Memvalidasi relasi Tim, Produk, dan Kanal dengan data yang ada</li>
						<li>Menampilkan laporan detail setelah proses selesai</li>
					</ul>
				</div>

				<div class="d-flex justify-content-between">
					<a href="<?= base_url('admin/customer-service') ?>" class="btn btn-secondary">
						<i class="fe fe-x"></i> Batal
					</a>
					<button type="submit" name="submit" value="1" class="btn btn-success">
						<i class="fe fe-upload"></i> Import Data
					</button>
				</div>
				<?= form_close() ?>
			</div>
		</div>

		<!-- Additional Info -->
		<div class="card shadow border-info mt-4">
			<div class="card-body">
				<h6 class="mb-3"><i class="fe fe-help-circle"></i> Tips Import Data</h6>
				<ul class="mb-0">
					<li>Pastikan tidak ada baris kosong di tengah data</li>
					<li>NIK harus unik, tidak boleh duplikat</li>
					<li>Nama Tim/Produk/Kanal harus persis sama dengan data master</li>
					<li>Jika ada error, perbaiki file dan upload ulang</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php
ob_start();
?>
<script>
	// Update file input label with filename
	$('.custom-file-input').on('change', function() {
		var fileName = $(this).val().split('\\').pop();
		$(this).siblings('.custom-file-label').addClass('selected').html(fileName);
	});
</script>
<?php
add_js(ob_get_clean());
?>

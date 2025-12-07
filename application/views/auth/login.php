<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Sistem Pendukung Keputusan Customer Service Terbaik">
	<meta name="author" content="">
	<link rel="icon" href="<?= base_url('favicon.ico') ?>">
	<title>Login - SPK Retensi Customer Service</title>

	<!-- Fonts CSS -->
	<link
		href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900&display=swap"
		rel="stylesheet">
	<!-- Feather Icons -->
	<link rel="stylesheet" href="<?= base_url('assets/css/feather.css') ?>">
	<!-- App CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/css/app-light.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/login-page.css') ?>">
</head>

<body class="light">
	<div class="login-wrapper d-flex align-items-center justify-content-center">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12">
					<?= form_open('login', ['id' => 'loginForm']) ?>
					<div class="login-card">
						<div class="row no-gutters">
							<!-- Left Column - Logo & Branding -->
							<div class="col-md-5">
								<div class="login-left">
									<img src="<?= base_url('assets/img/ethos_logo.png') ?>" alt="Ethos Kreatif Indonesia" class="login-logo">
									<div class="login-left-content">
										<!-- <h3>SPK Retensi CS</h3> -->
										<p>Sistem Pendukung Keputusan untuk menentukan Customer Service Retensi Terbaik</p>
										<span class="badge badge-primary-custom">PT Ethos Kreatif Indonesia</span>
									</div>
								</div>
							</div>

							<!-- Right Column - Form -->
							<div class="col-md-7">
								<div class="login-right">
									<!-- Header -->
									<div class="login-header">
										<h5>Selamat Datang</h5>
										<p>Silakan masuk untuk melanjutkan</p>
									</div>

									<!-- Flash Message Error -->
									<?php if ($this->session->flashdata('error')): ?>
									<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<small><?= $this->session->flashdata('error') ?></small>
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<?php endif; ?>

									<!-- Email Input -->
									<div class="form-group">
										<label for="inputEmail" class="form-label">Email</label>
										<input type="email" id="inputEmail" name="email" class="form-control"
											placeholder="nama@email.com" value="<?= set_value('email') ?>" required autofocus>
									</div>

									<!-- Password Input -->
									<div class="form-group mb-4">
										<label for="inputPassword" class="form-label">Password</label>
										<div class="position-relative">
											<input type="password" id="inputPassword" name="password" class="form-control pr-5"
												placeholder="••••••••" required>
											<button type="button" class="btn btn-link position-absolute" 
												id="togglePassword" 
												style="right: 0; top: 0; padding: 0.875rem 1rem; color: #64748b; text-decoration: none;">
												<i class="fe fe-eye" id="eyeIcon"></i>
											</button>
										</div>
									</div>

									<!-- Submit Button -->
									<button class="btn btn-primary btn-login btn-block" type="submit" id="loginBtn">
										Masuk
									</button>

									<!-- Footer -->
									<div class="login-footer">
										<small class="text-muted">
											© <?= date('Y') ?> SPK Retensi CS - PT. Ethos Kreatif Indonesia
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>

	<!-- Custom Login Script -->
	<script>
		$(document).ready(function () {
			// Toggle password visibility
			$('#togglePassword').on('click', function() {
				const passwordInput = $('#inputPassword');
				const eyeIcon = $('#eyeIcon');
				
				if (passwordInput.attr('type') === 'password') {
					passwordInput.attr('type', 'text');
					eyeIcon.removeClass('fe-eye').addClass('fe-eye-off');
				} else {
					passwordInput.attr('type', 'password');
					eyeIcon.removeClass('fe-eye-off').addClass('fe-eye');
				}
			});

			// Form validation
			$('#loginForm').on('submit', function (e) {
				const email = $('input[name="email"]').val().trim();
				const password = $('input[name="password"]').val().trim();

				if (!email || !password) {
					e.preventDefault();
					alert('Email dan password harus diisi!');
					return false;
				}

				// Show loading button
				const btn = $('#loginBtn');
				btn.prop('disabled', true);
				btn.html(
					'<span class="mr-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Memproses...'
					);
			});

			// Auto dismiss alert after 5 seconds
			setTimeout(function () {
				$('.alert').fadeOut('slow');
			}, 5000);
		});

	</script>
</body>

</html>

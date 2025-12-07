<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?= isset($page_title) ? "$page_title - " : "" ?>SPK Retensi Customer Service</title>
	<meta name="description" content="Sistem Pendukung Keputusan Customer Service Terbaik menggunakan Profile Matching">

	<!-- Favicon -->
	<link rel="icon" href="<?= base_url('assets/img/favicon.png') ?>">

	<!-- Fonts CSS -->
	<link
		href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900&display=swap"
		rel="stylesheet">

	<!-- Core CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/css/simplebar.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/feather.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/daterangepicker.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/app-light.css') ?>">

	<style>
		/* Remove horizontal scroll from sidebar */
		.sidebar-left {
			overflow-x: hidden !important;
			max-width: 100%;
		}

		.sidebar-left .vertnav,
		.sidebar-left .navbar-nav,
		.sidebar-left .nav-item {
			overflow-x: hidden !important;
			max-width: 100%;
		}

		.sidebar-left .nav-link {
			overflow-x: hidden !important;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		.sidebar-left .collapse {
			overflow-x: hidden !important;
			max-width: 100%;
		}

	</style>

	<!-- Optional CSS -->
	<?php if (!empty($include_datatables)): ?>
	<link rel="stylesheet" href="<?= base_url('assets/css/dataTables.bootstrap4.css') ?>">
	<?php endif; ?>
	<?php if (!empty($include_charts)): ?>
	<link rel="stylesheet" href="<?= base_url('assets/css/apexcharts.css') ?>">
	<?php endif; ?>
	<?= $additional_css ?? '' ?>
</head>

<body class="vertical light">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('partials/navbar'); ?>

		<!-- Sidebar -->
		<?php $this->load->view('partials/sidebar'); ?>

		<!-- Main Content -->
		<main role="main" class="main-content">
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col-12">
						<!-- Page Title -->
						<div class="mb-4 row align-items-center page-header">
							<div class="col">
								<h2 class="mb-0 page-title"><?= $page_title ?? 'Dashboard' ?></h2>
							</div>
							<?php if (!empty($breadcrumb)): ?>
							<div class="col-auto">
								<nav aria-label="breadcrumb">
									<ol class="p-0 m-0 bg-transparent breadcrumb">
										<?php foreach ($breadcrumb as $item): ?>
										<?php if (!empty($item['url'])): ?>
										<li class="breadcrumb-item"><a
												href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
										<?php else: ?>
										<li class="breadcrumb-item active" aria-current="page"><?= $item['title'] ?>
										</li>
										<?php endif; ?>
										<?php endforeach; ?>
									</ol>
								</nav>
							</div>
							<?php endif; ?>
						</div>

						<!-- Flash Messages -->
						<?php foreach (['success', 'error', 'warning', 'info'] as $type): ?>
						<?php if ($msg = $this->session->flashdata($type)): ?>
						<div
							class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<?= $msg ?>
						</div>
						<?php endif; ?>
						<?php endforeach; ?>

						<!-- Page Content -->
						<?= $content ?>

					</div> <!-- .col-12 -->
				</div> <!-- .row -->
			</div> <!-- .container-fluid -->
		</main> <!-- main -->
	</div> <!-- .wrapper -->

	<!-- Core Scripts -->
	<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/moment.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/simplebar.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/jquery.stickOnScroll.js') ?>"></script>
	<script src="<?= base_url('assets/js/tinycolor-min.js') ?>"></script>
	<script src="<?= base_url('assets/js/config-light.js') ?>"></script>
	<script src="<?= base_url('assets/js/apps.js') ?>"></script>

	<!-- Optional JS -->
	<?php if (!empty($include_datatables)): ?>
	<script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/dataTables.bootstrap4.min.js') ?>"></script>
	<?php endif; ?>
	<?php if (!empty($include_charts)): ?>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
	<script src="<?= base_url('assets/js/apexcharts.min.js') ?>"></script>
	<?php endif; ?>
	<?php if (!empty($include_sweetalert)): ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<?php endif; ?>

	<!-- Page Specific Scripts -->
	<?= $additional_js ?? '' ?>

	<!-- Common JS -->
	<?php if (!empty($include_datatables)): ?>
	<script>
		$(document).ready(function () {
			$('#dataTable-1').DataTable({
				autoWidth: true,
				"lengthMenu": [
					[10, 20, 30, -1],
					[10, 20, 30, "All"]
				]
			});
		});

	</script>
	<?php endif; ?>

	<script>
		$(function () {
			// Initialize Simplebar for sidebar
			if (typeof SimpleBar !== 'undefined') {
				var sidebarElement = document.getElementById('leftSidebar');
				if (sidebarElement && sidebarElement.hasAttribute('data-simplebar')) {
					new SimpleBar(sidebarElement);
				}
			}

			// Sidebar toggle for mobile
			$('.collapseSidebar').on('click', function () {
				$('#leftSidebar').toggleClass('show');
				$('body').toggleClass('sidebar-open');
			});

			// Close sidebar when clicking outside on mobile
			$(document).on('click', function (e) {
				if ($(window).width() < 992) {
					if (!$(e.target).closest('.sidebar-left, .collapseSidebar').length) {
						$('#leftSidebar').removeClass('show');
						$('body').removeClass('sidebar-open');
					}
				}
			});

			// Auto hide alert
			setTimeout(() => $('.alert').fadeOut('slow'), 4000);

			// Delete confirmation
			$(document).on('click', '.btn-delete', function (e) {
				e.preventDefault();
				const url = $(this).data('url') || $(this).attr('href');
				const title = $(this).data('title') || 'Hapus Data?';
				const text = $(this).data('text') || 'Data yang dihapus tidak dapat dikembalikan!';

				if (typeof Swal !== 'undefined') {
					Swal.fire({
						title: title,
						text: text,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#d33',
						cancelButtonColor: '#3085d6',
						confirmButtonText: 'Ya, hapus!',
						cancelButtonText: 'Batal'
					}).then(res => {
						if (res.isConfirmed) window.location.href = url;
					});
				} else {
					if (confirm('Yakin ingin menghapus data ini?')) {
						window.location.href = url;
					}
				}
			});
		});

	</script>
</body>

</html>

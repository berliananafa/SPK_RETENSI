<?php
// Get user data from session
$user_level = $this->session->userdata('user_level');
$current_url = uri_string();

// Load counts for organizational structure (only for admin)
$org_counts = [];
if ($user_level === 'admin') {
	$this->load->model('PenggunaModel');
	$org_counts = [
		'junior_manager' => $this->db->where('level', 'junior_manager')->count_all_results('pengguna'),
		'supervisor' => $this->db->where('level', 'supervisor')->count_all_results('pengguna'),
		'leader' => $this->db->where('level', 'leader')->count_all_results('pengguna'),
	];
}
?>

<aside class="bg-white shadow sidebar-left border-right" id="leftSidebar" data-simplebar>
	<a href="#" class="mt-3 ml-2 btn collapseSidebar toggle-btn d-lg-none text-muted" data-toggle="toggle">
		<i class="fe fe-x"><span class="sr-only"></span></i>
	</a>
	<nav class="vertnav navbar navbar-light">
		<!-- Logo -->
		<div class="mb-4 w-100 d-flex">
			<a class="mx-auto mt-2 text-center navbar-brand flex-fill" href="<?= base_url() ?>">
				<img src="<?= base_url('assets/img/ethos_logo.png') ?>" alt="Ethos Logo"
					style="max-width: 120px; height: auto;" class="mb-2">
				<!-- <h4 class="mb-0 text-primary">SPK Retensi CS</h4> -->
			</a>
		</div>

		<!-- Menu Admin -->
		<?php if ($user_level === 'admin'): ?>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'dashboard') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
					<i class="fe fe-home"></i>
					<span class="ml-3 item-text">Dashboard</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Master Data</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/pengguna') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/pengguna') ?>">
					<i class="fe fe-users"></i>
					<span class="ml-3 item-text">Manajemen Pengguna</span>
				</a>
			</li>
			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/produk') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/produk') ?>">
					<i class="fe fe-package"></i>
					<span class="ml-3 item-text">Manajemen Produk</span>
				</a>
			</li>
			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/kanal') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/kanal') ?>">
					<i class="fe fe-message-circle"></i>
					<span class="ml-3 item-text">Manajemen Kanal</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Organisasi</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li
				class="nav-item dropdown <?= (strpos($current_url, 'admin/junior-manager') !== false || strpos($current_url, 'admin/supervisor') !== false || strpos($current_url, 'admin/leader') !== false) ? 'active' : '' ?>">
				<a href="#organisasi" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'admin/junior-manager') !== false || strpos($current_url, 'admin/supervisor') !== false || strpos($current_url, 'admin/leader') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-briefcase"></i>
					<span class="ml-3 item-text">Struktur Organisasi</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'admin/junior-manager') !== false || strpos($current_url, 'admin/supervisor') !== false || strpos($current_url, 'admin/leader') !== false) ? 'show' : '' ?>"
					id="organisasi">
					<li
						class="nav-item <?= (strpos($current_url, 'admin/junior-manager') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/junior-manager') ?>">
							<span class="ml-1 item-text">Junior Manager</span>
							<?php if (isset($org_counts['junior_manager'])): ?>
							<span class="ml-2 badge badge-soft-primary"><?= $org_counts['junior_manager'] ?></span>
							<?php endif; ?>
						</a>
					</li>
					<li class="nav-item <?= (strpos($current_url, 'admin/supervisor') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/supervisor') ?>">
							<span class="ml-1 item-text">Supervisor</span>
							<?php if (isset($org_counts['supervisor'])): ?>
							<span class="ml-2 badge badge-soft-info"><?= $org_counts['supervisor'] ?></span>
							<?php endif; ?>
						</a>
					</li>
					<li class="nav-item <?= (strpos($current_url, 'admin/leader') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/leader') ?>">
							<span class="ml-1 item-text">Leader</span>
							<?php if (isset($org_counts['leader'])): ?>
							<span class="ml-2 badge badge-soft-success"><?= $org_counts['leader'] ?></span>
							<?php endif; ?>
						</a>
					</li>
				</ul>
			</li>
			<li
				class="nav-item dropdown <?= (strpos($current_url, 'admin/tim') !== false || strpos($current_url, 'admin/customer-service') !== false) ? 'active' : '' ?>">
				<a href="#team" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'admin/tim') !== false || strpos($current_url, 'admin/customer-service') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-users"></i>
					<span class="ml-3 item-text">Tim & CS</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'admin/tim') !== false || strpos($current_url, 'admin/customer-service') !== false) ? 'show' : '' ?>"
					id="team">
					<li class="nav-item <?= (strpos($current_url, 'admin/tim') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/tim') ?>">
							<span class="ml-1 item-text">Daftar Tim</span>
						</a>
					</li>
					<li
						class="nav-item <?= (strpos($current_url, 'admin/customer-service') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/customer-service') ?>">
							<span class="ml-1 item-text">Customer Service</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Konfigurasi SPK</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li
				class="nav-item dropdown <?= (strpos($current_url, 'admin/kriteria') !== false || strpos($current_url, 'admin/sub-kriteria') !== false) ? 'active' : '' ?>">
				<a href="#kriteria" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'admin/kriteria') !== false || strpos($current_url, 'admin/sub-kriteria') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-sliders"></i>
					<span class="ml-3 item-text">Kriteria</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'admin/kriteria') !== false || strpos($current_url, 'admin/sub-kriteria') !== false) ? 'show' : '' ?>"
					id="kriteria">
					<li class="nav-item <?= (strpos($current_url, 'admin/kriteria') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/kriteria') ?>">
							<span class="ml-1 item-text">Daftar Kriteria</span>
						</a>
					</li>
					<li class="nav-item <?= (strpos($current_url, 'admin/sub-kriteria') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/sub-kriteria') ?>">
							<span class="ml-1 item-text">Sub Kriteria</span>
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/range') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/range') ?>">
					<i class="fe fe-bar-chart-2"></i>
					<span class="ml-3 item-text">Range Nilai</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Penilaian</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item dropdown <?= (strpos($current_url, 'admin/nilai') !== false) ? 'active' : '' ?>">
				<a href="#nilai" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'admin/nilai') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-edit"></i>
					<span class="ml-3 item-text">Input Nilai CS</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'admin/nilai') !== false) ? 'show' : '' ?>"
					id="nilai">
					<li class="nav-item <?= (strpos($current_url, 'admin/nilai/input') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/nilai/input') ?>">
							<span class="ml-1 item-text">Input Penilaian</span>
						</a>
					</li>
					<li
						class="nav-item <?= (strpos($current_url, 'admin/nilai') !== false && strpos($current_url, 'admin/nilai/input') === false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('admin/nilai') ?>">
							<span class="ml-1 item-text">Monitoring Penilaian</span>
						</a>
					</li>
				</ul>
			</li>

			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/ranking') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/ranking') ?>">
					<i class="fe fe-award"></i>
					<span class="ml-3 item-text">Hasil Ranking</span>
				</a>
			</li>


			<li class="nav-item w-100 <?= (strpos($current_url, 'admin/laporan') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('admin/laporan') ?>">
					<i class="fe fe-file-text"></i>
					<span class="ml-3 item-text">Laporan Performa</span>
				</a>
			</li>
		</ul>

		<?php elseif ($user_level === 'junior_manager'): ?>
		<!-- Menu Junior Manager -->
		<ul class="navbar-nav flex-fill w-100">
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/dashboard') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/dashboard') ?>">
					<i class="fe fe-home"></i>
					<span class="ml-3 item-text">Dashboard</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Tim Saya</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/supervisor') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/supervisor') ?>">
					<i class="fe fe-user-check"></i>
					<span class="ml-3 item-text">Supervisor</span>
				</a>
			</li>
			<li
				class="nav-item dropdown <?= (strpos($current_url, 'junior-manager/team-overview') !== false || strpos($current_url, 'junior-manager/customer-service') !== false) ? 'active' : '' ?>">
				<a href="#team-jm" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'junior-manager/team-overview') !== false || strpos($current_url, 'junior-manager/customer-service') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-users"></i>
					<span class="ml-3 item-text">Tim & CS</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'junior-manager/team-overview') !== false || strpos($current_url, 'junior-manager/customer-service') !== false) ? 'show' : '' ?>"
					id="team-jm">
					<li
						class="nav-item <?= (strpos($current_url, 'junior-manager/team-overview') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('junior-manager/team-overview') ?>">
							<span class="ml-1 item-text">Overview Tim</span>
						</a>
					</li>
					<li
						class="nav-item <?= (strpos($current_url, 'junior-manager/customer-service') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('junior-manager/customer-service') ?>">
							<span class="ml-1 item-text">Customer Service</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Approval</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/kriteria') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/kriteria') ?>">
					<i class="fe fe-check-square"></i>
					<span class="ml-3 item-text">Data Kriteria</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Monitoring & Laporan</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/nilai') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/nilai') ?>">
					<i class="fe fe-eye"></i>
					<span class="ml-3 item-text">Monitor Penilaian</span>
				</a>
			</li>
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/ranking') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/ranking') ?>">
					<i class="fe fe-award"></i>
					<span class="ml-3 item-text">Hasil Ranking</span>
				</a>
			</li>
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'junior-manager/laporan') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('junior-manager/laporan') ?>">
					<i class="fe fe-file-text"></i>
					<span class="ml-3 item-text">Laporan Performa</span>
				</a>
			</li>
		</ul>

		<?php elseif ($user_level === 'supervisor'): ?>
		<!-- Menu Supervisor -->
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'supervisor/dashboard') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('supervisor/dashboard') ?>">
					<i class="fe fe-home"></i>
					<span class="ml-3 item-text">Dashboard</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Tim Saya</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'supervisor/leader') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('supervisor/leader') ?>">
					<i class="fe fe-user"></i>
					<span class="ml-3 item-text">Leader</span>
				</a>
			</li>
			<li
				class="nav-item dropdown <?= (strpos($current_url, 'supervisor/team') !== false || strpos($current_url, 'supervisor/customer-service') !== false) ? 'active' : '' ?>">
				<a href="#team-spv" data-toggle="collapse"
					aria-expanded="<?= (strpos($current_url, 'supervisor/team') !== false || strpos($current_url, 'supervisor/customer-service') !== false) ? 'true' : 'false' ?>"
					class="dropdown-toggle nav-link">
					<i class="fe fe-users"></i>
					<span class="ml-3 item-text">Tim & CS</span>
				</a>
				<ul class="pl-4 collapse list-unstyled w-100 <?= (strpos($current_url, 'supervisor/team') !== false || strpos($current_url, 'supervisor/customer-service') !== false) ? 'show' : '' ?>"
					id="team-spv">
					<li class="nav-item <?= (strpos($current_url, 'supervisor/team') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('supervisor/team') ?>">
							<span class="ml-1 item-text">Daftar Tim</span>
						</a>
					</li>
					<li
						class="nav-item <?= (strpos($current_url, 'supervisor/customer-service') !== false) ? 'active' : '' ?>">
						<a class="pl-3 nav-link" href="<?= base_url('supervisor/customer-service') ?>">
							<span class="ml-1 item-text">Customer Service</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Monitoring & Laporan</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'supervisor/nilai') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('supervisor/nilai') ?>">
					<i class="fe fe-eye"></i>
					<span class="ml-3 item-text">Monitor Penilaian</span>
				</a>
			</li>
			<li class="nav-item w-100 <?= (strpos($current_url, 'supervisor/ranking') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('supervisor/ranking') ?>">
					<i class="fe fe-award"></i>
					<span class="ml-3 item-text">Hasil Ranking</span>
				</a>
			</li>
			<li class="nav-item w-100 <?= (strpos($current_url, 'supervisor/laporan') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('supervisor/laporan') ?>">
					<i class="fe fe-file-text"></i>
					<span class="ml-3 item-text">Laporan Performa</span>
				</a>
			</li>
		</ul>

		<?php elseif ($user_level === 'leader'): ?>
		<!-- Menu Leader -->
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'leader/dashboard') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('leader/dashboard') ?>">
					<i class="fe fe-home"></i>
					<span class="ml-3 item-text">Dashboard</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Tim Saya</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'leader/anggota') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('leader/anggota') ?>">
					<i class="fe fe-users"></i>
					<span class="ml-3 item-text">Customer Service</span>
				</a>
			</li>
			<li
				class="nav-item w-100 <?= (strpos($current_url, 'leader/customer-service') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('leader/customer-service') ?>">
					<i class="fe fe-user-check"></i>
					<span class="ml-3 item-text">Customer Service</span>
				</a>
			</li>
		</ul>

		<p class="mt-4 mb-1 text-muted nav-heading"><span>Performance</span></p>
		<ul class="navbar-nav flex-fill w-100">
			<li class="nav-item w-100 <?= (strpos($current_url, 'leader/ranking') !== false) ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('leader/ranking') ?>">
					<i class="fe fe-award"></i>
					<span class="ml-3 item-text">Ranking Tim</span>
				</a>
			</li>
		</ul>
		<?php endif; ?>
	</nav>
</aside>

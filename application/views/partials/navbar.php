<?php
// Get user data from session
$user_name = $this->session->userdata('user_name') ?? 'User';
$user_email = $this->session->userdata('user_email') ?? '';
?>

<nav class="topnav navbar navbar-light">
	<button type="button" class="p-0 mt-2 mr-3 navbar-toggler text-muted collapseSidebar">
		<i class="fe fe-menu navbar-toggler-icon"></i>
	</button>
	<ul class="nav">
		<!-- <li class="nav-item nav-notif">
			<a class="my-2 nav-link text-muted" href="#" data-toggle="modal" data-target=".modal-notif">
				<i class="fe fe-bell fe-16"></i>
				<span class="dot dot-md bg-success"></span>
			</a>
		</li> -->
		<li class="nav-item dropdown">
			<a class="pr-0 nav-link dropdown-toggle text-muted" href="#" id="navbarDropdownMenuLink" role="button"
				data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mt-2 avatar avatar-sm">
					<img src="https://ui-avatars.com/api/?name=<?= urlencode($user_name) ?>&background=007bff&color=fff&size=128" alt="User"
						class="avatar-img rounded-circle">
				</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
				<h6 class="px-3 pt-2 mb-0 dropdown-header"><?= $user_name ?></h6>
				<small class="px-3 text-muted"><?= $user_email ?></small>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="<?= base_url('logout') ?>" id="logoutBtn">
					<i class="mr-2 fe fe-log-out"></i> Keluar
				</a>
			</div>
		</li>
	</ul>
</nav>


<!-- Modal Notifications -->
<!-- <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="notifModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="notifModalLabel">Notifikasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="list-group list-group-flush my-n3">
					<div class="bg-transparent list-group-item">
						<div class="row align-items-center">
							<div class="col-auto">
								<i class="fe fe-inbox fe-24 text-muted"></i>
							</div>
							<div class="col">
								<small><strong>Tidak ada notifikasi baru</strong></small>
								<div class="my-0 text-muted small">Semua notifikasi sudah dibaca</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div> -->

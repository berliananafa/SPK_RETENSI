<!-- Supervisor List -->
<div class="row">
	<div class="col-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<strong class="card-title">Daftar Supervisor</strong>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover table-table-striped datatable" id="dataTable-1">
						<thead>
							<tr>
								<th>No</th>
								<th>NIK</th>
								<th>Nama Supervisor</th>
								<th>Email</th>
								<th>Scope (Kanal × Produk)</th>
								<th>Total Tim</th>
								<th>Total CS</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($supervisors)): ?>
							<?php foreach ($supervisors as $index => $supervisor): ?>
							<tr>
								<td><?= $index + 1 ?></td>
								<td><span
										class="badge badge-soft-primary"><?= htmlspecialchars($supervisor->nik) ?></span>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<strong><?= htmlspecialchars($supervisor->nama_pengguna) ?></strong>
									</div>
								</td>
								<td><?= htmlspecialchars($supervisor->email) ?></td>
								<td>
									<?php 
                                        $scopes = $this->SupervisorScopeModel->getBySupervisor($supervisor->id_user);
                                        if (!empty($scopes)): 
                                        ?>
									<span class="badge badge-info badge-pill" title="Total kombinasi kanal × produk">
										<i class="fe fe-briefcase"></i> <?= count($scopes) ?> Scope
									</span>
									<?php else: ?>
									<span class="badge badge-secondary">Belum ada</span>
									<?php endif; ?>
								</td>
								<td><span class="badge badge-soft-info"><?= $supervisor->total_tim ?> Tim</span></td>
								<td><span class="badge badge-soft-success"><?= $supervisor->total_cs ?> CS</span></td>
								<td>
									<a href="<?= base_url('junior-manager/supervisor/detail/' . $supervisor->id_user) ?>"
										class="btn btn-sm btn-primary">
										<i class="fe fe-eye"></i> Detail
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php else: ?>
							<tr>
								<td colspan="8" class="text-center">Belum ada data supervisor</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

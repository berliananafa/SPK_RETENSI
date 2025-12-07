<!-- Dashboard Admin -->
<div class="row">
	<!-- Statistics Cards -->
	<div class="col-lg-3 col-md-6">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-five">
					<div class="stat-icon dib flat-color-1">
						<i class="pe-7s-users"></i>
					</div>
					<div class="stat-content">
						<div class="text-left dib">
							<div class="stat-text"><span class="count">125</span></div>
							<div class="stat-heading">Total CS</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-md-6">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-five">
					<div class="stat-icon dib flat-color-2">
						<i class="pe-7s-portfolio"></i>
					</div>
					<div class="stat-content">
						<div class="text-left dib">
							<div class="stat-text"><span class="count">15</span></div>
							<div class="stat-heading">Total Tim</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-md-6">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-five">
					<div class="stat-icon dib flat-color-3">
						<i class="pe-7s-graph1"></i>
					</div>
					<div class="stat-content">
						<div class="text-left dib">
							<div class="stat-text"><span class="count">89</span></div>
							<div class="stat-heading">Penilaian Selesai</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-md-6">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-five">
					<div class="stat-icon dib flat-color-4">
						<i class="pe-7s-clock"></i>
					</div>
					<div class="stat-content">
						<div class="text-left dib">
							<div class="stat-text"><span class="count">23</span></div>
							<div class="stat-heading">Penilaian Pending</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Charts Row -->
<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<h4 class="box-title">Grafik Performa CS Bulanan</h4>
			</div>
			<div class="card-body">
				<canvas id="performanceChart" height="100"></canvas>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h4 class="box-title">Distribusi Ranking</h4>
			</div>
			<div class="card-body">
				<canvas id="rankingChart"></canvas>
			</div>
		</div>
	</div>
</div>

<!-- Recent Activities -->
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title box-title">Penilaian Terbaru</h4>
				<div class="card-content">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>CS</th>
									<th>Evaluator</th>
									<th>Periode</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="media">
											<img src="<?= base_url('assets/images/avatar/1.jpg') ?>" alt="" class="rounded-circle mr-2" width="30">
											<div class="media-body">
												<div class="name">Andi Pratama</div>
											</div>
										</div>
									</td>
									<td>Supervisor A</td>
									<td>Nov 2025</td>
									<td><span class="badge badge-success">Selesai</span></td>
								</tr>
								<tr>
									<td>
										<div class="media">
											<img src="<?= base_url('assets/images/avatar/2.jpg') ?>" alt="" class="rounded-circle mr-2" width="30">
											<div class="media-body">
												<div class="name">Siti Nurhaliza</div>
											</div>
										</div>
									</td>
									<td>Junior Manager B</td>
									<td>Nov 2025</td>
									<td><span class="badge badge-warning">Draft</span></td>
								</tr>
								<tr>
									<td>
										<div class="media">
											<img src="<?= base_url('assets/images/avatar/3.jpg') ?>" alt="" class="rounded-circle mr-2" width="30">
											<div class="media-body">
												<div class="name">Budi Santoso</div>
											</div>
										</div>
									</td>
									<td>Supervisor C</td>
									<td>Nov 2025</td>
									<td><span class="badge badge-info">Review</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title box-title">Top 5 CS Terbaik Bulan Ini</h4>
				<div class="card-content">
					<div class="todo-list">
						<div class="tdl-holder">
							<div class="tdl-content">
								<ul class="list-unstyled">
									<li class="mb-3">
										<div class="media">
											<div class="rank-number bg-warning text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-weight: bold;">1</div>
											<div class="media-body">
												<h6 class="mt-0 mb-0">Sarah Wijaya</h6>
												<small class="text-muted">Tim Customer Care - Score: 95.2</small>
											</div>
											<div class="text-right">
												<i class="fa fa-trophy text-warning"></i>
											</div>
										</div>
									</li>
									<li class="mb-3">
										<div class="media">
											<div class="rank-number bg-secondary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-weight: bold;">2</div>
											<div class="media-body">
												<h6 class="mt-0 mb-0">Ahmad Rahman</h6>
												<small class="text-muted">Tim Technical Support - Score: 92.8</small>
											</div>
											<div class="text-right">
												<i class="fa fa-medal text-secondary"></i>
											</div>
										</div>
									</li>
									<li class="mb-3">
										<div class="media">
											<div class="rank-number bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-weight: bold;">3</div>
											<div class="media-body">
												<h6 class="mt-0 mb-0">Linda Sari</h6>
												<small class="text-muted">Tim Sales Support - Score: 89.5</small>
											</div>
											<div class="text-right">
												<i class="fa fa-award text-info"></i>
											</div>
										</div>
									</li>
									<li class="mb-3">
										<div class="media">
											<div class="rank-number bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-weight: bold;">4</div>
											<div class="media-body">
												<h6 class="mt-0 mb-0">Doni Prakoso</h6>
												<small class="text-muted">Tim Customer Care - Score: 87.3</small>
											</div>
										</div>
									</li>
									<li class="mb-3">
										<div class="media">
											<div class="rank-number bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-weight: bold;">5</div>
											<div class="media-body">
												<h6 class="mt-0 mb-0">Maya Indira</h6>
												<small class="text-muted">Tim Technical Support - Score: 85.9</small>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- JavaScript untuk Charts -->
<script>
	$(document).ready(function() {
		// Performance Chart
		const ctx1 = document.getElementById('performanceChart').getContext('2d');
		new Chart(ctx1, {
			type: 'line',
			data: {
				labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'],
				datasets: [{
					label: 'Rata-rata Score',
					data: [75, 78, 80, 79, 82, 85, 83, 87, 89, 88, 91],
					borderColor: '#4CAF50',
					backgroundColor: 'rgba(76, 175, 80, 0.1)',
					tension: 0.4
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'top',
					}
				},
				scales: {
					y: {
						beginAtZero: false,
						min: 70,
						max: 100
					}
				}
			}
		});

		// Ranking Distribution Chart
		const ctx2 = document.getElementById('rankingChart').getContext('2d');
		new Chart(ctx2, {
			type: 'doughnut',
			data: {
				labels: ['Excellent', 'Good', 'Average', 'Poor'],
				datasets: [{
					data: [25, 45, 25, 5],
					backgroundColor: [
						'#4CAF50',
						'#2196F3',
						'#FF9800',
						'#F44336'
					]
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'bottom'
					}
				}
			}
		});

		// Counter Animation
		$('.count').each(function() {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 2000,
				easing: 'swing',
				step: function(now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
	});
</script>

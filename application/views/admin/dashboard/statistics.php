<!-- Statistics Dashboard -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Statistik Sistem SPK Retensi</strong>
            </div>
            <div class="card-body">
                <p>Analisis performa dan statistik penggunaan sistem.</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Trend Penilaian Bulanan</strong>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Distribusi Level Pengguna</strong>
            </div>
            <div class="card-body">
                <canvas id="userLevelChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Tables -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Top Performer CS</strong>
            </div>
            <div class="card-body">
                <div class="table-stats order-table ov-h">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Nama CS</th>
                                <th>Tim</th>
                                <th>Score</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-success">1</span></td>
                                <td>Sarah Johnson</td>
                                <td>Team Alpha</td>
                                <td><strong>95.2</strong></td>
                                <td><i class="fa fa-arrow-up text-success"></i></td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">2</span></td>
                                <td>Mike Wilson</td>
                                <td>Team Beta</td>
                                <td><strong>92.8</strong></td>
                                <td><i class="fa fa-arrow-up text-success"></i></td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">3</span></td>
                                <td>Lisa Chen</td>
                                <td>Team Gamma</td>
                                <td><strong>90.5</strong></td>
                                <td><i class="fa fa-minus text-muted"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">System Health</strong>
            </div>
            <div class="card-body">
                <div class="progress-box progress-1">
                    <h4 class="por-title">Database Performance</h4>
                    <div class="por-txt">Excellent (98%)</div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 98%;" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="progress-box progress-2">
                    <h4 class="por-title">System Uptime</h4>
                    <div class="por-txt">99.9% uptime</div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 99%;" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="progress-box progress-3">
                    <h4 class="por-title">User Satisfaction</h4>
                    <div class="por-txt">4.8/5 rating</div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 96%;" aria-valuenow="96" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Laporan Aktivitas Harian</strong>
            </div>
            <div class="card-body">
                <div class="table-stats order-table ov-h">
                    <table class="table table-hover" id="dailyActivityTable">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10:30 AM</td>
                                <td>John Doe</td>
                                <td>Input Penilaian CS</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                                <td>5 penilaian diinput</td>
                            </tr>
                            <tr>
                                <td>10:25 AM</td>
                                <td>Jane Smith</td>
                                <td>Update Kriteria</td>
                                <td><span class="badge badge-info">Completed</span></td>
                                <td>Kriteria komunikasi diupdate</td>
                            </tr>
                            <tr>
                                <td>10:15 AM</td>
                                <td>Mike Johnson</td>
                                <td>Generate Ranking</td>
                                <td><span class="badge badge-warning">Processing</span></td>
                                <td>Menghitung ranking periode ini</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
$(document).ready(function() {
    // Monthly Trend Chart
    var ctx1 = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Jumlah Penilaian',
                data: [65, 78, 90, 81, 96, 85],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Level Distribution Chart
    var ctx2 = document.getElementById('userLevelChart').getContext('2d');
    var userLevelChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Supervisor', 'Manager', 'Leader'],
            datasets: [{
                data: [2, 5, 8, 12],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Initialize DataTable for daily activity
    $('#dailyActivityTable').DataTable({
        "pageLength": 10,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ]
    });
});
</script>

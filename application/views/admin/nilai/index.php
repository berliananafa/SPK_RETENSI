<!-- Monitoring Penilaian CS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Monitoring Data Penilaian Customer Service</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/nilai/input') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-upload"></i> Upload Penilaian
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filterPeriode" class="font-weight-bold">Periode:</label>
                        <input type="month" class="form-control form-control-sm" id="filterPeriode" 
                               value="<?= date('Y-m') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterKriteria" class="font-weight-bold">Kriteria:</label>
                        <select class="form-control form-control-sm" id="filterKriteria">
                            <option value="">-- Semua Kriteria --</option>
                            <?php if (!empty($kriteria)): ?>
                                <?php foreach ($kriteria as $krt): ?>
                                    <option value="<?= $krt->id_kriteria ?>"><?= htmlspecialchars($krt->kode_kriteria . ' - ' . $krt->nama_kriteria) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterTim" class="font-weight-bold">Tim:</label>
                        <select class="form-control form-control-sm" id="filterTim">
                            <option value="">-- Semua Tim --</option>
                            <?php if (!empty($tim)): ?>
                                <?php foreach ($tim as $t): ?>
                                    <option value="<?= $t->id_tim ?>"><?= htmlspecialchars($t->nama_tim) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold d-block">&nbsp;</label>
                        <button class="btn btn-info btn-sm btn-block" id="btnFilter">
                            <i class="fe fe-filter"></i> Filter Data
                        </button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card border-left-primary shadow-sm">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Penilaian
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= isset($total_penilaian) ? $total_penilaian : 0 ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fe fe-clipboard fe-32 text-primary opacity-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-success shadow-sm">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            CS Dinilai
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= isset($total_cs) ? $total_cs : 0 ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fe fe-users fe-32 text-success opacity-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-info shadow-sm">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Rata-rata Nilai
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= isset($rata_rata) ? number_format($rata_rata, 2) : '0.00' ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fe fe-bar-chart-2 fe-32 text-info opacity-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-warning shadow-sm">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Kriteria Aktif
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= isset($total_kriteria) ? $total_kriteria : 0 ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fe fe-sliders fe-32 text-warning opacity-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table id="dataTable-1" class="table table-hover table-borderless">
                        <thead >
                            <tr>
                                <th width="5%">No</th>
                                <th>NIP</th>
                                <th>Nama CS</th>
                                <th>Tim</th>
                                <th>Kriteria</th>
                                <th>Nilai</th>
                                <th>Nilai Konversi</th>
                                <th>Periode</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($penilaian)): ?>
                                <?php foreach ($penilaian as $index => $nilai): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><span class="badge badge-soft-primary"><?= htmlspecialchars($nilai->nik ?? '-') ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        <?= strtoupper(substr($nilai->nama_cs, 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <strong><?= htmlspecialchars($nilai->nama_cs) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">-</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?= htmlspecialchars($nilai->kode_kriteria) ?></span>
                                            <br><small class="text-muted"><?= htmlspecialchars($nilai->nama_kriteria) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-lg badge-warning"><?= number_format($nilai->nilai, 2) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-lg badge-success">-</span>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($nilai->created_at)) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url('admin/nilai/delete/' . $nilai->id_nilai) ?>" 
                                                   class="btn btn-danger btn-delete" 
                                                   data-title="Hapus Penilaian?"
                                                   data-text="Data penilaian akan dihapus permanen!"
                                                   title="Hapus">
                                                    <i class="fe fe-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fe fe-inbox fe-24 mb-3"></i>
                                        <p>Belum ada data penilaian untuk periode ini</p>
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

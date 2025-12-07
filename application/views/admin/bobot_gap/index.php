<!-- Header -->
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Bobot GAP</strong> Profile Matching</h3>
    </div>

    <div class="col-auto ml-auto text-right mt-n1">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
            <i class="fe fe-plus"></i> Tambah Bobot
        </button>
    </div>
</div>

<!-- Info Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-left-info mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Tentang Bobot GAP</h6>
                        <p class="mb-0 text-muted small">
                            Bobot GAP adalah nilai pembobotan untuk setiap selisih (gap) antara nilai aktual CS dengan nilai target. 
                            <strong>GAP = Nilai Aktual - Nilai Target</strong>. Semakin kecil gap (mendekati 0), semakin tinggi bobotnya. 
                            Standar Profile Matching menggunakan rentang gap -4 hingga +4 dengan bobot tertinggi (5.0) untuk gap = 0.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Daftar Bobot GAP</strong>
                <span class="float-right text-muted small">Total: <strong id="totalRecords">0</strong> data</span>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nilai gap...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btnSearch">
                                    <i class="fe fe-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-5 text-right">
                        <button type="button" class="btn btn-secondary btn-sm" id="btnResetFilter">
                            <i class="fe fe-refresh-cw"></i> Reset Filter
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btnSetDefault">
                            <i class="fe fe-settings"></i> Set Default
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="bobotGapTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nilai GAP</th>
                                <th width="20%">Bobot</th>
                                <th width="35%">Keterangan</th>
                                <th width="10%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Data - Replace with dynamic data from backend -->
                            <tr>
                                <td>1</td>
                                <td>
                                    <span class="badge badge-pill badge-success" style="font-size: 14px; padding: 8px 15px;">
                                        0
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                5.0
                                            </div>
                                        </div>
                                        <span class="ml-2 font-weight-bold text-success">5.0</span>
                                    </div>
                                </td>
                                <td><small class="text-muted">Tidak ada selisih (Kompetensi sesuai dengan yang dibutuhkan)</small></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit" 
                                            onclick="editBobotGap(1)">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBobotGap(1)">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <span class="badge badge-pill badge-info" style="font-size: 14px; padding: 8px 15px;">
                                        1
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                 style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                4.5
                                            </div>
                                        </div>
                                        <span class="ml-2 font-weight-bold text-info">4.5</span>
                                    </div>
                                </td>
                                <td><small class="text-muted">Kompetensi individu kelebihan 1 tingkat/level</small></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit" 
                                            onclick="editBobotGap(2)">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBobotGap(2)">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <span class="badge badge-pill badge-warning" style="font-size: 14px; padding: 8px 15px;">
                                        -1
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                4.0
                                            </div>
                                        </div>
                                        <span class="ml-2 font-weight-bold text-warning">4.0</span>
                                    </div>
                                </td>
                                <td><small class="text-muted">Kompetensi individu kekurangan 1 tingkat/level</small></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit" 
                                            onclick="editBobotGap(3)">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBobotGap(3)">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <span class="badge badge-pill badge-primary" style="font-size: 14px; padding: 8px 15px;">
                                        2
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                4.0
                                            </div>
                                        </div>
                                        <span class="ml-2 font-weight-bold text-primary">4.0</span>
                                    </div>
                                </td>
                                <td><small class="text-muted">Kompetensi individu kelebihan 2 tingkat/level</small></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit" 
                                            onclick="editBobotGap(4)">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBobotGap(4)">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <span class="badge badge-pill badge-danger" style="font-size: 14px; padding: 8px 15px;">
                                        -2
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar bg-danger" role="progressbar" 
                                                 style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                                3.5
                                            </div>
                                        </div>
                                        <span class="ml-2 font-weight-bold text-danger">3.5</span>
                                    </div>
                                </td>
                                <td><small class="text-muted">Kompetensi individu kekurangan 2 tingkat/level</small></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEdit" 
                                            onclick="editBobotGap(5)">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBobotGap(5)">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                            <tr class="table-info">
                                <td colspan="6" class="text-center">
                                    <i class="fe fe-alert-circle"></i> Data di atas adalah contoh default Profile Matching. 
                                    Klik <strong>Set Default</strong> untuk generate otomatis atau tambah manual sesuai kebutuhan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="fe fe-database fe-48 text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data bobot GAP. Klik <strong>Set Default</strong> untuk generate standar atau 
                        <strong>Tambah Bobot</strong> untuk input manual.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bobot GAP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/bobot-gap/store', ['id' => 'formCreate']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nilai_gap">Nilai GAP <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="nilai_gap" name="nilai_gap" 
                           placeholder="Contoh: 0, 1, -1, 2, -2" step="1" required>
                    <small class="form-text text-muted">
                        GAP = Nilai Aktual - Nilai Target (biasanya rentang -4 sampai +4)
                    </small>
                </div>

                <div class="form-group">
                    <label for="bobot">Bobot <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="bobot" name="bobot" 
                           placeholder="Contoh: 5.0, 4.5, 4.0" min="0" max="5" step="0.1" required>
                    <small class="form-text text-muted">
                        Nilai bobot 0-5 (semakin mendekati 0, semakin tinggi bobotnya)
                    </small>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                              placeholder="Contoh: Tidak ada selisih (Kompetensi sesuai dengan yang dibutuhkan)" required></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif" selected>Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="alert alert-info mb-0" role="alert">
                    <small>
                        <strong>Standar Profile Matching:</strong><br>
                        GAP 0 = 5.0 | GAP ±1 = 4.5 | GAP -2 = 3.5 | GAP ±2 = 4.0 | GAP -3 = 3.0 | 
                        GAP ±3 = 3.5 | GAP -4 = 2.5 | GAP ±4 = 3.0
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-save"></i> Simpan
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bobot GAP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/bobot-gap/update', ['id' => 'formEdit']) ?>
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_nilai_gap">Nilai GAP <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="edit_nilai_gap" name="nilai_gap" 
                           placeholder="Contoh: 0, 1, -1, 2, -2" step="1" required>
                    <small class="form-text text-muted">
                        GAP = Nilai Aktual - Nilai Target (biasanya rentang -4 sampai +4)
                    </small>
                </div>

                <div class="form-group">
                    <label for="edit_bobot">Bobot <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="edit_bobot" name="bobot" 
                           placeholder="Contoh: 5.0, 4.5, 4.0" min="0" max="5" step="0.1" required>
                    <small class="form-text text-muted">
                        Nilai bobot 0-5 (semakin mendekati 0, semakin tinggi bobotnya)
                    </small>
                </div>

                <div class="form-group">
                    <label for="edit_keterangan">Keterangan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3" 
                              placeholder="Contoh: Tidak ada selisih (Kompetensi sesuai dengan yang dibutuhkan)" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="edit_status" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-save"></i> Update
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#bobotGapTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[1, "asc"]] // Sort by nilai_gap
    });

    // Search
    $('#btnSearch, #searchInput').on('click keyup', function() {
        table.search($('#searchInput').val()).draw();
    });

    // Filter Status
    $('#filterStatus').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    // Reset Filter
    $('#btnResetFilter').on('click', function() {
        $('#searchInput').val('');
        $('#filterStatus').val('');
        table.search('').columns().search('').draw();
    });

    // Update total records
    table.on('draw', function() {
        $('#totalRecords').text(table.page.info().recordsDisplay);
    });
    $('#totalRecords').text(table.page.info().recordsTotal);

    // Set Default Button
    $('#btnSetDefault').on('click', function() {
        if(confirm('Generate default bobot GAP Profile Matching?\nIni akan membuat 9 bobot standar (GAP -4 hingga +4).')) {
            window.location.href = '<?= base_url("admin/bobot-gap/set-default") ?>';
        }
    });
});

// Edit Function
function editBobotGap(id) {
    // Ajax call to get data
    $.ajax({
        url: '<?= base_url("admin/bobot-gap/get/") ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#edit_id').val(data.id_bobot_gap);
            $('#edit_nilai_gap').val(data.nilai_gap);
            $('#edit_bobot').val(data.bobot);
            $('#edit_keterangan').val(data.keterangan);
            $('#edit_status').val(data.status);
        }
    });
}

// Delete Function
function deleteBobotGap(id) {
    if(confirm('Yakin ingin menghapus bobot GAP ini?\nData yang sudah dihapus tidak dapat dikembalikan.')) {
        window.location.href = '<?= base_url("admin/bobot-gap/delete/") ?>' + id;
    }
}
</script>

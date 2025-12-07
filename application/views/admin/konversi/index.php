<!-- Konversi List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Konversi Nilai</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/konversi/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Konversi
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter by CS -->
                <div class="mb-3">
                    <label for="filterCS" class="font-weight-bold">Filter berdasarkan Customer Service:</label>
                    <select class="form-control form-control-sm" id="filterCS" style="max-width: 300px;">
                        <option value="">-- Semua CS --</option>
                        <?php if (!empty($all_cs)): ?>
                            <?php foreach ($all_cs as $cs): ?>
                                <option value="<?= $cs->id_cs ?>"><?= htmlspecialchars($cs->nama_cs) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <table id="dataTable-1" class="table table-hover table-borderless">
                    <thead >
                        <tr>
                            <th width="5%">No</th>
                            <th>Customer Service</th>
                            <th>Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Nilai Asli</th>
                            <th>Range</th>
                            <th>Nilai Konversi</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($konversi)): ?>
                            <?php foreach ($konversi as $index => $knv): ?>
                                <tr data-cs="<?= $knv->id_cs ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong><?= htmlspecialchars($knv->nama_cs ?? '-') ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary"><?= htmlspecialchars($knv->kode_kriteria ?? '-') ?></span>
                                        <br><small class="text-muted"><?= htmlspecialchars($knv->nama_kriteria ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <strong class="text-info"><?= htmlspecialchars($knv->nama_sub_kriteria ?? '-') ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-lg badge-warning">
                                            <i class="fe fe-edit-3"></i> <?= number_format($knv->nilai_asli, 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= number_format($knv->batas_bawah ?? 0, 2) ?> - <?= number_format($knv->batas_atas ?? 0, 2) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-lg badge-success">
                                            <i class="fe fe-arrow-right"></i> <?= number_format($knv->nilai_konversi, 2) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/konversi/edit/' . $knv->id_konversi) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/konversi/delete/' . $knv->id_konversi) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Konversi?"
                                               data-text="Data konversi akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data Konversi</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-left-info">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-info"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi Konversi Nilai</h6>
                        <p class="mb-0 text-muted small">
                            Tabel konversi menyimpan data nilai asli Customer Service yang telah dikonversi berdasarkan range nilai yang telah ditentukan.
                            <br><strong>Proses:</strong> Nilai Asli → Dicari Range yang sesuai → Hasil Nilai Konversi
                            <br><strong>Contoh:</strong> CS memiliki nilai 75 untuk sub kriteria "Produktivitas". Jika 70-80 = Range nilai 4, maka Nilai Konversi = 4
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterSelect = document.getElementById('filterCS');
    const tableRows = document.querySelectorAll('#dataTable-1 tbody tr[data-cs]');
    
    filterSelect.addEventListener('change', function() {
        const selectedCS = this.value;
        
        tableRows.forEach(function(row) {
            if (selectedCS === '' || row.getAttribute('data-cs') === selectedCS) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update row numbers
        let visibleIndex = 1;
        tableRows.forEach(function(row) {
            if (row.style.display !== 'none') {
                row.querySelector('td:first-child').textContent = visibleIndex++;
            }
        });
    });

    // Delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const title = $(this).data('title') || 'Hapus Data?';
        const text = $(this).data('text') || 'Data akan dihapus permanen!';

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>

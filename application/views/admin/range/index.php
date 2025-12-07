<!-- Range Nilai List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Range Nilai</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/range/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Range
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter by Kriteria -->
                <div class="mb-3">
                    <label for="filterKriteria" class="font-weight-bold">Filter berdasarkan Kriteria:</label>
                    <select class="form-control form-control-sm" id="filterKriteria" style="max-width: 300px;">
                        <option value="">-- Semua Kriteria --</option>
                        <?php if (!empty($all_kriteria)): ?>
                            <?php foreach ($all_kriteria as $k): ?>
                                <option value="<?= $k->id_kriteria ?>"><?= htmlspecialchars($k->kode_kriteria . ' - ' . $k->nama_kriteria) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <table id="dataTable-1" class="table table-hover table-borderless">
                    <thead >
                        <tr>
                            <th width="5%">No</th>
                            <th>Sub Kriteria</th>
                            <th>Batas Bawah</th>
                            <th>Batas Atas</th>
                            <th>Nilai Range</th>
                            <th>Keterangan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ranges)): ?>
                            <?php foreach ($ranges as $index => $range): ?>
                                <tr data-kriteria="<?= $range->id_sub_kriteria ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <span class="badge badge-primary"><?= htmlspecialchars($range->kode_kriteria ?? '-') ?></span>
                                        <br><small class="text-muted"><?= htmlspecialchars($range->nama_kriteria ?? '-') ?></small>
                                        <br><strong class="text-info"><?= htmlspecialchars($range->nama_sub_kriteria ?? '-') ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-lg badge-info">
                                            <?= number_format($range->batas_bawah, 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-lg badge-info">
                                            <?= number_format($range->batas_atas, 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-lg badge-success">
                                            <i class="fe fe-arrow-right"></i> <?= number_format($range->nilai_range, 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($range->keterangan ?? '-') ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/range/edit/' . $range->id_range) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/range/delete/' . $range->id_range) ?>" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-title="Hapus Range?"
                                               data-text="Data range nilai akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data Range Nilai</p>
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
        <div class="card shadow-sm border-left-warning">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-warning"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi Range Nilai</h6>
                        <p class="mb-0 text-muted small">
                            Range nilai digunakan untuk mengkonversi nilai mentah menjadi nilai standar (normalized). 
                            Setiap range memiliki batas minimum dan maksimum yang tidak boleh tumpang tindih.
                            <br><strong>Contoh:</strong> Produktivitas 0-50 = 1, 51-100 = 2, 101-150 = 3, dst.
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
    const filterSelect = document.getElementById('filterKriteria');
    const tableRows = document.querySelectorAll('#dataTable-1 tbody tr[data-kriteria]');
    
    filterSelect.addEventListener('change', function() {
        const selectedKriteria = this.value;
        
        tableRows.forEach(function(row) {
            if (selectedKriteria === '' || row.getAttribute('data-kriteria') === selectedKriteria) {
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

<!-- Sub Kriteria List -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <strong class="card-title">Data Sub Kriteria</strong>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('admin/sub-kriteria/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Tambah Sub Kriteria
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

                <table id="dataTable-1" class="table table-hover table-striped">
                    <thead >
                        <tr>
                            <th width="5%">No</th>
                            <th>Kriteria</th>
                            <th>Nama Sub Kriteria</th>
                            <th width="10%">Bobot (%)</th>
                            <th width="12%" class="text-center">Status Kriteria</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sub_kriteria)): ?>
                            <?php foreach ($sub_kriteria as $index => $sub): ?>
                                <?php
                                    $status = $sub->status_approval ?? 'pending';
                                    $badge_class = 'secondary';
                                    if ($status === 'approved') $badge_class = 'success';
                                    elseif ($status === 'rejected') $badge_class = 'danger';
                                    elseif ($status === 'pending') $badge_class = 'warning';
                                ?>
                                <tr data-kriteria="<?= $sub->id_kriteria ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                      <strong><?= htmlspecialchars($sub->kode_kriteria ?? '-') ?></strong> - <?= htmlspecialchars($sub->nama_kriteria ?? '-') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($sub->nama_sub_kriteria) ?>
                                        <?php if (!empty($sub->keterangan)): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($sub->keterangan) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= number_format($sub->bobot_sub, 0) ?>%</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?= $badge_class ?>">
                                            <?= htmlspecialchars(ucfirst($status)) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/sub-kriteria/edit/' . $sub->id_sub_kriteria) ?>"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/sub-kriteria/delete/' . $sub->id_sub_kriteria) ?>"
                                               class="btn btn-sm btn-danger btn-delete"
                                               data-title="Hapus Sub Kriteria?"
                                               data-text="Data sub kriteria akan dihapus permanen!"
                                               title="Hapus">
                                                <i class="fe fe-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fe fe-inbox fe-24 mb-3"></i>
                                    <p>Belum ada data Sub Kriteria</p>
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
        <div class="card shadow-sm border-left-success">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-success"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi Sub Kriteria</h6>
                        <p class="mb-0 text-muted small">
                            Sub kriteria adalah penjabaran detail dari setiap kriteria utama.
                            <strong>Bobot Sub Kriteria</strong> merepresentasikan persentase dari <strong>total 100%</strong>.
                            Contoh: KPI (50%) + Rasio Target (40%) = 90% untuk kriteria Core Factor.
                            Kolom <strong class="text-success">Status Kriteria</strong> menunjukkan status approval kriteria induk.
                            Hanya sub kriteria dari kriteria yang <strong class="text-success">Approved</strong> yang dapat digunakan untuk penilaian.
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

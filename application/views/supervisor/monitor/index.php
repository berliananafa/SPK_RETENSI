<!-- Monitor Penilaian -->
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Monitor Penilaian</strong>
                <span class="small ml-2" style="opacity: 0.9;">Pantau semua penilaian dari tim Anda</span>
            </div>
            <div class="card-body">
                <?php if (!empty($nilai_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover datatables" id="table-nilai">
                            <thead >
                                <tr>
                                    <th>Customer Service</th>
                                    <th>Tim</th>
                                    <th>Kriteria</th>
                                    <th>Sub Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nilai_list as $nilai): ?>
                                    <?php 
                                        $scoreClass = $nilai->nilai >= 4 ? 'success' : ($nilai->nilai >= 3 ? 'info' : 'warning');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar avatar-xs" style="width: 32px; height: 32px;">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($nilai->nama_cs) ?>&background=<?= $scoreClass == 'success' ? '1cc88a' : ($scoreClass == 'info' ? '36b9cc' : 'f6c23e') ?>&color=fff&size=32" 
                                                             alt="<?= htmlspecialchars($nilai->nama_cs) ?>"
                                                             class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="col pl-0">
                                                    <strong><?= htmlspecialchars($nilai->nama_cs) ?></strong>
                                                    <div class="my-0 text-muted small"><?= htmlspecialchars($nilai->nik) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success">
                                                <i class="fe fe-users mr-1"></i><?= htmlspecialchars($nilai->nama_tim) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($nilai->nama_kriteria) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($nilai->nama_sub_kriteria) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $scoreClass ?>"><?= number_format($nilai->nilai, 2) ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-clock mr-1"></i><?= date('d M Y H:i', strtotime($nilai->created_at)) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-edit fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Penilaian</h5>
                        <p class="text-muted">Data penilaian akan ditampilkan di sini setelah proses evaluasi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-nilai').DataTable({
        order: [[5, 'desc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>

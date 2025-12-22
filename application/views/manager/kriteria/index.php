<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong class="card-title">Daftar Kriteria</strong>
        <small class="text-muted">Kelola dan setujui kriteria</small>
    </div>
    <div class="card-body">
        <?php if (!empty($kriteria)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $i => $k): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($k->nama_kriteria ?? $k->nama) ?></td>
                                <td><?= htmlspecialchars($k->bobot ?? ($k->weight ?? '-')) ?></td>
                                <td>
                                    <span class="badge badge-<?= ($k->status === 'approved') ? 'success' : 'secondary' ?>">
                                        <?= htmlspecialchars(ucfirst($k->status ?? 'draft')) ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <?php if (($k->status ?? '') !== 'approved'): ?>
                                        <button data-id="<?= $k->id_kriteria ?? $k->id ?>" class="btn btn-sm btn-primary btn-approve">Setujui</button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Disetujui</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fe fe-file-text text-muted" style="font-size: 36px;"></i>
                <p class="text-muted mt-2">Belum ada kriteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-approve')) {
        var btn = e.target;
        var id = btn.getAttribute('data-id');
        if (!id) return;
        if (!confirm('Setujui kriteria ini?')) return;

        btn.disabled = true;
        fetch('<?= base_url('junior-manager/kriteria/approve') ?>/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function(res){
            return res.json();
        }).then(function(json){
            if (json.status === 'success') {
                location.reload();
            } else {
                alert(json.message || 'Gagal');
                btn.disabled = false;
            }
        }).catch(function(){
            alert('Permintaan gagal');
            btn.disabled = false;
        });
    }
});
</script>

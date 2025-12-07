<!-- Ranking Results -->
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <strong class="card-title mb-0">Hasil Ranking Customer Service</strong>
                <span class="small ml-2" style="opacity: 0.9;">Hasil ranking CS dari tim Anda</span>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted small">Periode:</label>
                        <select class="form-control" id="filterPeriode" onchange="applyFilter()">
                            <option value="">-- Pilih Periode --</option>
                            <?php if (!empty($periodes)): ?>
                                <?php foreach ($periodes as $period): ?>
                                    <option value="<?= $period->periode ?>" <?= $period->periode == $selected_periode ? 'selected' : '' ?>>
                                        <?= $period->periode ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Produk:</label>
                        <select class="form-control" id="filterProduk" onchange="applyFilter()">
                            <option value="">-- Semua Produk --</option>
                            <?php if (!empty($produks)): ?>
                                <?php foreach ($produks as $produk): ?>
                                    <option value="<?= $produk->id_produk ?>" <?= $this->input->get('id_produk') == $produk->id_produk ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($produk->nama_produk) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Kanal:</label>
                        <select class="form-control" id="filterKanal" onchange="applyFilter()">
                            <option value="">-- Semua Kanal --</option>
                            <?php if (!empty($kanals)): ?>
                                <?php foreach ($kanals as $kanal): ?>
                                    <option value="<?= $kanal->id_kanal ?>" <?= $this->input->get('id_kanal') == $kanal->id_kanal ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kanal->nama_kanal) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Tim:</label>
                        <select class="form-control" id="filterTim" onchange="applyFilter()">
                            <option value="">-- Semua Tim --</option>
                            <?php if (!empty($teams)): ?>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= $team->id_tim ?>" <?= $this->input->get('id_tim') == $team->id_tim ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($team->nama_tim) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Rankings Table -->
                <?php if (!empty($rankings)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover datatables" id="table-ranking">
                            <thead >
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Customer Service</th>
                                    <th>Tim</th>
                                    <th>Produk</th>
                                    <th>Kanal</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankings as $ranking): ?>
                                    <tr>
                                        <td>
                                            <?php if ($ranking->peringkat <= 3): ?>
                                                <span class="badge badge-warning badge-pill" style="font-size: 1rem; padding: 8px 12px;">
                                                    <i class="fe fe-award"></i> #<?= $ranking->peringkat ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-secondary badge-pill" style="font-size: 0.9rem; padding: 6px 10px;">
                                                    #<?= $ranking->peringkat ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar avatar-sm" style="width: 36px; height: 36px;">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($ranking->nama_cs) ?>&background=<?= $ranking->peringkat <= 3 ? 'f6c23e' : '858796' ?>&color=fff&size=36" 
                                                             alt="<?= htmlspecialchars($ranking->nama_cs) ?>"
                                                             class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="col pl-0">
                                                    <strong><?= htmlspecialchars($ranking->nama_cs) ?></strong>
                                                    <div class="my-0 text-muted small"><?= htmlspecialchars($ranking->nik) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success">
                                                <i class="fe fe-users mr-1"></i><?= htmlspecialchars($ranking->nama_tim) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-package mr-1"></i><?= htmlspecialchars($ranking->nama_produk) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fe fe-message-circle mr-1"></i><?= htmlspecialchars($ranking->nama_kanal) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-success" style="font-size: 0.95rem; padding: 6px 12px;">
                                                <?= number_format($ranking->nilai_akhir, 4) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <span class="fe fe-award fe-64 mb-3 d-block" style="color: #4e73df; opacity: 0.3;"></span>
                        <h5 class="text-muted">Belum Ada Data Ranking</h5>
                        <p class="text-muted">Silakan pilih periode untuk melihat hasil ranking</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function applyFilter() {
    const periode = document.getElementById('filterPeriode').value;
    const produk = document.getElementById('filterProduk').value;
    const kanal = document.getElementById('filterKanal').value;
    const tim = document.getElementById('filterTim').value;
    
    let url = '<?= base_url('supervisor/ranking') ?>?';
    
    if (periode) url += 'periode=' + periode + '&';
    if (produk) url += 'id_produk=' + produk + '&';
    if (kanal) url += 'id_kanal=' + kanal + '&';
    if (tim) url += 'id_tim=' + tim + '&';
    
    window.location.href = url;
}

$(document).ready(function() {
    $('#table-ranking').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>

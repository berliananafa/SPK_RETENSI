<!-- Form Tambah Konversi Nilai -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Tambah Konversi Nilai</strong>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle mr-2"></i>
                        <?= validation_errors() ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle mr-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?= form_open('admin/konversi/store') ?>
                    <div class="form-group">
                        <label for="id_cs">Customer Service <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_cs" name="id_cs" required>
                            <option value="">-- Pilih Customer Service --</option>
                            <?php if (!empty($customer_service)): ?>
                                <?php foreach ($customer_service as $cs): ?>
                                    <option value="<?= $cs->id_cs ?>" <?= set_select('id_cs', $cs->id_cs) ?>>
                                        <?= htmlspecialchars($cs->nama_cs) ?> - <?= htmlspecialchars($cs->nik_cs) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Pilih CS yang akan dinilai</small>
                    </div>

                    <div class="form-group">
                        <label for="id_sub_kriteria">Sub Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_sub_kriteria" name="id_sub_kriteria" required>
                            <option value="">-- Pilih Sub Kriteria --</option>
                            <?php if (!empty($sub_kriteria)): ?>
                                <?php foreach ($sub_kriteria as $sub): ?>
                                    <option value="<?= $sub->id_sub_kriteria ?>" 
                                            data-target="<?= $sub->target ?>"
                                            <?= set_select('id_sub_kriteria', $sub->id_sub_kriteria) ?>>
                                        <?= htmlspecialchars(($sub->kode_kriteria ?? '') . ' - ' . ($sub->nama_kriteria ?? '') . ' > ' . $sub->nama_sub_kriteria) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Pilih sub kriteria untuk penilaian</small>
                    </div>

                    <div class="form-group">
                        <label for="nilai_asli">Nilai Asli <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="nilai_asli" name="nilai_asli" 
                               value="<?= set_value('nilai_asli') ?>" step="0.01" 
                               placeholder="Contoh: 85.50" required>
                        <small class="form-text text-muted">Masukkan nilai asli/aktual dari CS untuk sub kriteria yang dipilih</small>
                    </div>

                    <hr class="my-4">

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan Data
                        </button>
                        <a href="<?= base_url('admin/konversi') ?>" class="btn btn-secondary">
                            <i class="fe fe-x"></i> Batal
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <!-- Target Info Card -->
        <div class="card shadow-sm border-left-primary" id="targetCard" style="display:none;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-target fe-24 text-primary"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Target Nilai</h6>
                        <h3 class="mb-0" id="targetNilai">-</h3>
                        <small class="text-muted">Nilai target untuk sub kriteria ini</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Range Preview Card -->
        <div class="card shadow-sm border-left-info mt-3" id="rangeCard" style="display:none;">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fe fe-list text-info"></i> Range Nilai Tersedia
                </h6>
                <div id="rangeList" class="small">
                    <p class="text-muted">Pilih sub kriteria untuk melihat range</p>
                </div>
            </div>
        </div>

        <!-- Conversion Preview -->
        <div class="card shadow-sm border-left-success mt-3" id="previewCard" style="display:none;">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fe fe-check-circle text-success"></i> Hasil Konversi
                </h6>
                <div class="text-center">
                    <h1 class="display-4 text-success mb-0" id="previewNilai">-</h1>
                    <small class="text-muted" id="previewKeterangan">-</small>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card shadow-sm border-left-warning mt-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fe fe-info fe-24 text-warning"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Informasi</h6>
                        <div class="small text-muted">
                            Sistem akan <strong>otomatis</strong> mencari range yang sesuai dengan nilai asli yang Anda input dan mengkonversinya menjadi nilai standar.
                            <br><br>
                            <strong>Contoh:</strong><br>
                            Nilai Asli: 75<br>
                            Range: 70-80 = 4<br>
                            Nilai Konversi: <strong>4</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let ranges = [];

    // When sub kriteria changes
    $('#id_sub_kriteria').on('change', function() {
        const id_sub_kriteria = $(this).val();
        const target = $(this).find(':selected').data('target');

        if (id_sub_kriteria) {
            // Show target
            $('#targetNilai').text(target || '-');
            $('#targetCard').fadeIn();

            // Load ranges via AJAX
            $.ajax({
                url: '<?= base_url("admin/konversi/get_ranges/") ?>' + id_sub_kriteria,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    ranges = response;
                    displayRanges(response);
                    checkConversion();
                },
                error: function() {
                    $('#rangeList').html('<p class="text-danger">Gagal memuat data range</p>');
                    $('#rangeCard').fadeIn();
                }
            });
        } else {
            $('#targetCard, #rangeCard, #previewCard').fadeOut();
            ranges = [];
        }
    });

    // When nilai asli changes
    $('#nilai_asli').on('input', function() {
        checkConversion();
    });

    function displayRanges(data) {
        if (data.length === 0) {
            $('#rangeList').html('<div class="alert alert-warning py-2 px-3 mb-0"><small><i class="fe fe-alert-triangle"></i> Belum ada range untuk sub kriteria ini</small></div>');
        } else {
            let html = '<table class="table table-sm table-borderless mb-0">';
            data.forEach(function(range) {
                html += `
                    <tr>
                        <td class="py-1">
                            <span class="badge badge-light">${range.batas_bawah} - ${range.batas_atas}</span>
                        </td>
                        <td class="py-1 text-right">
                            <span class="badge badge-primary">${range.nilai_range}</span>
                        </td>
                    </tr>
                `;
            });
            html += '</table>';
            $('#rangeList').html(html);
        }
        $('#rangeCard').fadeIn();
    }

    function checkConversion() {
        const nilai_asli = parseFloat($('#nilai_asli').val());
        
        if (!isNaN(nilai_asli) && ranges.length > 0) {
            // Find matching range
            const matchedRange = ranges.find(function(range) {
                return nilai_asli >= parseFloat(range.batas_bawah) && 
                       nilai_asli <= parseFloat(range.batas_atas);
            });

            if (matchedRange) {
                $('#previewNilai').text(matchedRange.nilai_range);
                $('#previewKeterangan').text(
                    `Range: ${matchedRange.batas_bawah} - ${matchedRange.batas_atas}`
                );
                $('#previewCard').fadeIn();
            } else {
                $('#previewCard').fadeOut();
            }
        } else {
            $('#previewCard').fadeOut();
        }
    }

    // Trigger if has old input
    <?php if (set_value('id_sub_kriteria')): ?>
        $('#id_sub_kriteria').trigger('change');
    <?php endif; ?>
});
</script>

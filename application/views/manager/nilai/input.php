<!-- Input Penilaian -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Form Input Penilaian</strong>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fe fe-check-circle"></i> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-x-circle"></i> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('junior-manager/nilai/save') ?>" method="POST" id="formNilai">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_cs">Customer Service <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_cs" id="id_cs" required>
                                    <option value="">-- Pilih CS --</option>
                                    <?php if (!empty($cs_list)): ?>
                                        <?php foreach ($cs_list as $cs): ?>
                                            <option value="<?= $cs->id_cs ?>">
                                                <?= htmlspecialchars($cs->nik . ' - ' . $cs->nama_cs . ' (' . $cs->nama_tim . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kriteria">Kriteria <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_kriteria" id="id_kriteria" required>
                                    <option value="">-- Pilih Kriteria --</option>
                                    <?php if (!empty($kriteria)): ?>
                                        <?php foreach ($kriteria as $k): ?>
                                            <option value="<?= $k->id_kriteria ?>">
                                                <?= htmlspecialchars($k->kode_kriteria . ' - ' . $k->nama_kriteria) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_sub_kriteria">Sub Kriteria <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_sub_kriteria" id="id_sub_kriteria" required disabled>
                                    <option value="">-- Pilih Kriteria Terlebih Dahulu --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nilai">Nilai <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nilai" id="nilai" 
                                       step="0.01" min="0" max="100" required>
                                <small class="form-text text-muted">Masukkan nilai antara 0-100</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save"></i> Simpan Penilaian
                        </button>
                        <a href="<?= base_url('junior-manager/nilai/history') ?>" class="btn btn-secondary">
                            <i class="fe fe-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Load sub kriteria when kriteria is selected
    $('#id_kriteria').change(function() {
        var idKriteria = $(this).val();
        var subKriteriaSelect = $('#id_sub_kriteria');
        
        if (idKriteria) {
            $.ajax({
                url: '<?= base_url('junior-manager/nilai/get_sub_kriteria/') ?>' + idKriteria,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    subKriteriaSelect.html('<option value="">-- Pilih Sub Kriteria --</option>');
                    
                    if (data && data.length > 0) {
                        $.each(data, function(index, item) {
                            subKriteriaSelect.append(
                                '<option value="' + item.id_sub_kriteria + '">' +
                                item.kode_sub_kriteria + ' - ' + item.nama_sub_kriteria +
                                ' (Nilai: ' + item.nilai + ')' +
                                '</option>'
                            );
                        });
                        subKriteriaSelect.prop('disabled', false);
                    } else {
                        subKriteriaSelect.html('<option value="">-- Tidak Ada Sub Kriteria --</option>');
                        subKriteriaSelect.prop('disabled', true);
                    }
                },
                error: function() {
                    subKriteriaSelect.html('<option value="">-- Error Loading Data --</option>');
                    subKriteriaSelect.prop('disabled', true);
                }
            });
        } else {
            subKriteriaSelect.html('<option value="">-- Pilih Kriteria Terlebih Dahulu --</option>');
            subKriteriaSelect.prop('disabled', true);
        }
    });

    // Form validation
    $('#formNilai').submit(function(e) {
        var nilai = parseFloat($('#nilai').val());
        
        if (nilai < 0 || nilai > 100) {
            e.preventDefault();
            alert('Nilai harus antara 0 dan 100');
            return false;
        }
    });
});
</script>

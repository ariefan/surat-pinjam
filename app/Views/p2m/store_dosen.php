<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Dosen</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update' ? '/' . $row->dosenID : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama</label>
                <select id="dosen" class="form-control" name="dosen" required>
                    <option value="" disabled selected>Pilih nama Dosen</option>
                    <?php foreach ($row as $row) : ?>
                        <option value="<?= $row->dosenID ?>"><?= $row->dosenID ?> - <?= $row->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Departemen</label>
                <select id="department" class="form-control" name="department" value="<?= $row->department; ?>" required>
                    <option value="" disabled selected>Pilih departemen</option>
                    <option value="Fisika">Fisika</option>
                    <option value="Ilmu Komputer dan Elektronika">Ilmu Komputer dan Elektronika</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Matematika">Matematika</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gelar</label>
                <input placeholder="Gelar" autocomplete="off" type="text" class="form-control" name="degree" id="degree" value="<?= $row->degree; ?>" required>
            </div>

            <?php
            $is_prof = '';
            if (strpos(strtolower($row->degree), 'prof') !== false) {
                $is_prof = 'checked';
            }
            ?>
            
            <div class="form-group" style="display:none;"> <!--untuk mengisi otomatis apakah profesor atau bukan, dan tidak ditampilkan di halaman -->
                <label>Apakah Profesor</label>
                <input type="checkbox" id="is_prof" name="is_prof" value="checked" <?= $is_prof ?>>
            </div>

            <div class="form-group">
                <label>Masih Aktif</label>
                <select id="is_active" class="form-control" name="is_active" value="<?= $row->is_active; ?>" required>
                    <option value="" disabled selected>Silahkan Pilih</option>
                    <option value="Aktif" <?= $row->is_active == "Aktif" ? "selected" : "" ?>>Aktif</option>
                    <option value="Tidak Aktif" <?= $row->is_active == "Tidak Aktif" ? "selected" : "" ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group">
                <label>Mulai Aktif</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="active_start" onchange="get_nomor()" value="<?= $row->active_start; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Selesai Aktif</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="active_end" onchange="get_nomor()" value="<?= $row->active_end; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>NIDN</label>
                <input placeholder="NIDN" autocomplete="off" type="text" class="form-control" name="nidn" value="<?= $row->nidn; ?>" required>
            </div>

            <div class="form-group">
                <label>Sinta ID</label>
                <input placeholder="Sinta ID" autocomplete="off" type="text" class="form-control" name="sinta_id" value="<?= $row->sinta_id; ?>">
            </div>

            <div class="form-group">
                <label>Sinta Score</label>
                <input placeholder="Sinta Score" autocomplete="off" type="text" class="form-control" name="sinta_score_2023_01" value="<?= $row->sinta_score_2023_01; ?>">
            </div>

            <div class="form-group">
                <label>Google Scholar ID</label>
                <input placeholder="Google Scholar ID" autocomplete="off" type="text" class="form-control" name="Google_Scholar_ID" value="<?= $row->Google_Scholar_ID; ?>">
            </div>

            <div class="form-group">
                <label>Scopus ID</label>
                <input placeholder="Scopus ID" autocomplete="off" type="text" class="form-control" name="Scopus_ID" value="<?= $row->Scopus_ID; ?>">
            </div>

            <div class="form-group">
                <label>H-Index</label>
                <input placeholder="H-Index" autocomplete="off" type="text" class="form-control" name="H_index_2023_01" value="<?= $row->H_index_2023_01; ?>">
            </div>

            <div class="form-group">
                <label>WoS ID</label>
                <input placeholder="WoS ID" autocomplete="off" type="text" class="form-control" name="WoS_ID" value="<?= $row->WoS_ID; ?>">
            </div>

            <div class="form-group">
                <label>Publons ID</label>
                <input placeholder="Publons ID" autocomplete="off" type="text" class="form-control" name="publons_id" value="<?= $row->publons_id; ?>">
            </div>

            <div class="form-group">
                <label>Orcid ID</label>
                <input placeholder="Orcid ID" autocomplete="off" type="text" class="form-control" name="orcid_id" value="<?= $row->orcid_id; ?>">
            </div>

            <div class="form-group">
                <label>Laboratorium</label>
                <input placeholder="Laboratorium" autocomplete="off" type="text" class="form-control" name="laboratorium" value="<?= $row->laboratorium; ?>">
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <input placeholder="Program Studi" autocomplete="off" type="text" class="form-control" name="study_programmes" value="<?= $row->study_programmes; ?>">
            </div>

            <div class="form-group">
                <label>Expertise Group</label>
                <input placeholder="Expertise Group" autocomplete="off" type="text" class="form-control" name="expertise_group" value="<?= $row->expertise_group; ?>">
            </div>

            <div class="form-group">
                <label>Acad Staff</label>
                <input placeholder="Acad Staff" autocomplete="off" type="text" class="form-control" name="acad_staff" value="<?= $row->acad_staff; ?>">
            </div>

            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success" name="status" value="preview">Simpan</button>
            </div>

        </form>
    </div>
</div>

<!-- <button id="button-test">Test</button>
<div id="test"> -->

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<style>
    .cf::before,
    .cf::after {
        display: table;
        content: '';
    }

    .cf::after {
        clear: both;
    }

    .hiddenContent {
        display: none;
    }

    .margin-bottom {
        margin-bottom: 5px;
    }

    .margin-left {
        margin-left: 5px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
    $('#degree').on('input', function() {
        var degree = $(this).val().toLowerCase();
        if (degree.indexOf('prof') !== -1) {
        $('#is_prof').prop('checked', true);
        } else {
        $('#is_prof').prop('checked', false);
        }
    });
    });
</script>
<?= $this->endSection() ?>
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
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="name" value="<?= $row->name; ?>" required>
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
            
            <div class="form-group" style="display:none;">
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
                <label>Selesai Aktif</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="active_end" onchange="get_nomor()" value="<?= $row->active_end; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Laboratorium</label>
                <input placeholder="Laboratorium" autocomplete="off" type="text" class="form-control" name="laboratorium" value="<?= $row->laboratorium; ?>" required>
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
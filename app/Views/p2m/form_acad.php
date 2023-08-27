<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Tenaga Kependidikan</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update_acadStaf' ? '/' . $row->tendik_id : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama</label>
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="name" value="<?= $row->name; ?>" required>
            </div>

            <div class="form-group">
                <label>Departemen</label>
                <select id="department" class="form-control" name="department" value="<?= $row->department; ?>" required>
                    <option value="" disabled selected>Pilih departemen</option>
                    <option value="Fisika">Fisika</option>
                    <option value="Ilmu Komputer dan Elektronika">Ilmu Komputer dan Elektronika</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Matematika">Matematika</option>
                    <option value="Fakultas">Fakultas</option>
                </select>
            </div>

            <div class="form-group">
                <label>Laboratorium</label>
                <input placeholder="Laboratorium" autocomplete="off" type="text" class="form-control" name="laboratory" value="<?= $row->laboratory; ?>">
            </div>

            <div class="form-group">
                <label>Penelitian</label>
                <input placeholder="Penelitian" autocomplete="off" type="text" class="form-control" name="researches" value="<?= $row->researches; ?>">
            </div>

            <div class="form-group">
                <label>Penelitian 2</label>
                <input placeholder="Penelitian 2" autocomplete="off" type="text" class="form-control" name="researches_2" value="<?= $row->researches_2; ?>" required>
            </div>

            <div class="form-group">
                <label>Publikasi</label>
                <input placeholder="Publikasi" autocomplete="off" type="text" class="form-control" name="publications" value="<?= $row->publications; ?>" required>
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

</script>
<?= $this->endSection() ?>
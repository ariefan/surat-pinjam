<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Afiliasi Eksternal</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update_eksternal' ? '/' . $row->eksternal_id : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama</label>
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="name" value="<?= $row->name; ?>" required>
            </div>

            <div class="form-group">
                <label>Afiliasi</label>
                <input placeholder="Nama Afiliasi" autocomplete="off" type="text" class="form-control" name="affiliations" value="<?= $row->affiliations; ?>">
            </div>

            <div class="form-group">
                <label>Negara</label>
                <input placeholder="Negara" autocomplete="off" type="text" class="form-control" name="country" value="<?= $row->country; ?>">
            </div>

            <div class="form-group">
                <label>Publikasi</label>
                <input placeholder="Publikasi" autocomplete="off" type="text" class="form-control" name="publications_2_co_author" value="<?= $row->publications_2_co_author; ?>">
            </div>

            <div class="form-group">
                <label>Publikasi (sebagai Main Author)</label>
                <input placeholder="Publikasi" autocomplete="off" type="text" class="form-control" name="publications_3_main_author" value="<?= $row->publications_3_main_author; ?>" required>
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
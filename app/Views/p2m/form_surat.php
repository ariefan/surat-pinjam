<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Surat P2M</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update_surat' ? '/' . $row->id_surat : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama File</label>
                <input placeholder="Nama File" autocomplete="off" type="text" class="form-control" name="nama_file" value="<?= $row->nama_file; ?>" required>
            </div>

            <div class="form-group">
                <label>Jenis Surat</label>
                <input placeholder="Jenis Surat" autocomplete="off" type="text" class="form-control" name="jenis_surat" value="<?= $row->jenis_surat; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input placeholder="Tanggal Mulai" autocomplete="off" type="text" class="form-control" name="tanggal_mulai" value="<?= $row->tanggal_mulai; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input placeholder="Tanggal Selesai" autocomplete="off" type="text" class="form-control" name="tanggal_selesai" value="<?= $row->tanggal_selesai; ?>">
            </div>

            <div class="form-group">
                <label>Tempat</label>
                <input placeholder="Tempat" autocomplete="off" type="text" class="form-control" name="tempat" value="<?= $row->tempat; ?>" required>
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
<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Konferensi</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update_konferensi' ? '/' . $row->konferensi_id : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama</label>
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="Name" value="<?= $row->Name; ?>" required>
            </div>

            <div class="form-group">
                <label>Organizer</label>
                <input placeholder="Organizer" autocomplete="off" type="text" class="form-control" name="organizer" value="<?= $row->organizer; ?>">
            </div>

            <div class="form-group">
                <label>Lokasi Daerah</label>
                <input placeholder="Lokasi Daerah" autocomplete="off" type="text" class="form-control" name="location_regency" value="<?= $row->location_regency; ?>">
            </div>

            <div class="form-group">
                <label>Lokasi Negara</label>
                <input placeholder="Lokasi Negara" autocomplete="off" type="text" class="form-control" name="location_country" value="<?= $row->location_country; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="date_start" onchange="get_nomor()" value="<?= $row->date_start; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Tanggal Berakhir</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="date_end" onchange="get_nomor()" value="<?= $row->date_end; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>URL</label>
                <input placeholder="URL" autocomplete="off" type="text" class="form-control" name="url" value="<?= $row->url; ?>">
            </div>

            <div class="form-group">
                <label>Level</label>
                <input placeholder="Level Publikasi" autocomplete="off" type="text" class="form-control" name="levels" value="<?= $row->levels; ?>">
            </div>

            <div class="form-group">
                <label>Sitasi Publikasi</label>
                <input placeholder="Sitasi Publikasi" autocomplete="off" type="text" class="form-control" name="publication_citations" value="<?= $row->publication_citations; ?>">
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
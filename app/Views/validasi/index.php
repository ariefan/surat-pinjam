<?= $this->extend('layout/validation') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="row">
        <div class="card">
            <div class="card-body" id="kotakan" style="border:solid blue 0.1px;">
                <form>
                    <div class="form-group">
                        <label for="kodesurat">Masukkan kode surat</label>
                        <input type="" class="form-control" id="" value="<?= $id; ?>" name="id">
                    </div>
                    <button type=" submit" class="btn btn-success btn-block">Verifikasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script></script>
<?= $this->endSection() ?>
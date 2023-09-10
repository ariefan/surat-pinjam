<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4><?= $action == 'update' ? 'Edit' : 'Tambah'; ?> Peraturan</h4>
  </div>
  <div class="card-body">
 
    <form action="<?= site_url('peraturan/'.$action).($action == 'update' ? '/'.$row->id : '') ?>" method="post">
        <div class="form-group">
            <label>Tanggal Berlaku</label>
            <input type="date" class="form-control" name="tanggal_berlaku" value="<?= $row->tanggal_berlaku; ?>">
        </div>
        <div class="form-group">
            <label>Peraturan</label>
            <textarea class="form-control" name="peraturan"><?= $row->peraturan; ?></textarea>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" id="label-aktif" class="form-check-input" name="aktif" value="1" <?= (bool)$row->aktif ? 'checked' : ''; ?>> 
            <label class="form-check-label" for="label-aktif">Aktif</label>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<?= $this->endSection() ?>
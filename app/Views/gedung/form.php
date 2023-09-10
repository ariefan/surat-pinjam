<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>
      <?= $action == 'update' ? 'Edit' : 'Tambah'; ?> Gedung
    </h4>
  </div>
  <div class="card-body">

    <form action="<?= site_url('gedung/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="post">
      <div class="form-group">
        <label>Nama Gedung</label>
        <input type="text" class="form-control" name="nama_gedung" value="<?= $row->nama_gedung; ?>">
      </div>
      <div class="form-group">
        <label>Lokasi</label>
        <input type="text" class="form-control" name="lokasi" value="<?= $row->lokasi; ?>">
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
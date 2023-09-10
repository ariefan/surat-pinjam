<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>
      <?= $action == 'update' ? 'Edit' : 'Tambah'; ?> Peraturan
    </h4>
  </div>
  <div class="card-body">

    <form action="<?= site_url('ruang/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="post">
      <div class="form-group">
        <label for="gedung_id">Gedung</label>
        <select class="form-control" id="gedung_id" name="gedung_id">
          <?php foreach ($gedungs as $gedung): ?>
            <option value="<?= $gedung->id ?>" <?= $row->gedung->id == $gedung->id ? 'checked' : '' ?>><?= $gedung->nama_gedung ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="form-group">
        <label for="nama_ruang">Nama Ruang</label>
        <input type="text" class="form-control" id="nama_ruang" name="nama_ruang" value="<?= $row->nama_ruang ?>">
      </div>

      <div class="form-group">
        <label for="kapasitas">Kapasitas</label>
        <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="<?= $row->kapasitas ?>">
      </div>

      <div class="form-group">
        <label for="fasilitas">Fasilitas</label>
        <input type="text" class="form-control" id="fasilitas" name="fasilitas" value="<?= $row->fasilitas ?>">
      </div>

      <div class="form-group">
        <label for="dapat_disewa">Dapat Disewa</label>
        <select class="form-control" id="dapat_disewa" name="dapat_disewa">
          <option value="1">Ya</option>
          <option value="0">Tidak</option>
        </select>
      </div>

      <div class="form-group">
        <label for="harga_sewa">Harga Sewa</label>
        <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" value="<?= $row->harga_sewa ?>">
      </div>

      <div class="form-group">
        <label for="catatan">Catatan</label>
        <textarea class="form-control" id="catatan" name="catatan"><?= $row->catatan ?></textarea>
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
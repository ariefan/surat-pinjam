<?= $this->extend('layout/app') ?>


<?= $this->section('content') ?>
<h1 class="d-flex justify-content-center">Menu Undangan</h1>
<div class="d-flex column-gap-2">
  <a class="btn btn-success mb-2" href="<?= site_url('undangan/baru') ?>" role="button">Buat Baru</a>
  <div class="input-group mb-2" style="width: 300px">
    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
    <button type="button" class="btn btn-outline-primary">search</button>
  </div>
</div>
<table class="table table-success">
  <thead class="">
    <tr>
      <th>No</th>
      <th>Hal</th>
      <th>Tanggal</th>
      <th>Pukul</th>
      <th>Tempat</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <?php foreach ($undangan as $u) : ?>
      <tr>
        <th>
          <?= $u['id']; ?></th>
        </th>
        <th>
          <?= $u['hal']; ?>
        </th>
        <td>
          <?= $u['tanggal']; ?>
        </td>
        <td>
          <?= $u['pukul']; ?>
        </td>
        <td>
          <?= $u['tempat']; ?>
        </td>
        <td>
          <form method="post" action="<?= site_url('undangan/delete/' . $u['id']) ?>" onsubmit="return confirm('Apakah anda yakin?');">
            <div class="btn-group btn-group-sm" role="group">
              <a href="<?= site_url('undangan/pdf/' . $u['id']); ?>" class="btn btn-info" target="__blank">
                Lihat
              </a>
              <button type="submit" class="btn btn-danger">Cancel</button>
              <a href="<?= site_url('undangan/edit/' . $u['id']) ?>" class="btn btn-warning">Edit</a>
              <a href="" class="btn btn-secondary">
                Absensi
              </a>
              <a href="" class="btn btn-light">
                Notulensi
              </a>
            </div>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
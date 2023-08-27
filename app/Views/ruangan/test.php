<?= $this->extend('layout/app'); ?>

<?= $this->section('content'); ?>

<div class="container">
  <table class="table">
    <?= csrf_field(); ?>
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Nama</th>
        <th scope="col">Lokasi</th>
        <th scope="col">Akses</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($ruangan as $ruang) : ?>
        <tr>
          <th scope="row"><?= $ruang['id']; ?></th>
          <td><?= $ruang['nama']; ?></td>
          <td><?= $ruang['lokasi']; ?></td>
          <td><?= $ruang['akses']; ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <a href="/tambah_ruangan">
    <button class="btn btn-primary">Back</button>
  </a>
</div>

<?= $this->endSection(); ?>
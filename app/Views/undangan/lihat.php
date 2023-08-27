<?= $this->extend('layout/print') ?>

<?= $this->section('content') ?>
<h1>Menu Undangan</h1>
<table>
  <tr>
    <td>Tanggal Undangan</td>
    <td>:</td>
    <td>
      <?= $undangan['tanggal_undangan']; ?>
    </td>
  </tr>
  <tr>
    <td>Hal</td>
    <td>:</td>
    <td>
      <?= $undangan['hal']; ?>
    </td>
  </tr>
  <tr>
    <td>Lampiran</td>
    <td>:</td>
    <td>
      <?= $undangan['lampiran']; ?>
    </td>
  </tr>
  <tr>
    <td>Pengundang</td>
    <td>:</td>
    <td>
      <?= $undangan['pengundang']; ?>
    </td>
  </tr>
  <tr>
    <td>Sehubungan dengan</td>
    <td>:</td>
    <td>
      <?= $undangan['sehubungan_dengan']; ?>
    </td>
  </tr>
  <tr>
    <td>Hari</td>
    <td>:</td>
    <td>
      <?= $undangan['hari']; ?>
    </td>
  </tr>
  <tr>
    <td>Tanggal</td>
    <td>:</td>
    <td>
      <?= $undangan['tanggal']; ?>
    </td>
  </tr>
  <tr>
    <td>Pukul</td>
    <td>:</td>
    <td>
      <?= $undangan['pukul']; ?>
    </td>
  </tr>
  <tr>
    <td>Tempat</td>
    <td>:</td>
    <td>
      <?= $undangan['tempat']; ?>
    </td>
  </tr>
  <tr>
    <td>Acara</td>
    <td>:</td>
    <td>
      <?= $undangan['acara']; ?>
    </td>
  </tr>
  <tr>
    <td>Agenda</td>
    <td>:</td>
    <td>
      <?= $undangan['agenda']; ?>
    </td>
  </tr>
</table>
<?= $this->endSection() ?>
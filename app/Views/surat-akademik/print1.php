<!DOCTYPE html>
<html lang="en">
<?= $this->extend('layout/print') ?>

<?= $this->section('content') ?>
<?php
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
?>
<style>
  li {
    text-align: justify;
  }

  table {
    break-before: avoid;
    break-after: avoid;
  }

  .kop,
  .tentang {
    text-align: center;
  }

  .kop img {
    width: 60px;
  }

  .kop p {
    margin-top: 0;
  }

  .pagebreak {
    page-break-before: always;
  }

  .table {
    font-size: 12pt;
    /* border: 1px solid black; */
    /* border-collapse: collapse; */
    width: 100%;
    line-height: 1.3;
  }

  table tr {
    width: 100%;
  }

  table td {
    /* border: 1px solid black; */
    /* border-collapse: collapse; */
    padding: 5px;
  }

  .single td {
    width: 100%;
    /* border: 1px solid black; */
    /* border-collapse: collapse; */
    padding: 5px;
  }

  /* .tanggal {
        text-align: right;
    } */

  .bold {
    font-weight: bold;
  }
</style>

<!-- <div> halo wolrd </div> -->
<div class="row" style="margin-top:-30px;margin-left:-20;">
  <div class="col-2" style="float:left;">
    <img style="width:100px;" src="img/ugm.jpg">
  </div>
  <div class="col-10" style="color:#1f579c;margin-left:12px;margin-top:12px;">
    <div style="font-size:15pt; font-family:times;">UNIVERSITAS GADJAH MADA</div>
    <div style="font-size:11.8pt; font-family:times;"><b>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</b></div>
    <div style="font-size:10.5pt;font-family:Arial;">
      Sekip Utara BLS 21 Yogyakarta 55281 Telp. (0274) 513339 Fax. (0274) 513339<br>
      http://mipa.ugm.ac.id, E-mail: mipa@ugm.ac.id
    </div>
    <br>
    <br>
  </div>
</div>

<table class="table">
  <tr class="single">
    <td colspan="2">Nomor : <span>
        <?= ($row->no_surat ?? ''); ?>
      </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>
        <?= ($tanggal_pengajuan ?? ''); ?>
      </span><br></td>
  </tr>
  <tr class="single">
    <td colspan="2">Hal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Surat Permintaan Nilai ke-<span>
        <?= ($sp ?? ''); ?>
      </span><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Semester <span>
        <?= ($semester ?? ''); ?>
      </span><br></td>
  </tr>
  <tr class="single">
    <td colspan="2">Yth: <span>
        <?= ($dosen_pengampu ?? ''); ?>
      </span> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dosen Pengampu Sem. <span>
        <?= ($semester ?? ''); ?>
      </span>&nbsp;T.A. <span>
        <?= ($tahun_ajaran ?? ''); ?>
      </span><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FMIPA UGM<br><br></td>
  </tr>
  <tr class="single">
    <td colspan="2">Dengan hormat,<br></td>
  </tr>
  <tr class="single">
    <td colspan="2">Kami beritahukan kepada Bapak/Ibu Pengampu Matakuliah, bahwa menurut catatan kami hingga tanggal
      <?= ($tanggal_terlewat ?? ''); ?> masih belum memenuhi kewajiban menyerahkan Nilai <span class="bold">Matakuliah
        <span>
          <?= ($mata_kuliah ?? ''); ?>
        </span></span>
    </td>
  </tr>
  <tr class="single">
    <td colspan="2">Berdasarkan SK Dekan Nomor : 0061/J01.1.28/HK.01.30/2018 tentang Prosedur Permintaan dan
      Penyelesaian Nilai Akhir Matakuliah Pada Program Sarjana, Magister dan Doktor di Lingkungan FMIPA UGM, kami mohon
      perhatian dan bantuan Bapak/Ibu Pengampu Matakuliah tersebut diatas, mohon kiranya dapat segera menyelesaikan dan
      menyerahkan nilai tersebut ke Sie.Akademik. Apabila Bapak/Ibu Pengampu belum dapat menyelesaikan dan menyerahkan
      nilai tersebut maksimal tanggal <span class="bold">
        <?= ($tanggal_batas_akhir ?? ''); ?>
      </span>, maka kami akan mengirimkan surat peringatan ke-2.<br></td>
  </tr>
  <tr class="single">
    <td colspan="2">Atas perhatian dan bantuan Bapak/Ibu diucapkan terima kasih.<br><br></td>
  </tr>
  <tr class="double">
    <td></td>
    <td>a.n Dekan <br>Wakil Dekan Bidang Pendidikan, Pengajaran, dan Kemahasiswaan<br></td>
  </tr>
  <tr class="double">
    <td></td>
    <td>
      <div class="" style="display:inline-block;width:30%;vertical-align: top;">
        <img class="pl-4 pt-2" style="width:100px;height:100px;" src="<?= $qr; ?>" alt="">
      </div>
      <div class="" style="font-size: 10pt;display:inline-block;width:60%;vertical-align: top;">
        <?= $qr_note; ?>
      </div>
    </td>
  </tr>
  <tr class="double">
    <td></td>
    <td>Prof. Drs. Roto, M.Eng., Ph.D.<br>NIP. 196711171993031020<br><br></td>
  </tr>
  <?php $i = 1; ?>
  <tr class="single">
    <td colspan="2">Tembusan:
      <?php foreach ($row->tembusan as $tembusan): ?>
        <br>
        <?= ($i++) . '. ' . ($tembusan ?? ''); ?>
      <?php endforeach ?>
    </td>
  </tr>
</table>

<?= $this->endSection() ?>

</html>
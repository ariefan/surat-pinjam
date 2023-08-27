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
</style>
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
        <br><br>
    </div>
</div>

<div>
    <div class="text-center" style="padding-bottom:0px;">
        <span style="width:60px; display:inline-block;">Nomor</span>
        <span style="display:inline-block;">
            :
            <?= $row->status == 3 ? $row->no_surat : ''; ?>
        </span>
        <span style="display:inline-block;float:right;">
            <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?>
        </span>
    </div>
    <div class="text-center" style="padding-bottom:0px;">
        <span style="width:60px; display:inline-block;">Lamp.</span>
        <span style="display:inline-block;">
            :
            <?= $pageCount ?> Lembar
        </span>
    </div>
    <div class="text-center" style="padding-bottom:0px;">
        <span style="width:60px; display:inline-block;">Hal.</span>
        <span style="display:inline-block;">
            :
            <?= $row->tipe_surat == 'TANGGAPAN' ? 'Tanggapan' : ''; ?> Permohonan Bantuan Dosen Mengajar
        </span>
    </div>
    <div class="text-center" style="padding-bottom:12px;">
        <span style="width:60px; display:inline-block;"></span>
        <span style="display:inline-block;">
            &nbsp;&nbsp;Sem. Genap T.A. 2022/2023
        </span>
    </div>
</div>

<div>
    <div class="text-center" style="padding-bottom:0px;">
        <span style="display: inline-block;">
            Yth.
            <?= $row->jabatan_surat_tindaklanjut ?>
        </span>
        <span style="display: inline-block;"></span>
    </div>
    <div class="text-center" style="padding-bottom:0px;">
        <span style="width:30px; display:inline-block;"></span>
        <span style="display:inline-block;">
            <?= $row->fakultas_surat_tindaklanjut ?>
        </span>
    </div>
    <div class="text-center" style="padding-bottom:32px;">
        <span style="width:30px; display:inline-block;"></span>
        <span style="display:inline-block;">
            Universitas Gadjah Mada
        </span>
    </div>

</div>

<div>
    <span>Dengan hormat,</span>
    <div style="text-align: justify;">
        <?php if ($row->tipe_surat == 'TANGGAPAN'): ?>
            Menindaklanjuti surat
            <?= $row->jabatan_surat_tindaklanjut ?>
            <?= $row->fakultas_surat_tindaklanjut ?> nomor
            <?= $row->no_surat_tindaklanjut ?> tanggal
            <?= $formatter->format(strtotime($row->tanggal_surat_tindaklanjut)); ?> perihal seperti
            tersebut pada pokok surat, dengan ini kami sampaikan usulan dosen dari
            <?= $row->departemen_pembuat ?> FMIPA yang akan mengampu matakuliah pada semester genap T.A. 2022/2023
            di
            <?= $row->fakultas_surat_tindaklanjut ?> (daftar terlampir).
        <?php else: ?>
            Menindaklanjuti surat Ketua
            <?= $row->departemen_pembuat ?> nomor
            <?= $row->no_surat_tindaklanjut ?> tanggal
            <?= $formatter->format(strtotime($row->tanggal_surat_tindaklanjut)); ?> perihal seperti tersebut pada pokok
            surat, dengan ini kami mohon bantuan
            tenaga pengajar dari fakultas Saudara untuk dapat ditugaskan mengajar di
            <?= $row->departemen_pembuat ?> FMIPA.
            <br />
            <br />
            (Daftar Terlampir)
            <br />
            <br />
            Untuk itu, kami mohon Saudara dapat mengirimkan surat kesanggupan mengajar kepada kami
            dengan mencantumkan data terbaru Pangkat/Golongan Jabatan, nomor HP serta alamat email
            sebelum tanggal
            <?= $formatter->format(strtotime($row->tanggal_deadline)); ?>.
        <?php endif; ?>
    </div>
</div>
<br />
<div>
    <div class="">
        <div style="text-align:justify; padding-right:10px;">

        </div>
        <?php if (!empty($row->paragraf_baru)): ?>
            <div style="width:100%; text-align:justify; padding-right:10px; ">
                <?= $row->paragraf_baru; ?>
            </div>
        <?php endif; ?>
        <div style="width:100%; text-align:justify; padding-right:10px;">Demikian atas perhatian dan kerjasama
            Saudara,
            diucapkan terima kasih.</div>
    </div>
</div>
<br />

<div class="display:inline-block;float:right;">
    <div class="" style="display:inline-block;width:50%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <div>
            <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : ($penandatangan->label ?? '') . ''; ?>
        </div>
        <div class="" style="display:inline-block;width:25%;vertical-align: top;">
            <img class="pl-4 pt-2" style="width:80px;height:80px;" src="<?= $qr; ?>" alt="">
        </div>
        <div class="" style="font-size: 9pt;display:inline-block;width:67%;vertical-align: top;">
            <?= $qr_note; ?>
        </div>
        <div>
            <?= ($penandatangan->nama ?? ''); ?><br><br>
        </div>
    </div>
</div>

<?php if (count(json_decode($row->tembusan)) > 0): ?>
    <div class="row" style="position:absolute; top:240mm;"><br><br><br><br>
        <div class="col-sm-12">
            <p style="margin:0;">
                Tembusan:
            <ol style="margin:0;padding-left:1.4em;">
                <?php foreach (json_decode($row->tembusan) as $tembusan): ?>
                    <li>
                        <?= $tembusan; ?>
                    </li>
                <?php endforeach ?>
            </ol>
            </p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

</html>
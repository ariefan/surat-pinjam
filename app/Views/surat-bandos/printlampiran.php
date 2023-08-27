<!DOCTYPE html>
<html lang="en">
<?= $this->extend('layout/print') ?>

<?= $this->section('content') ?>
<?php
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
?>
<style>
    @page {
        margin: 0;
    }

    html {
        margin: 0px;
    }

    body {
        width: 100%;
        margin: 0;
        border: thin solid #666666;
    }

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

<div class="row">
    <div class="col-sm-12">
        <p class="text-justify" style="width:100%; text-align:justify; padding-right:10px;">
            Lampiran surat no.
            <?= $row->status == 3 ? $row->no_surat : ''; ?><br>
        </p>
    </div>
    <div style="text-align:center; width: 150%;">
        <strong>
            <?= $row->tipe_surat == "TANGGAPAN" ? "DAFTAR USULAN DOSEN PENGAMPU MATAKULIAH" : "DAFTAR PERMOHONAN MATAKULIAH YANG MEMBUTUHKAN DOSEN"; ?>

        </strong>
        <br />
        <strong>
            SEMESTER GENAP T.A. 2022/2023
        </strong>
    </div>
    <br />
    <br />

    <div style="width: 150%;">
        <?= $row->tabel; ?>
    </div>
    <br>
</div>

<!-- <div style="width:150%;">
    <div class="" style="display:inline-block;width:65%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:34%;vertical-align: top;">
        <p>
            <br>
            <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : ($penandatangan->label ?? '') . ''; ?>
        <div class="" style="display:inline-block;width:31%;vertical-align: top;">
            <img class="pl-4 pt-2" style="width:100px;height:100px;" src="<?= $qr; ?>" alt="">
        </div>
        <div class="" style="font-size: 10pt;display:inline-block;width:67%;vertical-align: top;">
            <?= $qr_note; ?>
        </div>
        <br>
        <?= ($penandatangan->nama ?? ''); ?><br><br>
        </p>
    </div>
</div> -->
<div style="width:150%">
    <div class="" style="display:inline-block;width:65%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:34%;vertical-align: top;">
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

<?= $this->endSection() ?>

</html>
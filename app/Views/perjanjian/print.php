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
<div class="row" style="margin-top:-30px;margin-left:-20;margin-right:-120px;">
    <div class="col-2" style="float:left;">
        <img style="width:100px;" src="img/ugm.jpg">
    </div>
    <div class="col-10" style="color:#1f579c;margin-left:12px;margin-top:12px;">
        <div style="font-size:15pt; font-family:times;">UNIVERSITAS GADJAH MADA</div>
        <div style="font-size:14pt; font-family:times;"><b>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</b></div>
        <div style="font-size:12pt;font-family:Arial;">
            Sekip Utara BLS 21 Yogyakarta 55281 Telp. (0274) 513339 Fax. (0274) 513339<br>
            http://mipa.ugm.ac.id, E-mail: mipa@ugm.ac.id
        </div>
        <br>
    </div>
</div>

<div>
    <div style="margin-left:-15px;" class="col-sm-12">
        <div class="text-center" style="padding-bottom:0px;">Nomor :
            <?= $row->status == 3 ? $row->no_surat : ''; ?><span style="float:right;">
                <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?>
            </span>
        </div>
        <div class="text-center" style="padding-bottom:12px;">Hal :
            <?= $row->nama_surat; ?>
        </div>
        <?= $row->isi_surat; ?><br>
    </div>
</div>
<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
            <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : 'a.n. '; ?>Dekan,<br>
            <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : ($penandatangan->label ?? '') . ''; ?>
        <div class="" style="display:inline-block;width:24%;vertical-align: top;">
            <img class="pl-4 pt-2" style="width:73px;height:73px;" src="<?= $qr; ?>" alt="">
        </div>
        <div class="" style="font-size: 9pt;display:inline-block;width:74%;vertical-align: top;">
            <?= $qr_note; ?>
        </div>
        <br>
        <?= ($penandatangan->nama ?? ''); ?><br>
        NIP
        <?= ($penandatangan->nip ?? ''); ?><br>
        </p>
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


<div class="row" style="position:absolute; top:220mm;"><br><br><br><br><br><br><br>
    <div class="col-sm-12" style="font-family:calibri;"></div>
    <!-- <hr style="margin:16px-35px;width:740px;border:solid black 1px;"> -->
</div>

<?php if (strlen($row->isi_lampiran) > 0): ?>
    <div class="pagebreak"> </div>

    <div class="row">
        <div class="col-sm-12">
            <p class="text-justify" style="width:100%; text-align:justify; padding-right:10px;">
                Lampiran Surat Nomor
                <?= $row->status == 3 ? $row->no_surat : ''; ?><br>
                Tanggal
                <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?><br>
            </p>
            <?= $row->isi_lampiran; ?><br>
        </div>
    </div>

    <div class="">
        <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
        <div class="" style="display:inline-block;width:49%;vertical-align: top;">
            <p>
                <br>
                <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : 'a.n. '; ?>Dekan,<br>
                <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : ($penandatangan->label ?? '') . ''; ?>
            <div class="" style="display:inline-block;width:24%;vertical-align: top;">
                <img class="pl-4 pt-2" style="width:73px;height:73px;" src="<?= $qr; ?>" alt="">
            </div>
            <div class="" style="font-size: 9pt;display:inline-block;width:74%;vertical-align: top;">
                <?= $qr_note; ?>
            </div>
            <br>
            <?= ($penandatangan->nama ?? ''); ?><br>
            NIP
            <?= ($penandatangan->nip ?? ''); ?><br>
            </p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

</html>
<!DOCTYPE html>
<html lang="en">
<?= $this->extend('layout/print') ?>

<?= $this->section('content') ?>
<style>
    body {
        font-size: 11pt !important;
        font-family: "Bookman Old Style", Georgia, serif !important;
    }

    p,
    ul,
    ol {
        margin-top: 0 !important;
    }

    ul,
    ol {
        padding-left: 18px !important;
    }

    li {
        text-align: justify;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
?>
<div class="kop">
    <img src="img/ugm.jpg">
    <p>
        KEPUTUSAN DEKAN FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM<br />
        UNIVERSITAS GADJAH MADA</br><br>
        NOMOR
        <?= $row->status == 3 ? $row->no_surat : ''; ?>
    </p>
</div>

<div class="tentang">
    <p>TENTANG</p>
    <p>
        <?= $row->nama_surat; ?>
    </p>
    <p>
        DEKAN FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM<br />
        UNIVERSITAS GADJAH MADA</br>
    </p>
</div>

<div>
    <div style="width:18%; float:left;">Menimbang</div>
    <div style="float:left;">:</div>
    <div style="width:80%; float:right;">
        <?= $row->menimbang; ?>
    </div>
</div>
<span style="clear:both;"></span>

<div>
    <div style="width:18%; float:left;">Mengingat</div>
    <div style="float:left;">:</div>
    <div style="width:80%; float:right;">
        <?= $row->mengingat; ?>
    </div>
</div>
<span style="clear:both;"></span>

<div>
    <div style="width:18%; float:left;">Memperhatikan</div>
    <div style="float:left;">:</div>
    <div style="width:80%; float:right;">
        <?= $row->memperhatikan; ?>
    </div>
</div>
<span style="clear:both;"></span>



<div class="row">
    <div class="col-12" style="text-align:center;">MEMUTUSKAN :</div>
</div><br />

<?= $row->memutuskan; ?><br>

<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
            Ditetapkan di Yogyakarta<br />
            Pada tanggal
            <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?><br />
            <?= $penandatangan->label == 'Dekan' ? '' : 'a.n. '; ?>Dekan,<br>
            <?= $penandatangan->label == 'Dekan' ? '' : $penandatangan->label . '<br>'; ?>
            <img class="pl-4 pt-2" style="width:100px;height:100px;" src="<?= $qr; ?>" alt=""><br>
            <?= $penandatangan->nama; ?>
        </p>
    </div>
</div>

<div class="row avoid-break">
    <div class="col-12">
        <p style="margin:0;">
            Tembusan:
        <ol style="margin:0;padding-left:1.4em;">
            <?php foreach ($row->tembusan as $tembusan): ?>
                <li>
                    <?= $tembusan; ?>
                </li>
            <?php endforeach ?>
        </ol>
        di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Gadjah Mada
        </p>
    </div>
</div>

<?php if (!empty($row->lampiran)): ?>
    <div class="row" style="page-break-before: always;">
        <div class="col-12">
            LAMPIRAN KEPUTUSAN DEKAN FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM UNIVERSITAS GADJAH MADA
            <table>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">NOMOR</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top; text-align: justify;">
                            <?= $row->no_surat; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">TANGGAL</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top; text-align: justify;">
                            <?= strtoupper($formatter->format(strtotime($row->tanggal_pengajuan))); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">TENTANG</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top; text-align: justify;">
                            <div>
                                <?= strtoupper($row->nama_surat); ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table><br />
        </div><br />
        <div class="col-12">
            <?= $row->lampiran; ?>
        </div>
        <div class="col-12">
            <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
            <div class="" style="display:inline-block;width:49%;vertical-align: top;">
                <p>
                    <br>
                    <?= $penandatangan->label == 'Dekan' ? '' : 'a.n. '; ?>Dekan,<br>
                    <?= $penandatangan->label == 'Dekan' ? '' : $penandatangan->label . '<br>'; ?>
                    <img class="pl-4 pt-2" style="width:100px;height:100px;" src="<?= $qr; ?>" alt=""><br>
                    <?= $penandatangan->nama; ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

</html>
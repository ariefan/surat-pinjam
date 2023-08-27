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
    <div style="text-align:center;" class="col-sm-12">
        <div style="font-size:13pt;"><b><u>SURAT KETERANGAN LULUS</u></b></div>
        <div class="text-center">NOMOR:
            <?= $row->no_surat ?>
        </div><br>
    </div>
    <div style="margin-left:-15px;">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr>
                <td>Nama </td>
                <td style="width:50px;"></td>
                <td>:
                    <?= ($penandatangan->nama ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>NIP </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->nip ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Pangkat / gol. </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->pangkat ?? ''); ?> (
                    <?= ($penandatangan->golongan ?? ''); ?>)
                </td>
            </tr>
            <tr>
                <td>Jabatan </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->jabatan ?? ''); ?>
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div style="margin-left:-15px;">
        <p>dengan ini menerangkan bahwa:</p>
        <table>
            <tr>
                <td>Nama </td>
                <td style="width:50px;"></td>
                <td>:
                    <?= ($row->nama_mhs ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>NIM </td>
                <td></td>
                <td>:
                    <?= ($row->nim ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Program studi </td>
                <td></td>
                <td>:
                    <?= ($row->prodi_pengaju ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Departemen </td>
                <td></td>
                <td>:
                    <?= ($row->departemen_pengaju ?? ''); ?>
                </td>
            </tr>
        </table>
        <br>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <p style="width:100%; text-align:justify; padding-right:10px;">
            Telah dinyatakan <b>lulus</b> Program Strata 1 FMIPA UGM berdasarkan hasil rapat Yudisium tanggal
            <?= $row->tanggal_yudisium; ?>, sehingga yang bersangkutan berhak menyandang gelar
            <?= $row->sebutan_gelar ?> (
            <?= $row->gelar ?>), dengan IPK
            <?= ($row->ipk_pengaju ?? '') ?>, SKS
            <?= ($row->sks_pengaju ?? '') ?>, Predikat
            <?= ($row->predikat_pengaju ?? '') ?>.
        </p>
        <p>
            Ijazah akan diberikan kepada mahasiswa tersebut pada saat Wisuda Universitas Gadjah Mada Periode
            <?= $row->periode_wisuda ?> bulan
            <?= $row->bulan_wisuda ?>.
        </p>
        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
</div>
<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
            Yogyakarta,
            <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?><br>
            <?= ($penandatangan->label ?? '') == 'Dekan' ? '' : 'a.n. '; ?>Dekan,<br>
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
</div>

<?= $this->endSection() ?>

</html>
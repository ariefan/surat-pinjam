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
    <div style="text-align:center;" class="col-sm-12">
        <div style="font-size:13pt;"><b><u>SURAT KETERANGAN AKTIF</u></b></div>
        <div class="text-center">Nomor:
            <?= $row->status == 3 ? $row->no_surat : ''; ?>
        </div>
        Tanggal:
        <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?><br><br>
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
                    <?= ($penandatangan->pangkat ?? ''); ?>
                    <?= ($penandatangan->golongan ?? ''); ?> (Guru Besar)
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
                    <?= ($row->prog_studi ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Departemen </td>
                <td></td>
                <td>:
                    <?= ($row->departemen ?? ''); ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <p style="width:100%; text-align:justify; padding-right:10px;">
            dinyatakan aktif terdaftar sebagai mahasiswa pada Semester
            <?= ($row->semester ?? ''); ?> Tahun Akademik
            <?= ($row->tahun_ajaran ?? ''); ?> di Fakultas
            Matematika dan Ilmu Pengetahuan Alam Universitas Gadjah Mada.
        </p>
        <p style="width:100%; text-align:justify; padding-right:10px;">
            <?= $row->keperluan; ?>
        </p>
        <p style="width:100%; text-align:justify; padding-right:10px;">Demikian agar dipergunakan sebagaimana mestinya.
        </p>
    </div>
</div>
<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
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

<?php if (count(json_decode($row->tembusan)) > 0): ?>
    <div class="row" style="position:absolute; top:210mm;"><br><br><br><br><br><br><br><br>
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
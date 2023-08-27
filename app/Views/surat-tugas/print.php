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
    <div style="text-align:center;" class="col-sm-12">
        <div style="font-size:13pt;"><b>SURAT TUGAS</b></div>
        <div class="text-center">NOMOR
            <?= $row->status == 3 ? $row->no_surat : ''; ?><br>Tanggal
            <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?>
        </div><br>
    </div>
    <div style="margin-left:-15px;">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr>
                <td>Nama </td>
                <td style="width:50px;"></td>
                <td>:
                    <?= ($penandatangan->nama ?? ''); ?>,
                </td>
            </tr>
            <tr>
                <td>NIP </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->nip ?? ''); ?>,
                </td>
            </tr>
            <tr>
                <td>Pangkat/gol.</td>
                <td></td>
                <td>:
                    <?= ($penandatangan->pangkat ?? ''); ?> (
                    <?= ($penandatangan->golongan ?? ''); ?>),
                </td>
            </tr>
            <tr>
                <td>Jabatan </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->jabatan ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>&nbsp; Fakultas Matematika dan Ilmu Pengetahuan Alam</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>&nbsp; Universitas Gadjah Mada</td>
            </tr>
        </table>
        <br>
    </div>
</div>
<?php
$tanggal_kegiatan = '';
if (count($row->tanggal_kegiatan) > 0) {
    if (count($row->tanggal_kegiatan) > 1) {
        $last_tanggal = array_pop($row->tanggal_kegiatan);
        foreach ($row->tanggal_kegiatan as $tgl) {
            $tanggal_kegiatan .= $formatter->format(strtotime($tgl)) . ', ';
        }
        $tanggal_kegiatan .= ' dan ' . $formatter->format(strtotime($last_tanggal));
    } else {
        $tanggal_kegiatan = $formatter->format(strtotime($row->tanggal_kegiatan_mulai));
    }
} else {
    $tanggal_kegiatan = $formatter->format(strtotime($row->tanggal_kegiatan_mulai));
    $tanggal_kegiatan .= $row->tanggal_kegiatan_mulai == $row->tanggal_kegiatan_selesai ? '' : ' s.d. ' . $formatter->format(strtotime($row->tanggal_kegiatan_selesai));
}
if ($row->tanggal_kegiatan_mulai == '0000-00-00') {
    $tanggal_kegiatan = '';
} else {
    $tanggal_kegiatan = 'pada tanggal ' . $tanggal_kegiatan;
}
?>
<div class="row">
    <div class="col-sm-12">
        <p style="width:100%; text-align:justify; padding-right:10px;">
            dengan ini memberikan tugas kepada yang namanya tersebut dalam lampiran surat untuk mengikuti/melakukan
            kegiatan
            <?= $row->nama_surat; ?>
            <?= empty($row->lokasi_kegiatan) ? '' : ' yang dilaksanakan ' . (str_contains(strtolower($row->lokasi_kegiatan), 'daring') ? 'secara' : 'di'); ?>
            <?= empty($row->lokasi_kegiatan) ? '' : ' ' . $row->lokasi_kegiatan; ?>
            <?= empty($tanggal_kegiatan) ? '' : ' ' . $tanggal_kegiatan; ?>.<br>
        </p>
        <?php if (!empty($row->paragraf_baru)): ?>
            <p style="width:100%; text-align:justify; padding-right:10px;">
                <?= $row->paragraf_baru; ?>
            </p>
        <?php endif; ?>
        <p style="width:100%; text-align:justify; padding-right:10px;">Demikian surat tugas ini dibuat, untuk dapat
            dilaksanakan dengan sebaik-baiknya.</p>
    </div>
</div>
<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
            <br>
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

<?php
$tembusans = [];
foreach (json_decode($row->tembusan) as $t) {
    if (!empty($t))
        $tembusans[] = $t;
}
if (count($tembusans) > 0): ?>
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

<div class="row" style="position:absolute; top:210mm;"><br><br><br><br><br><br><br>
    <div class="col-sm-12" style="font-family:calibri;"></div>
    <!-- <hr style="margin:16px-35px;width:740px;border:solid black 1px;"> -->
</div>

<div class="pagebreak"> </div>

<div class="row">
    <div class="col-sm-12">
        <p class="text-justify" style="width:100%; text-align:justify; padding-right:10px;">
            Lampiran Surat Nomor
            <?= $row->status == 3 ? $row->no_surat : ''; ?><br>
            Tanggal
            <?= $formatter->format(strtotime($row->tanggal_pengajuan)); ?><br>
            <!-- Daftar nama dosen/tendik/mahasiswa kegiatan <?= $row->nama_surat; ?> yang dilaksanakan pada tanggal <?= date('d F Y', strtotime($row->tanggal_kegiatan_mulai)); ?><?= $row->tanggal_kegiatan_mulai == $row->tanggal_kegiatan_selesai ? '' : ' s.d. ' . date('d F Y', strtotime($row->tanggal_kegiatan_selesai)); ?>. -->
        </p>
        <?= $row->tabel; ?><br>
    </div>
</div>

<div class="">
    <div class="" style="display:inline-block;width:49%;vertical-align: top;"></div>
    <div class="" style="display:inline-block;width:49%;vertical-align: top;">
        <p>
            <br>
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
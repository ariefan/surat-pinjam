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
        <div class="text-center" style="padding-bottom:12px;">
            <span style="width:60px; display:inline-block;">Hal</span>
            <span style="display:inline-block;">
                :
                <?= $row->nama_surat; ?>
            </span>
        </div>
        <div class="continer" style="width: 50%;">
            <p style="line-height: 1;">Yth.
                <?= $row->kepada_surat; ?><br>
                <?= $row->lokasi_kegiatan; ?><br>
                <?= $row->alamat_kegiatan; ?><br>
                <?= empty($row->alamat_tambahan_satu) ? '' : $row->alamat_tambahan_satu . '<br>'; ?>
                <?= empty($row->alamat_tambahan_dua) ? '' : $row->alamat_tambahan_dua . '<br>'; ?><br>
            </p>
        </div>
        <p style="line-height: 1.2;">Dengan hormat, kami sampaikan bahwa mahasiswa Fakultas Matematika dan Ilmu
            Pengetahuan Alam Universitas Gadjah Mada tersebut di bawah ini:</p>

        <?php if (count($row->peserta) == 1): ?>
            <?php foreach ($row->peserta as $peserta): ?>


                <table style="border-collapse: collapse; width: 55.3205%; height: 10px; border-width: 0px; border-style: none;"
                    border="1">
                    <colgroup>
                        <col style="width: 29.4726%;">
                        <col style="width: 1.15607%;">
                        <col style="width: 69.3479%;">
                    </colgroup>
                    <tbody>
                        <tr style="height: 14px;">
                            <td style="line-height: 1; border-width: 0px; height: 14px;">Nama</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">:</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">
                                <?= $peserta->nama ?>
                            </td>
                        </tr>
                        <tr style="height: 14px;">
                            <td style="line-height: 1; border-width: 0px; height: 14px;">NIM</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">:</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">
                                <?= $peserta->nim ?>
                            </td>
                        </tr>
                        <tr style="height: 14px;">
                            <td style="line-height: 1; border-width: 0px; height: 14px;">Program Studi</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">:</td>
                            <td style="line-height: 1; border-width: 0px; height: 14px;">
                                <?= $peserta->prodi ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach ?>
        <?php else: ?>

            <table style="width:100%; border: 1px solid; border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid;">Nama Lengkap</th>
                    <th style="border: 1px solid;">NIM</th>
                    <th style="border: 1px solid;">Program Studi</th>
                </tr>
                <?php foreach ($row->peserta as $peserta): ?>
                    <tr>
                        <td style="border: 1px solid;">
                            <?= $peserta->nama ?>
                            << /td>
                        <td style="border: 1px solid;">
                            <?= $peserta->nim ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?= $peserta->prodi ?>
                        </td>
                    </tr>>
                <?php endforeach ?>
            </table>

        <?php endif; ?>
        <p style="line-height: 1;">bermaksud melaksanakan
            <?= $row->jenis_kegiatan ?> di
            <?= $row->lokasi_kegiatan ?> dari
            <?= $formatter->format(strtotime($row->tanggal_kegiatan_mulai)); ?> s.d
            <?= $formatter->format(strtotime($row->tanggal_kegiatan_selesai)); ?>. Maka dengan ini kami mohon bantuan
            Saudara berkenan memberikan izin bagi mahasiswa tersebut.
        </p>
        <p style="line-height: 1;">Atas perhatian dan kerja sama Saudara, kami mengucapkan terima kasih.</p>
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
        NIP : <?= ($penandatangan->nip ?? ''); ?>
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


<!-- <div class="row" style="position:absolute; top:220mm;"><br><br><br><br><br><br><br>
    <div class="col-sm-12" style="font-family:calibri;"></div>
    <hr style="margin:16px-35px;width:740px;border:solid black 1px;">
</div> -->

<?= $this->endSection() ?>

</html>
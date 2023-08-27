<!DOCTYPE html>
<html lang="en">
<?= $this->extend('layout/print') ?>

<?= $this->section('content') ?>
<?php
$formatter = new IntlDateFormatter('en_US', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
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
        <div style="font-size:11.8pt; font-family:times;"><b>FACULTY OF MATHEMATICS AND NATURAL SCIENCES</b></div>
        <div style="font-size:10.5pt;font-family:Arial;">
            Sekip Utara BLS 21 Yogyakarta 55281 Phone: (0274) 513339 Fax: (0274) 513339
            http://mipa.ugm.ac.id, E-mail: mipa@ugm.ac.id
        </div>
        <br><br>
    </div>
</div>

<div>
    <div style="text-align:center;" class="col-sm-12">
        <div style="font-size:13pt;"><b><u>Letter of Graduation</u></b></div>
        <div class="text-center">NUMBER:
            <?= $row->no_surat ?>
        </div><br>
    </div>
    <div style="margin-left:-15px;">
        <p>The undersigned:</p>
        <table>
            <tr>
                <td>Name </td>
                <td style="width:50px;"></td>
                <td>:
                    <?= ($penandatangan->nama ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>ID </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->nip ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Rank </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->pangkat ?? ''); ?> (
                    <?= ($penandatangan->golongan ?? ''); ?>)
                </td>
            </tr>
            <tr>
                <td>Position </td>
                <td></td>
                <td>:
                    <?= ($penandatangan->jabatan ?? ''); ?>
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div style="margin-left:-15px;">
        <p>hereby states that: </p>
        <table>
            <tr>
                <td>Name </td>
                <td style="width:50px;"></td>
                <td>:
                    <?= ($row->nama_mhs ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Student ID </td>
                <td></td>
                <td>:
                    <?= ($row->nim ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Study Program </td>
                <td></td>
                <td>:
                    <?= ($row->prodi_pengaju ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Department </td>
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
            Has been declared as having <b>passed</b> the Bachelors Degree at Faculty of Mathematics and Natural
            Sciences UGM based on the Judisium meeting on
            <?= $row->tanggal_yudisium; ?>, and is entitled to hold the Bachelor of Science Degree (B.Sc), with GPA of
            <?= ($row->ipk_pengaju ?? '') ?>,
            <?= ($row->sks_pengaju ?? '') ?> credits, with Predicate
            <?php if ($row->predikat_pengaju == "-") { ?>
            <?php } else if ($row->predikat_pengaju == "Memuaskan") { ?>
                    "Satisfactory".
                <?php } else if ($row->predikat_pengaju == "Sangat Memuaskan") { ?>
                        "Highly Satisfactory".
                    <?php } else if ($row->predikat_pengaju == "Pujian") { ?>
                            "Cum Laude".
                        <?php } ?>
        </p>
        <p>
            The diploma will be given to the student during the Commencement ceremony of Universitas Gadjah Mada,
            at the Period
            <?= substr($row->periode_wisuda, 0, 4) ?> of
            <?= substr($row->periode_wisuda, -9) ?> Academic Year
            in
            <?php $bulan_tahun = explode(" ", $row->bulan_wisuda); ?>
            <?php if ($bulan_tahun[0] == "November") { ?>
                November
            <?php } else if ($bulan_tahun[0] == "Februari") { ?>
                    February
                <?php } else if ($bulan_tahun[0] == "Mei") { ?>
                        May
                    <?php } else if ($bulan_tahun[0] == "Agustus") { ?>
                            August
                        <?php } ?>
            <?= $bulan_tahun[1] ?>.
        </p>
        <p>Thus, this certificate is made for the purposes it may serve.</p>
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
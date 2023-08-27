<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="font-weight-bold">Form Yudisium
        </h3>
    </div>

    <div class="card-body">
        <div class="form-group">
            <label>Nama&nbsp;:</label><span class="ml-2">
                <?= $row->mahasiswa->nama; ?>
            </span>
        </div>

        <div class="form-group">
            <label>NIM&emsp;:</label><span class="ml-2">
                <?= $row->mahasiswa->nim; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Prodi&nbsp;&nbsp;:</label><span class="ml-2">
                <?= $row->mahasiswa->prodi; ?>
            </span>
        </div>

        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <form id="approve_bulk"
                        action="<?= site_url('yudisium/' . (($row->pengajuan_penghapusan_matkul_status == "1") ? 'cancelapprovehapusmatkul/' : 'approvehapusmatkul/')) . $row->id ?>"
                        method="POST" enctype="multipart/form-data"></form>

                    <input type="hidden" name="jsonmatkul" value='<?= json_encode($row->matakuliah); ?>'
                        form="approve_bulk" />

                    <table class="table table-bordered table-valign-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    Kode Matkul
                                </th>
                                <th>
                                    Nama Matkul
                                </th>
                                <th>
                                    SKS
                                </th>
                                <th>
                                    Nilai
                                </th>
                                <th>
                                    Keterangan
                                </th>
                                <th>Aksi Setujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($row->matakuliah as $matkul) { ?>
                                <tr>
                                    <th scope="row">
                                        <?= empty($no) ? $no = 1 : ++$no; ?>
                                    </th>
                                    <td>
                                        <?= $matkul->kode_matkul; ?>
                                    </td>
                                    <td>
                                        <?= $matkul->nama_matkul; ?>
                                    </td>
                                    <td>
                                        <?= $matkul->sks; ?>
                                    </td>
                                    <td>
                                        <?= $matkul->nilai; ?>
                                    </td>
                                    <td class="<?= ($matkul->keterangan == "wajib") ? "text-danger" : ""; ?>">
                                        <?= $matkul->keterangan; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <input form="approve_bulk" type="checkbox" name="matkul[]" class="check"
                                                <?= ($matkul->status == 1) ? "checked" : "" ?>
                                                value="<?php echo $matkul->kode_matkul; ?>,<?php echo $matkul->nilai; ?>,<?php echo $matkul->sks; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>

            <h3 for="">Simulasi</h3>
            <br>
            <!-- <label for="">Sebelum Penghapusan</label><br>
                <label for="initipk">IPK awal&nbsp;&nbsp;:&nbsp;</label>
                <input type="number" id="initipk" value='0'>
                <label for="initsks">SKS awal&nbsp;&nbsp;:&nbsp;</label>
                <input type="number" id="initsks" value='0'>
                <label for="initd">Jumlah D awal&nbsp;&nbsp;:&nbsp;</label>
                <input type="number" id="initd" value='0'>
                <label for="initdplus">Jumlah D+ awal&nbsp;&nbsp;:&nbsp;</label>
                <input type="number" id="initdplus" value='0'> -->
            <?php if ($row->listmatkul == ""): ?>
                <b>Akademik Belum Memasukkan Nilai Sebelum Pembatalan</b>
            <?php else: ?>
                <form action="<?= site_url('yudisium/simpanmatkul/') . $row->id ?>" method="POST"
                    enctype="multipart/form-data">
                    <!-- <label for="">Matakuliah yang ingin dihapus</label> -->
                    <div id="">
                        <div class="form-row mb-2 matkul-item">
                            <div class="col-md-2">
                                <label>IPK</label>
                                <input type="number" readonly id="initipk" step="any" class="form-control" placeholder="IPK"
                                    name="ipk" value="<?= $row->listmatkul ? $row->listmatkul->ipk : ""; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>SKS</label>
                                <input type="number" readonly id="initsks" class="form-control" placeholder="Jumlah SKS"
                                    name="sks" value="<?= $row->listmatkul ? $row->listmatkul->sks : ""; ?>">
                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <label>Nilai D+</label>
                                <input type=" number" readonly id="initdplus" class="form-control"
                                    placeholder="Jumlah Nilai D+" name="D+"
                                    value="<?= $row->listmatkul ? $row->listmatkul->{"D+"} : ""; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Nilai D</label>
                                <input type=" number" readonly id="initd" class="form-control" placeholder="Jumlah Nilai D"
                                    name="D" value="<?= $row->listmatkul ? $row->listmatkul->{"D"} : ""; ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Nilai E</label>
                                <input type=" number" readonly id="inite" class="form-control" placeholder="Jumlah Nilai E"
                                    name="E" value="<?= $row->listmatkul ? $row->listmatkul->{"E"} : ""; ?>">
                            </div>
                        </div>
                    </div>
                </form>
                <form action="">
                    <br><br>
                    <label for="">Setelah Penghapusan</label><br>
                    <label for="finalipk">IPK akhir: &nbsp;</label>
                    <input id="finalipk" value="<?= $row->listmatkul ? $row->listmatkul->ipk : "0"; ?>" form="approve_bulk"
                        name="finalipk" readonly></input>
                    <label for="finalsks">SKS akhir: &nbsp;</label>
                    <input id="finalsks" value="<?= $row->listmatkul ? $row->listmatkul->sks : "0"; ?>" form="approve_bulk"
                        name="finalsks" readonly></input>
                    <label for="finaldplus">Jumlah D+ akhir: &nbsp;</label>
                    <input id="finaldplus" value="<?= $row->listmatkul ? $row->listmatkul->{"D+"} : "0"; ?>"
                        form="approve_bulk" name="finaldplus" readonly>
                    <label for="finald">Jumlah D akhir: &nbsp;</label>
                    <input id="finald" value="<?= $row->listmatkul ? $row->listmatkul->{"D"} : "0"; ?>" form="approve_bulk"
                        name="finald" readonly>
                    <label for="finale">Jumlah E akhir: &nbsp;</label>
                    <input id="finale" value="<?= $row->listmatkul ? $row->listmatkul->{"E"} : "0"; ?>" form="approve_bulk"
                        name="finale" readonly>
                </form>
            <?php endif ?>
        </div>

        <form action="<?= site_url('yudisium/komentar/') . $row->id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group mt-4">
                <label for="">Komentar Revisi</label>
                <div>
                    Komentar Akademik&ensp;:
                    <?= $row->komentar->akademik ?>
                </div>
                <div>
                    Komentar Perpus&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                    <?= $row->komentar->perpus ?>
                </div>
                <div>
                    <textarea rows="4" cols="50" id="komentar" name="komentar"><?= $row->komentar->prodi ?></textarea>
                </div>
                <input type="hidden" name="pengirim" value="prodi" />
                <button type="submit" name="aksi" value="komentar">Kirim revisi</button>
            </div>
        </form>

        <form action="<?= site_url('yudisium/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
            method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="checkbox" id="data_validation" name="data_validation" required>
                <span>
                    <?= ($row->pengajuan_penghapusan_matkul_status == "1") ?
                        "Pembatalan keputusan approve tidak melanggar aturan akademik dan disetujui untuk dibatalkan" :
                        "Pembatalan/penghapusan matkul tidak melanggar aturan akademik dan disetujui untuk dibatalkan"
                        ?>
                </span><span style="color:red;">*</span>
            </div>

            <div class="mt-5">
                <button type="submit" form="approve_bulk"
                    class="btn <?= ($row->pengajuan_penghapusan_matkul_status == "1") ? "btn-danger" : "btn-primary" ?>"
                    id="approve_selected" disabled="true">
                    <?= ($row->pengajuan_penghapusan_matkul_status == "1") ? "Batalkan" : "Approve Selected" ?>
                </button>
            </div>

        </form>
    </div>
</div>

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>
    $(document).ready(function (e) {
        function calculate() {
            var ipk = $('#initipk').val();
            var sks = $('#initsks').val();
            var d = ($('#initd').val() == "") ? 0 : $('#initd').val();
            var dplus = ($('#initdplus').val() == "") ? 0 : $('#initdplus').val();
            var e = ($('#inite').val() == "") ? 0 : $('#inite').val();

            var val = parseFloat(ipk) * parseFloat(sks);

            for (let i = 0; i < $('#approve_bulk')[0].length - 1; i++) {
                // console.log($('#approve_bulk')[0][i].checked);
                // console.log($('#approve_bulk')[0][i].value);
                if ($('#approve_bulk')[0][i].checked) {
                    var data = $('#approve_bulk')[0][i].value.split(',');

                    if (data[1] == 'A') {
                        data[1] = 4;
                    } else if (data[1] == 'A-') {
                        data[1] = 3.75;
                    } else if (data[1] == 'A/B') {
                        data[1] = 3.5;
                    } else if (data[1] == 'B+') {
                        data[1] = 3.25;
                    } else if (data[1] == 'B') {
                        data[1] = 3;
                    } else if (data[1] == 'B-') {
                        data[1] = 2.75;
                    } else if (data[1] == 'B/C') {
                        data[1] = 2.5;
                    } else if (data[1] == 'C+') {
                        data[1] = 2.25;
                    } else if (data[1] == 'C') {
                        data[1] = 2;
                    } else if (data[1] == 'C-') {
                        data[1] = 1.75;
                    } else if (data[1] == 'C/D') {
                        data[1] = 1.5;
                    } else if (data[1] == 'D+') {
                        data[1] = 1.25;
                        dplus -= data[2];
                    } else if (data[1] == 'D') {
                        data[1] = 1;
                        d -= data[2];
                    } else if (data[1] == 'E') {
                        data[1] = 0;
                        e -= data[2];
                    }

                    val -= parseFloat(data[1]) * parseFloat(data[2]);
                    sks -= parseFloat(data[2]);
                }
            }

            if (sks == 0) {
                $('#finalipk').val(0);
                $('#finalsks').val(0);
                $('#finald').val(0);
                $('#finaldplus').val(0);
                $('#finale').val(0);
            } else {
                $('#finalipk').val(val / parseInt(sks));
                $('#finalsks').val(sks);
                $('#finald').val(d);
                $('#finaldplus').val(dplus);
                $('#finale').val(e);
            }
        }

        calculate();

        $('.check').click(function (e) {
            calculate();
        });

        $('#initipk, #initsks, #initdplus, #initd, #inite').change(function (e) {
            calculate();
        });

        $('#data_validation').click(function (e) {
            $('#approve_selected').prop('disabled', ($('#approve_selected').prop('disabled') ? false : true));
        });
    });
</script>
<?= $this->endSection() ?>
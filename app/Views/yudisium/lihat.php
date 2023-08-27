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
                <?= $row->prodi->jenjang . "-" . $row->prodi->nama; ?>
            </span>
        </div>

        <div class="border p-4 rounded">
            <h5 class="font-weight-bold">Upload berkas</h5>
            <hr />
            <form action="<?= site_url('yudisium/uploadAdmin/') . $row->id ?>" method="POST"
                enctype="multipart/form-data">
                <!-- <div class="form-group">
                    <label for="">Transkrip nilai</label><span style="color:red;">*</span>
                    <?php if ($row->transkrip_nilai): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->transkrip_nilai ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/transkrip_nilai/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <input id="transkrip_nilai" type="file" accept="application/pdf" name="transkrip_nilai"
                        <?= $row->transkrip_nilai ? "" : "required" ?> class="form-control"
                        onchange="validateFileSize(this)">
                </div>
                <div class="form-group">
                    <label for="">KHS lengkap</label><span style="color:red;">*</span>
                    <?php if ($row->khs_lengkap): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->khs_lengkap ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/khs_lengkap/" . $row->id); ?>" target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <input <?= $row->khs_lengkap ? "" : "required" ?> id="khs_lengkap" type="file"
                        accept="application/pdf" name="khs_lengkap" class="form-control"
                        onchange="validateFileSize(this)">
                </div> -->
                <div class="form-group">
                    <label for="">Profil mahasiswa</label>
                    <?php if ($row->profil_mahasiswa): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->profil_mahasiswa ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/profil_mahasiswa/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <input id="profil_mahasiswa" type="file" accept="application/pdf" name="profil_mahasiswa"
                        <?= $row->profil_mahasiswa ? "" : "required" ?> class="form-control"
                        onchange="validateFileSize(this)">
                </div>
                <button type="submit" name="aksi" value="upload">Upload</button>
                <!-- <button <?= $row->transkrip_nilai && $row->khs_lengkap && $row->profil_mahasiswa ? "" : "disabled" ?>
                    type="submit" name="status" value="kirim">Kirim ke Prodi</button> -->
            </form>
        </div>

        <div class="border p-4 rounded mt-3">
            <h5 class="font-weight-bold">Sebaran Nilai <b>Sebelum</b> Pembatalan</h5>
            <hr />
            <form action="<?= site_url('yudisium/simpanmatkul/') . $row->id ?>" method="POST"
                enctype="multipart/form-data">
                <!-- <label for="">Matakuliah yang ingin dihapus</label> -->
                <div id="">
                    <div class="form-row mb-2 matkul-item">
                        <div class="col-md-2">
                            <label>IPK</label>
                            <input type="number" step="any" class="form-control" placeholder="IPK" name="ipk"
                                value="<?= $row->listmatkul ? $row->listmatkul->ipk : ""; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>SKS</label>
                            <input type="number" class="form-control" placeholder="Jumlah SKS" name="sks"
                                value="<?= $row->listmatkul ? $row->listmatkul->sks : ""; ?>">
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <label>Nilai D+</label>
                            <input type=" number" class="form-control" placeholder="Jumlah Nilai D+" name="D+"
                                value="<?= $row->listmatkul ? $row->listmatkul->{"D+"} : ""; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Nilai D</label>
                            <input type=" number" class="form-control" placeholder="Jumlah Nilai D" name="D"
                                value="<?= $row->listmatkul ? $row->listmatkul->{"D"} : ""; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Nilai E</label>
                            <input type=" number" class="form-control" placeholder="Jumlah Nilai E" name="E"
                                value="<?= $row->listmatkul ? $row->listmatkul->{"E"} : ""; ?>">
                        </div>
                    </div>
                </div>
                <button type="submit" name="aksi" value="simpan">Simpan Nilai</button>
            </form>
        </div>

        <div class="border p-4 rounded mt-3">
            <h5 class="font-weight-bold">Dokumen yang disubmit mahasiswa</h5>
            <hr />
            <form action="<?= site_url('yudisium/uploadAdmin/') . $row->id ?>" method="POST"
                enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Sertifikat PPSMB</label><span style="color:red;">*</span>
                    <?php if ($row->sertifikat_ppsmb): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->sertifikat_ppsmb ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/sertifikat_ppsmb/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                        <div>
                            <input type="checkbox" id="sertifikat_ppsmb_status" name="sertifikat_ppsmb_status"
                                <?= $row->sertifikat_ppsmb_status ? "checked" : "" ?>>
                            <span>Sesuai</span>
                        </div>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label for="">Sertifikat pengurus UKM</label>
                    <?php if ($row->sertifikat_pengurus_ukm): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->sertifikat_pengurus_ukm ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/sertifikat_pengurus_ukm/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <?php if (!$row->sertifikat_pengurus_ukm): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                Tidak ada
                            </div>
                        </div>
                    <?php endif ?>
                    <div>
                        <input type="checkbox" id="sertifikat_pengurus_ukm_status" name="sertifikat_pengurus_ukm_status"
                            <?= $row->sertifikat_pengurus_ukm_status ? "checked" : "" ?>>
                        <span>Sesuai</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Surat bebas pustaka perpustakaan UGM (pusat)</label><span style="color:red;">*</span>
                    <?php if ($row->surat_bebas_pinjam_perpus_ugm): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->surat_bebas_pinjam_perpus_ugm ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/surat_bebas_pinjam_perpus_ugm/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <div>
                        <input type="checkbox" id="surat_bebas_pinjam_perpus_ugm_status"
                            name="surat_bebas_pinjam_perpus_ugm_status" <?= $row->surat_bebas_pinjam_perpus_ugm_status ? "checked" : "" ?>>
                        <span>Sesuai</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Dokumen skripsi final</label><span style="color:red;">*</span>
                    <?php if ($row->dokumen_skripsi_final): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->dokumen_skripsi_final ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/dokumen_skripsi_final/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <div>
                        <input type="checkbox" id="dokumen_skripsi_final_status" name="dokumen_skripsi_final_status"
                            <?= $row->dokumen_skripsi_final_status ? "checked" : "" ?>>
                        <span>Sesuai</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Lembar pengesahan skripsi (scan)</label><span style="color:red;">*</span>
                    <?php if ($row->lembar_pengesahan_skripsi): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->lembar_pengesahan_skripsi ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/lembar_pengesahan_skripsi/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <div>
                        <input type="checkbox" id="lembar_pengesahan_skripsi_status"
                            name="lembar_pengesahan_skripsi_status" <?= $row->lembar_pengesahan_skripsi_status ? "checked" : "" ?>>
                        <span>Sesuai</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Bukti pernah internasional exposure (IUP Wajib)</label>
                    <?php if ($row->pernah_internasional_exposure): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <?= $row->pernah_internasional_exposure ?>
                            </div>
                            <a class="btn btn-sm btn-success ml-2" title="view file"
                                href="<?= base_url("yudisium/viewPdf/pernah_internasional_exposure/" . $row->id); ?>"
                                target="__blank">Lihat
                                File</a>
                        </div>
                    <?php endif ?>
                    <?php if (!$row->sertifikat_pengurus_ukm): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                Tidak ada
                            </div>
                        </div>
                    <?php endif ?>
                    <div>
                        <input type="checkbox" id="pernah_internasional_exposure_status"
                            name="pernah_internasional_exposure_status" <?= $row->pernah_internasional_exposure_status ? "checked" : "" ?>>
                        <span>Sesuai</span>
                    </div>
                </div>
                <button type="submit" name="aksi" value="simpan">Simpan Status</button>
            </form>
        </div>


        <div class="border p-4 rounded mt-3">
            <h5 class="font-weight-bold">Status Lain</h5>
            <hr />
            <div class="form-group">

            </div>

            <div class="form-group">
                Surat bebas pinjam lab MIPA : <span class="font-weight-bold">
                    <?= $row->status_pengajuan_surat_bebas_pinjam_lab ? "SUDAH" : "BELUM" ?>
                </span>
                di-approve
            </div>
            <div class="form-group">
                Status penghapusan mata kuliah : <span class="font-weight-bold">
                    <?= $row->pengajuan_penghapusan_matkul_status ? "SUDAH" : "BELUM" ?>
                </span>
                di-approve
            </div>
        </div>

        <div class="border p-4 rounded mt-3">
            <h5 class="font-weight-bold">Sebaran Nilai <b>Setelah</b> Pembatalan</h5>
            <hr />
            <div class="form-group">

            </div>
            <?php if (!empty($row->sebaranSetelahHapus)): ?>
                <div class="form-group">
                    SKS Total : <span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["sksSetelahBatal"] ?>
                    </span>
                </div>
                <div class="form-group">
                    IPK : <span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["ipkSetelahBatal"] ?>
                    </span>
                </div>
                <div class="form-group">
                    Pelanggaran : <br /><span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["pelanggaranJumlahD"] ? "Nilai D > 25%<br />" : "" ?>
                    </span>
                    <span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["pelanggaranTotalSks"] ? "SKS Total < 144 sks<br />" : "" ?>
                    </span>
                    <span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["pelanggaranJumlahPenghapusan"] ? "Matkul dihapus > 10%<br />" : "" ?>
                    </span>

                    <span class="font-weight-bold">
                        <?= $row->sebaranSetelahHapus["pelanggaranJumlahE"] ? "Terdapat nilai E<br />" : "" ?>
                    </span>
                </div>

            <?php else: ?>
                <div class="form-group">
                    Prodi belum acc atau nilai sebelum pembatalan belum diinput
                </div>
            <?php endif ?>

        </div>

        <form action="<?= site_url('yudisium/uploadAdmin/') . $row->id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group mt-4">
                <label for="">Komentar Revisi</label>
                <div>
                    Komentar Prodi&ensp;&nbsp;&nbsp;:
                    <?= $row->komentar->prodi ?>
                </div>
                <div>
                    Komentar Perpus&nbsp;:
                    <?= $row->komentar->perpus ?>
                </div>
                <div>
                    <textarea rows="4" cols="50" id="komentar"
                        name="komentar"><?= $row->komentar->akademik ?></textarea>
                </div>
                <input type="hidden" name="pengirim" value="akademik" />
                <button type="submit" name="aksi" value="komentar">Kirim revisi</button>
            </div>
        </form>

        <form action="<?= site_url('yudisium/ajukanyudisium/') . $row->id ?>" method="POST"
            enctype="multipart/form-data">
            <div class="form-group">
                <label>Tanggal Yudisium</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input required type="date" class="form-control" name="tanggal_yudisium" value="<?php if ($row->tanggal_yudisium)
                        echo date('Y-m-d', strtotime($row->tanggal_yudisium)); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Tanggal diterima UGM</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <!-- <input required type="number" class="form-control" name="lama_studi"
                        value="<?= $row->lama_studi; ?>"> -->
                    <input required type="date" class="form-control" name="mulai_studi">
                </div>
            </div>
            <div class="form-group">
                <input type="checkbox" id="data_validation" name="data_validation" required>
                <span>Seluruh syarat yudisium telah dipenuhi dan siap diajukan ke sidang yudisium</span><span
                    style="color:red;">*</span>
            </div>

            <div class="mt-5">
                <button <?= $row->status == 3 ? "" : "disabled" ?> type="submit" class="btn btn-primary" name="status"
                    value="4">Ajukan</button>
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
    function validateFileSize(input) {
        if (input.files && input.files[0]) {
            let maxSizeInMB = 0;
            if (getFileExtension(input.value) == 'pdf') {
                maxSizeInMB = 2;
            } else {
                maxSizeInMB = 5;
            }
            var fileSizeInBytes = input.files[0].size;
            var fileSizeInMB = fileSizeInBytes / (1024 * 1024);
            if (fileSizeInMB > maxSizeInMB) {
                alert('Ukuran file ' + getFileExtension(input.value) + ' tidak boleh lebih dari ' + maxSizeInMB + ' MB.');
                input.value = '';
                return
            }
        }
    }

    function uploadFile(file_name, user_id, input) {
        jQuery.ajax({
            type: 'POST',
            url: '<?= base_url('yudisium/upload') ?>' + '/' + file_name + '/' + user_id,
            data: new FormData(input.files[0]),
            processData: false,
            contentType: false,
            success: function (res) {
                if (res) {
                    console.log(res);
                }
            }
        })
    }

    function getFileExtension(filename) {
        return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
    }

    function copas(text) {
        // let text = document.getElementById(input).value;
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
        } catch (err) {
            console.error('Unable to copy to clipboard', err);
        }
        document.body.removeChild(textArea);
    }

    $(document).ready(function (e) {
    });
</script>
<?= $this->endSection() ?>
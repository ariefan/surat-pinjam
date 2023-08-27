<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Yudisium
        </h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('yudisium/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
            method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama</label><span style="color:red;">*</span>
                <input type="text" class="form-control" readonly name="nama" id="nama"
                    value="<?= $row->mahasiswa->nama; ?>">
            </div>

            <div class="form-group">
                <label>NIM</label><span style="color:red;">*</span>
                <input type="text" class="form-control" readonly name="nim" id="nim"
                    value="<?= $row->mahasiswa->nim; ?>">
            </div>

            <div class="form-group">
                <label>Prodi</label><span style="color:red;">*</span>
                <select class="form-control" name="prodi_id">
                    <?php foreach ($row->prodi as $i) { ?>
                        <option value="<?= $i->id ?>" <?= $row->prodi == ($i->id) ? 'selected' : ''; ?>><?= $i->jenjang ?>-<?= $i->nama ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="">Sertifikat PPSMB</label><span style="color:red;">*</span>
                <?php if ($row->sertifikat_ppsmb): ?>
                    <div class="d-flex align-items-center mb-2">
                        <div>
                            <?= $row->sertifikat_ppsmb ?>
                        </div>
                        <a class="btn btn-sm btn-success ml-2" title="view file"
                            href="<?= base_url("yudisium/viewPdf/sertifikat_ppsmb/" . $row->id); ?>" target="__blank">Lihat
                            File</a>
                    </div>
                <?php endif ?>
                <input id="sertifikat_ppsmb" type="file" accept="application/pdf" name="sertifikat_ppsmb"
                    <?= $row->sertifikat_ppsmb ? "" : "required" ?> class="form-control"
                    onchange="validateFileSize(this)">
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
                <input id="sertifikat_pengurus_ukm" type="file" accept="application/pdf" name="sertifikat_pengurus_ukm"
                    class="form-control" onchange="validateFileSize(this)">
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
                <input id="surat_bebas_pinjam_perpus_ugm" type="file" accept="application/pdf"
                    name="surat_bebas_pinjam_perpus_ugm" <?= $row->surat_bebas_pinjam_perpus_ugm ? "" : "required" ?>
                    class="form-control" onchange="validateFileSize(this)">
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
                <input id="dokumen_skripsi_final" type="file" accept="application/pdf" name="dokumen_skripsi_final"
                    <?= $row->dokumen_skripsi_final ? "" : "required" ?> class="form-control"
                    onchange="validateFileSize(this)">
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
                <input id="lembar_pengesahan_skripsi" type="file" accept="application/pdf"
                    name="lembar_pengesahan_skripsi" <?= $row->lembar_pengesahan_skripsi ? "" : "required" ?>
                    class="form-control" onchange="validateFileSize(this)">
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
                <input id="pernah_internasional_exposure" type="file" accept="application/pdf"
                    name="pernah_internasional_exposure" class="form-control" onchange="validateFileSize(this)">
            </div>

            <div class="form-group">
                <label for="">Tandai checkbox berikut, jika</label><span style="color:red;">*</span>
                <div>
                    <input type="checkbox" id="status_pengajuan_surat_bebas_pinjam"
                        name="status_pengajuan_surat_bebas_pinjam" required <?= $action == 'update' ? "checked" : "" ?>>
                    <span>Saya mengajukan surat bebas pinjam perpustakaan FMIPA, laboratorium di lingkungan FMIPA</span>
                </div>
            </div>

            <div class="form-group">
                <label for="">Tandai checkbox berikut, jika</label>
                <div>
                    <input type="checkbox" id="status_penghapusan_matkul" name="status_penghapusan_matkul"
                        <?= !empty($row->matakuliah) ? "checked" : "" ?>>
                    <span>Saya mengajukan penghapusan mata kuliah</span>
                </div>
            </div>

            <div class="form-group matkul-wrapper" <?= !empty($row->matakuliah) ? "" : "hidden" ?>>
                <div class="card my-4" style="background-color: #ddd; border:solid #aaa 1px;">
                    <div class="card-header">
                        <h3 class="card-title">Pencarian Nama Mata Kuliah</h3>
                    </div>

                    <div class="card-body">
                        Cari: <input id="matkul-search" class="form-control">
                    </div>
                </div>
                <label for="">Matakuliah yang ingin dihapus</label>
                <?php foreach ($row->matakuliah as $matkul): ?>
                    <div id="">
                        <div class="form-row mb-2 matkul-item">
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Kode Matkul" name="kode_matkul[]"
                                    value="<?= $matkul->kode_matkul; ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Nama Matkul" name="nama_matkul[]"
                                    value="<?= $matkul->nama_matkul; ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="SKS" name="sks[]"
                                    value="<?= $matkul->sks; ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" placeholder="Nilai" name="nilai[]"
                                    value="<?= $matkul->nilai; ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" placeholder="Keterangan" name="keterangan[]">
                                    <option value="wajib">wajib</option>
                                    <option value="pilihan">pilihan</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-outline-danger matkul-btn-delete" type="button"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>

                        </div>
                    </div>
                <?php endforeach ?>
                <div id="list-matkul">

                </div>

                <div><button type="button" class="btn btn-success btn-sm" id="add-matkul"><i
                            class="fa-solid fa-plus"></i></button> Tambah Mata Kuliah</div>
            </div>

            <hr />

            <div class="form-group">
                <input type="checkbox" id="data_validation" name="data_validation" required>
                <span>Dengan ini saya menyatakan bahwa data yang saya submit adalah benar dan selanjutnya diajukan
                    sebagai syarat yudisium</span><span style="color:red;">*</span>
            </div>

            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button <?= $row->status > 0 ? "disabled" : "" ?> type="submit" class="btn btn-success" name="aksi"
                    value="preview">Simpan Draft</button>
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Yudisium</button>
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
        $("#add-tembusan").click(function () {
            $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `)


            $('.tembusan-btn-delete').click(function () {
                console.log($(this).closest('.tembusan-item'));
                $(this).closest('.tembusan-item').remove();
            });
        });

        $('.tembusan-btn-delete').click(function () {
            console.log($(this).closest('.tembusan-item'));
            $(this).closest('.tembusan-item').remove();
        });

        // check if checkbox is checked
        $("#status_penghapusan_matkul").change(function () {
            if (this.checked) {
                $('.matkul-wrapper').removeAttr('hidden');
            } else {
                $('.matkul-wrapper').attr('hidden', true);
            }
        });

        $('#tabel-search').hide();
        $("#matkul-search").autocomplete({
            minLength: 0,
            source: function (request, response) {
                $.ajax({
                    url: "<?= base_url('matakuliah/findmatkul'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: response
                });
            },
            focus: function (event, ui) {
                $("#matkul-search").val(ui.item.nama);
                return false;
            },
            select: function (event, ui) {
                $('#list-matkul').append(`
            <div class="form-row mb-2 matkul-item">
                    <div class="col-md-2">
                        <input readonly type="text" class="form-control" placeholder="Kode Matkul" name="kode_matkul[]" value="${ui.item.kode}">
                    </div>
                    <div class="col-md-3">
                        <input readonly type="text" class="form-control" placeholder="Nama Matkul" name="nama_matkul[]" value="${ui.item.nama}">
                    </div>
                    <div class="col-md-2">
                        <input readonly type="text" class="form-control" placeholder="SKS" name="sks[]" value="${ui.item.sks}">
                    </div>
                    <div class="col-md-2">
                        <input readonly type="text" class="form-control" placeholder="Keterangan" name="keterangan[]" value="${ui.item.keterangan}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" required class="form-control" placeholder="Nilai" name="nilai[]" value="">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-danger matkul-btn-delete" type="button"><i
                                class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `);


                $('.matkul-btn-delete').click(function () {
                    $(this).closest('.matkul-item').remove();
                });

                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div> Kode: " + item.kode + "<br> Nama: " + item.nama + "<br> Prodi: " + item.prodi + "</div>")
                .appendTo(ul);
        };

        $("#add-matkul").click(function () {
            $('#list-matkul').append(`
            <div class="form-row mb-2 matkul-item">
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Kode Matkul" name="kode_matkul[]" value="">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Nama Matkul" name="nama_matkul[]" value="">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="SKS" name="sks[]" value="">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" placeholder="Keterangan" name="keterangan[]">
                            <option value="wajib">wajib</option>
                            <option value="pilihan">pilihan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Nilai" name="nilai[]" value="">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-danger matkul-btn-delete" type="button"><i
                                class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `);

            $('.matkul-btn-delete').click(function () {
                $(this).closest('.matkul-item').remove();
            });
        });

        $('.matkul-btn-delete').click(function () {
            $(this).closest('.matkul-item').remove();
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #fff;
        padding: 5px 20px;
        border-radius: 5px;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.4s ease-out;
    }

    .toast.show {
        opacity: 1;
    }

    .toast-body {
        font-size: 16px;
        line-height: 1.5;
    }

    /* table.table-bordered {
        border: 1px solid #002ca3 !important;
    }

    table.table-bordered>thead>tr>th {
        border: 1px solid #002ca3 !important;
    }

    table.table-bordered>tbody>tr>td {
        border: 1px solid #002ca3 !important;
    }

    .table-responsive {
        opacity: 1 !important;
    } */
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Nav Bar khusus perjanjian -->
<nav class="nav nav-pills nav-fill">
    <?php
    $akses = [
        'details' => $gol_pic_mou == 2 ||  $gol_pic_mou == 1 ? '' : 'disabled',
        'reviews' => ($gol_pic_mou == 2 && $status_mou >= 1) || ($gol_pic_mou == 1 && $status_mou >= 1) ? '' : 'disabled',
        'luaran' => ($gol_pic_mou == 2 && $status_mou >= 3) || ($gol_pic_mou == 1 && $status_mou >= 3) ? '' : 'disabled',
        'monev' => ($gol_pic_mou == 2 && $status_mou >= 3) || ($gol_pic_mou == 1 && $status_mou >= 3) ? '' : 'disabled',
    ];
    $active = [
        'details' => $active == 1 ? 'active' : '',
        'reviews' => $active == 2 ? 'active' : '',
        'luaran' => $active == 3 ? 'active' : '',
        'monev' => $active == 4 ? 'active' : '',
    ]
    ?>
    <a class="nav-item nav-link" href="<?= site_url("perjanjian"); ?>"><i class="fa-solid fa-arrow-left"></i></a>

    <a class="nav-item nav-link <?= $akses['details'] ?> <?= $active['details'] ?>" href="<?= site_url("perjanjian/details/" . $id_mou); ?>">Detail</a>
    <a class="nav-item nav-link <?= $akses['reviews'] ?> <?= $active['reviews'] ?>" href="<?= site_url("perjanjian/reviews/" . $id_mou); ?>">Review</a>
    <a class="nav-item nav-link <?= $akses['luaran'] ?> <?= $active['luaran'] ?>" href="<?= site_url("perjanjian/luaran/" . $id_mou); ?>">Luaran</a>
    <a class="nav-item nav-link <?= $akses['monev'] ?> <?= $active['monev'] ?>" href="<?= site_url("perjanjian/monev/" . $id_mou); ?>">Monitoring dan Evaluasi</a>
</nav>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 mt-2">
            <div class="col-sm-12">
                <h1 class="text text-lg font-weight-bold m-0">Monitoring dan Evaluasi / Monev </h1>
            </div>
            <div class="col-sm-12">
                <h3 class="text text-md font-weight-light m-0">Kerjasama <span class="text text-md font-weight-normal m-0"><?= $judul_kerjasama ?></span> </h3>
            </div>
        </div>
    </div>
</div>
<div class="content" id="monev">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>

                    <a style="float: left;" class="btn btn-success mr-2" href="#" title="Tambah monev" id="btnUploadmonev" data-toggle="modal" data-target="#modal-monev"><i class=" fa-solid fa-upload">
                        </i> Tambah Monev</a>

                <?php } ?>
            </div>
        </div>


        <div class="col mx-auto" id="errorWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Terjadi Kesalahan dalam memuat data, mohon coba lagi</div>

                <button type="button" class="btn btn-primary" onclick="reloadmonev()"><i class="fa-solid fa-rotate-right"></i> Reload</button>

            </center>

        </div>
        <div class="col mx-auto" id="emptyWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Belum ada Monitoring dan Evaluasi, silahkan dapat menambahkan</div>

                <!-- <button type="button" class="btn btn-primary" onclick="reloadmonev()"><i class="fa-solid fa-rotate-right"></i> Reload</button> -->
            </center>

        </div>

        <div class="col mx-auto" id="loadingmonev" style="align-self: center; display: none;">

            <center>
                <div class="spinner-border mt-3 text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="h3 mt-3 col text-secondary" style="float: left;"> Sedang Memuat...
                </div>

            </center>

        </div>



        <div id="monevTable">
            <div class="row">
                <div class="table-responsive">
                    <div class="col-sm-12">
                        <!-- <?php
                                $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                                    $url = site_url('suratkp/index') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                                    $is_selected = $sort_column == $column_name ? '' : 'text-white';
                                    $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                                    return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
                                }
                                ?> -->
                        <table class="table table-bordered table-valign-top table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 10%;">
                                        Periode/Semester
                                    </th>
                                    <th style="width: 10%;">
                                        Evaluator
                                    </th>



                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="monev-table">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="toast" id="notice">
            <div class="toast-body"></div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>



<?= $this->section('css') ?>
<?= $this->endSection() ?>



<?= $this->section('js') ?>

<!-- Modal detail monev -->
<div class="modal fade" id="detailMonevModal" tabindex="-1" aria-labelledby="monevDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="monevDetailLabel">Detail Monev Kerja Sama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <label for="detail-semester-monev">Semester</label>
                        <div class="text text-md" id="detail-semester-monev">

                        </div>
                    </div>
                    <div class="col">
                        <label for="detail-status-kegiatan-monev">Status Kegiatan</label>
                        <div class="text text-md" id="detail-status-kegiatan-monev">

                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <label for="detail-aktifitas-sudah-monev">Aktifitas Sudah DIlakukan</label>
                        <div class="text text-md" id="detail-aktifitas-sudah-monev">

                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <label for="detail-aktifitas-belum-monev">Aktifitas Belum Dilakukan</label>
                        <div class="text text-md" id="detail-aktifitas-belum-monev">

                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <label for="detail-kendala-monev">Kendala</label>
                        <div class="text text-md" id="detail-kendala-monev">

                        </div>
                    </div>
                </div>



                <div class="row mt-3">
                    <div class="col">
                        <label for="detail-solusi-monev">Rencana Solusi</label>
                        <div class="text text-md" id="detail-solusi-monev">

                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="detail-solusi-monev">Rencana Perpanjang Kerjasama di tahun ini</label>
                        <div class="text text-md" id="detail-perpanjangan-monev">

                        </div>
                    </div>
                </div>




                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto " data-dismiss="modal">Close</button>


                </div>

            </div>

        </div>
    </div>
</div>


<!-- SECTION DELETE MONEV -->
<div class="modal fade" id="deletemonevModal" tabindex="-1" role="dialog" aria-labelledby="deletemonevModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletemonevModalLabel">Hapus Monev</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin ingin menghapus Monev ini?
            </div>
            <div class="modal-footer">
                <div id="monev-delete-loading-bar" style="display: none;">
                    <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                    </div>
                    <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div id="btnGroupDeletemonev">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button id="btnDeletemonev" value="" type="button" class="btn btn-danger">Hapus</button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit monev -->
<div class="modal fade" id="editMonevModal" tabindex="-1" aria-labelledby="editMonevModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" id="edit-monev-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMonevModalLabel">Edit Monev Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-2">
                        <div class="col">
                            <label for="detail-semester-edit-monev">Semester</label>
                            <div class="text text-md" id="detail-semester-edit-monev">

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Evaluator</label><span style="color:red;">*</span>
                        <input type="text" id="evaluator-edit-monev" name="evaluator-edit-monev" class="form-control" placeholder="Evaluator" required>
                    </div>

                    <div class="form-group">
                        <label for="">Status Kegiatan</label><span style="color:red;">*</span>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status-kegiatan-edit-monev" id="status-aktif-edit-monev" value="1" required>
                            <label class="form-check-label" for="status-aktif">
                                Aktif
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status-kegiatan-edit-monev" id="status-pasif-edit-monev" value="0" required>
                            <label class="form-check-label" for="status-pasif">
                                Pasif
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Aktifitas Sudah</label><span style="color:red;">*</span>
                        <textarea class="form-control" rows="3" id="aktifitas-sudah-edit-monev" name="aktifitas-sudah-edit-monev" placeholder="Masukkan aktifitas-aktifitas atau kegiatan-kegiatan dari perjanjian yang sudah dilakukan (jika ada)" value="" autocomplete="off" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Aktifitas Belum (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="aktifitas-belum-edit-monev" name="aktifitas-belum-edit-monev" placeholder="Masukkan aktifitas-aktifitas atau kegiatan-kegiatan dari perjanjian yang belum dilakukan (jika ada)" value="" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Kendala (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="kendala-edit-monev" name="kendala-edit-monev" placeholder="Masukkan kendala dari kegiatan perjanjian (jika ada)" value="" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Rencana Solusi (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="solusi-edit-monev" name="solusi-edit-monev" placeholder="Ajukan rencana solusi dari kendala di atas (jika ada)" value="" autocomplete="off"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Jika kerjasama selesai di tahun ini, mohon dipilih apakah berencana untuk memperpanjang kerjasama di tahun ini?<span style="color:red;"></span> </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="perpanjangan-edit-monev" id="perpanjangan-ya-edit-monev" value="1">
                            <label class="form-check-label" for="perpanjangan-ya-edit-monev">
                                Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="perpanjangan-edit-monev" id="perpanjangan-tidak-edit-monev" value="0">
                            <label class="form-check-label" for="perpanjangan-tidak-edit-monev">
                                Tidak
                            </label>
                        </div>
                    </div>


                    <div class="modal-footer">

                        <div id="monev-edit-loading-bar" style="display: none;">
                            <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                            </div>
                            <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                                <span class="sr-only">Loading...</span>
                            </div>

                        </div>


                        <div class="btn-group-edit-monev">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="btn-edit-monev" name="btn-edit-monev" class="btn btn-primary"> Save Changes </button>

                        </div>

                    </div>

                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Upload monev -->
<div class="modal fade" id="modal-monev" tabindex="-1" aria-labelledby="monevModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" id="monev-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="monevModalLabel">Tambah Monev Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- <div class="form-row"> -->
                    <!-- <div class="col">
                            <div class="form-group">
                                <label for="tahun-monev">Tahun</label><span style="color:red;">*</span>
                                <select class="form-control" id="tahun-monev" name="tahun-monev" required>
                                    <option value="" selected>Pilih...</option>

                                </select>
                            </div>
                        </div> -->
                    <!-- <div class="col">
                            <div class="form-group">
                                <label for="semester-monev">Semester</label><span style="color:red;">*</span>
                                <select class="form-control" id="semester-monev" name="semester-monev" required>
                                    <option value="" selected>Pilih...</option>
                                    <option value="1">Gasal</option>
                                    <option value="0">Genap</option>
                                </select>

                            </div>
                        </div> -->
                    <!-- </div> -->

                    <!-- <div class="form-group">
                        <label for="">Tahun Akademik</label><span style="color:red;">*</span>
                        <input readonly type="text" id="tahun-akademik-monev" name="tahun-akademik-monev" class="form-control" value="">
                    </div> -->
                    <div class="form-group">
                        <label for="">Tahun Akademik</label><span style="color:red;">*</span>
                        <select class="form-control" id="tahun-akademik-monev" name="tahun-akademik-monev" required>
                            <option value="" selected> --------- Pilih Semester --------- </option>

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="">Evaluator</label><span style="color:red;">*</span>
                        <input type="text" id="evaluator-monev" name="evaluator-monev" class="form-control" placeholder="Evaluator" required>
                    </div>

                    <div class="form-group">
                        <label for="">Status Kegiatan</label><span style="color:red;">*</span>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status-kegiatan" id="status-aktif" value="1" required>
                            <label class="form-check-label" for="status-aktif">
                                Aktif
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status-kegiatan" id="status-pasif" value="0" required>
                            <label class="form-check-label" for="status-pasif">
                                Pasif
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Aktifitas Sudah</label><span style="color:red;">*</span>
                        <textarea class="form-control" rows="3" id="open-source-plugins" name="aktifitas-sudah" placeholder="Masukkan aktifitas-aktifitas atau kegiatan-kegiatan dari perjanjian yang sudah dilakukan (jika ada)" value="" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Aktifitas Belum (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="open-source-plugins" name="aktifitas-belum" placeholder="Masukkan aktifitas-aktifitas atau kegiatan-kegiatan dari perjanjian yang belum dilakukan (jika ada)" value="" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Kendala (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="open-source-plugins" name="kendala" placeholder="Masukkan kendala dari kegiatan perjanjian (jika ada)" value="" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Rencana Solusi (jika ada)</label><span style="color:red;"></span>
                        <textarea class="form-control" rows="3" id="open-source-plugins" name="solusi" placeholder="Ajukan rencana solusi dari kendala di atas (jika ada)" value="" autocomplete="off"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Jika kerjasama selesai di tahun ini, mohon dipilih apakah berencana untuk memperpanjang kerjasama di tahun ini?<span style="color:red;"></span> </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="perpanjangan" id="perpanjangan-ya" value="1">
                            <label class="form-check-label" for="perpanjangan-ya">
                                Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="perpanjangan" id="perpanjangan-tidak" value="0">
                            <label class="form-check-label" for="perpanjangan-tidak">
                                Tidak
                            </label>
                        </div>
                    </div>


                    <div class="modal-footer">

                        <div id="monev-upload-loading-bar" style="display: none;">
                            <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                            </div>
                            <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                                <span class="sr-only">Loading...</span>
                            </div>

                        </div>

                        <button type="submit" id="btn-upload-monev" name="btn-upload-monev" class="btn btn-primary"> Submit </button>
                    </div>

                </div>
        </form>
    </div>
</div>

<div class="class">

</div>

<script src="<?= base_url('pdf.js'); ?>">
</script>
<script>
    <?= base_url('monev/toggle'); ?>
</script>





</tr>

<script>
    jQuery.fn.visible = function() {
        return this.css('visibility', 'visible');
    };

    // const toast = document.getElementById('notice');

    // Add hover effect to cells


    $(document).ready(function() {



        var pilihan_semester = ''

        //initialize
        get_list_semester()
        show_monev();



        function text_bg(color, textColor, text) {
            var html = `<span class="badge bg-${color} text-${textColor}">${text}</span>`
            return html;
        }

        function show_error(isShowed = true) {
            if (isShowed) {
                $("#errorWarning").show();
            } else {
                $("#errorWarning").hide();
            }
        }

        function show_empty(isShowed = true) {
            if (isShowed) {
                $("#emptyWarning").show();
            } else {
                $("#emptyWarning").hide();
            }
        }

        function show_loading(isShowed = true) {
            if (isShowed) {
                $("#loadingmonev").show();
            } else {
                $("#loadingmonev").hide();
            }
        }

        function show_monevTable(isShowed = true) {
            if (isShowed) {
                $("#monevTable").show();
            } else {
                $("#monevTable").hide();
            }
        }

        var reload = function reload() {
            show_error(false)
            show_monev()
        }


        function selectElement(id, valueToSelect) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        }

        function get_list_semester() {
            $.ajax({
                type: 'GET',
                url: "<?php echo site_url('perjanjian/monev_list_semester/' . $id_mou . '/') ?>",
                async: true,
                dataType: 'json',
                success: function(data) {
                    let list_semester = data
                    var html = `<option value="" > --------- Pilih Semester --------- </option>`;
                    var i;
                    for (i = 0; i < list_semester.length; i++) {
                        let periode = list_semester[i]['tahun']
                        let semester = list_semester[i]['semester']


                        if (semester == 1) {
                            let smt_element = `<option value="${periode}-${semester}" data-periode="${periode}" data-semester="${semester}">Semester Gasal ${periode} / ${periode+1} </option>`
                            html += `${smt_element}`
                            continue;
                        } else {
                            let smt_element = `<option value="${periode}-${semester}" data-periode="${periode}" data-semester="${semester}">Semester Genap ${periode-1} / ${periode} </option>`
                            html += `${smt_element}`
                            continue;
                        }
                    }

                    pilihan_semester = html

                }
            })
        }

        function get_list_tahun() {
            $.ajax({
                url: "<?= base_url('perjanjian/monev_list_tahun/') . $id_mou ?>",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    let html = `<option value="" selected>Pilih...</option>`;
                    for (let i = 0; i < data.length; i++) {
                        html += `<option value="${data[i]['tahun']}">${data[i]['tahun']}</option>`;
                    }
                    $('#tahun-monev').html(html);

                    let html_semester = `<option value="" selected>Pilih...</option>
                                    <option value="1">Gasal</option>
                                    <option value="0">Genap</option>`
                    $('#semester-monev').html(html_semester);
                },
                error: function(data) {
                    alert(`Error get data from ajax ${data}`);
                }
            });
        }

        $('#modal-monev').on('shown.bs.modal', function(e) {
            // get_list_tahun();
            $("#tahun-akademik-monev").html(pilihan_semester)


        })
        $('#modal-monev').on('hide.bs.modal', function(e) {
            $('#tahun-akademik-monev').val('');

        })




        $('#detailMonevModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            let semester = button.data('periode') // Extract info from data-* attributes
            let status_kegiatan = parseInt(button.data('status-kegiatan')) ? 'Aktif' : 'Pasif' // Extract info from data-* attributes
            let aktifitas_sudah = button.data('aktifitas-sudah') // Extract info from data-* attributes
            let aktifitas_belum = button.data('aktifitas-belum') // Extract info from data-* attributes
            let kendala = button.data('kendala') // Extract info from data-* attributes
            let solusi = button.data('solusi') // Extract info from data-* attributes
            let perpanjangan = button.data('perpanjangan') != '-' ? (parseInt(button.data('perpanjangan')) ? 'Ya' : 'Tidak') : '-' // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            // modal.find('.modal-title').text('New message to ' + recipient)  
            $('#detail-semester-monev').html(semester)
            $('#detail-status-kegiatan-monev').html(status_kegiatan)
            $('#detail-aktifitas-sudah-monev').html(textarea_encode(aktifitas_sudah))
            $('#detail-aktifitas-belum-monev').html(textarea_encode(aktifitas_belum))
            $('#detail-kendala-monev').html(textarea_encode(kendala))
            $('#detail-solusi-monev').html(textarea_encode(solusi))
            $('#detail-perpanjangan-monev').html(perpanjangan)

            // $('#btnFinalisasi').val(recipient)
        })

        $('#editMonevModal').on('show.bs.modal', function(event) {


            var button = $(event.relatedTarget) // Button that triggered the modal

            console.log(button.data('monev'))
            $("#edit-monev-form").attr('data-monev', button.data('monev'))

            let semester = button.data('periode') // Extract info from data-* attributes
            let evaluator = button.data('evaluator') // Extract info from data-* attributes
            let status_kegiatan = button.data('status-kegiatan') // Extract info from data-* attributes
            let aktifitas_sudah = button.data('aktifitas-sudah') == '-' ? "" : button.data('aktifitas-sudah') // Extract info from data-* attributes
            let aktifitas_belum = button.data('aktifitas-belum') == '-' ? "" : button.data('aktifitas-belum') // Extract info from data-* attributes
            let kendala = button.data('kendala') == '-' ? "" : button.data('kendala') // Extract info from data-* attributes
            let solusi = button.data('solusi') == '-' ? "" : button.data('solusi') // Extract info from data-* attributes
            let perpanjangan = button.data('perpanjangan') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            // modal.find('.modal-title').text('New message to ' + recipient)  
            $('#detail-semester-edit-monev').html(semester)
            $('[name="evaluator-edit-monev"]').val(evaluator);
            $('[name="aktifitas-sudah-edit-monev"]').val((aktifitas_sudah));
            $('[name="aktifitas-belum-edit-monev"]').val((aktifitas_belum));
            $('[name="kendala-edit-monev"]').val((kendala));
            $('[name="solusi-edit-monev"]').val((solusi));

            if (status_kegiatan == 1) {
                $('#status-aktif-edit-monev').prop('checked', true);
            } else if (status_kegiatan == 0) {
                $('#status-pasif-edit-monev').prop('checked', true);
            }

            if (perpanjangan == 1) {
                $('#perpanjangan-ya-edit-monev').prop('checked', true);
            } else if (perpanjangan == 0) {
                $('#perpanjangan-tidak-edit-monev').prop('checked', true);
            }




            // $('#btnFinalisasi').val(recipient)
        })



        window.reloadmonev = reload

        // var periode = ''
        // var semester = ''


        // function selectElement(id, valueToSelect) {
        //     let element = document.getElementById(id);
        //     element.value = valueToSelect;
        // }

        // $('select').on('change', function() {
        //     const selectedOption = $(this).find('option:selected');
        //     periode = parseInt(selectedOption.data('periode'));
        //     semester = parseInt(selectedOption.data('semester'));
        //     console.log(periode, semester)
        //     $(this).blur()
        //     show_monev()
        // });




        function show_monev(latest = 0) {

            //initialize
            get_list_semester()

            show_loading();

            show_monevTable(false);

            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('perjanjian/monev_get_json/' . $id_mou . '/') ?>",
                async: true,
                dataType: 'json',
                success: function(data) {


                    if (data == 'error') {
                        show_error()
                        show_loading(false)
                        show_monevTable(false)

                    } else {

                        var html = ``;
                        var i;
                        var j = 1;
                        var rows = data['rows'];
                        var data_count = rows.length;

                        if (data_count == 0) {
                            show_empty()
                            show_loading(false)
                            show_monevTable(false)
                        } else {

                            // console.log(rows)
                            for (i = 0; i < data_count; i++) {

                                let tahun = parseInt(rows[i]['periode'])

                                let semester = parseInt(rows[i]['semester']) ? `Semester Gasal ${tahun} / ${tahun+1}` : `Semester Genap ${tahun-1} / ${tahun}`

                                let evaluator = rows[i]['evaluator'] ? rows[i]['evaluator'] : '-'
                                let status_kegiatan = rows[i]['status_kegiatan']

                                let empty_check = [null, ""]

                                let perpanjangan = empty_check.includes(rows[i]['perpanjangan']) ? '-' : rows[i]['perpanjangan']
                                let aktifitas_sudah = empty_check.includes(rows[i]['aktifitas_sudah']) ? '-' : rows[i]['aktifitas_sudah']
                                let aktifitas_belum = empty_check.includes(rows[i]['aktifitas_belum']) ? '-' : rows[i]['aktifitas_belum']
                                let kendala = empty_check.includes(rows[i]['kendala']) ? '-' : rows[i]['kendala']
                                let solusi = empty_check.includes(rows[i]['solusi']) ? '-' : rows[i]['solusi']

                                let data_detail = `data-periode="${semester}" data-tahun="${tahun}" data-evaluator="${evaluator}" data-semester="${rows[i]['semester']}" data-status-kegiatan="${status_kegiatan}" 
                            data-aktifitas-sudah="${aktifitas_sudah}" data-aktifitas-belum="${aktifitas_belum}" 
                            data-kendala="${kendala}" data-solusi="${solusi}" data-perpanjangan="${perpanjangan}"`

                                html += `
                        <tr class="monev-row">
                                <th scope="row" class="monev-no"> ${j++} </th>
            
                                <td class="monev-cell monev-semester">
                                    ${semester}
                                </td>
                                <td class="monev-cell monev-evaluator">
                                    ${rows[i]['evaluator']}
                                </td>
    
                            
                                <td class="monev-aksi">
                                <div class="btn-group btn-group-sm" role="group">
                                    <form>
                                        <button type="button" data-monev="${data['rows'][i]['id_monev']}" ${data_detail} data-monev="${data['rows'][i]['id_monev']}" class="btn btn-success" title="Details" data-toggle="modal" data-target="#detailMonevModal"><i class=" fa-solid fa-ellipsis-h"></i></button>
                                        <button type="button" data-monev="${data['rows'][i]['id_monev']}" ${data_detail} data-monev="${data['rows'][i]['id_monev']}" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#editMonevModal"><i class=" fa-solid fa-pen-to-square"></i></button>
                                        <button type="button" data-monev="${data['rows'][i]['id_monev']}" class="btn btn-danger" title="Delete" data-toggle="modal" data-target="#deletemonevModal"><i class=" fa-solid fa-trash"></i></button>
                                    </form>
                                    </div>    

                                </td>

                     `
                            }
                            show_empty(false)
                            show_loading(false);
                            show_monevTable()
                            $('#monev-table').html(html)

                            // add copy click
                            const toast = document.getElementById('notice');
                            const cells = document.getElementsByClassName('monev-cell');
                            for (let i = 0; i < cells.length; i++) {
                                cells[i].addEventListener('mouseover', function() {
                                    this.style.backgroundColor = '#daebff';
                                    this.style.cursor = 'pointer';
                                });
                                cells[i].addEventListener('mouseout', function() {
                                    this.style.backgroundColor = '';
                                    this.style.cursor = '';
                                });
                            }

                            // Add click effect to cells
                            for (let i = 0; i < cells.length; i++) {
                                cells[i].addEventListener('click', function() {
                                    // Copy the cell's content to the clipboard
                                    const range = document.createRange();
                                    range.selectNode(this);
                                    window.getSelection().removeAllRanges();
                                    window.getSelection().addRange(range);
                                    document.execCommand('copy');
                                    window.getSelection().removeAllRanges();



                                    // Show toast message that content has been copied
                                    const toastBody = toast.querySelector('.toast-body');
                                    toastBody.innerHTML = `Copied`;
                                    toast.classList.add('show');
                                    setTimeout(function() {
                                        toast.classList.remove('show');
                                    }, 2000);
                                });
                            }
                        }
                    }
                }
            })
        }


        function textarea_encode(text) {
            return text.replace(/\r\n|\r|\n/g, "<br />");
        }

        function textarea_decode(text) {
            return text.replace(/<br\s?\/?>/g, "\n");
        }


        $('#monev-form').on('submit', function(e) {
            if (!this.checkValidity()) {
                // e.preventDefault();
                e.stopPropagation();
            }
            e.preventDefault()

            let aktifitas_sudah = $('[name="aktifitas-sudah"]').val();
            // aktifitas_sudah = textarea_encode(aktifitas_sudah)
            let aktifitas_belum = $('[name="aktifitas-belum"]').val();
            // aktifitas_belum = textarea_encode(aktifitas_belum)
            let kendala = $('[name="kendala"]').val();
            // kendala = textarea_encode(kendala)
            let solusi = $('[name="solusi"]').val();
            // solusi = textarea_encode(solusi)


            var form_data = new FormData(this);
            form_data.append('tahun-akademik-monev', $('#tahun-akademik-monev').val())
            form_data.append('aktifitas-sudah', aktifitas_sudah)
            form_data.append('aktifitas-belum', aktifitas_belum)
            form_data.append('kendala', kendala)
            form_data.append('solusi', solusi)

            // console.log(form_data.getcaaeredl('aktifitas-sudah'))


            // console.log(form_data.)
            $("#monev-upload-loading-bar").show();
            $('#btn-upload-monev').hide();



            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/upload_monev/' . $id_mou) ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {

                    $status = data['status'] ?? '';

                    if ($status == 'sudah') {
                        alert(data['message'])
                        $('#monev-upload-loading-bar').hide();
                        $('#btn-upload-monev').show();
                        return
                    } else if (data != 'success') {
                        // console.log(data)
                        alert('Terjadi kesalahan dalam mengupload monev, mohon coba lagi')
                    };

                    $('#modal-monev').modal('hide');
                    // resetSelectElement(document.getElementById('#tahun-monev'));
                    // resetSelectElement(document.getElementById('#semester-monev'));

                    // $('[name="status-kegiatan"]').val("");

                    // $('input[name=status-kegiatan]').prop('checked',false);
                    // tinymce.get('open-source-plugins').setContent("");
                    // tinymce.get('open-source-plugins').setContent("");
                    // tinymce.get('open-source-plugins').setContent("");
                    // tinymce.get('open-source-plugins').setContent("");


                    $('[name="evaluator-monev"]').val("");
                    $('[name="aktifitas-sudah"]').val("");
                    $('[name="aktifitas-belum"]').val("");
                    $('[name="kendala"]').val("");
                    $('[name="solusi"]').val("");
                    // $('[name="perpanjangan"]').val("");
                    $('input[name=status-kegiatan]').prop('checked', false);
                    $('input[name=perpanjangan]').prop('checked', false);

                    $('#monev-upload-loading-bar').hide();
                    $('#btn-upload-monev').show();

                    ;
                    show_monev();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        $('#edit-monev-form').on('submit', function(e) {
            e.preventDefault()

            let aktifitas_sudah = $('[name="aktifitas-sudah-edit-monev"]').val();
            // aktifitas_sudah = textarea_encode(aktifitas_sudah)
            let aktifitas_belum = $('[name="aktifitas-belum-edit-monev"]').val();
            // aktifitas_belum = textarea_encode(aktifitas_belum)
            let kendala = $('[name="kendala-edit-monev"]').val();
            // kendala = textarea_encode(kendala)
            let solusi = $('[name="solusi-edit-monev"]').val();
            // solusi = textarea_encode(solusi)


            var form_data = new FormData(this);
            var id_monev = $("#edit-monev-form").attr("data-monev");
            form_data.append('id_monev', id_monev)
            form_data.append('aktifitas-sudah-edit-monev', aktifitas_sudah)
            form_data.append('aktifitas-belum-edit-monev', aktifitas_belum)
            form_data.append('kendala-edit-monev', kendala)
            form_data.append('solusi-edit-monev', solusi)


            // console.log(form_data.)
            $("#monev-edit-loading-bar").show();
            $('.btn-group-edit-monev').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/edit_monev/' . $id_mou) ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {

                    if (data != 'success') {
                        // console.log(data)
                        alert('Terjadi kesalahan dalam mengupdate monev, mohon coba lagi')
                    };

                    $('#editMonevModal').modal('hide');
                    // resetSelectElement(document.getElementById('#tahun-monev'));
                    // resetSelectElement(document.getElementById('#semester-monev'));

                    // $('[name="status-kegiatan"]').val("");

                    // $('input[name=status-kegiatan]').prop('checked',false);
                    $('[name="evaluator-edit-monev"]').val("");
                    $('[name="aktifitas-sudah-edit-monev"]').val("");
                    $('[name="aktifitas-belum-edit-monev"]').val("");
                    $('[name="kendala-edit-monev"]').val("");
                    $('[name="solusi-edit-monev"]').val("");
                    // $('[name="perpanjangan"]').val("");
                    $('input[name=status-kegiatan-edit-monev]').prop('checked', false);
                    $('input[name=perpanjangan-edit-monev]').prop('checked', false);

                    $('#monev-edit-loading-bar').hide();
                    $('.btn-group-edit-monev').show();

                    show_monev();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        $('#deletemonevModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('monev') // Extract info from data-* attributes
            // console.log(recipient)
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            // modal.find('.modal-title').text('New message to ' + recipient)  
            // $('#nomonevTitle').html(recipient)
            $('#btnDeletemonev').val(recipient)
        })

        $('#btnDeletemonev').on('click', function(e) {
            e.preventDefault();

            var id_monev = this.value;
            // console.log(id_monev)

            $("#monev-delete-loading-bar").show();
            $('#btnGroupDeletemonev').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/delete_monev/') ?>" + id_monev,
                dataType: 'JSON',
                success: function(data) {

                    console.log(data)

                    if (data == 'error') {
                        alert('Terjadi kesalahan dalam menghapus monev, mohon coba lagi')
                    };
                    $('#deletemonevModal').modal('hide');
                    $('#monev-delete-loading-bar').hide();
                    $('#btnGroupDeletemonev').show();

                    show_monev();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

    });



    function copyInnerHTML(elementId) {
        // Get the element
        const element = document.getElementById(elementId);

        // Create a temporary input element
        const tempInput = document.createElement("input");

        // Set the value of the input element to the innerHTML of the original element
        tempInput.value = element.innerHTML;

        // Add the input element to the document
        document.body.appendChild(tempInput);

        // Select the text in the input element
        tempInput.select();

        // Copy the selected text to the clipboard
        document.execCommand("copy");

        // Remove the input element from the document
        document.body.removeChild(tempInput);
    }
</script>
<?= $this->endSection() ?>
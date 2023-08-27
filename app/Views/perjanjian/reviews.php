<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
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
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="text text-lg font-weight-bold m-0">Review </h1>
            </div>
            <div class="col-sm-12">
                <h3 class="text text-md font-weight-light m-0">Kerja Sama <span class="text text-md font-weight-normal"><?= $judul_kerjasama ?></span> </h3>
            </div>
        </div>
    </div>
</div>
<div class="content" id="reviews">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>
                    <?php
                    $akses = [
                        'download_final' => $id_versi_final ? '' : 'hidden',
                        'ajukan_revisi' => $status_mou >= 2  ? 'hidden' : '',
                        'finalisasi' => $status_mou >= 2  ? 'hidden' : ''
                    ];
                    ?>
                    <a <?= $akses['ajukan_revisi'] ?> style="float: left;" class="btn btn-success mr-2" href="#" title="Ajukan Revisi" id="btnUploadRevisi" data-toggle="modal" data-target="#modal-revisi"><i class=" fa-solid fa-upload">
                        </i> Ajukan Revisi</a>


                    <a <?= $akses['finalisasi'] ?> data-toggle="modal" data-target="#modal-finalisasi" class="btn btn-danger mr-2" title="Finalisasi" id="btnUploadFinalisasi"> <i class="fa-solid fa-gavel"></i> Finalisasi </a>


                    <div class="btn-group dropdown ml-2" style="float: right;">
                        <a class="btn btn-primary" href="<?php echo base_url('perjanjian/download/docx/' . $id_versi_asli) ?>" title="Download Versi Asli" id="btnDownloadAsli"><i class=" fa-solid fa-download">
                                <style>
                                    i {
                                        margin: 4px 4px 4px 4px;
                                    }
                                </style>
                            </i> Download Versi Awal</a>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?php echo base_url('perjanjian/download/docx/' . $id_versi_asli) ?>">Microsoft Word (.docx)</a>
                            <a class="dropdown-item" href="<?php echo base_url('perjanjian/download/pdf/' . $id_versi_asli) ?>">PDF Document (.pdf)</a>
                        </div>
                    </div>

                    <div style="float: left;" class="btn-group dropdopwn" id="btnDownloadFinal" <?= $akses['download_final'] ?>>
                        <a id="linkDownloadFinal" class="btn btn-danger" href="<?php echo base_url('perjanjian/download/pdf/' . $id_versi_final) ?>" title="Download Versi Final"><i class=" fa-solid fa-download">
                                <style>
                                    i {
                                        margin: 4px 4px 4px 4px;
                                    }
                                </style>
                            </i> Download Versi Final</a>
                        <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a id="linkDownloadFinalDropdownDocx" class="dropdown-item" href="<?php echo base_url('perjanjian/download/docx/' . $id_versi_final) ?>">Microsoft Word (.docx)</a>
                            <a id="linkDownloadFinalDropdownPDF" class="dropdown-item" href="<?php echo base_url('perjanjian/download/pdf/' . $id_versi_final) ?>">PDF Document (.pdf)</a>
                        </div>
                    </div>


                <?php } ?>
            </div>
        </div>


        <div class="col mx-auto" id="errorWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Terjadi Kesalahan dalam memuat data, mohon coba lagi</div>

                <button type="button" class="btn btn-primary" onclick="reloadRevisi()"><i class="fa-solid fa-rotate-right"></i> Reload</button>

            </center>

        </div>

        <div class="col mx-auto" id="loadingRevisi" style="align-self: center; display: none;">

            <center>
                <div class="spinner-border mt-3 text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="h3 mt-3 col text-secondary" style="float: left;"> Sedang Memuat...
                </div>

            </center>

        </div>

        <div class="revisiContent" style="display: none;">



            <div class="jumbotron jumbotron-fluid p-3 mb-3">
                <div class="col mx-auto">
                    <h1 class="text text-lg"> <i class="fa-solid fa-circle-info text-left"></i> Petunjuk </h1>
                    <p class="lead text text-md font-weight-light text-left">
                        Untuk melihat dokumen kerjasama di <span class="text font-weight-normal">Google Docs</span> dapat mengggunakan <span class="text font-weight-normal">akun PIC UGM</span> yang bersangkutan dengan kerjasama ini atau
                        <span class="text font-weight-normal">akun yang memiliki wewenang. Daftar akun bisa dilihat <a style="cursor: pointer;" class="text text-primary" data-toggle="modal" data-target="#akun2-modal">disini</a>.</span><br>
                        <!-- <span class="text text-md font-weight-light">Email: <span class="text text-md font-weight-normal"> <?= $email_pic_ugm ?> </span></span> -->
                    </p>
                    <div class="text text-sm font-weight-normal"><span class="text-sm bg-primary p-1 mr-2"><i class="fa-solid fa-eye"></i></span> Gunakan docs ini untuk melihat dokumen asli saat diupload.</div>
                    <div class="text text-sm font-weight-normal"><span class="text-sm bg-warning p-1 mr-2"><i class="fa-solid fa-eye"></i></span> Gunakan docs ini untuk memberi komentar atau saran edit. </div>
                </div>
            </div>


            <div id="revisiTable">
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
                                        <th style="width: 8%;">
                                            Revisi ke-
                                        </th>
                                        <th style="width: 30%; overflow-wrap: break-word;">
                                            Keterangan
                                        </th>
                                        <th style="width: 12%;">
                                            Status Revisi
                                        </th>
                                        <th style="width: 12%;">
                                            Tanggal Revisi Diajukan
                                        </th>

                                        <th style="width: 16%;">Dokumen Original</th>
                                        <th style="width: 16%;">Dokumen Untuk Dikomentari</th>
                                    </tr>
                                </thead>
                                <tbody id="revisi-table">

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>



<?= $this->section('css') ?>
<?= $this->endSection() ?>



<?= $this->section('js') ?>

<!-- Modal Upload Revisi -->
<div class="modal fade" id="modal-revisi" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" id="revisi-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiModalLabel">Upload Dokumen Revisi Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="jumbotron jumbotron-fluid p-3 mb-3">
                        <div class="col mx-auto">
                            <h3 class="text text-md"> <i class="fa-solid fa-circle-info text-left"></i> Petunjuk </h3>
                            <p class="lead text text-md font-weight-light text-left">
                                Setelah revisi diajukan maka status revisi sebelumnya akan menjadi <span class="badge bg-success">Revisi Selesai</span> . <br>
                                Gunakan dokumen <span class="badge bg-primary">Revisi Diajukan</span> untuk memberikan review terbaru.
                            </p>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label><span style="color:red;">*</span>
                        <textarea class="form-control" rows="5" id="keterangan" name="keterangan" placeholder="Masukkan keterangan/deskripsi revisi" value="" autocomplete="off" required></textarea>
                        <input class="form-control" type="hidden" id="link" name="link" value="" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="">File Revisi Kerja Sama (docx maks. 2MB)</label><span style="color:red;">*</span>
                        <input type="file" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" id="dokumen-mou" name="dokumen-mou" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }" required>
                    </div>

                </div>

                <div class="modal-footer">

                    <div id="revisi-upload-loading-bar" style="display: none;">
                        <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                        </div>
                        <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-upload-revisi" name="btn-upload-revisi" class="btn btn-primary">Upload & Ajukan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal upload finlaiasasi -->
<div class="modal fade" id="modal-finalisasi" tabindex="-1" aria-labelledby="finalisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" id="finalisasi-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalsasiModalLabel">Finalisasi Dokumen Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div style="background: rgba(255, 231, 205, 0.5) !important;" class="jumbotron jumbotron-fluid p-3 mb-3">
                        <div class="col mx-auto">
                            <h1 class="text text-lg">
                                <i class="fa-solid fa-triangle-exclamation"></i> Peringatan
                            </h1>
                            <p class="lead text text-md font-weight-light text-left">
                                Pastikan data kerja sama sudah diisi dengan benar dan dokumen final yang akan diunggah benar, Setelah Finalisasi data dan dokumen kerja sama <span class="font-weight-normal">tidak dapat diubah/direvisi kembali.</span> <br>
                            </p>
                            <a class="btn btn-warning btn-xs mt-1 mb-3" href="<?= base_url("perjanjian/edit/" . $id_mou); ?>"><i class=" fa-solid fa-pen-to-square"></i> Edit Kerja Sama</a>
                        </div>
                    </div>





                    <div class="form-group">
                        <label>Keterangan Finalisasi</label><span style="color:red;">*</span>
                        <textarea class="form-control" rows="5" id="keterangan-finalisasi" name="keterangan-finalisasi" placeholder="Masukkan keterangan/deskripsi finalisasi" value="" autocomplete="off" required></textarea>
                        <input class="form-control" type="hidden" id="link" name="link" value="" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="">File Finalisasi Dokumen Kerja Sama (docx maks. 2MB)</label><span style="color:red;">*</span>
                        <input type="file" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" id="dokumen-finalisasi-mou" name="dokumen-finalisasi-mou" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }" required>
                    </div>

                    <div class="row mt-1">
                        <div class="col-auto">
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="checkbox" value="" id="Check1">
                                <label class="form-check-label text-sm font-italic" for="Check1">
                                    Tandai checkbox di samping untuk mengkonfirmasi data di dalam detail kerja sama sudah benar dan tidak akan diubah kembali
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">

                    <div id="finalisasi-upload-loading-bar" style="display: none;">
                        <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                        </div>
                        <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-upload-finalisasi" name="btn-upload-finalisasi" class="btn btn-danger">Finalisasi</button>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- Modal upload finlaiasasi -->
<div class="modal fade" id="modal-share" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" id="share-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalsasiModalLabel">Share Dokumen Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div style="background: rgba(255, 231, 205, 0.5) !important;" class="jumbotron jumbotron-fluid p-3 mb-3">
                        <div class="col mx-auto">
                            <h1 class="text text-lg"> <i class="fa-solid fa-triangle-exclamation"></i> Peringatan </h1>
                            <p class="lead text text-md font-weight-light text-left">
                                PERINGATAN!, dengan mengirim akses dokumen ini ke akun orang lain maka orang lain dapat <span class="font-weight-normal">melihat</span> dokumen ini melalui Google Docs. <br>

                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Email</label><span style="color:red;">*</span>
                        <input type="email" id="email-shared" name="email-shared" placeholder="example@mail.com" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="">Pesan</label>
                        <textarea class="form-control" rows="3" id="pesan-shared" name="pesan-shared" placeholder="Masukkan pesan (opsional)" value="" autocomplete="off"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Tipe Akses</label>
                        <input type="text" id="access-type-view" name="access-type-view" value="" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Dokumen</label>
                        <input type="text" id="nama-dokumen-view" name="nama-dokumen-view" value="" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <input hidden type="text" id="access-type" name="access-type" value="" class="form-control" required readonly>
                        <input hidden type="text" id="id_gdrive_dokumen" name="id_gdrive_dokumen" value="" class="form-control" required readonly>
                    </div>

                </div>

                <div class="modal-footer">

                    <div id="share-access-loading-bar" style="display: none;">
                        <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                        </div>
                        <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-share-submit" name="btn-share-submit" class="btn btn-primary">Share</button>
                </div>

            </div>
        </form>
    </div>
</div>

<div id="akun2-modal" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Akun</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="jumbotron jumbotron-fluid p-3 mb-3">
                    <div class="col mx-auto">
                        <p class="lead text text-md font-weight-normal text-left">
                            Dokumen Kerja sama dapat diakses melalui Google Docs. Berikut akun-akun yang dapat mengakses dokumen kerja sama ini di Google Docs secara default.<br>
                        </p>
                    </div>
                </div>
                <div class="daftar-akun">
                    <div class="row">
                        <div class="col-1">
                            1.
                        </div>
                        <div class="col">
                            Dekan
                        </div>
                        <div class="col">
                            (dekan.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            2.
                        </div>
                        <div class="col">
                            Wakil Dekan 1
                        </div>
                        <div class="col">
                            (wd1.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            3.
                        </div>
                        <div class="col">
                            Wakil Dekan 2
                        </div>
                        <div class="col">
                            (wd2.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            4.
                        </div>
                        <div class="col">
                            Wakil Dekan 3
                        </div>
                        <div class="col">
                            (wd3.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            5.
                        </div>
                        <div class="col">
                            Wakil Dekan 4
                        </div>
                        <div class="col">
                            (wd4.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            6.
                        </div>
                        <div class="col">
                            OIA
                        </div>
                        <div class="col">
                            (oia.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            7.
                        </div>
                        <div class="col">
                            Sekretaris Dekan
                        </div>
                        <div class="col">
                            (setdekan.mipa@ugm.ac.id) / (sri_retno@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            8.
                        </div>
                        <div class="col">
                            KKA
                        </div>
                        <div class="col">
                            (kka.mipa@ugm.ac.id)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            9.
                        </div>
                        <div class="col">
                            PIC UGM : <?= $nama_pic_ugm ?>
                        </div>
                        <div class="col">
                            (<?= $email_pic_ugm ?>)
                        </div>
                    </div>
                    <?php if (count($emails_default)) : ?>
                        <div class="row">
                            <div class="col-1">
                                10.
                            </div>
                            <div class="col">
                                Kepala <?= $departemen_ugm ?>
                            </div>
                            <div class="col">
                                (<?= $emails_default[0] ?? 'Tidak ada' ?>)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1">
                                11.
                            </div>
                            <div class="col">
                                Sekretaris <?= $departemen_ugm ?>
                            </div>
                            <div class="col">
                                (<?= $emails_default[1] ?? 'Tidak ada' ?>)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1">
                                12.
                            </div>
                            <div class="col">
                                Admin <?= $departemen_ugm ?>
                            </div>
                            <div class="col">
                                (<?= $emails_default[2] ?? 'Tidak ada' ?>)
                            </div>
                        </div>  
                    <?php endif  ?>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="notification-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notifikasi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="alertMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- sdadasdsad -->
<!-- <a href="#" onclick="()=>{show_finalisasi_modal()}" class="btn btn-danger" title="Finalisasi"><i class=" fa-solid fa-circle-check"></i></a> -->


<script src="<?= base_url('pdf.js'); ?>"></script>
<script>
    <?= base_url('revisi/toggle'); ?>
</script>
<script>
    jQuery.fn.visible = function() {
        return this.css('visibility', 'visible');
    };



    $(document).ready(function() {
        show_revisi();


        // disable the button by default
        $('#btn-upload-finalisasi').prop('disabled', true)
        $('#modal-finalisasi').on('shown.bs.modal', function() {
            if ($('#Check1').is(':checked')) {
                $('#btn-upload-finalisasi').prop('disabled', false); // enable the button if checkbox is checked
            } else {
                $('#btn-upload-finalisasi').prop('disabled', true); // disable the button if checkbox is unchecked
            }
        });



        $('#Check1').change(function() {
            if ($(this).is(':checked')) {
                $('#btn-upload-finalisasi').prop('disabled', false); // enable the button if checkbox is checked
            } else {
                $('#btn-upload-finalisasi').prop('disabled', true); // disable the button if checkbox is unchecked
            }
        });

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

        function show_loading(isShowed = true) {
            if (isShowed) {
                $("#loadingRevisi").show();
            } else {
                $("#loadingRevisi").hide();
            }
        }

        function show_revisiTable(isShowed = true) {
            if (isShowed) {
                $(".revisiContent").show();
            } else {
                $(".revisiContent").hide();
            }
        }

        function textarea_encode(text) {
            return text.replace(/\r\n|\r|\n/g, "<br />");
        }

        function textarea_decode(text) {
            return text.replace(/<br\s?\/?>/g, "\n");
        }

        function show_revisi() {
            show_loading();
            show_revisiTable(false);
            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('perjanjian/reviews_get_json/' . $id_mou . '/') ?>",
                async: true,
                dataType: 'json',
                success: function(data) {
                    if (data == 'error') {
                        show_error()
                        show_loading(false)
                        show_revisiTable(false)
                    } else {

                        var status_revisi = {
                            //ASLI
                            '0': text_bg('secondary', 'light', 'Dokumen Awal'),
                            //DIAJUKAN
                            '2': text_bg('primary', 'light', 'Revisi Diajukan'),

                            //DIKOMENTARI
                            '0**': `${text_bg('warning', 'dark', 'Dikomentari')} <br>
                        ${text_bg('secondary', 'light', 'Dokumen Awal')} `,
                            '3': text_bg('warning', 'dark', 'Dikomentari'),

                            //TELAH DIREVISI
                            '0*': `${text_bg('success', 'light', 'Revisi Selesai')} <br>
                        ${text_bg('secondary', 'light', 'Dokumen Awal')} <br>
                        `,
                            '1': text_bg('success', 'light', 'Revisi Selesai'),

                            //FINAL
                            '4': text_bg('danger', 'light', 'Final'),
                            '4*': `${text_bg('danger', 'light', 'Final')} <br>
                          ${text_bg('secondary', 'light', 'Dokumen Awal')}
                        `
                        };

                        var isReviewed = data['status_mou'] >= 2;
                        var akses_finalisasi = isReviewed ? 'hidden' : '';
                        if (isReviewed) {
                            $('#btnUploadRevisi').hide();
                            $('#btnUploadFinalisasi').hide();
                            $('#btnDownloadFinal').removeAttr('hidden');
                            let versi_final_docx = '<?= site_url('perjanjian/download/docx/') ?>' + data['id_versi_final']
                            let versi_final_pdf = '<?= site_url('perjanjian/download/pdf/') ?>' + data['id_versi_final']
                            $('#linkDownloadFinal').attr('href', versi_final_pdf);
                            $('#linkDownloadFinalDropdownDocx').attr('href', versi_final_docx);
                            $('#linkDownloadFinalDropdownPDF').attr('href', versi_final_pdf);
                        }

                        var html = '';
                        var i;
                        var j = 1;
                        var data_count = data['rows'].length;



                        for (i = 0; i < data_count; i++) {
                            let id_revisi = data['rows'][i]['id_revisi_mou'] == -1 ? 'Final' : data['rows'][i]['id_revisi_mou'];
                            let keterangan = data['rows'][i]['keterangan'];
                            let status_revisi_data = data['rows'][i]['status_revisi'];
                            let tanggal_revisi = data['rows'][i]['tanggal_revisi'];
                            let id_gdrive_dokumen_ori = data['rows'][i]['id_gdrive_dokumen_ori'];
                            let id_gdrive_dokumen = data['rows'][i]['id_gdrive_dokumen'];
                            let url_view_dokumen_ori = data['rows'][i]['url_view_dokumen_ori']
                            let url_view_dokumen = data['rows'][i]['url_view_dokumen']

                            let url_download_dokumen_ori_docx = `<?= site_url('perjanjian/download/docx/') ?>${id_gdrive_dokumen_ori}`
                            let url_download_dokumen_docx = `<?= site_url('perjanjian/download/docx/') ?>${id_gdrive_dokumen}`
                            let url_download_dokumen_ori_pdf = `<?= site_url('perjanjian/download/pdf/') ?>${id_gdrive_dokumen_ori}`
                            let url_download_dokumen_pdf = `<?= site_url('perjanjian/download/pdf/') ?>${id_gdrive_dokumen}`

                            let dokumen_ori = `
                            <div class="btn-group btn-group-sm" role="group">
                                        
                                            <form action="${url_download_dokumen_ori_docx}" method="GET" enctype="multipart/form-data">
                                                <button type="submit" class="btn btn-success" title="Download"><i class=" fa-solid fa-download"></i></button>
                                            </form>
                                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="${url_download_dokumen_ori_docx}">Microsoft Word (.docx)</a>
                                            <a class="dropdown-item" href="${url_download_dokumen_ori_pdf}">PDF Document (.pdf)</a>
                                        </div>

                                        <a href="${url_view_dokumen_ori}" target="_blank" rel="noopener noreferrer" class="btn btn-primary" title="Lihat di Google Docs"><i class=" fa-solid fa-eye"></i></a>
                                        <button type="button" data-revisi="${id_revisi}" data-access_type="1" data-review="${id_gdrive_dokumen_ori}" class="btn btn-info" title="Share Original" data-toggle="modal" data-target="#modal-share"><i class="fa-solid fa-share-nodes"></i></button>

                            </div> <br>
                            `

                            let dokumen_dikomentari = id_revisi == 'Final' ? '' : `
                            <div class="btn-group btn-group-sm" role="group">
                                        
                                            <form action="${url_download_dokumen_docx}" method="GET" enctype="multipart/form-data">
                                                <button type="submit" class="btn btn-success" title="Download"><i class=" fa-solid fa-download"></i></button>
                                            </form>
                                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                            <a class="dropdown-item" href="${url_download_dokumen_docx}">Microsoft Word (.docx)</a>
                                            <a class="dropdown-item" href="${url_download_dokumen_pdf}">PDF Document (.pdf)</a>
                                        </div>

                                            <a href="${url_view_dokumen}" target="_blank" rel="noopener noreferrer" class="btn btn-warning" title="Komentari di Google Docs"><i class=" fa-solid fa-eye"></i></a>
                                            <button type="button" data-revisi="${id_revisi}" data-access_type="2" data-review="${id_gdrive_dokumen}" class="btn btn-info" title="Share" data-toggle="modal" data-target="#modal-share"><i class="fa-solid fa-share-nodes"></i></button>
                                        </div> <br>
                            `
                            html += `
                        <tr>
                                    <th scope="row">${j++}</th>
                                    <td>
                                        ${id_revisi}
                                    </td>
                                    <td style="overflow-wrap: break-word;" >
                                        ${keterangan}
                                    </td>
                                    <td>
                                        ${status_revisi[status_revisi_data]}
                                    </td>
                                    <td>
                                        ${tanggal_revisi}
                                    </td>
                                    <td>
                                    
                                        ${dokumen_ori}
                                        
                                    </td>
                                    <td>
                                    
                                        ${dokumen_dikomentari}
                                        
                                    </td>
                                    
                                </tr>
                        `;

                        }
                        show_loading(false);
                        show_revisiTable();
                        $('#revisi-table').html(html);
                    }
                }
            })
        }

        // function show_notifikasi(msg) {
        //     var html = `${msg}`
        //     $('#notifikasi-msg').html(msg);
        //     $(notifikasi).toast({
        //         delay: 5000
        //     });
        //     $(notifikasi).toast('show');
        // }

        // $('#btnUploadRevisi').on('click', function(e) {
        //     show_notifikasi('Halo ini notif')
        // })

        $('#revisi-form').on('submit', function(e) {
            e.preventDefault();
            var keterangan = document.getElementById('keterangan').value;
            var text = textarea_encode(keterangan);
            // var text = keterangan.replace(/\n/g, '[n1]');
            // document.getElementById('keterangan').value = text


            var form_data = new FormData(this);
            form_data.append('keterangan', text)
            $("#revisi-upload-loading-bar").show();
            $('#btn-upload-revisi').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/upload_revisi2/' . $id_mou) ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {
                    if (data == 'error') {
                        alert('Terjadi Kesalahan, Mohon Coba Lagi');
                    }
                    $('#modal-revisi').modal('hide');
                    $('[name="keterangan"]').val("");
                    $('[name="dokumen-mou"]').val("");
                    $('#revisi-upload-loading-bar').hide();
                    $('#btn-upload-revisi').show();
                    // console.log(data);
                    // console.log('SUCCESS');
                    show_revisi();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        $('#finalisasi-form').on('submit', function(e) {
            e.preventDefault();
            var keterangan = document.getElementById('keterangan-finalisasi').value;
            var text = textarea_encode(keterangan);
            // var text = keterangan.replace(/\n/g, '[n1]');
            // document.getElementById('keterangan').value = text


            var form_data = new FormData(this);
            form_data.append('keterangan-finalisasi', text)
            $("#finalisasi-upload-loading-bar").show();
            $('#btn-upload-finalisasi').hide();

            $.ajax({
                type: "POST",
                // url: "<?php echo site_url('perjanjian/upload_revisi/' . $id_mou) ?>",
                url: "<?php echo site_url('perjanjian/upload_revisi2/' . $id_mou . '/1') ?>",
                // url: "<?= base_url('perjanjian/upload_revisi2') ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data)
                    if (data == 'error') {
                        alert('Terjadi Kesalahan, Mohon Coba Lagi');
                    }

                    $('#modal-finalisasi').modal('hide');
                    $('[name="keterangan-finalisasi"]').val("");
                    $('[name="dokumen-finalisasi-mou"]').val("");
                    $('#finalisasi-upload-loading-bar').hide();
                    $('#btn-upload-finalisasi').show();
                    // console.log(data);
                    // console.log('SUCCESS');
                    show_revisi();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        $('#modal-share').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var access_type = button.data('access_type') // Extract info from data-* attributes
            var id_gdrive_dokumen = button.data('review') // Extract info from data-* attributes
            var revisi = button.data('revisi')

            // $('#noRevisiTitle').html(recipient)
            $('#access-type').val(access_type)
            $('#id_gdrive_dokumen').val(id_gdrive_dokumen)
            $('#access-type-view').val(access_type == 2 ? 'Commenter' : 'Viewer')
            $('#nama-dokumen-view').val(`Revisi ke- ${revisi}`)
        })

        $('#share-form').on('submit', function(e) {
            e.preventDefault();

            var form_data = new FormData(this);

            $('#share-access-loading-bar').show();
            $('#btn-share-submit').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/share_access_to_email') ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data)
                    if (data == 'error') {
                        alert('Terjadi Kesalahan, Mohon Coba Lagi');
                    }

                    $('#modal-share').modal('hide');
                    $('[name="email-shared"]').val("");
                    $('[name="pesan-shared"]').val("");
                    $('#share-access-loading-bar').hide();
                    $('#btn-share-submit').show();

                    // console.log(data); 

                    if (data == 'success') {
                        $('#alertMessage').text("Dokumen berhasil dibagikan");
                        $("#notification-modal").modal("show");
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });

            e.preventDefault();
        });



        $('#finalisasiModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('revisi') // Extract info from data-* attributes
            console.log(recipient)
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            // modal.find('.modal-title').text('New message to ' + recipient)  
            // $('#noRevisiTitle').html(recipient)
            // $('#btnFinalisasi').val(recipient)
        })

        $('#btnFinalisasi').on('click', function(e) {
            e.preventDefault();

            var id_revisi = this.value;

            $.ajax({
                type: "POST",
                // url: "<?php echo site_url('perjanjian/upload_revisi/' . $id_mou) ?>",
                url: `<?php echo site_url('perjanjian/finalisasi_review/' . $id_mou . '/') ?> ${id_revisi}`,
                // url: "<?= base_url('perjanjian/upload_revisi2') ?>",
                // data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {
                    if (data == 'error') {
                        alert('Terjadi Kesalahan, Mohon Coba Lagi');
                    }
                    $('#finalisasiModal').modal('hide');
                    // $('#btn-upload-revisi').show();
                    // console.log(data);
                    // console.log('SUCCESS');
                    show_revisi();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        var reload = function reload() {
            show_error(false)
            show_revisi()
        }

        window.reloadRevisi = reload
    });
</script>
<?= $this->endSection() ?>
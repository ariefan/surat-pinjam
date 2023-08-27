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
    <a class="nav-item nav-link" href="<?= site_url("perjanjian"); ?>"><i class="fa-solid fa-arrow-left"></i> </a>

    <a class="nav-item nav-link <?= $akses['details'] ?> <?= $active['details'] ?>" href="<?= site_url("perjanjian/details/" . $id_mou); ?>">Detail</a>
    <a class="nav-item nav-link <?= $akses['reviews'] ?> <?= $active['reviews'] ?>" href="<?= site_url("perjanjian/reviews/" . $id_mou); ?>">Review</a>
    <a class="nav-item nav-link <?= $akses['luaran'] ?> <?= $active['luaran'] ?>" href="<?= site_url("perjanjian/luaran/" . $id_mou); ?>">Luaran</a>
    <a class="nav-item nav-link <?= $akses['monev'] ?> <?= $active['monev'] ?>" href="<?= site_url("perjanjian/monev/" . $id_mou); ?>">Monitoring dan Evaluasi</a>
</nav>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="text text-lg font-weight-bold m-0">Luaran </h1>
            </div>
            <div class="col-sm-12">
                <h3 class="text text-md font-weight-light m-0">Kerja Sama <span class="text text-md font-weight-normal m-0"><?= $judul_kerjasama ?></span></h3>
            </div>
        </div>
    </div>
</div>
<div class="content" id="luaran">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>

                    <a style="float: left;" class="btn btn-success mr-2" href="#" title="Tambah Luaran" id="btnUploadLuaran" data-toggle="modal" data-target="#modal-luaran"><i class=" fa-solid fa-upload">
                        </i> Tambah Luaran</a>

                <?php } ?>
            </div>
        </div>

        <div class="col mx-auto" id="errorWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Terjadi Kesalahan dalam memuat data, mohon coba lagi</div>

                <button type="button" class="btn btn-primary" onclick="reloadLuaran()"><i class="fa-solid fa-rotate-right"></i> Reload</button>

            </center>

        </div>

        <div class="col mx-auto" id="emptyWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Belum ada Luaran, silahkan dapat menambahkan</div>

                <!-- <button type="button" class="btn btn-primary" onclick="reloadmonev()"><i class="fa-solid fa-rotate-right"></i> Reload</button> -->
            </center>

        </div>

        <div class="col mx-auto" id="loadingLuaran" style="align-self: center; display: none;">

            <center>
                <div class="spinner-border mt-3 text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="h3 mt-3 col text-secondary" style="float: left;"> Sedang Memuat...
                </div>

            </center>

        </div>



        <div id="luaranTable">
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
                                        Tanggal
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Nama Luaran
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Deskripsi
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Bentuk Kegiatan
                                    </th>
                                    <th style="width: 10%;">
                                        Jumlah
                                    </th>
                                    <th style="width: 10%; overflow-wrap: break-word;">
                                        Satuan
                                    </th>

                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="luaran-table">

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

<div class="modal fade" id="deleteLuaranModal" tabindex="-1" role="dialog" aria-labelledby="deleteLuaranModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLuaranModalLabel">Hapus luaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin ingin menghapus luaran ini?
            </div>
            <div class="modal-footer">
                <div id="luaran-delete-loading-bar" style="display: none;">
                    <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                    </div>
                    <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div id="btnGroupDeleteLuaran">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button id="btnDeleteLuaran" value="" type="button" class="btn btn-danger">Hapus</button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Upload luaran -->
<div class="modal fade" id="modal-luaran" tabindex="-1" aria-labelledby="luaranModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" id="luaran-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="luaranModalLabel">Upload Luaran Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Luaran</label><span style="color:red;">*</span>
                        <input type="text" id="nama-luaran" name="nama-luaran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label><span style="color:red;">*</span>
                        <textarea class="form-control" rows="5  " id="deskripsi" name="deskripsi" placeholder="Masukkan keterangan/deskripsi luaran" value="" autocomplete="off" required></textarea>
                        <input class="form-control" type="hidden" id="link" name="link" value="" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="">Bentuk Kegiatan</label><span style="color:red;">*</span>
                        <input type="text" id="bentuk-kegiatan" name="bentuk-kegiatan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Luaran</label><span style="color:red;">*</span>
                        <input type="number" id="jumlah-luaran" name="jumlah-luaran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Satuan</label><span style="color:red;">*</span>
                        <input type="text" id="satuan" name="satuan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">File Luaran Kerjasama (PDF maks. 2MB)</label><span style="color:red;">*</span>
                        <input type="file" accept="application/pdf" id="dokumen-luaran" name="dokumen-luaran" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }" required>
                    </div>


                    <div class="modal-footer">

                        <div id="luaran-upload-loading-bar" style="display: none;">
                            <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                            </div>
                            <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                                <span class="sr-only">Loading...</span>
                            </div>

                        </div>

                        <button type="submit" id="btn-upload-luaran" name="btn-upload-luaran" class="btn btn-primary"> Submit </button>
                    </div>

                </div>
        </form>
    </div>
</div>



<script src="<?= base_url('pdf.js'); ?>"></script>
<script>
    <?= base_url('luaran/toggle'); ?>
</script>







</tr>
<script>
    jQuery.fn.visible = function() {
        return this.css('visibility', 'visible');
    };

    // const toast = document.getElementById('notice');

    // Add hover effect to cells


    $(document).ready(function() {
        show_luaran();

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
                $("#loadingLuaran").show();
            } else {
                $("#loadingLuaran").hide();
            }
        }

        function show_luaranTable(isShowed = true) {
            if (isShowed) {
                $("#luaranTable").show();
            } else {
                $("#luaranTable").hide();
            }
        }

        var reload = function reload() {
            show_error(false)
            show_luaran()
        }

        function textarea_encode(text) {
            return text.replace(/\r\n|\r|\n/g, "<br />");
        }

        function textarea_decode(text) {
            return text.replace(/<br\s?\/?>/g, "\n");
        }

        window.reloadLuaran = reload


        function show_luaran() {
            show_loading();
            show_luaranTable(false);
            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('perjanjian/luaran_get_json/' . $id_mou . '/') ?>",
                async: true,
                dataType: 'json',
                success: function(data) {

                    var html = ``;
                    var i;
                    var j = 1;
                    var rows = data['rows'];
                    var data_count = rows.length;
                    // console.log(rows)

                    if (data_count == 0) {
                        show_empty()
                        show_loading(false)
                        show_monevTable(false)
                    } else {
                        for (i = 0; i < data_count; i++) {

                            // let luaranDiv = $('.luaran-row').clone(true);

                            // luaranDiv.attr('class', 'luaran-row-set')
                            // luaranDiv.removeAttr('hidden')

                            // luaranDiv.children('.luaran-nama').html(rows['nama_luaran']);
                            // luaranDiv.children('.luaran-deskripsi').html(rows['deskripsi']);

                            // html.append(luaranDiv);

                            html += `
                        <tr class="luaran-row">
                                <th scope="row" class="luaran-no"> ${j++} </th>
                                <td class="luaran-cell luaran-tanggal">
                                    ${rows[i]['created_at']}
                                </td>
                                <td class="luaran-cell luaran-nama">
                                    ${rows[i]['nama_luaran']}
                                </td>
                                <td class="luaran-cell luaran-deskripsi"  style="overflow-wrap: break-word;">
                                    ${rows[i]['deskripsi_luaran']}
                                </td>
                                <td class="luaran-cell luaran-bentuk-kegiatan">
                                    ${rows[i]['bentuk_kegiatan']}
                                </td>
                                <td class="luaran-cell luaran-jumlah">
                                    ${rows[i]['jumlah_luaran']}
                                </td>
                                <td class="luaran-cell luaran-satuan">
                                ${rows[i]['satuan']}

                                </td>
                                <td class="luaran-aksi">
                                <div class="btn-group btn-group-sm" role="group">
                                    <form class="luaran-aksi-download" action="<?php echo site_url('perjanjian/download_luaran/') ?>${rows[i]['id_luaran']}" method="GET" enctype="multipart/form-data">
                                        <button type="submit" class="btn btn-success" title="Download"><i class=" fa-solid fa-download"></i></button>
                                    </form>
                                    <form>
                                        <button type="button" data-luaran="${data['rows'][i]['id_luaran']}" class="btn btn-danger" title="Delete" data-toggle="modal" data-target="#deleteLuaranModal"><i class=" fa-solid fa-trash"></i></button>
                                    </form>


                                    </div>    

                                </td>

                     `
                        }
                        show_empty(false)
                        show_loading(false);
                        show_luaranTable()
                        $('#luaran-table').html(html)

                        // add copy click
                        const toast = document.getElementById('notice');
                        const cells = document.getElementsByClassName('luaran-cell');
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
            })
        }


        $('#luaran-form').on('submit', function(e) {
            e.preventDefault();

            let deskripsi = $('[name="deskripsi"]').val();
            deskripsi = textarea_encode(deskripsi);

            var form_data = new FormData(this);
            form_data.append('deskripsi', deskripsi);
            $("#luaran-upload-loading-bar").show();
            $('#btn-upload-luaran').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/upload_luaran/' . $id_mou) ?>",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {

                    if (data == 'error') {
                        alert('Terjadi kesalahan dalam mengupload luaran, mohon coba lagi')
                    };
                    $('#modal-luaran').modal('hide');
                    $('[name="nama-luaran"]').val("");
                    $('[name="deskripsi"]').val("");
                    $('[name="bentuk-kegiatan"]').val("");
                    $('[name="jumlah-luaran"]').val("");
                    $('[name="satuan"]').val("");
                    $('[name="dokumen-luaran"]').val("");
                    $('#luaran-upload-loading-bar').hide();
                    $('#btn-upload-luaran').show();

                    show_luaran();

                },
                error: function(e) {
                    console.log(e);
                }
            });


            e.preventDefault();
        });

        $('#deleteLuaranModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('luaran') // Extract info from data-* attributes
            // console.log(recipient)
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            // modal.find('.modal-title').text('New message to ' + recipient)  
            // $('#noLuaranTitle').html(recipient)
            $('#btnDeleteLuaran').val(recipient)
        })

        $('#btnDeleteLuaran').on('click', function(e) {
            e.preventDefault();

            var id_luaran = this.value;

            $("#luaran-delete-loading-bar").show();
            $('#btnGroupDeleteLuaran').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/delete_luaran/' . $id_mou . '/') ?>" + id_luaran,
                dataType: 'JSON',
                success: function(data) {

                    if (data == 'error') {
                        alert('Terjadi kesalahan dalam menghapus luaran, mohon coba lagi')
                    };
                    $('#deleteLuaranModal').modal('hide');
                    $('#luaran-delete-loading-bar').hide();
                    $('#btnGroupDeleteLuaran').show();

                    show_luaran();

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
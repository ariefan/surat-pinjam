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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 mt-2">
            <div class="col-sm-12">
                <h1 class="text text-lg font-weight-bold m-0">Monitoring dan Evaluasi / Monev </h1>
            </div>
            <div class="col-sm-12">
                <h3 class="text text-md font-weight-normal m-0">Perjanjian <?= $judul_kerjasama ?></h3>
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
        <div class="form-group row">
            <div class="col-md-5">
                <select class="form-control" name="monev_semester" id="monev_semester">


                </select>
            </div>
        </div>

        <div class="col mx-auto" id="errorWarning" style="align-self: center; display: none; vertical-align: middle;">

            <center>
                <div class="h5">Terjadi Kesalahan dalam memuat data, mohon coba lagi</div>

                <button type="button" class="btn btn-primary" onclick="reloadmonev()"><i class="fa-solid fa-rotate-right"></i> Reload</button>

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



        <div id="monevTable" class="row">
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
                        <table class="table table-bordered table-valign-top table-striped table-hover" style="table-layout: fixed;
                        ">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 10%;">
                                        Evaluator
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Mekanisme
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Mulai
                                    </th>
                                    <th style="width: 15%; overflow-wrap: break-word;">
                                        Selesai
                                    </th>

                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="monev-table">

                            </tbody>
                        </table>
                        <!-- <?= str_replace(
                                    '<li >',
                                    '<li class="page-item">',
                                    str_replace(
                                        '<li class="',
                                        '<li class="page-item ',
                                        str_replace("<a ", '<a class="page-link" ', $pager->links())
                                    )
                                ); ?> -->
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

<!-- Modal Upload monev -->
<div class="modal fade" id="modal-monev" tabindex="-1" aria-labelledby="monevModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" id="monev-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="monevModalLabel">Upload monev Kerja Sama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Evaluator</label><span style="color:red;">*</span>
                        <input type="text" id="evaluator-monev" name="evaluator-monev" class="form-control" placeholder="Evaluator" required>
                    </div>
                    <div class="form-group">
                        <label>Mekanisme</label><span style="color:red;">*</span>
                        <input type="text" id="mekanisme-monev" name="mekanisme-monev" class="form-control" placeholder="Mekanisme" required>
                    </div>

                    <div class="form-group">
                        <label for="">Waktu</label><span style="color:red;">*</span>
                        <input type="datetime-local" id="w-mulai-monev" name="w-mulai-monev" class="form-control" placeholder="mulai" required>
                        <p>s/d</p>
                        <input type="datetime-local" id="w-selesai-monev" name="w-selesai-monev" class="form-control" placeholder="selesai" required>
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





<script src="<?= base_url('pdf.js'); ?>"></script>
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

        show_semesters()
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

        window.reloadmonev = reload

        var periode = ''
        var semester = ''

        function show_semesters() {

            $.ajax({
                type: 'GET',
                url: "<?php echo site_url('perjanjian/monev_list_semester/' . $id_mou . '/') ?>",
                async: true,
                dataType: 'json',
                success: function(data) {
                    let list_semester = data
                    var html = ``;
                    var i;
                    for (i = 0; i < list_semester.length; i++) {
                        let periode = list_semester[i]['tahun']
                        let semester = list_semester[i]['semester']


                        if (semester == 1) {
                            let smt_element = `<option value="${periode}-${semester}" data-periode="${periode}" data-semester="${semester}">Semester Gasal ${periode} / ${periode+1} </option>`
                            html += `${smt_element}`
                            continue;
                        } else {
                            let smt_element = `<option value="${periode}-${semester}"data-periode="${periode}" data-semester="${semester}">Semester Genap ${periode-1} / ${periode} </option>`
                            html += `${smt_element}`
                            continue;
                        }
                    }

                    console.log(html)

                    $("#monev_semester").html(html)
                }
            })


        }

        function selectElement(id, valueToSelect) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        }

        $('select').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            periode = parseInt(selectedOption.data('periode'));
            semester = parseInt(selectedOption.data('semester'));
            console.log(periode, semester)
            $(this).blur()
            show_monev()
        });




        function show_monev(latest = 0) {

            show_loading();

            show_monevTable(false);

            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('perjanjian/monev_get_json/' . $id_mou . '/') ?>",
                data: {
                    'periode_selected': periode,
                    'smt_selected': semester
                },
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
                        // console.log(rows)
                        for (i = 0; i < data_count; i++) {


                            html += `
                        <tr class="monev-row">
                                <th scope="row" class="monev-no"> ${j++} </th>
            
                                <td class="monev-cell monev-evaluator">
                                    ${rows[i]['evaluator']}
                                </td>
                                <td class="monev-cell monev-mekanisme"  style="overflow-wrap: break-word;">
                                    ${rows[i]['mekanisme']}
                                </td>
                                <td class="monev-cell monev-waktu-mulai">
                                    ${rows[i]['waktu_mulai']}
                                </td>
                                <td class="monev-cell monev-waktu-selesai">
                                    ${rows[i]['waktu_selesai']}
                                </td>
                            
                                <td class="monev-aksi">
                                <div class="btn-group btn-group-sm" role="group">
                                    <form>
                                        <button type="button" data-monev="${data['rows'][i]['id_monev']}" class="btn btn-danger" title="Delete" data-toggle="modal" data-target="#deletemonevModal"><i class=" fa-solid fa-trash"></i></button>
                                    </form>
                                    </div>    

                                </td>

                     `
                        }
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
            })
        }


        $('#monev-form').on('submit', function(e) {
            e.preventDefault();

            var form_data = new FormData(this);
            form_data.append('periode_selected', periode);
            form_data.append('smt_selected', semester);
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

                    if (data == 'error') {
                        alert('Terjadi kesalahan dalam mengupload monev, mohon coba lagi')
                    };
                    $('#modal-monev').modal('hide');
                    $('[name="evaluator-monev"]').val("");
                    $('[name="mekanisme-monev"]').val("");
                    $('[name="w-mulai-monev"]').val("");
                    $('[name="w-selesai-monev"]').val("");

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

            $("#monev-delete-loading-bar").show();
            $('#btnGroupDeletemonev').hide();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('perjanjian/delete_monev/' . $id_mou . '/') ?>" + id_monev,
                dataType: 'JSON',
                success: function(data) {

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
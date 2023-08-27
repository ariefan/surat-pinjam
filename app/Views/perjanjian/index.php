<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>

<style>
    .btn-monev {
        background-color: #749D60 !important;
    }

    .btn-monev:hover {
        background-color: #638552 !important;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Pengajuan PKS/MoU MIPA</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <a class="btn btn-success" title="Tambah" id="btnTambahST"
                    href="<?= site_url('perjanjian/create'); ?>">Tambah</a>
                <?php if ($gol_pic_mou == "2") { ?>
                    <a class="btn btn-info" title="Export to Excel" id="btnExcel" data-toggle="modal"
                        data-target="#modal-excel">Get Excel</a>
                <?php } ?>
            </div>
        </div>

        <form action="<?= site_url("perjanjian/index"); ?>" method="get" id="indexForm">
            <div class="form-group row">
                <div class="col-sm-5">
                    <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                    <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                    <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="filter_status" id="filter_status">
                        <option value="" <?= $filter_status == '' ? 'selected' : ''; ?>>Semua</option>
                        <option value="0" <?= $filter_status == '0' ? 'selected' : ''; ?>>Draft</option>
                        <option value="1" <?= $filter_status == '1' ? 'selected' : ''; ?>>Proses Review</option>
                        <option value="2" <?= $filter_status == '2' ? 'selected' : ''; ?>>Review Selesai</option>
                        <option value="3" <?= $filter_status == '3' ? 'selected' : ''; ?>>Ditandatangani</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="filter_jenis" id="filter_jenis">
                        <option value="">Semua</option>
                        <option value="MoU" <?= $filter_jenis == 'MoU' ? 'selected' : ''; ?>>MoU</option>
                        <option value="PKS" <?= $filter_jenis == 'PKS' ? 'selected' : ''; ?>>PKS</option>
                        <option value="SPK" <?= $filter_jenis == 'SPK' ? 'selected' : ''; ?>>SPK/Kontrak</option>
                        <option value="MoA" <?= $filter_jenis == 'MoA' ? 'selected' : ''; ?>>MoA</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                </div>
            </div>
        </form>



        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                        $url = site_url('suratkp/index') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                        $is_selected = $sort_column == $column_name ? '' : 'text-white';
                        $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                        return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
                    }
                        ?>
                    <table class="table table-bordered table-valign-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <?= $print_header('No Dokumen', 'no_dokumen_ugm', $q); ?>
                                </th>
                                <th>
                                    <a
                                        href="<?= site_url('perjanjian/index'); ?>?q=<?= $q; ?>&sort_column=tipe_dokumen&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Jenis</a>
                                    <i
                                        class="pl-2 <?= $sort_column == 'tipe_dokumen' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'tipe_dokumen' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a
                                        href="<?= site_url('perjanjian/index'); ?>?q=<?= $q; ?>&sort_column=judul_kerjasama&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Judul
                                        Perjanjian</a>
                                    <i
                                        class="pl-2 <?= $sort_column == 'judul_kerjasama' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'judul_kerjasama' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a
                                        href="<?= site_url('perjanjian/index'); ?>?q=<?= $q; ?>&sort_column=nama&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Pengaju
                                        (PIC)</a>
                                    <i
                                        class="pl-2 <?= $sort_column == 'pic' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'pic' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a
                                        href="<?= site_url('perjanjian/index'); ?>?q=<?= $q; ?>&sort_column=tanggal_pengajuan&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Tanggal
                                        Pengajuan</a>
                                    <i
                                        class="pl-2 <?= $sort_column == 'tanggal_pengajuan' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'tanggal_pengajuan' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>Periode Perjanjian</th>
                                <th>
                                    <a
                                        href="<?= site_url('perjanjian/index'); ?>?q=<?= $q; ?>&sort_column=status_mou&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Status</a>
                                    <i
                                        class="pl-2 <?= $sort_column == 'status_mou' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'status_mou' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <!-- <?php if (!in_array(session('jenis_user'), ['verifikator', 'departemen'])) { ?>
                                <th>Verifikasi Oleh</th>
                                <?php } ?> -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) { ?>
                                <?php
                                $akses = [
                                    'delete' => ($row->status_mou < 1 && $gol_pic_mou == 2) || ($row->status_mou < 1 && $row->id_user_pic == $user_id) || ($row->status_mou < 1 && $row->id_user_pic == $user_id) ? '' : 'disabled',
                                    'edit' => ($gol_pic_mou == 2 && $row->status_mou <= 1) || ($row->id_user_pic == $user_id && $gol_pic_mou == 1 && $row->status_mou <= 1) || (in_array($row->id_user_pic, $bawahan) && $gol_pic_mou == 1 && $row->status_mou <= 1) ? '' : 'disabled',
                                    'details' => $gol_pic_mou == 2 || ($row->id_user_pic == $user_id && $gol_pic_mou == 1) || (in_array($row->id_user_pic, $bawahan) && $gol_pic_mou == 1) ? '' : 'disabled',
                                    'reviews' => ($gol_pic_mou == 2 && $row->status_mou >= 1) || ($row->id_user_pic == $user_id && $gol_pic_mou == 1 && $row->status_mou >= 1) || (in_array($row->id_user_pic, $bawahan) && $gol_pic_mou == 1 && $row->status_mou >= 1) ? '' : 'disabled',
                                    'luaran' => ($gol_pic_mou == 2 && $row->status_mou >= 3) || ($row->id_user_pic == $user_id && $gol_pic_mou == 1 && $row->status_mou >= 3) || (in_array($row->id_user_pic, $bawahan) && $gol_pic_mou == 1 && $row->status_mou >= 3) ? '' : 'disabled',
                                    'monev' => ($gol_pic_mou == 2 && $row->status_mou >= 3) || ($row->id_user_pic == $user_id && $gol_pic_mou == 1 && $row->status_mou >= 3) || (in_array($row->id_user_pic, $bawahan) && $gol_pic_mou == 1 && $row->status_mou >= 3) ? '' : 'disabled',

                                ];

                                $status_deskripsi = [
                                    '0' => '<span class="text-sm badge badge-secondary">Draft</span>',
                                    '1' => '<span class="text-sm badge badge-warning">Proses Review</span>',
                                    '2' => '<span class="text-sm badge badge-info">Review Selesai</span>',
                                    '3' => '<span class="text-sm badge badge-success">Ditandatangani</span>',
                                    '4' => '<span class="text-sm badge badge-success">Aktif</span>',
                                    '5' => '<span class="text-sm badge badge-danger">Nonaktif</span>',
                                ];

                                $sisa_waktu = $row->tanggal_akhir_kerjasama == "0000-00-00" || $row->tanggal_akhir_kerjasama == null ? '' : date_diff(date_create(date('Y-m-d')), date_create($row->tanggal_akhir_kerjasama))->format('%a');
                                // echo json_encode($sisa_waktu);
                            

                                $kuning = '<span class="text-sm badge badge-warning">Sisa ' . $sisa_waktu . ' Hari </span>';
                                $merah = '<span class="text-sm badge badge-danger">Sisa ' . $sisa_waktu . ' Hari </span>';
                                $selesai = '<span class="text-sm badge badge-danger">Telah Berakhir</span>';

                                $sisa_waktu = $sisa_waktu != '' && (int) $sisa_waktu <= 360 ? ((int) $sisa_waktu <= 30 ? ((int) $sisa_waktu <= 0 ? $selesai : $merah) : $kuning) : '';
                                $sisa_waktu = $row->tanggal_akhir_kerjasama < date('Y-m-d') ? $selesai : $sisa_waktu;

                                ?>
                                <tr>
                                    <th scope="row">
                                        <?= empty($no) ? $no = 1 : ++$no; ?>
                                    </th>
                                    <td>
                                        <?= $row->status_mou == 3 ? $row->no_dokumen_ugm : ''; ?>
                                    </td>
                                    <td>
                                        <?= $row->tipe_dokumen ?>
                                    </td>
                                    <td>
                                        <?= $row->judul_kerjasama; ?>
                                    </td>
                                    <td>
                                        <?= $row->pic ?>
                                    </td>
                                    <td>
                                        <?= $row->tanggal_pengajuan; ?>
                                    </td>
                                    <td>

                                        <?php if ($row->status_mou >= 3): ?>
                                            <?php if ($row->tanggal_akhir_kerjasama == NULL): ?>
                                                Tidak Dibatasi
                                            <?php else: ?>
                                                <?= $row->tanggal_mulai_kerjasama; ?> s/d
                                                <?= $row->tanggal_akhir_kerjasama; ?> <br>
                                                <?= $sisa_waktu ?>
                                            <?php endif ?>
                                        <?php else: ?>
                                            Belum Dimulai
                                        <?php endif ?>

                                    </td>
                                    <td>
                                        <?= $status_deskripsi[$row->status_mou]; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php if (!in_array($jenis_user, ['dekan', 'wadek'])): ?>
                                                <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit"
                                                    href="<?= base_url("perjanjian/edit/" . $row->id_mou); ?>"><i
                                                        class="fa-solid fa-pencil"></i></a>
                                                <a class="btn btn-danger <?= $akses['delete']; ?>" title="delete"
                                                    href="<?= base_url("perjanjian/delete/" . $row->id_mou); ?>"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i
                                                        class="fa-solid fa-times"></i></a>
                                            <?php endif ?>
                                            <a class="btn btn-success btn-sm <?= $akses['details']; ?>" title="Lihat Detail"
                                                href="<?= base_url("perjanjian/details/" . $row->id_mou); ?>"><i
                                                    class="fa fa-ellipsis-h"></i></a>
                                            <a class="btn btn-info btn-sm button-reviews <?= $akses['reviews']; ?>"
                                                title="Lihat Review"
                                                href="<?= site_url("perjanjian/reviews/" . $row->id_mou); ?>"><i
                                                    class="fa-regular fa-file-lines"></i></a>
                                            <a class="btn btn-primary <?= $akses['luaran']; ?>" title="Luaran"
                                                href="<?= base_url("perjanjian/luaran/" . $row->id_mou); ?>"><i
                                                    class="fa-solid fa-list"></i></a>
                                            <a class="btn btn-monev <?= $akses['monev']; ?>" title="Monev"
                                                href="<?= base_url("perjanjian/monev/" . $row->id_mou); ?>"><i
                                                    style="color:#E9E9E9;" class="fa-regular fa-eye"></i></a>

                                        </div><br>
                                        <div class="text-danger">
                                            <?= session('jenis_user') == 'verifikator' && empty($row->no_surat) ? '<b>no surat harus diisi dulu</b>' : ''; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?= str_replace(
                        '<li >',
                        '<li class="page-item">',
                        str_replace(
                            '<li class="',
                            '<li class="page-item ',
                            str_replace("<a ", '<a class="page-link" ', $pager->links())
                        )
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modalClass" id="modal-excel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= site_url('perjanjian/databaseToExcel') ?>" enctype="multipart/form-data" id="excel-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalExcelTitle">Export Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Export:</label><span style="color:red;">*</span><br />
                        <div class="col">
                            <select name="excelType" id="excelType">
                                <option selected value="kerjasama">Kerjasama</option>
                                <option value="monev">Monev</option>
                            </select>
                        </div>
                        <br />
                        <div id="form-monev" style="display:none;">
                            <div class="col">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Semester</label><span style="color:red;">*</span>
                                    </div>
                                    <div class="col">
                                        <label>Tahun Akademik</label><span style="color:red;">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-4">
                                        <select name="smt" id="smt" class="form-control" required>
                                            <option value="1">Gasal</option>
                                            <option value="0">Genap</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input class="form-control" name="tahun1"
                                            style="width: 5rem; display: inline-block;" type="text" pattern="\d*"
                                            maxlength="4" id="year1">
                                        &nbsp;/&nbsp;
                                        <input class="form-control" name="tahun2"
                                            style="width: 5rem; display: inline-block;" type="text" pattern="\d*"
                                            maxlength="4" id="year2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="form-kerjasama">
                            <div class="form-group mx-2">
                                <label for="">Periode waktu</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="start_year" id="start_year" class="form-control"
                                            placeholder="Start Year" required>
                                    </div>
                                    <div class="col-1 text-center mt-1">s/d</div>
                                    <div class="col">
                                        <input type="number" name="end_year" id="end_year" class="form-control"
                                            placeholder="End Year" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="btnUploadTTD">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" onclick="closeForm()" value=""
                                class="btn btn-success">Download</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>

<!-- Komentar -->
<div class="modal fade" id="modal-komentar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="padding:20px;">
            <b>Komentar:</b>
            <p id="komentar"></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('pdf.js'); ?>"></script>
<script>
    function closeForm() {
        // Perform form submission via AJAX or fetch

        // Once the form is successfully submitted, close the modal
        // $('#modal-excel').modal('hide');
    }

    function viewpdf(id, akses) {
        $('#modal-pdf').modal('show')
        $('#form-komentar').attr('action', "<?= site_url('suratkp/update/') ?>" + id);
        if (akses) {
            $('#preview-panel').show()
        } else {
            $('#preview-panel').hide()
        }
        var url = "<?= site_url('suratkp/topdf/'); ?>" + id
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= base_url('
        pdf.worker.js '); ?>';
        var loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function (pdf) {
            console.log('PDF loaded');
            var container = document.getElementById('pdf-container');
            container.innerHTML = '';

            for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                pdf.getPage(pageNumber).then(function (page) {
                    console.log('Page loaded');

                    var scale = 1.25;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    var wrapper = document.createElement("div");
                    var canvas = document.createElement("canvas");
                    var context = canvas.getContext('2d');
                    wrapper.style.marginBottom = "16px";
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    canvas.style.margin = "0 auto";
                    canvas.style.display = "block";
                    canvas.style.border = "solid #ccc 1px";

                    wrapper.appendChild(canvas)
                    container.appendChild(wrapper);

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        console.log('Page rendered');
                    });
                });
            }
        }, function (reason) {
            console.error(reason);
        });
    }

    $(document).ready(function (e) {

        // $('#filter_status').on('change', function() {
        //     $('#indexForm').submit();
        // })
        // $('#filter_jenis').on('change', function() {
        //     $('#indexForm').submit();
        // })
        $('#excelType').on('change', function () {
            var selectedOption = $(this).val();
            var formMonev = $('#form-monev');
            var formKerjasama = $('#form-kerjasama');


            if (selectedOption === 'monev') {
                formMonev.show();
                formKerjasama.hide();

                $('#smt').prop('required', true);
                $('#year1').prop('required', true);
                $('#year2').prop('required', true);
                $('#start_year').prop('required', false);
                $('#end_year').prop('required', false);
            } else if (selectedOption === 'kerjasama') {
                formMonev.hide();
                formKerjasama.show();
                $('#smt').prop('required', false);
                $('#year1').prop('required', false);
                $('#year2').prop('required', false);
                $('#start_year').prop('required', true);
                $('#end_year').prop('required', true);
            }
        });

        $('#btnExcel').click(function () {
            var date = new Date();
            var year = date.getFullYear();

            var month = date.getMonth() + 1; // Mendapatkan bulan (1-12)
            var semester = (month >= 1 && month <= 6) ? '0' : '1';
            $('#smt').val(semester).change();

            if (semester == 0) {
                var tahun1 = year - 1;
                var tahun2 = year;
            } else {
                var tahun1 = year;
                var tahun2 = year + 1;
            }
            console.log(tahun2);
            $('#year1').val(tahun1);
            $('#year2').val(tahun2);
        });

        $("#year1").on("input", function () {
            var year1 = parseInt($(this).val());
            $("#year2").val(year1 + 1);
        });

        $("#year2").on("input", function () {
            var year2 = parseInt($(this).val());
            $("#year1").val(year2 - 1);
        });


        $('.button-komentar').on('click', function () {
            $('#komentar').html('<i>' + $(this).data('komentar') + '</i>');
        });

        $('.button-preview').on('click', function () {
            $('textarea[name=komentar]').text($(this).data('komentar'));
            if ($(this).data('approve') == 'disabled') {
                $('#approve').addClass('disabled');
                $('#decline').addClass('disabled');
            } else {
                $('#approve').removeClass('disabled');
                $('#decline').removeClass('disabled');
            }
        });

        let id = '<?= (session()->getFlashData('
        preview ')); ?> ';
    if (id.length > 0) {
        viewpdf(id, false);
    }
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Yudisium</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <?php if ((in_array($jenis_user, ['mahasiswa']))) { ?>
                    <a class="btn btn-success" title="Tambah" id="btnTambahST"
                        href="<?= site_url('yudisium/create'); ?>">Tambah</a>
                <?php } ?>
            </div>
        </div>

        <!-- <form action="<?= site_url("yudisium/index"); ?>" method="get">
            <div class="form-group row">
                <div class="col-sm-8">
                    <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                    <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                    <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                </div>
                <?php if (!in_array($jenis_user, ['verifikator', 'departemen'])) { ?>
                    <div class="col-sm-2">
                        <select class="form-control" name="status">
                            <option value="">Semua</option>
                            <option value="1" <?= $status == '1' ? 'selected' : ''; ?>>Baru</option>
                            <option value="2" <?= $status == '2' ? 'selected' : ''; ?>>Terverifikasi</option>
                            <option value="3" <?= $status == '3' ? 'selected' : ''; ?>>Sudah Ditandatangani</option>
                        </select>
                    </div>
                <?php } ?>
                <div class="col-sm-2">
                    <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                </div>
            </div>
        </form> -->

        <?php if (in_array($jenis_user, ['admin', 'verifikator'])): ?>
            <form action="<?= site_url("yudisium/buatsheet"); ?>" method="post">
                <div class="form-group">
                    <label>Tanggal Yudisium</label><span style="color:red;">*</span><br />
                    <div class="col-sm-5">
                        <input required type="date" class="form-control" name="tanggal_yudisium" />
                    </div>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" title="Cari" type="submit">Download Sheet</a>
                </div>
        </div>
        </form>
    <?php endif ?>

    <div class="row">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                    $url = site_url('yudisium/index') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                    $is_selected = $sort_column == $column_name ? '' : 'text-white';
                    $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                    return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
                }
                    ?>
                <form id="approve_bulk" action="<?= site_url('yudisium/approvebebaspinjam') ?>" method="POST"
                    enctype="multipart/form-data"></form>
                <?php if (in_array($jenis_user, ["perpus", "lab"])): ?>

                    <button type="submit" form="approve_bulk">
                        Approve Selected
                    </button>
                <?php endif ?>
                <table class="table table-bordered table-valign-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>
                                <?= $print_header('Nama Mahasiswa', 'nama', $q); ?>
                            </th>
                            <th>
                                <?= $print_header('NIM', 'nim', $q); ?>
                            </th>
                            <th>
                                <?= $print_header('Prodi', 'prodi', $q); ?>
                            </th>
                            <th>
                                <?= $print_header('Status', 'status', $q); ?>
                            </th>
                            <?php if (!in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                <th>Komentar</th>
                            <?php endif ?>
                            <th>Aksi</th>
                            <?php if (!in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                <th>Tanggal Yudisium</th>
                            <?php endif ?>
                            <?php if (in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                <th>Komentar Revisi</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) { ?>
                            <?php
                            $akses = [
                                'edit' => ($row->status <= 1) ? '' : 'disabled',
                                'delete' => $row->status <= 1 ? '' : 'disabled',
                                'download' => $row->status == 4 ? '' : 'disabled',
                                'comment' => $row->status < 3 && !in_array($jenis_user, ['mahasiswa']) ? '' : 'disabled',
                                'preview' => 1,
                            ];
                            ?>
                            <tr>
                                <th scope="row">
                                    <?= empty($no) ? $no = 1 : ++$no; ?>
                                </th>
                                <td>
                                    <?= $row->nama; ?>
                                </td>
                                <td>
                                    <?= $row->nim; ?>
                                </td>
                                <td>
                                    <?= $row->jenjang . "-" . $row->prodi; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = [
                                        0 => 'Draft',
                                        1 => 'Baru',
                                        2 => 'Perlu Revisi',
                                        3 => 'Dalam Proses',
                                        4 => 'Selesai'
                                    ];

                                    $status_pengajuan = [
                                        0 => 'Baru',
                                        1 => 'Approved'
                                    ]
                                        ?>
                                    <?= $jenis_user == "lab" ? $status_pengajuan[$row->status_pengajuan_surat_bebas_pinjam_lab] : ($jenis_user == "perpus" ? $status_pengajuan[$row->status_pengajuan_surat_bebas_pinjam_perpus] : $status[$row->status]); ?>
                                </td>
                                <?php if (!in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                    <td>
                                        <?php if ($row->status > 0): ?>
                                            <p>
                                                Akademik:
                                                <?= $row->komentar->akademik ?? "-"; ?>
                                            </p>
                                            <p>
                                                Prodi:
                                                <?= $row->komentar->prodi ?? "-"; ?>
                                            </p>
                                            <p>
                                                Perpus & Lab:
                                                <?= $row->komentar->perpus ?? "-"; ?>
                                            </p>
                                        <?php endif ?>
                                    </td>
                                <?php endif ?>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if (in_array($jenis_user, ['mahasiswa'])): ?>
                                            <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit"
                                                href="<?= base_url("yudisium/edit/" . $row->id); ?>"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <a class="btn btn-danger ml-2 <?= $akses['delete']; ?>" title="delete"
                                                href="<?= base_url("yudisium/delete/" . $row->id); ?>"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i
                                                    class="fa-solid fa-times"></i></a>
                                        <?php endif ?>
                                        <?php if (in_array($jenis_user, ['admin', 'verifikator'])): ?>
                                            <a class="btn btn-warning <?= $row->status == 4 ? "disabled" : "" ?>" title="edit"
                                                href="<?= base_url("yudisium/lihat/" . $row->id); ?>"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                        <?php endif ?>
                                        <?php if (in_array($jenis_user, ['prodi'])): ?>
                                            <a class="btn btn-warning <?= $row->status == 4 ? "disabled" : "" ?>" title="lihat"
                                                href="<?= base_url("yudisium/prodiLihat/" . $row->id); ?>"><i
                                                    class="fa-solid fa-eye"></i></a>
                                        <?php endif ?>
                                        <?php if (in_array($jenis_user, ["perpus", "lab"])): ?>
                                            <input form="approve_bulk" type="checkbox" name="id[]"
                                                value="<?php echo $row->id; ?>" <?php if (($jenis_user == "lab" && $row->status_pengajuan_surat_bebas_pinjam_lab) || ($jenis_user == "perpus" && $row->status_pengajuan_surat_bebas_pinjam_perpus)) {
                                                       echo 'checked disabled';
                                                   } ?>>
                                            <a class="btn btn-danger ml-2 <?php if (!($jenis_user == "lab" && $row->status_pengajuan_surat_bebas_pinjam_lab) || ($jenis_user == "perpus" && $row->status_pengajuan_surat_bebas_pinjam_perpus) || $row->status == 4) {
                                                echo 'disabled';
                                            } ?>" title="delete"
                                                href="<?= base_url("yudisium/cancelapprovebebaspinjam/" . $row->id); ?>"
                                                onclick="return confirm('Apakah anda yakin ingin membatalkan status ini?');"><i
                                                    class="fa-solid fa-times"></i></a>
                                        <?php endif ?>
                                    </div>
                                </td>
                                <?php if (!in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                    <td>
                                        <?php if ($row->tanggal_yudisium): ?>
                                            <?php echo date('m/d/Y', strtotime($row->tanggal_yudisium)) ?>
                                        <?php endif ?>
                                    </td>
                                <?php endif ?>
                                <?php if (in_array($jenis_user, ["perpus", "lab", "prodi"])): ?>
                                    <td>
                                        <form action="<?= site_url('yudisium/komentar/') . $row->id ?>" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="form-group mt-4">
                                                <label for="">Komentar Revisi</label>
                                                <div>
                                                    <textarea <?= $row->status == 4 ? "disabled" : "" ?> rows="4" cols="25"
                                                        id="komentar"
                                                        name="komentar"><?= ($jenis_user == "prodi") ? $row->komentar->prodi : $row->komentar->perpus ?></textarea>
                                                </div>
                                                <input type="hidden" name="pengirim" value="<?= $jenis_user ?>" />
                                                <button <?= $row->status == 4 ? "disabled" : "" ?> type="submit" name="aksi"
                                                    value="komentar">Kirim revisi</button>
                                            </div>
                                        </form>
                                    </td>
                                <?php endif ?>
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

<!-- Preview Modal -->
<div class="modal fade" id="modal-pdf" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-9">
                        <div id="pdf-container"
                            style="border:solid #ccc 1px;background-color:#ddd;height:750px;overflow-y: scroll;"></div>
                    </div>
                    <div class="col-sm-3" id="preview-panel">

                        <form id="form-komentar" style="float:left;" action="" method="POST">
                            <div class="form-group mb-1">
                                <label>Komentar</label>
                                <textarea class="form-control" name="komentar"
                                    style="font-size: 80%; width: 260px;"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary" type="submit">Komentari</a>
                            </div>
                            <div class="form-group" style="position: absolute;bottom:0;">
                                <button type="submit" name="status" value="-1" id="decline" class="btn btn-danger"
                                    onclick="return confirm('Jika anda memilih decline, maka surat akan dikembalikan akses ke pembuat surat. Apakah anda yakin ingin mendecline surat ini?');">Decline</button>
                                <button type="submit" name="status"
                                    value="<?= in_array($jenis_user, ['dekan', 'wadek']) ? 3 : 2; ?>" id="approve"
                                    class="btn btn-success"
                                    onclick="return confirm('Apakah anda yakin ingin menyetujui surat ini?');">Approve</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
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
    function viewpdf(id, akses) {
        $('#modal-pdf').modal('show')
        $('#form-komentar').attr('action', "<?= site_url('suratbandos/update/') ?>" + id);
        if (akses) {
            $('#preview-panel').show()
        } else {
            $('#preview-panel').hide()
        }
        var url = "<?= site_url('suratbandos/topdf/'); ?>" + id
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= base_url('pdf.worker.js'); ?>';
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
        $('.button-komentar').on('click', function () {
            $('#komentar').html('<i>' + $(this).data('komentar') + '</i>');
        });

        $('.button-preview').on('click', function () {
            $('textarea[name=komentar]').text($(this).data('komentar'));
            if ($(this).data('approve') == 'disabled') {
                $('#approve').hide();
                $('#decline').hide();
                $('#approve').addClass('disabled');
                $('#decline').addClass('disabled');
            } else {
                $('#approve').show();
                $('#decline').show();
                $('#approve').removeClass('disabled');
                $('#decline').removeClass('disabled');
            }
        });

        let id = '<?= empty(session()->getFlashData('preview')) ? $preview_id : session()->getFlashData('preview'); ?>';
        if (id.length > 0) {
            viewpdf(id, false);
        }
    });
</script>
<?= $this->endSection() ?>
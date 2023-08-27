<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Mata Kuliah</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
            </div>
        </div>

        <?php if ((in_array($jenis_user, ['prodi', 'akademik']))) { ?>
            <form action="<?= site_url("matakuliah/tambah"); ?>" method="post">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" name="kodematkul" class="form-control" placeholder="Kode Matkul" value="<?= $q; ?>">
                        <input type="text" name="namamatkul" class="form-control" placeholder="Nama Matkul" value="<?= $q; ?>">
                        <input type="number" name="sksmatkul" class="form-control" placeholder="SKS" value="<?= $q; ?>">
                        <select class="form-control" name="keterangan">
                            <option value="wajib" <?= $status == 'wajib' ? 'selected' : ''; ?>>Wajib</option>
                            <option value="pilihan" <?= $status == 'pilihan' ? 'selected' : ''; ?>>Pilihan</option>
                        </select>
                        <select class="form-control" name="prodi">
                            <?php foreach ($prodi as $i) { ?>
                                <option value="<?php $i->jenjang ?>-<?php $i->nama ?>" <?= $status == ($i->jenjang . '-' . $i->nama) ? 'selected' : ''; ?>><?= $i->jenjang ?>-<?= $i->nama ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                        <button class="btn btn-success" title="Cari" type="submit">Simpan</a>
                    </div>
                </div>
            </form>
        <?php } ?>

        <form action="<?= site_url("matakuliah/index"); ?>" method="get">
            <div class="form-group row">
                <div class="col-sm-8">
                    <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                    <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                    <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="status">
                        <option value="">Semua</option>
                        <option value="wajib" <?= $status == 'wajib' ? 'selected' : ''; ?>>Wajib</option>
                        <option value="pilihan" <?= $status == 'pilihan' ? 'selected' : ''; ?>>Pilihan</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                        $url = site_url('matakuliah/index') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
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
                                    <?= $print_header('Kode Matkul', 'kode', $q); ?>
                                </th>
                                <th>
                                    <?= $print_header('Nama Matkul', 'nama', $q); ?>
                                </th>
                                <th>
                                    <?= $print_header('SKS', 'sks', $q); ?>
                                </th>
                                <th>
                                    <?= $print_header('Keterangan', 'keterangan', $q); ?>
                                </th>
                                <th>
                                    <?= $print_header('Program Studi', 'prodi', $q); ?>
                                </th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) { ?>
                                <tr>
                                    <th scope="row">
                                        <?= empty($no) ? $no = 1 : ++$no; ?>
                                    </th>
                                    <td>
                                        <?= $row->kode; ?>
                                    </td>
                                    <td>
                                        <?= $row->nama; ?>
                                    </td>
                                    <td>
                                        <?= $row->sks; ?>
                                    </td>
                                    <td>
                                        <?= $row->keterangan; ?>
                                    </td>
                                    <td>
                                        <?= $row->prodi; ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-danger ml-2" title="delete"
                                            href="<?= base_url("matakuliah/delete/" . $row->id); ?>"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus matkul ini?');"><i
                                                class="fa-solid fa-times"></i></a>
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
        
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Surat Rekomendasi</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>
                    <a class="btn btn-success" title="Tambah" id="btnTambahST" href="<?= site_url('suratrekomendasi/create'); ?>">Tambah</a>
                <?php } ?>
            </div>
        </div>

        <form action="<?= site_url("suratrekomendasi/index"); ?>" method="get">
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
        </form>

        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                        $url = site_url('suratrekomendasi/index') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                        $is_selected = $sort_column == $column_name ? '' : 'text-white';
                        $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                        return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
                    }
                    ?>
                    <table class="table table-bordered table-valign-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th><?= $print_header('No Surat', 'no_surat', $q); ?></th>
                                <th>
                                    <a href="<?= site_url('suratrekomendasi/index'); ?>?q=<?= $q; ?>&sort_column=nama_surat&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Nama Surat</a>
                                    <i class="pl-2 <?= $sort_column == 'nama_surat' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'nama_surat' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('suratrekomendasi/index'); ?>?q=<?= $q; ?>&sort_column=nama&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Pengaju</a>
                                    <i class="pl-2 <?= $sort_column == 'nama' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'nama' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('suratrekomendasi/index'); ?>?q=<?= $q; ?>&sort_column=tanggal_pengajuan&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Tanggal Pengajuan</a>
                                    <i class="pl-2 <?= $sort_column == 'tanggal_pengajuan' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'tanggal_pengajuan' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>Periode Surat Izin</th>
                                <th>
                                    <a href="<?= site_url('suratrekomendasi/index'); ?>?q=<?= $q; ?>&sort_column=status&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Status</a>
                                    <i class="pl-2 <?= $sort_column == 'status' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'status' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
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
                                    'edit' => $row->status <= 1 ? '' : 'disabled',
                                    'delete' => $row->status <= 1 ? '' : 'disabled',
                                    'download' => $row->status == 3 ? '' : 'disabled',
                                    'comment' => $row->status < 3 && !in_array($jenis_user, ['dekan', 'wadek']) ? '' : 'disabled',
                                    'upload_pj' => '',
                                    'preview' => (($row->status == 1 && in_array($jenis_user, ['verifikator'])) || ($row->status == 2 && in_array($jenis_user, ['dekan', 'wadek', 'verifikator']))) && !empty($row->no_surat) ? 1 : 0,
                                    'share' => '',
                                    'approve' => ($row->status == 1 && in_array($jenis_user, ['verifikator'])) || ($row->status == 2 && in_array($jenis_user, ['dekan', 'wadek', 'verifikator'])) ? '' : 'disabled',
                                    'enrollment' => ''
                                ];
                                ?>
                                <tr>
                                    <th scope="row"><?= empty($no) ? $no = 1 : ++$no; ?></th>
                                    <td><?= $row->status == 3 ? $row->no_surat : ''; ?></td>
                                    <td>
                                        <?= $row->nama_surat; ?>
                                    </td>
                                    <td><?= $row->nama; ?></td>
                                    <td><?= $row->tanggal_pengajuan; ?></td>
                                    <td><?= $row->tanggal_kegiatan_mulai; ?> s/d <?= $row->tanggal_kegiatan_selesai; ?></td>
                                    <td class="<?= in_array(session('jenis_user'), ['dekan', 'wadek']) ? 'bg-danger' : ''; ?>">
                                        <?= get_status($row->status, $row->verifikasi_verifikator, $row->verifikasi_departemen); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) : ?>
                                                <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit" href="<?= base_url("suratrekomendasi/edit/" . $row->id); ?>"><i class="fa-solid fa-pencil"></i></a>
                                                <a class="btn btn-danger <?= $akses['delete']; ?>" title="delete" href="<?= base_url("suratrekomendasi/delete/" . $row->id); ?>" onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i class="fa-solid fa-times"></i></a>
                                            <?php endif ?>
                                            <a class="btn btn-success <?= $akses['download']; ?>" title="download" href="<?= base_url("suratrekomendasi/topdf/" . $row->id); ?>" target="__blank"><i class="fa-solid fa-eye"></i></a>
                                            <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) : ?>
                                                <!-- <a class="btn btn-primary btn-sm <?= $akses['upload_pj']; ?>" title="Unggah Laporan Pertanggungjawaban" href="<?= base_url("/suratrekomendasi/upload/" . $row->id); ?>"><i class="fa-solid fa-upload"></i></a> -->
                                                <a class="btn btn-warning btn-sm button-komentar <?= $akses['comment']; ?>" title="Lihat Komentar" href="#modal-komentar" data-toggle="modal" data-komentar="<?= $row->komentar ?? '-- tidak ada komentar --'; ?>"><i class="fa-solid fa-comment-dots"></i></a>
                                                <a class="btn btn-outline-primary btn-sm <?= $akses['share']; ?>" title="Bagikan" href="<?= base_url("suratrekomendasi/share/" . $row->id); ?>"><i class="fa-solid fa-share"></i></a>
                                            <?php endif ?>
                                            <a class="btn btn-success <?= $akses['enrollment']; ?>" title="Enrollment" href="<?= base_url("suratrekomendasi/enrollment/" . $row->id); ?>"><i class="fa-solid fa-play"></i></a>

                                            <a class="btn btn-info btn-sm button-preview" title="Lihat dan Setujui" href="#" onclick="viewpdf('<?= $row->id; ?>', <?= $akses['preview']; ?>)" data-komentar="<?= $row->komentar; ?>" data-approve="<?= $akses['approve']; ?>"><i class="fa-solid fa-person-circle-check"></i></a>
                                        </div><br>
                                        <div class="text-danger"><?= session('jenis_user') == 'verifikator' && empty($row->no_surat) ? '<b>no surat harus diisi dulu</b>' : ''; ?></div>
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

<!-- Preview Modal -->
<div class="modal fade" id="modal-pdf" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-9">
                        <div id="pdf-container" style="border:solid #ccc 1px;background-color:#ddd;height:750px;overflow-y: scroll;"></div>
                    </div>
                    <div class="col-sm-3" id="preview-panel">

                        <form id="form-komentar" style="float:left;" action="" method="POST">
                            <div class="form-group mb-1">
                                <label>Komentar</label>
                                <textarea class="form-control" name="komentar" style="font-size: 80%; width: 260px;"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary" type="submit">Komentari</a>
                            </div>
                            <div class="form-group" style="position: absolute;bottom:0;">
                                <button type="submit" name="status" value="-1" id="decline" class="btn btn-danger" onclick="return confirm('Jika anda memilih decline, maka surat akan dikembalikan akses ke pembuat surat. Apakah anda yakin ingin mendecline surat ini?');">Decline</button>
                                <button type="submit" name="status" value="<?= in_array($jenis_user, ['dekan', 'wadek', 'verifikator']) ? 3 : 2; ?>" id="approve" class="btn btn-success" onclick="return confirm('Apakah anda yakin ingin menyetujui surat ini?');">Approve</button>
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
        $('#form-komentar').attr('action', "<?= site_url('suratrekomendasi/update/') ?>" + id);
        if (akses) {
            $('#preview-panel').show()
        } else {
            $('#preview-panel').hide()
        }
        var url = "<?= site_url('suratrekomendasi/topdf/'); ?>" + id
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= base_url('pdf.worker.js'); ?>';
        var loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(pdf) {
            console.log('PDF loaded');
            var container = document.getElementById('pdf-container');
            container.innerHTML = '';

            for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                pdf.getPage(pageNumber).then(function(page) {
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
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }
        }, function(reason) {
            console.error(reason);
        });
    }

    $(document).ready(function(e) {
        $('.button-komentar').on('click', function() {
            $('#komentar').html('<i>' + $(this).data('komentar') + '</i>');
        });

        $('.button-preview').on('click', function() {
            $('textarea[name=komentar]').text($(this).data('komentar'));
            if ($(this).data('approve') == 'disabled') {
                $('#approve').addClass('disabled');
                $('#decline').addClass('disabled');
            } else {
                $('#approve').removeClass('disabled');
                $('#decline').removeClass('disabled');
            }
        });

        let id = '<?= (session()->getFlashData('preview')); ?>';
        if (id.length > 0) {
            viewpdf(id, false);
        }
    });
</script>
<?= $this->endSection() ?>
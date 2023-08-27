<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<?php
$status_label;
switch ($row->status) {
    case 1:
        $status_label = "Baru";
        break;
    case 2:
        $status_label = "Menunggu untuk Ditandatangani";
        break;
    default:
        $status_label = "Sudah Ditandatangani";
        break;
}
?>
<div class="container mt-3">
    <h1>Preview (<?= $status_label; ?>)</h1>
    <div class="row">
        <div class="col-sm-9">
            <div id="pdf-container" style="border:solid #ccc 1px;background-color:#ddd;height:750px;overflow-y: scroll;"></div>
        </div>
        <div class="col-sm-3">

            <form id="form-komentar" style="float:left;" action="" method="POST">
                <div class="form-group">
                    <a href="<?= base_url('buatsurat/edit/' . $row->id); ?>" class="btn btn-lg btn-warning <?= $row->status == 3 ? 'disabled' : ''; ?>" style="width:150px;">Edit</a>
                </div>

                <form action="<?= base_url('buatsurat/update/' . $row->id) ?>" method="post">
                    <div class="form-group">
                        <?php if ((session('jenis_user') == 'verifikator' && $row->status < 2) && !empty($row->no_surat)) : ?>
                            <input type="hidden" class="form-control disabled" name="status" value="2" />
                            <button type="submit" class="btn btn-lg btn-primary" style="width:150px;" onclick="return confirm('Apakah anda yakin ingin verifikasi surat ini?')">Verifikasi</button>
                        <?php else : ?>
                            <button type="button" class="btn btn-lg btn-primary disabled" style="width:150px;">Verifikasi</button>
                        <?php endif; ?>
                    </div>
                </form>

                <form action="<?= base_url('buatsurat/update/' . $row->id) ?>" method="post">
                    <div class="form-group">
                        <?php if (session('pegawai_id') == $row->penandatangan_pegawai_id && $row->status == 2) : ?>
                            <input type="hidden" class="form-control disabled" name="status" value="3" />
                            <button type="submit" class="btn btn-lg btn-primary" style="width:150px;" onclick="return confirm('Apakah anda yakin ingin verifikasi surat ini?')">TTD</button>
                        <?php else : ?>
                            <button type="button" class="btn btn-lg btn-primary disabled" style="width:150px;">TTD</button>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="form-group">
                    <a href="<?= base_url('buatsurat/share/' . $row->id); ?>" class="btn btn-lg btn-info" style="width:150px;">Bagikan</a>
                </div>
            </form>

        </div>
    </div>

    <?php
    $a[] = $row->user_id;
    foreach ($shares as $user) $a[] = $user->id;
    ?>
    <?php if (in_array(session('id'), $a)) : ?>
        <h3>Chat</h3>
        <?php foreach ($shares as $user) : ?>
            <span class="badge badge-success"><?= $user->nama; ?></span>
        <?php endforeach; ?>
        <form action="<?= site_url('buatsurat/send'); ?>" method="post" class="mt-2">
            <input type="hidden" value="<?= $row->id; ?>" name="buat_surat_id">
            <div class="row">
                <div class="col-sm-10 pb-2">
                    <ul class="list-group" id="chat-list">
                        <?php foreach ($chats as $chat) : ?>
                            <li class="list-group-item list-group-item-info">
                                <b><?= $chat->nama; ?></b><br>
                                <?= $chat->isi_chat; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8"><input type="text" class="form-control" id="chat-input" name="isi_chat" /></div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary" id="chat-send">Kirim</button>
                    <button type="button" class="btn btn-warning" onclick="$('#modal-todo-buatsurat').modal('show'); $('#tugas-buatsurat').val($('#chat-input').val())">Todo list</button>
                    <!-- <a href="#" class="btn btn-warning" onclick="$('#modal-todo').modal('show'); $('#deadline').val('2099-01-01'); $('#deadline-input').hide(); $('#link').val('<?= current_url(); ?>');">Todo List</a> -->
                </div>
            </div><br><br>
        </form>
    <?php endif; ?>
</div>

<!-- Modal Todo -->
<div class="modal fade" id="modal-todo-buatsurat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('todo/store'); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tugas</label>
                        <input class="form-control" type="text" id="tugas-buatsurat" name="tugas" placeholder="Masukkan deskripsi tugas" value="" autocomplete="off">
                        <input class="form-control" type="hidden" id="link" name="link" value="<?= current_url(); ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Kirim to do list ke</label>
                        <?php
                        $users = (new \App\Models\UserModel())->get()->getResult();
                        ?>
                        <select class="form-control" name="user_ids[]" multiple>
                            <?php foreach ($shares as $user) : ?>
                                <option value=<?= $user->id; ?> <?= session('id') == $user->id ? 'selected' : ''; ?>><?= $user->nama; ?><?= session('id') == $user->id ? ' (Saya)' : ''; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>


<?= $this->section('css') ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $("#todo-body").hide();
        $("#todo-header").click(function() {
            $("#todo-body").toggle();
        });
        $(".todo-check").change(function() {
            var id = $(this).data('id');
            fetch('<?= base_url('todo/toggle'); ?>/' + id, {
                cache: 'no-store'
            }).then(response => response.text()).then(data => {
                if (data === 'true') {
                    $("label[for='todo-" + id + "']").css("text-decoration", "line-through");
                } else {
                    $("label[for='todo-" + id + "']").css("text-decoration", "");
                }
            });
        });
    });
</script>

<script src="<?= base_url('pdf.js'); ?>"></script>
<script>
    function viewpdf(id) {
        $('#modal-pdf').modal('show')
        $('#form-komentar').attr('action', "<?= site_url('buatsurat/update/') ?>" + id);
        var url = "<?= site_url('buatsurat/topdf/'); ?>" + id
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
        viewpdf('<?= $row->id; ?>')

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
    });



    setInterval(() => {
        console.log('');
        TextTrackCueList
        // Use AJAX to update the conversations list
        let q = document.querySelector('#chat-search').value
        fetch('<?= base_url('api/buatsuratthreadchat/index/<?=  $row->id; ?>'); ?>', {
            cache: 'no-store'
        }).then(response => response.text()).then(html => {
            console.log(html);
            let doc = (new DOMParser()).parseFromString(html, 'text/html');
            document.querySelector('.chat-widget-conversations').innerHTML = doc.querySelector('.chat-widget-conversations').innerHTML;
            conversationHandler();
        });
    }, 5000);
</script>
<?= $this->endSection() ?>
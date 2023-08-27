<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Excel Surat Peringatan Akademik</h3>
    </div>

    <div class="card-body">
        <a class="btn btn-outline-primary" title="Download Petunjuk Pembuatan Surat Akademik" href="<?= base_url('/user_manual/template_surat_akademik.xlsx'); ?>">Download Template Spreadsheet Surat Akademik</a>
        <br>
        <br>
        <form action="<?= site_url('suratakademik/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Masukan File Excel (".xlsx"/".xls")</label><span style="color:red;">*</span>
                <br>
                <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls" required />
            </div>

            <div class="card my-4" style="background-color: #ddd; border:solid #aaa 1px;">
                <div class="card-header">
                    <h3 class="card-title">Pencarian Nama Dosen/Tendik</h3>
                </div>

                <div class="card-body">
                    Cari: <input id="pegawai-search" class="form-control">
                    <table class="table table-sm table-bordered" id="tabel-search">
                        <tr>
                            <td>Nama</td>
                            <td id="user_nama"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_nama').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td id="user_nip"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_nip').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Prodi</td>
                            <td id="user_prodi"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_prodi').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Departemen</td>
                            <td id="user_departemen"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_departemen').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Pangkat</td>
                            <td id="user_pangkat"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_pangkat').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Golongan</td>
                            <td id="user_golongan"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_golongan').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Surat Peringatan Akademik</button>
            </div>

        </form>
    </div>
</div>

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<style>
    .cf::before,
    .cf::after {
        display: table;
        content: '';
    }

    .cf::after {
        clear: both;
    }

    .hiddenContent {
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(e) {

        $('#tabel-search').hide();
        $("#pegawai-search").autocomplete({
                minLength: 0,
                source: function(request, response) {
                    $.ajax({
                        url: "<?= base_url('home/autocomplete'); ?>",
                        dataType: "json",
                        data: {
                            term: request.term,
                        },
                        success: response
                    });
                },
                focus: function(event, ui) {
                    $("#pegawai-search").val(ui.item.username);
                    return false;
                },
                select: function(event, ui) {
                    $('#tabel-search').show();
                    $("#pegawai-search").val(ui.item.username);
                    $("#user_username").html(ui.item.username);
                    $("#user_nama").html(ui.item.nama_publikasi);
                    $("#user_nip").html(ui.item.nip);
                    $("#user_prodi").html(ui.item.prodi);
                    $("#user_departemen").html(ui.item.departemen);
                    $("#user_pangkat").html(ui.item.pangkat);
                    $("#user_golongan").html(ui.item.golongan);

                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.nama_publikasi + "<br>" + item.nip + "</div>")
                    .appendTo(ul);
            };
    });
    
    function copas(text){
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
    
</script>
<?= $this->endSection() ?>

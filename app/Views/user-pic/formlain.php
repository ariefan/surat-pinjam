<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form PIC</h3>
    </div>

    <div class="card-body">
        <form id="piclain" action="<?= site_url('pic/' . $action) . ($action == 'update' ? '/' . $row->id_list_pic : '') ?>" method="POST">
        <div class="card-body mb-4" style="background-color: #ddd; border:solid #aaa 1px;">
            Cari user: <input id="user-search" class="form-control">
        </div>
            <input type="hidden" id="id_user_pic" name="id_user_pic" value="<?= $row->id_user_pic; ?>">
            <div class="form-group">
                <label>Account User</label><br />
                <input placeholder="Gunakan fitur cari di atas untuk mencari user" style="background-color:#dddddd;" required onfocus="blur();" id="email" class="form-control" type="text" name="email" value="<?= $row->email; ?>">
            </div>
            <div class="form-group">
                <label>Tipe PIC</label><br />
                <select class="form-control" name="tipe_pic" id="tipe_pic" style="width:50%" required>
                <option selected disabled value="">Pilih Tipe PIC</option>
                    <option value="1">PIC tambahan</option>
                    <option value="2">Admin Departemen</option>
                </select>
            </div>
            <div class="form-group">
                <label>Departemen</label><br/>
                <select class="form-control" name="departemen_ugm" id="departemen_ugm" style="width:50%" required>
                    <option selected disabled value="">Pilih Departemen</option>
                    <option value="Departemen Ilmu Komputer dan Elektronika">Departemen Ilmu Komputer dan Elektronika</option>
                    <option value="Departemen Matematika">Departemen Matematika</option>
                    <option value="Departemen Fisika">Departemen Fisika</option>
                    <option value="Departemen Kimia">Departemen Kimia</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nama</label><br />
                <input class="form-control" id="nama_ugm" type="text" name="nama_ugm" value="<?= $row->nama_ugm; ?>" required>
            </div>

            <div class="form-group">
                <label>No Telp</label><br />
                <input class="form-control" id="no_telp_ugm" type="number" name="no_telp_ugm" value="<?= $row->no_telp_ugm; ?>"
            </div>

            <div class="form-group">
                <label>Alamat</label><br />
                <input class="form-control" type="text" name="alamat_ugm" value="<?= $row->alamat_ugm; ?>">
            </div>

            <br />

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
            
            <!-- <button type="reset" class="btn btn-warning">Reset Password</button> -->
            <!-- <button type="submit" class="btn btn-success" name="status" value="4">Simpan Draft</button> -->
            <button type="submit" class="btn btn-primary" name="status" value="1">Simpan</button>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('magicsuggest.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('magicsuggest.js'); ?>"></script>
<script>

// $(".readonly").keydown(function(e){
//         e.preventDefault();
//     });

// $('#piclain').on('submit', function(e) {
//       e.preventDefault();

//       //ambil data dari modal
//       var form_data = new FormData(this);
//       var validate = validateForm();
//       if (!validate) {
//         console.log('validasi gagal')
//         // event.preventDefault();
//         return false;
//       }
//     });

// function validateForm() {
//       var isValid = true;
//       $('div.alert').remove();
//       // validate No Dokumen UGM
//       var email = $("#email").val();
//       if (!email) {
//         $("#email").addClass("is-invalid");
//         isValid = false;
//         console.log('false')
//       } else {
//         $("#email").removeClass("is-invalid");
//       }
//     };

$(document).ready(function(e) {
    $("#user-search").autocomplete({ 
                minLength: 0,
                source: function(request, response) {
                    $.ajax({
                        url: "<?= base_url('pic/usersearch'); ?>",
                        dataType: "json",
                        data: {
                            term: request.term,
                        },
                        success: response,
                    });
                },
                focus: function(event, ui) {
                    $("#pic-search").val(ui.item.nama_ugm);
                    return false;
                },
                select: function(event, ui) {
                    console.log(ui.item);
                    $("#email").val(ui.item.email);
                    $("#nama_ugm").val(ui.item.nama);
                    $("#id_user_pic").val(ui.item.id_user_pic);
                    return false;
                }
            })
            $("#user-search").autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.nama+ "<br>" + item.email + "</div>")
                    .appendTo(ul);
            };

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
                        success: response,
                        error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                            } 
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
<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Surat Rekomendasi</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('suratrekomendasi/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST" enctype="multipart/form-data">

            <?php if (session('jenis_user') == 'verifikator') : ?>
                <div class="card my-4" style="border:solid #aaa 1px;">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Pengolah</label><span style="color:red;">*</span>
                            <?= view('data-nomor-surat/pengolah_surat.html'); ?>
                        </div>
                        <div class="form-group">
                            <label>Kode Perihal</label><span style="color:red;">*</span>
                            <?= view('data-nomor-surat/kode_perihal.html'); ?>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi</label><span style="color:red;">*</span>
                            <span id="klasifikasi"></span>
                        </div>
                        <div class="form-group">
                            <label>Penandatangan</label><span style="color:red;">*</span>
                            <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
                                <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan) : ?>
                                    <option value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]</option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <!-- <div class="form-group">
                            <label>Nomor Surat</label>
                            <input type="text" class="form-control" readonly name="no_surat" id="no_surat" value="<?= $row->no_surat; ?>">
                        </div> -->
                        <input type="hidden" class="form-control" readonly name="no_surat" id="no_surat" value="<?= $row->no_surat; ?>">
                    </div>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>Nama Surat</label><span style="color:red;">*</span>
                <input type="text" name="nama_surat" placeholder="Permohonan Surat Rekomendasi ...." class="form-control" autocomplete="on" value="<?= $row->nama_surat; ?>">
            </div>
            <div class="form-group">
                <label>Nama Kegiatan</label><span style="color:red;">*</span>
                <input type="text" name="nama_kegiatan" placeholder="Nama Kegiatan" class="form-control" autocomplete="on" value="<?= $row->nama_kegiatan; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Pembuatan Surat</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()" value="<?= $row->tanggal_pengajuan; ?>">
                    <!-- <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_surat_tugas" value="<?= $row->tanggal_pengajuan; ?>"> -->
                </div>
            </div>

            <div class="form-group">
                <label>Universitas Tujuan</label><span style="color:red;">*</span>
                <input type="text" name="lokasi_kegiatan" placeholder="Nama Universitas, Negara" class="form-control" autocomplete="on" value="<?= $row->lokasi_kegiatan; ?>">
            </div>


            <div class="form-group">
                <label>Tanggal Kegiatan</label><span style="color:red;">*</span><br />
                <div id="list-rentang-tanggal_kegiatan">
                    <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                        <input type="date" class="form-control" name="tanggal_kegiatan_mulai" value="<?= $row->tanggal_kegiatan_mulai; ?>">
                    </div>
                    <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                        <input type="date" class="form-control" name="tanggal_kegiatan_selesai" value="<?= $row->tanggal_kegiatan_selesai; ?>">
                    </div>
                </div>

                <div class="form-group">

                <div class="col">
                            <div class="row">
                                <div class="col-2">
                                    <label>Semester</label><span style="color:red;">*</span>
                                </div>
                                <div class="col">
                                    <label>Tahun Akademik</label><span style="color:red;">*</span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-2">
                                    <select name="smt" id="smt" class="form-control">
                                        <option value="1">Gasal</option>
                                        <option value="0">Genap</option>
                                    </select>
                                </div>
                                <div class="col">
                                        <input class="form-control" name="tahun1" style="width: 10rem; display: inline-block;" type="text" pattern="\d*"  maxlength="4" id="year1">
                                        &nbsp;/&nbsp;
                                        <input class="form-control" name="tahun2" style="width: 10rem; display: inline-block;" type="text" pattern="\d*"  maxlength="4" id="year2">
                                </div>
                            </div>
                        </div>
                </div>                 

                <div class="form-group">
                    <label>Surat Kepada</label>
                    <textarea class="form-control surat" id="open-source-plugins" name="kepada_surat">
                    <?php echo ($row->kepada_surat != NULL) ? $row->kepada_surat : '<em>To</em>:'; ?>
                    </textarea>
                </div>


                <div class="form-group">
                    <label for="">Identitas Mahasiswa</label>
                    <?php foreach ($row->peserta as $peserta) : ?>
                        <div id="">
                            <div class="form-row mb-2 mahasiswa-item">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]" value="<?= $peserta->nama; ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]" value="<?= $peserta->nim; ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="deptMhs[]" id="dept" class="form-control" value="<?= $peserta->prodi; ?>">
                                        <option disable selected value="">Pilih Departemen</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Computer Science and Electronics">Computer Science and Electronics</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Mathematics">Mathematics</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="Program Studi" name="prodiMhs[]" value="<?= $peserta->prodi; ?>">
                                </div>
                                <div class="col-auto">
                                <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button" onclick="deleteFormRow(this)"><i class="fa-solid fa-trash"></i></button>
                                </div>

                            </div>
                        </div>
                    <?php endforeach ?>
                    <div class="form-row mb-2 mahasiswa-item">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]" value="">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]" value="">
                        </div>
                        <div class="col-md-3">
                            <select name="deptMhs[]" id="dept" class="form-control">
                                <option value="" disabled selected>Pilih Departemen</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Computer Science and Electronics">Computer Science and Electronics</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Mathematics">Mathematics</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" placeholder="Study Program" name="prodiMhs[]" value="">
                            <div class="font-italic" style="color:grey; font-size:0.8rem;">Gunakan bahasa inggris</div>
                        </div>
                        <div class="col-auto">
                        <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button" onclick="deleteFormRow(this)"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <div id="list-mahasiswa">

                    </div>

                    <div><button type="button" class="btn btn-success btn-sm" id="add-mahasiswa"><i class="fa-solid fa-plus"></i></button> Tambah Mahasiswa</div>
                </div>

                <div class="form-group" style="display:none">
                    <label>Verifikator</label><span style="color:red;">*</span>
                    <select class="form-control" name="departemen_pegawai_id">
                        <?php foreach ($departemens as $departemen) : ?>
                            <option value="<?= $departemen->kepala_pegawai_id; ?>" <?= $row->departemen_pegawai_id == $departemen->kepala_pegawai_id ? 'selected' : ''; ?>> <?= $departemen->nama_departemen; ?></option>
                        <?php endforeach ?>
                    </select>
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
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_nama').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td id="user_nip"></td>
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_nip').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                            <tr>
                                <td>Prodi</td>
                                <td id="user_prodi"></td>
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_prodi').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                            <tr>
                                <td>Departemen</td>
                                <td id="user_departemen"></td>
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_departemen').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                            <tr>
                                <td>Pangkat</td>
                                <td id="user_pangkat"></td>
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_pangkat').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                            <tr>
                                <td>Golongan</td>
                                <td id="user_golongan"></td>
                                <td><i style="cursor:pointer;" onclick="navigator.clipboard.writeText($('#user_golongan').text());return false;" class="fa-solid fa-copy" title="copy"></i></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-success" name="status" value="preview">Simpan Draft dan Tampilkan Preview</button>
                    <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Surat Tugas</button>
                </div>

        </form>
    </div>
</div>

<!-- <button id="button-test">Test</button>
<div id="test"> -->

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>

function deleteFormRow(button) {
    // Traverse up the DOM tree to find the parent form row
    var formRow = button.closest('.mahasiswa-item');
    
    // Remove the form row from the DOM
    formRow.remove();
  }

var useDarkMode = false; //window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
  selector: 'textarea#open-source-plugins',
  plugins: 'print preview paste searchreplace autolink autosave save directionality template advlist lists wordcount',
  menubar: '',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat',
  toolbar_sticky: false,
  autosave_ask_before_unload: true,
  autosave_interval: '30s',
  autosave_prefix: '{path}{query}-{id}-',
  autosave_restore_when_empty: false,
  autosave_retention: '2m',
  image_advtab: true,
  link_list: [{
      title: 'My page 1',
      value: 'https://www.tiny.cloud'
    },
    {
      title: 'My page 2',
      value: 'http://www.moxiecode.com'
    }
  ],
  image_list: [{
      title: 'My page 1',
      value: 'https://www.tiny.cloud'
    },
    {
      title: 'My page 2',
      value: 'http://www.moxiecode.com'
    }
  ],
  image_class_list: [{
      title: 'None',
      value: ''
    },
    {
      title: 'Some class',
      value: 'class-name'
    }
  ],
  importcss_append: true,
  file_picker_callback: function(callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', {
        text: 'My text'
      });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', {
        alt: 'My alt text'
      });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', {
        source2: 'alt.ogg',
        poster: 'https://www.google.com/logos/google.jpg'
      });
    }
  },
  templates: [{
      title: 'New Table',
      description: 'creates a new table',
      content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
    },
    {
      title: 'Starting my story',
      description: 'A cure for writers block',
      content: 'Once upon a time...'
    },
    {
      title: 'New list with dates',
      description: 'New List with dates',
      content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
    }
  ],
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: 300,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: 'mceNonEditable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table',
  skin: useDarkMode ? 'oxide-dark' : 'oxide',
  content_css: useDarkMode ? 'dark' : 'default',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

    // tinymce.init({
    //     selector: 'textarea#basic-example',
    //     plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
    //     menubar: '',
    //     toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
    //     toolbar_sticky: true,
    //     content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    // });

    // tinymce.init({
    //     selector: 'textarea#informasi-tambahan',
    //     plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
    //     menubar: '',
    //     toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
    //     toolbar_sticky: true,
    //     content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    // });

    // $("#button-test").click(function() {
    //     let s
    //     $("select[name='dt[klasifikasi_id]'] > optgroup > option").each(function() {
    //         s += this.text + "<br>"
    //     });
    //     $("select[name='dt[klasifikasi_id]'] > option").each(function() {
    //         s += this.text + "<br>"
    //     });
    //     $("#test").html(s)
    // });


    //NOMOR SURAT PARSING =======================================================================================================
    let get_nomor = () => {
        let el = event.target
        let regExp = /\[([^)]+)\]/
        // if (el.name === 'dt[kode_dokumen_id]') {
        if (el.id.includes("kode_dokumen_id")) {
            // let label = el.options[el.selectedIndex].innerHTML
            // let kode = regExp.exec(label)[1]
            let kode = regExp.exec(el.innerHTML)[1]
            fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_' + kode + '.html'), {
                cache: 'no-store'
            }).then(response => response.text()).then(data => {
                if (data != 'error') {
                    document.querySelector('#klasifikasi').innerHTML = data
                    $('select[name="dt[klasifikasi_id]"]').select2()
                }
            })
        }
        let no_surat = document.querySelector("input[name=no_surat]")
        try {
            let penandatangan = document.querySelector("select[name='penandatangan_pegawai_id']")
            let pengolah = document.querySelector("select[name='dt[pengolah_surat_id]']")
            let kode_perihal = document.querySelector("select[name='dt[kode_dokumen_id]']")
            let klasifikasi = document.querySelector("select[name='dt[klasifikasi_id]']")
            // let tahun = document.querySelector("#no_surat-tahun").value
            let tahun = (new Date($("input[name=tanggal_pengajuan]").val())).getFullYear()
            pengolah = regExp.exec(pengolah.options[pengolah.selectedIndex].innerHTML)[1]
            kode_perihal = regExp.exec(kode_perihal.options[kode_perihal.selectedIndex].innerHTML)[1]
            klasifikasi = klasifikasi.options[klasifikasi.selectedIndex].innerHTML.split(" - ")[0].replace(' ', '')
            penandatangan = regExp.exec(penandatangan.options[penandatangan.selectedIndex].innerHTML)[1]
            no_surat.value = `/UN1/${penandatangan}/${pengolah}/${kode_perihal}.${klasifikasi}/${tahun}`

        } catch (e) {}
    }

    try {
        let arr = $("input[name=no_surat]").val().split("/")
        let penandatangan = arr[2]
        let pengolah = arr[3]
        let kode_perihal = arr[4].split('.')[0]
        let klasifikasi = arr[4].split('.')[1] + (arr[4].split('.').length > 2 ? '.' + arr[4].split('.')[2].replace(' ', '') : '')
        let tahun = arr[5]
        $("select[name='penandatangan_pegawai_id'] > option").each(function() {
            if (this.text.includes('[' + penandatangan + ']')) {
                $("select[name='penandatangan_pegawai_id']").val(this.value)
            }
        });
        $("select[name='dt[pengolah_surat_id]'] > option").each(function() {
            if (this.text.includes(pengolah)) {
                $("select[name='dt[pengolah_surat_id]']").val(this.value)
            }
        });
        $("select[name='dt[kode_dokumen_id]'] > option").each(function() {
            if (this.text.includes(kode_perihal)) {
                $("select[name='dt[kode_dokumen_id]']").val(this.value)
                fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_' + kode_perihal + '.html'), {
                    cache: 'no-store'
                }).then(response => response.text()).then(data => {
                    if (data != 'error') {
                        document.querySelector('#klasifikasi').innerHTML = data
                        if (klasifikasi.split('.').length == 1) {
                            $("select[name='dt[klasifikasi_id]'] > option").each(function() {
                                if (this.text.includes(klasifikasi)) {
                                    $("select[name='dt[klasifikasi_id]']").val(this.value)
                                }
                            });
                        } else {
                            $("select[name='dt[klasifikasi_id]'] > optgroup > option").each(function() {
                                if (this.text.includes(klasifikasi)) {
                                    $("select[name='dt[klasifikasi_id]']").val(this.value)
                                }
                            });
                        }
                    }
                })
            }
        })
        document.querySelector("#no_surat-tahun").value = tahun
    } catch (e) {}
    //=======================================================================================================================

    $(document).ready(function(e) {

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

        $("#year1").on("input", function () {
            var year1 = parseInt($(this).val());
            $("#year2").val(year1 + 1);
        });

        $("#year2").on("input", function () {
            var year2 = parseInt($(this).val());
            $("#year1").val(year2 - 1);
        });


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

        

        $("#add-tembusan").click(function() {
            $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `);
        });

        $("#add-mahasiswa").click(function() {
            $('#list-mahasiswa').append(`
            <div class="form-row mb-2 mahasiswa-item">
                <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]" value="">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]" value="">
                        </div>
                        <div class="col-md-3">
                            <select name="deptMhs[]" id="dept" class="form-control select2-dept">
                                <option value="" disabled selected>Pilih Departemen</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Computer Science and Electronics">Computer Science and Electronics</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Mathematics">Mathematics</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" placeholder="Study Program" name="prodiMhs[]" value="">
                            <div class="font-italic" style="color:grey; font-size:0.8rem;">Gunakan bahasa inggris</div>
                        </div>
                        <div class="col-auto">
                        <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button" onclick="deleteFormRow(this)"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
            `);
            $('.select2-dept').select2();
        });
    });
</script>
<?= $this->endSection() ?>
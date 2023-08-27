<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Surat Kerja Praktik/Izin</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('suratkp/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST" enctype="multipart/form-data">

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
                <input type="hidden" value="'Bidang Pendidikan, Pengajaran, dan Kemahasiswaan'" name="kategori">
            </div>

            <div class="form-group">
                <label>Jenis Kegiatan</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="text" class="form-control" list="jeniskeg" name="jenis_kegiatan" placeholder="Kerja Praktik/Tugas Akhir/dll" value="<?= $row->jenis_kegiatan; ?>" />
                    <datalist id="jeniskeg">
                        <option>Kerja Praktik</option>
                        <option>Tugas Akhir</option>
                        <option>Proyek Riset</option>
                    </datalist>
                </div>
            </div>
            <div class="form-group">
                <label>Tanggal Pembuatan Surat</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()" value="<?= $row->tanggal_pengajuan; ?>">
                    <!-- <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_surat_tugas" value="<?= $row->tanggal_pengajuan; ?>"> -->
                </div>
            </div>

            <div class="form-group">
                <label>Perihal</label><span style="color:red;">*</span>
                <input type="text" name="nama_surat" placeholder="Permohonan kerja praktik/tugas akhir/dll" class="form-control" autocomplete="on" value="<?= $row->nama_surat; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Kegiatan</label><span style="color:red;">*</span><br />
                <!-- <div class="row">
                    <div class="form-check ml-3 mb-2">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="opsi_tgl1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            sampai dengan
                        </label>
                    </div>
                    <div class="form-check ml-3 mb-2">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="opsi_tgl2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            dan
                        </label>
                    </div>
                </div> -->
                <div id="list-rentang-tanggal_kegiatan">
                    <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                        <input type="date" class="form-control" name="tanggal_kegiatan_mulai" value="<?= $row->tanggal_kegiatan_mulai; ?>">
                    </div>
                    <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                        <input type="date" class="form-control" name="tanggal_kegiatan_selesai" value="<?= $row->tanggal_kegiatan_selesai; ?>">
                    </div>
                </div>

                <!-- <div id="list-tanggal_kegiatan">
                    <?php foreach ($row->tanggal_kegiatan ?? [] as $tanggal_kegiatan) : ?>
                        <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                            <input type="date" class="form-control" placeholder="Tanggal Kegiatan" name="tanggal_kegiatan[]" value="<?= $tanggal_kegiatan; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger tanggal_kegiatan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div><button type="button" class="btn btn-success btn-sm" id="add-tanggal_kegiatan"><i class="fa-solid fa-plus"></i> Tambah Tanggal Kegiatan</button></div>
            </div> -->

                <div class="form-group">
                    <label>Surat Kepada</label>
                    <input class="form-control" name="kepada_surat" id="informasi-tambahan" placeholder="Ex: Human Resource Development/Kepala/Direktur/dll" value="<?= $row->kepada_surat; ?>">
                </div>

                <div class="form-group">
                    <label>Lokasi Kegiatan</label>
                    <input class="form-control mb-2" type="text" name="lokasi_kegiatan" placeholder="Masukkan Nama Tempat Kegiatan" autocomplete="off" value="<?= $row->lokasi_kegiatan; ?>">
                    <input class="form-control mb-2" type="text" name="alamat_kegiatan" placeholder="Masukkan Alamat Kegiatan" autocomplete="off" value="<?= $row->alamat_kegiatan; ?>">
                    <input class="form-control mb-2" type="text" name="alamat_tambahan_satu" placeholder="Masukkan Alamat Tambahan" autocomplete="off" value="<?= $row->alamat_tambahan_satu; ?>">
                    <input class="form-control mb-2" type="text" name="alamat_tambahan_dua" placeholder="Masukkan Alamat Tambahan" autocomplete="off" value="<?= $row->alamat_tambahan_dua; ?>">
                </div>

                <div class="form-group">
                    <label for="">Identitas Mahasiswa</label>
                    <?php foreach ($row->peserta as $peserta) : ?>
                        <div id="">
                            <div class="form-row mb-2 mahasiswa-item">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]" value="<?= $peserta->nama; ?>">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]" value="<?= $peserta->nim; ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="Program Studi" name="prodiMhs[]" value="<?= $peserta->prodi; ?>">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                                </div>

                            </div>
                        </div>
                    <?php endforeach ?>
                    <div class="form-row mb-2 mahasiswa-item">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]" value="">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]" value="">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Program Studi" name="prodiMhs[]" value="">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
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

                <div class="form-group">
                    <label>Tembusan</label>
                    <div id="list-tembusan">
                        <?php
                        $db = \Config\Database::connect();
                        $results = $db->query("SELECT nama_publikasi FROM departemen JOIN pegawais ON departemen.kepala_pegawai_id = pegawais.id")->getResult();
                        $vals = [];
                        foreach ($results as $result) {
                            $vals[] = $result->nama_publikasi;
                        }
                        ?>
                        <?php foreach ($row->tembusan as $tembusan) : ?>
                            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                                <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]" value="<?= $tembusan; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                            <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]" value="">
                            <span style="color:red;">&nbsp;&nbsp;otomatis surat dibagikan</span>
                        </div>
                    </div>
                    <div><button type="button" class="btn btn-success btn-sm" id="add-tembusan"><i class="fa-solid fa-plus"></i></button> Tambah Tembusan</div>
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
    $(function() {
        var availableTags = <?= json_encode($vals); ?>;
        $(".tags").autocomplete({
            source: availableTags
        });
    });
</script>
<script>
    tinymce.init({
        selector: 'textarea#basic-example',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
        menubar: '',
        toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
        toolbar_sticky: true,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    tinymce.init({
        selector: 'textarea#informasi-tambahan',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
        menubar: '',
        toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
        toolbar_sticky: true,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

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


        // $("#penandatangan_search").autocomplete({
        //         minLength: 0,
        //         source: function(request, response) {
        //             $.ajax({
        //                 url: "<?= base_url('home/autocomplete'); ?>",
        //                 dataType: "json",
        //                 data: {
        //                     term: request.term,
        //                 },
        //                 success: response
        //             });
        //         },
        //         focus: function(event, ui) {
        //             $("#penandatangan_search").val(ui.item.nama_publikasi);
        //             return false;
        //         },
        //         select: function(event, ui) {
        //             $("#penandatangan_value").val(ui.item.id);
        //             return false;
        //         }
        //     })
        //     .autocomplete("instance")._renderItem = function(ul, item) {
        //         return $("<li>")
        //             .append("<div>" + item.nama_publikasi + "<br>" + item.nip + "</div>")
        //             .appendTo(ul);
        //     };



        // $("#btnAddTembusan").click(function() {
        //     $('#list-tembusan').append(`<div><input class="form-control mt-2" type="text" name="tembusan[]" /></div>`);
        // });

        // let id = 0;
        // let users = 'mahasiswa';
        // $("#btnAddAnggota").click(function() {
        //     id++;
        //     $('#anggota').append(
        //             `<tr>
        //         <td>
        //         <select class="form-control" name="kategori" class="kategori">
        //             <option value="mahasiswa">Mahasiswa</option>
        //             <option value="dosen">Dosen</option>
        //             <option value="tendik">Tendik</option>
        //         </select>
        //         </td>
        //         <td>
        //             <input type="text" name="nama[]" placeholder="Nama" class="form-control zmauto" id="${id}">
        //             <input type="hidden" class="user_id" name="user_id[]" value="">
        //         </td>
        //         <td>
        //             <button type="button" class="btn btn-danger btn-sm" onClick="$(this).parent().parent().remove();">Delete</buton>
        //         </td>
        //     </tr>`
        //         )
        //         .find(":text")
        //         .autocomplete({
        //             source: function(request, response) {
        //                 console.log(users);
        //                 $.ajax({
        //                     url: "<?= base_url('home/autocomplete'); ?>",
        //                     dataType: "json",
        //                     data: {
        //                         term: request.term,
        //                         users: users,
        //                     },
        //                     success: response
        //                 });
        //             },
        //             select: function(event, ui) {
        //                 console.log(ui.item.id);
        //                 $(this).closest('tr').find('.user_id').val(ui.item.id);
        //             }
        //         });

        //     $('select[name=kategori]').on('click', function() {
        //         users = $(this).closest('tr').find('select').val();
        //         console.log(users);
        //     });

        //     $('select[name=kategori]').on('change', function() {
        //         $(this).closest('tr').find('input[type=text]').val('');
        //     });

        //     $('input[type=text]').on('keydown', function() {
        //         users = $(this).closest('tr').find('select').val();
        //         console.log(users);
        //     });
        // });


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
            <div class="form-row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" name="namaMhs[]">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="NIM Lengkap" name="nimMhs[]">
                        </div>
                        <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Program Studi" name="prodiMhs[]">
                        </div>
                        <div class="col-md-1">
                        <button class="btn btn-outline-danger mahasiswa-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
            `);
        });

        $(".tembusan-btn-delete").click(function() {
            console.log($(this).closest('.tembusan-item'));
            $(this).closest('.tembusan-item').remove();
        });

        $(".mahasiswa-btn-delete").click(function() {
            console.log($(this).closest('.mahasiswa-item'));
            $(this).closest('.mahasiswa-item').remove();
        });




        $("#add-tanggal_kegiatan").click(function() {
            $('#list-tanggal_kegiatan').append(`
            <div class="input-group input-group-sm mb-1 tanggal_kegiatan-item col-sm-5">
                <input type="date" class="form-control" placeholder="tanggal_kegiatan" name="tanggal_kegiatan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tanggal_kegiatan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `);

            $(".tanggal_kegiatan-btn-delete").click(function() {
                console.log($(this).closest('.tanggal_kegiatan-item'));
                $(this).closest('.tanggal_kegiatan-item').remove();
            });
        });


        $('#list-rentang-tanggal_kegiatan').show();
        $('#list-tanggal_kegiatan').hide();
        $('#add-tanggal_kegiatan').hide();
        $('#opsi_tgl1').change(() => {
            if ($('#opsi_tgl1').is(':checked')) {
                $('#list-rentang-tanggal_kegiatan').show();
                $('#list-tanggal_kegiatan').hide();
                $('#add-tanggal_kegiatan').hide();
            }
        })
        $('#opsi_tgl2').change(() => {
            if ($('#opsi_tgl2').is(':checked')) {
                $('#list-rentang-tanggal_kegiatan').hide();
                $('#list-tanggal_kegiatan').show();
                $('#add-tanggal_kegiatan').show();
            }
        })


    });
</script>
<?= $this->endSection() ?>
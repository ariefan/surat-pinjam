<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Surat Aktif</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('surataktif/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
            method="POST" enctype="multipart/form-data">

            <?php if (session('jenis_user') == 'verifikator'): ?>
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
                                <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan): ?>
                                    <option value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]</option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <!-- <div class="form-group">
                            <label>Nomor Surat</label>
                            <input type="text" class="form-control" readonly name="no_surat" id="no_surat" value="<?= $row->no_surat; ?>">
                        </div> -->
                        <input type="hidden" class="form-control" readonly name="no_surat" id="no_surat"
                            value="<?= $row->no_surat; ?>">
                    </div>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>Tanggal Surat</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()"
                        value="<?= $row->tanggal_pengajuan; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Verifikator</label><span style="color:red;">*</span>
                <select class="form-control" name="departemen_pegawai_id">
                    <?php foreach ($departemens as $departemen): ?>
                        <option value="<?= $departemen->kepala_pegawai_id; ?>"
                            <?= $row->departemen_pegawai_id == $departemen->kepala_pegawai_id ? 'selected' : ''; ?>> <?= $departemen->nama_departemen; ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="nama_mhs"
                    value="<?= $row->nama_mhs; ?>" required>
            </div>

            <div class="form-group">
                <label>NIM</label>
                <input placeholder="NIM Lengkap" autocomplete="off" type="text" class="form-control" name="nim"
                    value="<?= $row->nim; ?>" required>
            </div>

            <div class="form-group">
                <label>Departemen</label>
                <select id="departemen" class="form-control" name="departemen" value="<?= $row->departemen; ?>"
                    required>
                    <option value="" disabled>Pilih departemen</option>
                    <option value="Fisika" <?= $row->departemen == 'Fisika' ? 'selected' : ''; ?>>Fisika</option>
                    <option value="Ilmu Komputer dan Elektronika" <?= $row->departemen == 'Ilmu Komputer dan Elektronika' ? 'selected' : ''; ?>>
                        Ilmu Komputer dan Elektronika</option>
                    <option value="Kimia" <?= $row->departemen == 'Kimia' ? 'selected' : ''; ?>>Kimia</option>
                    <option value="Matematika" <?= $row->departemen == 'Matematika' ? 'selected' : ''; ?>>Matematika
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Program</label>
                <select class="form-control" name="program" value="<?= $row->program; ?>" required>
                    <option value="Sarjana" <?= $row->program == 'Sarjana' ? 'selected' : ''; ?>>Sarjana</option>
                    <option value="Magister" <?= $row->program == 'Magister' ? 'selected' : ''; ?>>Magister</option>
                    <option value="Doktor" <?= $row->program == 'Doktor' ? 'selected' : ''; ?>>Doktor</option>
                </select>
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <select class="form-control" name="prog_studi" value="<?= $row->prog_studi; ?>" required>
                    <option id="pilih" value="" disabled>Pilih program studi</option>
                    <option id="fisika" class="hiddenContent" value="Sarjana Fisika" <?= $row->prog_studi == 'Sarjana Fisika' ? 'selected' : ''; ?>>Sarjana Fisika</option>
                    <option id="geofisika" class="hiddenContent" value="Sarjana Geofisika" <?= $row->prog_studi == 'Sarjana Geofisika' ? 'selected' : ''; ?>>Sarjana Geofisika</option>
                    <option id="ik" class="hiddenContent" value="Sarjana Elektronika dan Instrumentasi"
                        <?= $row->prog_studi == 'Sarjana Elektronika dan Instrumentasi' ? 'selected' : ''; ?>>Sarjana
                        Elektronika dan Instrumentasi</option>
                    <option id="elins" class="hiddenContent" value="Sarjana Ilmu Komputer" <?= $row->prog_studi == 'Sarjana Ilmu Komputer' ? 'selected' : ''; ?>>Sarjana Ilmu Komputer</option>
                    <option id="kimia" class="hiddenContent" value="Sarjana Kimia" <?= $row->prog_studi == 'Sarjana Kimia' ? 'selected' : ''; ?>>Sarjana Kimia</option>
                    <option id="matematika" class="hiddenContent" value="Sarjana Matematika"
                        <?= $row->prog_studi == 'Sarjana Matematika' ? 'selected' : ''; ?>>Sarjana Matematika</option>
                    <option id="statistika" class="hiddenContent" value="Sarjana Statistika"
                        <?= $row->prog_studi == 'Sarjana Statistika' ? 'selected' : ''; ?>>Sarjana Statistika</option>
                    <option id="aktu" class="hiddenContent" value="Sarjana Ilmu Aktuaria" <?= $row->prog_studi == 'Sarjana Ilmu Aktuaria' ? 'selected' : ''; ?>>Sarjana Ilmu Aktuaria</option>
                    <option id="mafis" class="hiddenContent" value="Magister Fisika" <?= $row->prog_studi == 'Magister Fisika' ? 'selected' : ''; ?>>Magister Fisika</option>
                    <option id="makom" class="hiddenContent" value="Magister Ilmu Komputer"
                        <?= $row->prog_studi == 'Magister Ilmu Komputer' ? 'selected' : ''; ?>>Magister Ilmu Komputer
                    </option>
                    <option id="makim" class="hiddenContent" value="Magister Kimia" <?= $row->prog_studi == 'Magister Kimia' ? 'selected' : ''; ?>>Magister Kimia</option>
                    <option id="mamat" class="hiddenContent" value="Magister Matematika" <?= $row->prog_studi == 'Magister Matematika' ? 'selected' : ''; ?>>Magister Matematika</option>
                    <option id="dofis" class="hiddenContent" value="Doktor Ilmu Fisika" <?= $row->prog_studi == 'Doktor Ilmu Fisika' ? 'selected' : ''; ?>>Doktor Ilmu Fisika</option>
                    <option id="dokom" class="hiddenContent" value="Doktor Ilmu Komputer" <?= $row->prog_studi == 'Doktor Ilmu Komputer' ? 'selected' : ''; ?>>Doktor Ilmu Komputer</option>
                    <option id="dokim" class="hiddenContent" value="Doktor Kimia" <?= $row->prog_studi == 'Doktor Kimia' ? 'selected' : ''; ?>>Doktor Kimia</option>
                    <option id="domat" class="hiddenContent" value="Doktor Matematika" <?= $row->prog_studi == 'Doktor Kimia' ? 'selected' : ''; ?>>Doktor Matematika</option>
                </select>
            </div>

            <div class="form-group">
                <label>Semester</label>
                <select id="semester" class="form-control" name="semester" value="<?= $row->departemen; ?>" required>
                    <!-- <option value="" disabled selected>Semester</option> -->
                    <option value="Ganjil" <?= $row->semester == 'Ganjil' ? 'selected' : ''; ?>>Ganjil</option>
                    <option value="Genap" <?= $row->semester == 'Genap' ? 'selected' : ''; ?>>Genap</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tahun Akademik</label>
                <input placeholder="Tahun Ajaran" autocomplete="off" type="text" class="form-control"
                    name="tahun_ajaran"
                    value="<?= $row->tahun_ajaran ?? (date('m') < '8' ? (date('Y') - 1) . "/" . date('Y') : date('Y') . "/" . (date('Y') + 1)) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Tujuan Surat</label>
                <div class="margin-bottom">
                    <input placeholder="Tujuan" autocomplete="off" type="text" class="form-control" name="keperluan"
                        value="<?= $row->keperluan; ?>">
                </div>
                <div class="margin-left">
                    <p id="bpjs"><input type="checkbox" id="bpjsCheckbox">Surat Keterangan ini akan digunakan untuk
                        kelengkapan administrasi pengaktifan kembali status kepesertaan BPJS</p>
                    <p id="lain"><input type="checkbox" id="lainCheckbox">Surat Keterangan ini akan digunakan untuk
                        keperluan lain</p>
                </div>
            </div>

            <label style="font-size: larger;">Informasi Tambahan (Opsional)</label>
            <button type="button" id="toggle-informasi-tambahan" class="btn btn-success btn-sm" data-toggle="collapse"
                data-target="#info"><i class="fa-solid fa-plus"></i></button>
            <div class="card collapse cf" id="info">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Orang Tua</label>
                        <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control"
                            name="nama_ortu" value="<?= $row->nama_ortu; ?>">
                    </div>

                    <div class="form-group">
                        <label>NIP</label>
                        <input placeholder="NIP Orang Tua" autocomplete="off" type="text" class="form-control"
                            name="nip_ortu" value="<?= $row->nip_ortu; ?>">
                    </div>

                    <div class="form-group">
                        <label>Pangkat dan Golongan</label>
                        <input placeholder="Pangkat" autocomplete="off" type="text" class="form-control"
                            name="pangkat_ortu" value="<?= $row->pangkat_ortu; ?>">
                    </div>

                    <div class="form-group">
                        <label>Instansi</label>
                        <input placeholder="Instansi" autocomplete="off" type="text" class="form-control"
                            name="instansi_ortu" value="<?= $row->instansi_ortu; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Tembusan</label>
                <div id="list-tembusan">
                    <?php foreach ($row->tembusan as $tembusan): ?>
                        <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                            <input type="text" class="form-control" placeholder="Tembusan" name="tembusan[]"
                                value="<?= $tembusan; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div><button type="button" class="btn btn-success btn-sm" id="add-tembusan"><i
                            class="fa-solid fa-plus"></i></button> Tambah Tembusan</div>
            </div>

            <!-- <div class="form-group">
                <label for="">Upload File Dasar Penerbitan Surat Tugas (pdf maks 2MB)</label><span style="color:red;">*</span>
                <input type="file" accept="application/pdf" name="berkas" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
                <?php if (file_exists('upload/dasar_penerbitan_surat_tugas/' . $row->id . '.pdf')): ?>
                    <a target="__blank" href="<?= base_url('upload/dasar_penerbitan_surat_tugas/' . $row->id . '.pdf') ?>">Dasar Penerbitan</a>
                <?php endif ?>
            </div> -->

            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success" name="status" value="preview">Simpan Draft dan Tampilkan
                    Preview</button>
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Permohonan Surat
                    Aktif</button>
            </div>

        </form>
    </div>
</div>

<!-- <button id="button-test">Test</button>
<div id="test"> -->

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

    .margin-bottom {
        margin-bottom: 5px;
    }

    .margin-left {
        margin-left: 5px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
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

    $('#bpjsCheckbox').on('change', function () {
        if ($(this).prop('checked')) {
            $('input[name="keperluan"]').val('Surat Keterangan ini akan digunakan untuk kelengkapan administrasi pengaktifan kembali status kepesertaan BPJS');
        } else {
            $('input[name="keperluan"]').val("");
        }
    })

    $('#lainCheckbox').on('change', function () {
        if ($(this).prop('checked')) {
            $('input[name="keperluan"]').val('Surat Keterangan ini akan digunakan untuk keperluan lain');
        } else {
            $('input[name="keperluan"]').val("");
        }
    })

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

        } catch (e) { }
    }

    try {
        let arr = $("input[name=no_surat]").val().split("/")
        let penandatangan = arr[2]
        let pengolah = arr[3]
        let kode_perihal = arr[4].split('.')[0]
        let klasifikasi = arr[4].split('.')[1] + (arr[4].split('.').length > 2 ? '.' + arr[4].split('.')[2].replace(' ', '') : '')
        let tahun = arr[5]
        $("select[name='penandatangan_pegawai_id'] > option").each(function () {
            if (this.text.includes('[' + penandatangan + ']')) {
                $("select[name='penandatangan_pegawai_id']").val(this.value)
            }
        });
        $("select[name='dt[pengolah_surat_id]'] > option").each(function () {
            if (this.text.includes(pengolah)) {
                $("select[name='dt[pengolah_surat_id]']").val(this.value)
            }
        });
        $("select[name='dt[kode_dokumen_id]'] > option").each(function () {
            if (this.text.includes(kode_perihal)) {
                $("select[name='dt[kode_dokumen_id]']").val(this.value)
                fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_' + kode_perihal + '.html'), {
                    cache: 'no-store'
                }).then(response => response.text()).then(data => {
                    if (data != 'error') {
                        document.querySelector('#klasifikasi').innerHTML = data
                        if (klasifikasi.split('.').length == 1) {
                            $("select[name='dt[klasifikasi_id]'] > option").each(function () {
                                if (this.text.includes(klasifikasi)) {
                                    $("select[name='dt[klasifikasi_id]']").val(this.value)
                                }
                            });
                        } else {
                            $("select[name='dt[klasifikasi_id]'] > optgroup > option").each(function () {
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
    } catch (e) { }
    //=======================================================================================================================

    $("#add-tembusan").click(function () {
        $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `);

        $(".tembusan-btn-delete").click(function () {
            console.log($(this).closest('.tembusan-item'));
            $(this).closest('.tembusan-item').remove();
        });
    });

    $(document).ready(function (e) {

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
    });
</script>
<?= $this->endSection() ?>
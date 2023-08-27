<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Excel Surat Keterangan Lulus</h3>
    </div>

    <div class="card-body">
        <div class="pb-3">
            <a class="btn btn-outline-primary" title="Download Template (.xlsx)" onclick="return confirm('Apakah anda yakin ingin mendownload template (.xslx)?');" href="<?= base_url('templates/templateskl.xlsx'); ?>" download>Download Template Spreadsheet (.xlsx)</a>
            <a class="btn btn-outline-primary" title="Download Template (.xls)" onclick="return confirm('Apakah anda yakin ingin mendownload template (.xls)?');" href="<?= base_url('templates/templateskl.xls'); ?>" download>Download Template Spreadsheet (.xls)</a>
        </div>
        <form action="<?= site_url('suratketeranganlulus/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST" enctype="multipart/form-data">
            <?php if (session('jenis_user') == 'verifikator') : ?>
                <div class="card my-4" style="border:solid #aaa 1px;">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Penandatangan</label><span style="color:red;">*</span>
                            <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
                                <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan) : ?>
                                    <option value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]</option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>Verifikator</label><span style="color:red;">*</span>
                <select class="form-control" name="departemen_pegawai_id">
                    <?php foreach ($departemens as $departemen) : ?>
                        <option value="<?= $departemen->kepala_pegawai_id; ?>" <?= $row->departemen_pegawai_id == $departemen->kepala_pegawai_id ? 'selected' : ''; ?>> <?= $departemen->nama_departemen; ?></option>
                    <?php endforeach ?>
                </select>
            </div>


            <div class="form-group">
                <label>Masukan File Excel (".xlsx"/".xls")</label><span style="color:red;">*</span>
                <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls" required />
            </div>

            <!-- 
            <div class="form-group">
                <label>NIM</label>
                <input placeholder="NIM Lengkap" autocomplete="off" type="text" class="form-control" name="nim" value="<?= $row->nim; ?>" required>
            </div>

            <div class="form-group">
                <label>Departemen</label>
                <select id="departemen" class="form-control" name="departemen_pengaju" value="<?= $row->departemen_pengaju; ?>" required>
                    <option value="" disabled selected>Pilih departemen</option>
                    <option value="Fisika">Fisika</option>
                    <option value="Ilmu Komputer dan Elektronika">Ilmu Komputer dan Elektronika</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Matematika">Matematika</option>
                </select>
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <select class="form-control" name="prodi_pengaju" value="<?= $row->prodi_pengaju; ?>" required>
                    <option id="pilih" value="" disabled selected>Pilih program studi</option>
                    <option id="fisika" class="hiddenContent" value="Fisika">Fisika</option>
                    <option id="geofisika" class="hiddenContent" value="Geofisika">Geofisika</option>
                    <option id="ik" class="hiddenContent" value="Elektronika dan Instrumentasi">Elektronika dan Instrumentasi</option>
                    <option id="elins" class="hiddenContent" value="Ilmu Komputer">Ilmu Komputer</option>
                    <option id="kimia" class="hiddenContent" value="Kimia">Kimia</option>
                    <option id="aktu" class="hiddenContent" value="Ilmu Aktuaria">Ilmu Aktuaria</option>
                    <option id="matematika" class="hiddenContent" value="Matematika">Matematika</option>
                </select>
            </div>

            <div class="form-group">
                <label>No. SKL</label>
                <input placeholder="Nomor SKL" autocomplete="off" type="text" class="form-control" name="no_surat" value="<?= $row->no_surat; ?>" required>
            </div>


            <div class="form-group">
                <label>Tanggal Yudisium</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_yudisium" onchange="get_nomor()" value="<?= $row->tanggal_yudisium; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Periode Wisuda</label>
                <input placeholder="Periode Wisuda" autocomplete="off" type="text" class="form-control" name="periode_wisuda" value="<?= $row->periode_wisuda; ?>" required>
            </div>

            <div class="form-group">
                <label>Tanggal Lulus</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_lulus" onchange="get_nomor()" value="<?= $row->tanggal_lulus; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>SKS</label>
                <input placeholder="SKS yang diperoleh" autocomplete="off" type="number" class="form-control" name="sks_pengaju" value="<?= $row->sks_pengaju; ?>" required>
            </div>

            <div class="form-group">
                <label>IPK</label>
                <input placeholder="IPK yang diperoleh" autocomplete="off" type="number" step="0.01" class="form-control" name="ipk_pengaju" value="<?= $row->ipk_pengaju; ?>" required>
            </div>

            <div class="form-group">
                <label>Predikat</label>
                <input placeholder="Predikat IPK" autocomplete="off" type="text" class="form-control" name="predikat_pengaju" value="<?= $row->predikat_pengaju; ?>" required>
            </div>

            <div class="form-group">
                <label>Gelar</label>
                <input placeholder="Gelar yang diperoleh" autocomplete="off" type="text" class="form-control" name="gelar" value="<?= $row->gelar; ?>" required>
            </div>

            <div class="form-group">
                <label>Sebutan Gelar</label>
                <input placeholder="Sebutan Gelar" autocomplete="off" type="text" class="form-control" name="sebutan_gelar" value="<?= $row->sebutan_gelar; ?>" required>
            </div> -->



            <!-- <div class="form-group">
                <label for="">Upload File Dasar Penerbitan Surat Tugas (pdf maks 2MB)</label><span style="color:red;">*</span>
                <input type="file" accept="application/pdf" name="berkas" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
                <?php if (file_exists('upload/dasar_penerbitan_surat_tugas/' . $row->id . '.pdf')) : ?>
                    <a target="__blank" href="<?= base_url('upload/dasar_penerbitan_surat_tugas/' . $row->id . '.pdf') ?>">Dasar Penerbitan</a>
                <?php endif ?>
            </div> -->

            <div class="mt-5">
                <button type="submit" class="btn btn-success" name="status" value="0">Simpan Draft</button>
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Permohonan Surat Keterangan Lulus</button>
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

    $('#departemen').change(function() {
        if ($(this)[0].options[1].selected) {
            $("#fisika").show();
            $("#geofisika").show();
            $("#ik").hide();
            $("#elins").hide();
            $("#kimia").hide();
            $("#aktu").hide();
            $("#matematika").hide();
            $("#pilih").hide();
        }
        if ($(this)[0].options[2].selected) {
            $("#ik").show();
            $("#elins").show();
            $("#fisika").hide();
            $("#geofisika").hide();
            $("#kimia").hide();
            $("#aktu").hide();
            $("#matematika").hide();
            $("#pilih").hide();
        }
        if ($(this)[0].options[3].selected) {
            $("#kimia").show();
            $("#fisika").hide();
            $("#geofisika").hide();
            $("#ik").hide();
            $("#elins").hide();
            $("#aktu").hide();
            $("#matematika").hide();
            $("#pilih").hide();
        }
        if ($(this)[0].options[4].selected) {
            $("#matematika").show();
            $("#aktu").show();
            $("#fisika").hide();
            $("#geofisika").hide();
            $("#ik").hide();
            $("#elins").hide();
            $("#kimia").hide();
            $("#pilih").hide();
        }
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

    $("#add-tembusan").click(function() {
        $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `);

        $(".tembusan-btn-delete").click(function() {
            console.log($(this).closest('.tembusan-item'));
            $(this).closest('.tembusan-item').remove();
        });
    });

    $(document).ready(function(e) {
</script>
<?= $this->endSection() ?>
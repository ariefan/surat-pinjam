<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form
            <?= $row->tipe_surat == "TANGGAPAN" ? "Tanggapan" : "Permohonan"; ?> Surat Bantuan Dosen
        </h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('suratbandos/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
            method="POST" enctype="multipart/form-data">

            <?php if (in_array(session('jenis_user'), ['verifikator', 'admin'])): ?>
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
                            <?php
                            $db = \Config\Database::connect();
                            $results = $db->query("SELECT nama_publikasi FROM departemen JOIN pegawais ON departemen.kepala_pegawai_id = pegawais.id")->getResult();
                            $vals = [];
                            foreach ($results as $result) {
                                $vals[] = $result->nama_publikasi;
                            }
                            ?>
                            <label>Penandatangan</label><span style="color:red;">*</span>
                            <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
                                <?php
                                $jabatan = [];
                                foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan):
                                    $jabatan[] = $penandatangan->nama_penandatangan; ?>
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

            <div class="form-group" style="display: none;">
                <label>Tipe Surat</label><span style="color:red;">*</span>
                <input type="text" value="<?= $row->tipe_surat ?>" name="tipe_surat" class="form-control" readonly>
            </div>

            <!-- <div class="form-group">
                <label>Nama Surat</label><span style="color:red;">*</span>
                <input type="text" name="nama_surat" placeholder="Masukkan Nama Surat" class="form-control"
                    autocomplete="off" value="<?= $row->nama_surat; ?>">
            </div> -->

            <div class="form-group">
                <label>Tanggal Pembuatan Surat Bantuan Dosen</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()"
                        value="<?= $row->tanggal_pengajuan; ?>">
                    <!-- <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_surat_tugas" value="<?= $row->tanggal_pengajuan; ?>"> -->
                </div>
            </div>

            <!-- <div class="form-group">
                <label>Judul Kegiatan</label><span style="color:red;">*</span>
                <input type="text" name="nama_surat" placeholder="Masukkan Judul Kegiatan" class="form-control"
                    autocomplete="off" value="<?= $row->nama_surat; ?>">
            </div> -->


            <div class="form-group">
                <label>Fakultas Surat yang
                    <?= $row->tipe_surat == "TANGGAPAN" ? "Ditindak Lanjut" : "Dituju"; ?>
                </label><span style="color:red;">*</span>
                <input type="text" name="fakultas_surat_tindaklanjut" required
                    placeholder="Masukkan Fakultas Surat yang Ditindak Lanjuti" class="form-control" autocomplete="off"
                    value="<?= $row->fakultas_surat_tindaklanjut; ?>">
            </div>
            <div class="form-group">
                <label>Jabatan Pembuat Surat yang
                    <?= $row->tipe_surat == "TANGGAPAN" ? "Ditindak Lanjut" : "Dituju"; ?>
                </label><span style="color:red;">*</span>
                <input type="text" name="jabatan_surat_tindaklanjut" required
                    placeholder="Wakil Dekan Bidang Pendidikan dan Kemahasiswaan" class="form-control"
                    autocomplete="off" value="<?= $row->jabatan_surat_tindaklanjut; ?>">
            </div>
            <div class="form-group">
                <label>Nomor Surat yang Ditindak Lanjut</label><span style="color:red;">*</span>
                <input type="text" name="no_surat_tindaklanjut" placeholder="Masukkan Surat yang Ditindak Lanjuti"
                    required class="form-control" autocomplete="off" value="<?= $row->no_surat_tindaklanjut; ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Surat yang Ditindak Lanjut</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_surat_tindaklanjut" required
                        onchange="get_nomor()" required value="<?= $row->tanggal_surat_tindaklanjut; ?>">
                    <!-- <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_surat_tugas" value="<?= $row->tanggal_pengajuan; ?>"> -->
                </div>
            </div>

            <?php if ($row->tipe_surat == "PERMOHONAN"): ?>
                <div class="form-group">
                    <label>Batas Waktu Tanggapan</label><span style="color:red;">*</span><br />
                    <div class="col-sm-5">
                        <input type="date" class="form-control" name="tanggal_deadline" onchange="get_nomor()" required
                            value="<?= $row->tanggal_deadline; ?>">
                        <!-- <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_surat_tugas" value="<?= $row->tanggal_pengajuan; ?>"> -->
                    </div>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>Departemen Pembuat</label><span style="color:red;">*</span>
                <select class="form-control" name="departemen_pembuat">
                    <?php foreach ($row->departemens as $departemen): ?>
                        <option value="<?= $departemen->nama_departemen; ?>"
                            <?= $row->departemen_pembuat == $departemen->nama_departemen ? 'selected' : ''; ?>> <?=
                                        $departemen->nama_departemen; ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class=" form-group">
                <label>Lampiran
                    <?= $row->tipe_surat == 'PERMOHONAN' ? "Permintaan Dosen untuk Matakuliah" : "Usulan Dosen Pengampu"; ?>
                </label><span style="color:red;">*</span><br>
                <span style="color:green;">Cara copy paste table dari Ms. Word/Excel ke dalam lampiran surat tugas: <br>
                    1. Buka <a href='https://docs.google.com/spreadsheets/u/0/'
                        style="color:white;background-color: blue;" target='_blank'>Google Sheet</a>, kemudian buat
                    dokumen baru.<br>
                    2. Copy paste table dari Ms. word/excel ke Google Sheet.<br>
                    3. Copy table yang ada di Google Sheet tsb. dan paste ke bagian lampiran form surat tugas.<br>
                    4. Untuk merapikan table, bisa dilakukan dengan cara blok table kemudian klik kanan dan pilih table
                    properties (<span style=" color:red;">*saran: sesuaikan ukuran cell spacing dan cell
                        padding</span>).<br>
                </span>
                <textarea id="basic-example" name="tabel">
                <?php if (!empty($row->tabel)): ?>
                                                                                                <?= $row->tabel; ?>
                <?php elseif ($row->tipe_surat == 'PERMOHONAN'): ?>
                                                                                                <table style="border-collapse: collapse; width: 100%;" border="1">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th style="width: 15%;"><strong>Kode</strong></th>
                                                                                                    <th style="width: 25%;"><strong>Matakuliah</strong></th>
                                                                                                    <th style="width: 20%;"><strong>Prodi</strong></th>
                                                                                                    <th style="width: 15%;"><strong>Peserta</strong></th>
                                                                                                    <th style="width: 25%;"><strong>Jadwal</strong></th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                <?php else: ?>
                                                                                            <table style="border-collapse: collapse; width: 100%;" border="1">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th style="width: 12.5%;"><strong>Matakuliah</strong></th>
                                                                                                    <th style="width: 12.5%;"><strong>Prodi & klas</strong></th>
                                                                                                    <th style="width: 25%;"><strong>Dosen</strong></th>
                                                                                                    <th style="width: 15%;"><strong>NIP/NIKA</strong></th>
                                                                                                    <th style="width: 15%;"><strong>Pangkat, gol & jabatan</strong></th>
                                                                                                    <th style="width: 20%;"><strong>No. telp</strong></th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                    </table>
                <?php endif ?>
                </textarea>
            </div>

            <?php if ($row->tipe_surat == "TANGGAPAN"): ?>
                <div class="form-group">
                    <label>Informasi Tambahan</label>
                    <textarea class="form-control" name="paragraf_baru" id="informasi-tambahan"
                        placeholder="Masukkan Informasi Tambahan Jika Diperlukan">
                                                                                        <?php if (!empty($row->paragraf_baru)): ?>
                                                                                                                                                <?= $row->paragraf_baru; ?>
                                                                                        <?php elseif ($row->tipe_surat == 'TANGGAPAN'): ?>
                                                                                                                                                <div>Bersama ini pula kami sertakan ketentuan yang hendaknya menjadi perhatian, antara lain :</div>
                                                                                                                                                         <div style="text-align: justify;">
                                                                                                                                                         <ol>
                                                                                                                                                         <li>Jumlah dosen yang ditugaskan sudah menjadi ketetapan departemen dengan memperhatikan beban kerja masing-masing dosen.</li>
                                                                                                                                                         <li>Setiap kelas maksimum berisi .......... mahasiswa dengan dilengkapi sarana perkuliahan yang memadai sesuai kebutuhan pengajaran ..............., jika peserta lebih dari ......... mahasiswa maka prodi wajib menyediakan asisten dosen (asisten dapat diusulkan oleh dosen pengampu matakuliah) dengan biaya (HR asisten) ditanggung oleh prodi tersebut.</li>
                                                                                                                                                         <li>Penjadwalan perkuliahan hendaknya diadakan selain hari ........ jam xx:xx &ndash; xx:xx, karena waktu tersebut digunakan untuk rapat dosen departemen dan mohon diatur sedemikian rupa sehingga tidak bentrok dengan jadwal mengajar di FMIPA dengan memperhatikan jadwal yang telah ditentukan di Simaster.</li>
                                                                                                                                                         <li>Silabus setiap matakuliah mohon dapat dikirim melalui email ....................</li>
                                                                                                                                                         </ol>
                                                                                                                                                             </div>
                                                                                            <?php endif ?>
                                                                                        </textarea>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>Verifikator</label><span style="color:red;">*</span>
                <select class="form-control" name="departemen_pegawai_id">
                    <?php foreach ($departemens as $departemen): ?>
                        <option value="<?= $departemen->kepala_pegawai_id; ?>"
                            <?= $row->departemen_pegawai_id == $departemen->kepala_pegawai_id ? 'selected' : ''; ?>> <?=
                                        $departemen->nama_departemen; ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tembusan</label>
                <div id="list-tembusan">
                    <?php
                    $db = \Config\Database::connect();
                    $results = $db->query("SELECT nama_publikasi FROM departemen JOIN pegawais ON departemen.kepala_pegawai_id = pegawais.id")->getResult();
                    $vals = []; foreach ($results as $result) {
                        $vals[] = $result->nama_publikasi;
                    }
                    ?>
                    <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                        <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]" value="">
                        <span style="color:red;">&nbsp;&nbsp;otomatis surat dibagikan</span>
                    </div>
                    <?php foreach ($row->tembusan as $tembusan): ?>
                        <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                            <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]"
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
                            <td><i style="cursor:pointer;"
                                    onclick="navigator.clipboard.writeText($('#user_nama').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td id="user_nip"></td>
                            <td><i style="cursor:pointer;"
                                    onclick="copas($('#user_nip').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Prodi</td>
                            <td id="user_prodi"></td>
                            <td><i style="cursor:pointer;"
                                    onclick="copas($('#user_prodi').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Departemen</td>
                            <td id="user_departemen"></td>
                            <td><i style="cursor:pointer;"
                                    onclick="copas($('#user_departemen').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Pangkat</td>
                            <td id="user_pangkat"></td>
                            <td><i style="cursor:pointer;"
                                    onclick="copas($('#user_pangkat').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Golongan</td>
                            <td id="user_golongan"></td>
                            <td><i style="cursor:pointer;"
                                    onclick="copas($('#user_golongan').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success" name="status" value="preview">Simpan Draft dan Tampilkan
                    Preview</button>
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Surat Tugas</button>
            </div>

        </form>
    </div>
</div>

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
    $(function () {
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
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
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

    //NOMOR SURAT PARSING =======================================================================================================
    let get_nomor = () => {
        let el = event.target
        let regExp = /\[([^)]+)\]/
        if (el.id.includes("kode_dokumen_id")) {
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
            let tahun = (new Date($("input[name=tanggal_pengajuan]").val())).getFullYear()
            pengolah = regExp.exec(pengolah.options[pengolah.selectedIndex].innerHTML)[1]
            kode_perihal = regExp.exec(kode_perihal.options[kode_perihal.selectedIndex].innerHTML)[1]
            klasifikasi = klasifikasi.options[klasifikasi.selectedIndex].innerHTML.split(" - ")[0].replace(' ', '')
            penandatangan = regExp.exec(penandatangan.options[penandatangan.selectedIndex].innerHTML)[1]
            no_surat.value = `/UN1/${penandatangan}/${pengolah}/${kode_perihal}.${klasifikasi}/${tahun}`
        } catch (e) { }
    }
    //=======================================================================================================================

    $(document).ready(function (e) {
        //custom penomoran surat tugas
        $('select[name="dt[pengolah_surat_id]"]').val("136");
        $('select[name="dt[kode_dokumen_id]"]').val("16");
        $('select[name="penandatangan_pegawai_id"]').val("129");
        $('select[name="dt[pengolah_surat_id]"]').select2();
        $('select[name="dt[kode_dokumen_id]"]').select2();
        $('select[name="penandatangan_pegawai_id"]').select2();
        fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_TD.html'), {
            cache: 'no-store'
        }).then(response => response.text()).then(data => {
            if (data != 'error') {
                document.querySelector('#klasifikasi').innerHTML = data
                $('select[name="dt[klasifikasi_id]"]').val("366")
                $('select[name="dt[klasifikasi_id]"]').select2()
            }
        })

        var availableJabatan = <?= json_encode($jabatan); ?>;
        $('input[name="jabatan_surat_tindaklanjut"]').autocomplete({
            source: availableJabatan
        })

        $('#tabel-search').hide();
        $("#pegawai-search").autocomplete({
            minLength: 0,
            source: function (request, response) {
                $.ajax({
                    url: "<?= base_url('home/autocomplete'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: response
                });
            },
            focus: function (event, ui) {
                $("#pegawai-search").val(ui.item.username);
                return false;
            },
            select: function (event, ui) {
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
            .autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<div>" + item.nama_publikasi + "<br>" + item.nip + "</div>")
                    .appendTo(ul);
            };

        $("#add-tembusan").click(function () {
            $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control tags" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `)

            var availableTags = <?= json_encode($vals); ?>;
            $(".tags").autocomplete({
                source: availableTags
            });

            $('.tembusan-btn-delete').click(function () {
                console.log($(this).closest('.tembusan-item'));
                $(this).closest('.tembusan-item').remove();
            });
        });

        $('.tembusan-btn-delete').click(function () {
            console.log($(this).closest('.tembusan-item'));
            $(this).closest('.tembusan-item').remove();
        });
    });
</script>
<?= $this->endSection() ?>
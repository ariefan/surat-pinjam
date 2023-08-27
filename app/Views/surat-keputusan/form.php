<?= $this->extend('layout/app') ?>


<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>
            <?= $action == 'update' ? 'Edit' : 'Tambah'; ?> Surat Keputusan
        </h4>
    </div>
    <div class="card-body">

        <form action="<?= site_url('suratkeputusan/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
            method="post" enctype="multipart/form-data">

            <?php if (session('jenis_user') == 'verifikator'): ?>
                <div class="card my-4" style="border:solid #aaa 1px;">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Pengolah</label>
                            <?= view('data-nomor-surat/pengolah_surat.html'); ?>
                        </div>
                        <div class="form-group">
                            <label>Kode Perihal</label>
                            <?= view('data-nomor-surat/kode_perihal.html'); ?>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi</label>
                            <span id="klasifikasi"></span>
                        </div>
                        <!-- <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" onchange="get_nomor()" class="form-control" id="no_surat-tahun" value="<?= date('Y'); ?>">
                        </div> -->
                        <div class="form-group">
                            <label>Penandatangan</label>
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
                        <input type="hidden" name="no_surat" id="no_surat" value="<?= $row->no_surat; ?>">
                    </div>
                </div>
            <?php endif ?>


            <div class="form-group">
                <label>Nama Surat</label>
                <input type="text" class="form-control" name="nama_surat" value="<?= $row->nama_surat; ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Surat Keputusan</label>
                <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()"
                    value="<?= $row->tanggal_pengajuan; ?>">
            </div>
            <div class="form-group">
                <label>Kategori Kegiatan</label><span style="color:red;">*</span>
                <?php
                $kategories = [
                    'Bidang Umum',
                    'Bidang Pendidikan, Pengajaran, dan Kemahasiswaan',
                    'Bidang Keuangan, Aset, dan Sumber Daya Manusia',
                    'Bidang Penelitian dan Pengabdian kepada Masyarakat',
                    'Bidang Alumni, Kerjasama, dan Inovasi',
                ];
                ?>
                <select class="form-control" name="kategori">
                    <?php foreach ($kategories as $kat): ?>
                        <option value="<?= $kat; ?>" <?= $row->kategori == $kat ? 'selected' : ''; ?>><?= $kat; ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Menimbang</label>
                <textarea class="form-control sk" name="menimbang">
                    <?= $row->menimbang; ?>
                </textarea>
            </div>

            <div class="form-group">
                <div class="card my-4" style="background-color: #ddd; border:solid #aaa 1px;">
                    <div class="card-body">
                        <label>Cari Peraturan</label>
                        <input type="text" class="form-control form-control-sm" id="peraturan-search"
                            placeholder="Cari kata kunci">
                        <table class="table table-sm table-bordered" id="tabel-cari">
                            <tbody id="peraturan-list">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Mengingat</label>
                <textarea class="form-control sk" name="mengingat">
                    <?= $row->mengingat; ?>
                </textarea>
            </div>

            <div class="form-group">
                <label>Memperhatikan</label>
                <textarea class="form-control sk" name="memperhatikan">
                    <?= $row->memperhatikan; ?>
                </textarea>
            </div>

            <div class="form-group">
                <label>Memutuskan</label>
                <textarea class="form-control sk" name="memutuskan">
                <?php if (empty($row->memutuskan)): ?>
                        <table>
                            <tr>
                                <td style="vertical-align: top;">Menetapkan</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;text-align:justify;">
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">KESATU</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;text-align:justify;">
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">KEDUA</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;text-align:justify;">
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">KETIGA</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;text-align:justify;">
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">KEEMPAT</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;text-align:justify;">
                                    </li>
                                </td>
                            </tr>
                    </table>
                <?php else: ?>
                        <?= $row->memutuskan; ?>
                <?php endif ?>
            </textarea>
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
                <label>Tembusan</label>
                <div id="list-tembusan">
                    <?php foreach ($row->tembusan as $tembusan): ?>
                        <div class="input-group input-group-sm mb-1 tembusan-item">
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

            <div class="form-group">
                <label for="">Upload File Dasar Penerbitan Surat Keputusan (pdf maks 2MB)</label><span
                    style="color:red;">*</span>
                <input type="file" accept="application/pdf" id="berkas" name="berkas" class="form-control"
                    onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
                <?php if (file_exists('upload/dasar_penerbitan_surat_keputusan/' . $row->id . '.pdf')): ?>
                    <a target="__blank"
                        href="<?= base_url('upload/dasar_penerbitan_surat_keputusan/' . $row->id . '.pdf') ?>">Dasar
                        Penerbitan</a>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label>Lampiran Anggota</label><span style="color:red;">*</span>
                <textarea class="sk" name="lampiran">
                <?php if (!empty($row->lampiran)): ?>
                        <?= $row->lampiran; ?>
                <?php else: ?>
                        <table style="border-collapse: collapse; width: 100%;" border="1">
                            <thead>
                                <tr>
                                    <th style="width: 2.5%;">No</th>
                                    <th style="width: 50%;">Nama</th>
                                    <th style="width: 20%;">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                <?php endif ?>
                </textarea>
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
                            <td><i style="cursor:pointer;" onclick="copas($('#user_nama').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td id="user_nip"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_nip').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Prodi</td>
                            <td id="user_prodi"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_prodi').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Departemen</td>
                            <td id="user_departemen"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_departemen').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Pangkat</td>
                            <td id="user_pangkat"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_pangkat').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                        <tr>
                            <td>Golongan</td>
                            <td id="user_golongan"></td>
                            <td><i style="cursor:pointer;" onclick="copas($('#user_golongan').text());return false;"
                                    class="fa-solid fa-copy" title="copy"></i></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- <div class=" form-group">
                <button type="reset" class="btn btn-warning">Reset</button>
                <?php if (empty($row->status)): ?>
                    <button type="submit" value="0" name="status" class="btn btn-outline-primary">Simpan Draft</button>
                <?php endif ?>
                <button type="submit" value="1" name="status" class="btn btn-primary">Ajukan Surat Keputusan</button>
            </div> -->
            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success" name="status" value="preview">Simpan Draft</button>
                <button type="submit" class="btn btn-primary" name="status" value="1">Ajukan Surat Keputusan</button>
            </div>
        </form>

    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
    tinymce.init({
        selector: 'textarea.sk',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
        menubar: '',
        toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
        toolbar_sticky: true,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    function copas(text) {
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

    $(document).ready(function (e) {

        $("#peraturan-search").on('input', function (e) {
            $.ajax({
                url: "<?= base_url('peraturan/data'); ?>",
                dataType: "json",
                data: {
                    q: $(this).val(),
                },
                success: function (result) {
                    let s = '';
                    result.forEach(item => {
                        s += `
                            <tr>
                                <td>` + item.peraturan + `</td>
                                <td><button type="button" class="btn btn-sm btn-primary" onclick="navigator.clipboard.writeText('` + item.peraturan + `');" title="Copy"><i class="fa-solid fa-copy" title="copy"></i></button></td>
                            </tr>
                        `;
                    });
                    $(".btn-clipboard").tooltip();
                    $("#peraturan-list").html(s);
                }
            });
        });

        $("#add-tembusan").click(function () {
            $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item">
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
    });
</script>
<?= $this->endSection() ?>
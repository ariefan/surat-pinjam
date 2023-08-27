<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h4>Penomoran Surat</h4>
    </div>
    <div class="card-body">
        <form action="<?= base_url('penomoransurat/store'); ?>" method="post">
            <div class="card my-4" style="border:solid #aaa 1px;">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            Perihal <span style="color:red;"><b>*</b></span>
                        </label>
                        <input placeholder="Perihal Surat" autocomplete="off" type="text" class="form-control"
                            name="dt[surat_keluar_perihal]" value="">
                    </div>
                    <div class="form-group">
                        <label>
                            Penandatangan Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <!-- <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
                            <option>- Pilih Penandatangan Surat -</option>
                            <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan): ?>
                                <option value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]</option>
                            <?php endforeach ?>
                        </select> -->
                        <?= view('data-nomor-surat/penandatangan_surat.html'); ?>
                    </div>
                    <div class="form-group">
                        <label>
                            Pengolah Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <?= view('data-nomor-surat/pengolah_surat.html'); ?>
                    </div>
                    <div class="form-group">
                        <label>
                            Kode Perihal <span style="color:red;"><b>*</b></span>
                        </label>
                        <?= view('data-nomor-surat/kode_perihal.html'); ?>
                    </div>
                    <div class="form-group">
                        <label>
                            Klasifikasi Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <span id="klasifikasi"></span>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Tujuan Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <input placeholder=" Instansi/Organisasi/Jabatan" autocomplete="off" type="text"
                                class="form-control" name="dt[surat_keluar_instansi]" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Sifat Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <select name="dt[sifat_id]" class="form-control" tabindex="-1" aria-hidden="true">
                                <option value="">- Pilih Sifat Surat -</option>
                                <option value="1">Biasa </option>
                                <option value="2">Segera </option>
                                <option value="3">Rahasia </option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Tanggal Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <input type="date" autocomplete="off" name="dt[surat_keluar_tgl]" onchange="get_nomor()"
                                class="form-control bsdatepicker" value="" placeholder="Tanggal Surat">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label">
                            Jumlah Nomor Surat Yang Ingin Diambil
                        </label>
                        <input type="number" name="jml_no_surat" value="1" class="form-control" />
                    </div>
                    <div class="row form-group">
                        <button type="submit" class="btn btn-primary">Ambil Nomor Surat</button>
                    </div>
                    <div class="row form-group">
                        <label class="text-danger">*Nomor surat yang sudah diambil tidak bisa dibatalkan</label>
                    </div>
                    <!-- <div class="form-group">
                        <label>Nomor Surat</label>
                        <input type="text" class="form-control" name="no_surat" id="no_surat" value="">
                    </div> -->
                    <input type="hidden" name="no_surat" id="no_surat" value="">
                </div>
            </div>
        </form>
    </div>

    <?php if (in_array(session('jenis_user'), ['verifikator', 'admin', 'tendik', 'dekan', 'wadek', 'dosen', 'departemen'])) {
        ?>
        <div class="card-body" id="tabel_no_surat">

            <form action="<?= site_url("penomoransurat/index"); ?>#tabel_no_surat" method="get">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                    </div>
                </div>
            </form>

            <?= str_replace(
                '<li >',
                '<li class="page-item">',
                str_replace(
                    '<li class="',
                    '<li class="page-item ',
                    str_replace("<a ", '<a class="page-link" ', $pager->links())
                )
            ); ?>
            <div class="card my-4" style="border:solid #aaa 1px;">
                <table class="table table-bordered table-valign-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width:320px;">No Surat</th>
                            <th style="width:380px;">Perihal</th>
                            <th style="width:380px;">Tujuan Surat</th>
                            <th>Sifat Surat</th>
                            <th style="width:100px;">Tanggal Surat</th>
                            <th>Pengaju</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php
                    // $db = \Config\Database::connect();
                    // $result = $db->query("
                    //         SELECT detail_no_surat.id, no_surat, perihal, tujuan_surat, sifat_surat, tanggal_surat, nama_publikasi FROM detail_no_surat JOIN users ON users.id = detail_no_surat.user_id JOIN pegawais ON users.id = pegawais.user_id ORDER BY detail_no_surat.created_at DESC")->getResult();
                    foreach ($rows as $r) { ?>
                        <tr>
                            <th scope="row">
                                <?= (empty($no) ? $no = 1 : ++$no) + ((($_GET['page'] ?? 1) - 1) * 50); ?>
                            </th>
                            <td>
                                <?= str_replace('-', ' sampai dengan ', $r->no_surat); ?>
                            </td>
                            <td>
                                <?= ($r->sifat_surat == 3) ? ($r->user_id_pembuat == session('id') ? $r->perihal : '-') : $r->perihal ?>
                            </td>
                            <td>
                                <?= ($r->sifat_surat == 3) ? ($r->user_id_pembuat == session('id') ? $r->tujuan_surat : '-') : $r->tujuan_surat ?>
                            </td>
                            <td>
                                <?php
                                switch ($r->sifat_surat) {
                                    case 1:
                                        echo 'Biasa';
                                        break;
                                    case 2:
                                        echo 'Segera';
                                        break;
                                    default:
                                        echo 'Rahasia';
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <?= ($r->sifat_surat == 3) ? ($r->user_id_pembuat == session('id') ? $r->tanggal_surat : '-') : $r->tanggal_surat ?>
                            </td>
                            <td>
                                <?= $r->nama_publikasi; ?><br>
                                <!-- <?= $r->user_id_pembuat . ', ' . session('id') ?> -->
                                <span style="color: blue;">
                                    <?= $r->tanggal_buat; ?>
                                </span>
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if ($r->sifat_surat != 3 || $r->user_id_pembuat == session('id')): ?>
                                        <a class="btn btn-warning" title="Edit"
                                            href="<?= base_url("penomoransurat/edit/" . $r->id); ?>">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-primary" title="Upload"
                                            href="<?= base_url("penomoransurat/upload/" . $r->id); ?>">
                                            <i class="fa-solid fa-upload"></i>
                                        </a>
                                        <?php if (file_exists("upload/penomoran_surat/$r->id.pdf") || file_exists("upload/penomoran_surat/$r->id.zip")): ?>
                                            <a class="btn btn-success" title="Download"
                                                href="<?= base_url("penomoransurat/download/" . $r->id); ?>">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a class="btn btn-warning" title="Edit"
                                        href="<?= base_url("penomoransurat/edit/" . $r->id); ?>"><i
                                            class="fa-solid fa-pencil"></i></a>
                                    <a class="btn btn-primary" title="Upload"
                                        href="<?= base_url("penomoransurat/upload/" . $r->id); ?>"><i
                                            class="fa-solid fa-upload"></i></a>
                                    <?php if (file_exists("upload/penomoran_surat/$r->id.pdf") || file_exists("upload/penomoran_surat/$r->id.zip")): ?>
                                        <a class="btn btn-success" title="Download"
                                            href="<?= base_url("penomoransurat/download/" . $r->id); ?>"><i
                                                class="fa-solid fa-download"></i></a>
                                    <?php endif; ?>
                                </div>
                            </td> -->
                        </tr>
                        <?php
                    }
    }
    ?>
            </table>
        </div>

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


<!-- No Surat -->
<div class=" modal fade" id="modal-nosurat" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="padding:20px;">
            <b>Nomor Surat :</b>
            <?= session()->getFlashData('no_surat'); ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    //Wajib diisi
    $('input').prop('required', true)
    $('select').prop('required', true)

    let no_surat = "<?= session()->getFlashData('no_surat'); ?>"
    if (no_surat.length > 0) {
        copas(no_surat)
        $('#modal-nosurat').modal('show')
    }

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
        window.scrollTo({ top: 0, behavior: 'smooth' });
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
            let tahun = (new Date($("input[name='dt[surat_keluar_tgl]']").val())).getFullYear()
            pengolah = regExp.exec(pengolah.options[pengolah.selectedIndex].innerHTML)[1]
            kode_perihal = regExp.exec(kode_perihal.options[kode_perihal.selectedIndex].innerHTML)[1]
            klasifikasi = klasifikasi.options[klasifikasi.selectedIndex].innerHTML.split(" - ")[0].replace(' ', '')
            penandatangan = regExp.exec(penandatangan.options[penandatangan.selectedIndex].innerHTML)[1]
            no_surat.value = `/UN1/${penandatangan}/${pengolah}/${kode_perihal}.${klasifikasi}/${tahun}`

            //Wajib diisi
            $('input').prop('required', true)
            $('select').prop('required', true)

        } catch (e) { }
    }

    try {
        let arr = $("input[name=no_surat]").val().split("/")
        let pengolah = arr[3]
        let kode_perihal = arr[4].split('.')[0]
        let klasifikasi = arr[4].split('.')[1] + (arr[4].split('.').length > 2 ? '.' + arr[4].split('.')[2].replace(' ', '') : '')
        let tahun = arr[5]
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
</script>

<?= $this->endSection() ?>
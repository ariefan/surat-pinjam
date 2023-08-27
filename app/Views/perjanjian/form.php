<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    input::file-selector-button {
        font-weight: bold;
        color: dodgerblue;
        border: thin solid grey;
        border-radius: 3px;
    }

    input[type="file"]:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Submit Dokumen Kerjasama</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('perjanjian/' . $action) . ($action == 'update' ? '/' . $row->id_mou : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Tipe Dokumen</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">

                    <select type="text" class="form-control" list="tipedok" name="tipe_dokumen" placeholder="Pilih Tipe Dokumen" value="<?= $row->tipe_dokumen; ?>">
                        <?php if ($row->tipe_dokumen == NULL) : ?>
                            <option disabled selected value> Pilih Tipe Dokumen </option>
                        <?php else : ?>
                            <option value="<?= $row->tipe_dokumen; ?>"><?= $row->tipe_dokumen; ?></option>
                        <?php endif; ?>
                        <option value="MoU">MoU</option>
                        <option value="PKS">PKS</option>
                        <option value="SPK / Kontrak">SPK / Kontrak</option>
                        <option value="MoA">MoA</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Tanggal Pembuatan Kerjasama</label><span style="color:red;">*</span><br />
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()" value="<?= $row->tanggal_pengajuan; ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Judul Kerjasama</label><span style="color:red;">*</span><br />
                <input type="text" class="form-control" name="judul_kerjasama" value="<?= $row->judul_kerjasama; ?>">
            </div>
            <div class="form-group">
                <label>Negara</label><span style="color:red;">*</span><br />
                <input type="text" class="form-control" name="negara" value="<?= $row->negara; ?>">
            </div>
            
            <div class="form-group">
                <label>Program Studi Terlibat</label><span style="color:red;">*</span>
                <select name="program_studi_terlibat[]" multiple="multiple" id="prodi" class="form-control">
                    <?php
                    if (isset($row->program_studi_terlibat)) {
                        foreach ($options->program_studi as $option) {
                            $selected = in_array($option, $row->program_studi_terlibat) ? 'selected' : ''; // Check if the option should be selected
                            echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                        }
                    } else {
                        foreach ($options->program_studi as $option) {
                            echo '<option value="' . $option . '" >' . $option . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Bidang Kerjasama</label><span style="color:red;">*</span>
                <select multiple="multiple" name="bidang_kerjasama[]" id="bidKjsm" class="form-control">
                    <?php if (isset($row->bidang_kerjasama)) : ?>
                        <optgroup label="UMUM">
                            <?php foreach ($options->umum as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                        <optgroup label="AKADEMIK/PENDIDIKAN">
                            <?php foreach ($options->akademik as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                        <optgroup label="PENDIDIKAN NON GELAR">
                            <?php foreach ($options->non_gelar as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                        <optgroup label="PENELITIAN">
                            <?php foreach ($options->penelitian as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                        <optgroup label="Lain-lain">
                            <?php foreach ($options->lain as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <?php foreach ($row->custom_option as $option) {
                                $selected = in_array($option, $row->bidang_kerjasama) ? 'selected' : ''; // Check if the option should be selected
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            } ?>
                        </optgroup>
                    <?php else : ?>
                        <?= view('perjanjian/bidang_kerjasama.html'); ?>
                    <?php endif; ?>
                </select>

                <div class="row mt-3">
                    <div class="col-4">
                        <input type="text" id="customInput" class="form-control" name="bidang_kerjasama_lain" placeholder="Isikan Bidang Kerjasama lain jika ada">
                    </div>
                    <div class="col-2">
                        <button class="btn-sm btn-success" id="addCustom"> Add Option </button>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Nominal Kerjasama</label>
                <div class="row">

                    <div class="col-2">
                        <select style="width:10rem;" name="currency" id="currency">
                            <option disabled selected value="">Select Kurs</option>
                            <?php foreach ($kurs as $key => $value) : ?>
                                <option value="<?= $key; ?>" <?= $row->currency == $key ? 'selected' : ''; ?>><?= $value; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-5">
                        <input type="number" data-type="currency" onchange="nominalKerjasamaFormatting()" name=nominal_kerjasama class="form-control" id="nominal_kerjasama" placeholder="Isi nominal tanpa titik. Ex 5 juta = 5000000" value=<?= $row->nominal_kerjasama; ?>>
                        <input readonly type="text" name=nominal_kerjasama_preview class="form-control mt-2" id="nominal_kerjasama_preview">
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Jangka Waktu Kerjasama</label><span style="color:red;">*</span><br />
                <div class="row">
                    <div class="form-check ml-3 mb-2">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="opsi_tgl1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            sampai
                        </label>
                    </div>
                    <div class="form-check ml-3 mb-2">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="opsi_tgl2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Tidak dibatasi
                        </label>
                    </div>
                </div>
                <div id="list-rentang-tanggal_kegiatan">
                    <div class="row ml-1">
                        <input class="form-control" style="width:5rem;" type="number" name="tahun" placeholder="Tahun" id="tahun" value="<?= $row->jangka_waktu['tahun']; ?>">
                        <span class="mt-2 pl-2 pr-4">Tahun</span>
                        <input class="form-control" style="width:5rem;" type="number" name="bulan" placeholder="Bulan" id="bulan" value="<?= $row->jangka_waktu['bulan']; ?>">
                        <span class="mt-2 pl-2 pr-4">Bulan</span>
                    </div>
                </div>


                <div class="form-group mt-3">
                    <label>Data Institusi Mitra</label>
                    <div class="card-body" style="background-color: #ddd; border:solid #aaa 1px;">
                        Cari: <input id="mitra-search" class="form-control">
                    </div>
                    <input class="form-control mb-2" type="text" id="nama_mitra" name="nama_mitra" placeholder="Masukkan Nama Mitra" autocomplete="off" value="<?= $row->nama_mitra; ?>">
                    <input class="form-control mb-2" type="text" id="alamat_mitra" name="alamat_mitra" placeholder="Masukkan Alamat Mitra" autocomplete="off" value="<?= $row->alamat_mitra; ?>">
                    <input class="form-control mb-2" type="number" id="no_telp_mitra" name="no_telp_mitra" placeholder="Masukkan No Telepon Mitra" autocomplete="off" value="<?= $row->no_telp_mitra; ?>">
                    <input class="form-control mb-2" type="email" id="email_mitra" name="email_mitra" placeholder="Masukkan Email Mitra" autocomplete="off" value="<?= $row->email_mitra; ?>">
                </div>
                <div class="form-group">
                    <label>PIC Mitra</label>
                    <input class="form-control mb-2" type="text" name="nama_pic_mitra" placeholder="Masukkan Nama PIC Mitra" autocomplete="off" value="<?= $row->nama_pic_mitra; ?>">
                    <input class="form-control mb-2" type="text" name="jabatan_pic_mitra" placeholder="Masukkan Jabatan PIC Mitra" autocomplete="off" value="<?= $row->jabatan_pic_mitra; ?>">
                    <input class="form-control mb-2" type="text" name="alamat_pic_mitra" placeholder="Masukkan Alamat PIC Mitra" autocomplete="off" value="<?= $row->alamat_pic_mitra; ?>">
                    <input class="form-control mb-2" type="number" name="no_telp_pic_mitra" placeholder="Masukkan No Telepon PIC Mitra" autocomplete="off" value="<?= $row->no_telp_pic_mitra; ?>">
                    <input class="form-control mb-2" type="email" name="email_pic_mitra" placeholder="Masukkan Email PIC Mitra" autocomplete="off" value="<?= $row->email_pic_mitra; ?>">
                </div>
                <div class="form-group">
                    <label>Pejabat Penandatangan</label>
                    <select name="pejabat_penandatanganan_ugm" id="pejabat_penandatanganan_ugm" class="form-control">
                            <?php 
                            $ttdugm = [
                                'Prof. Dr.Eng. Kuwat Triyana, M.Si.',
                                'Prof. dr. Ova Emilia M.Med.Ed., Sp.OG(K)., Ph.D.',
                            ];
                            foreach ($ttdugm as $pejabat) : ?>
                                <option value="<?= $pejabat; ?>" <?= $row->pejabat_penandatanganan_ugm == $pejabat ? 'selected' : ''; ?>><?= $pejabat; ?></option>
                            <?php endforeach ?>
                    </select>
                    <!-- <input class="form-control mb-2" type="text" name="pejabat_penandatanganan_ugm" placeholder="Masukkan Nama Penandatanganan UGM" autocomplete="off" value="<?= $row->pejabat_penandatanganan_ugm; ?>"> -->
                    <input class="form-control my-2" type="text" name="pejabat_penandatanganan_mitra" placeholder="Masukkan Nama Penandatanganan Mitra" autocomplete="off" value="<?= $row->pejabat_penandatanganan_mitra; ?>">
                </div>

                <div class="form-group">
                    <label for="">Dana Pengembangan Institusi berdasarkan Peraturan Rektor Nomor 8 Tahun 2021 &nbsp;&nbsp;</label>
                    <button type="button" style="color:red;" class="btn" data-toggle="modal" data-target="#ModalDPI"><i class="fa fa-info-circle"></i></button>
                    <label for=""> <i>
                            <= Peraturan Rektor Nomor 8 Tahun 2021</i></label>
                    <br>
                    <div style="display:flex; align-items:center;">
                                <select class="form-control" style="width:70%" name="keterangan_dpi" id="keterangan_dpi">
                                <option value="" disabled selected>Pilih Jenis Kerjasama</option>
                            <?php 
                                    $ketDPI = [
                                        'Pendidikan/Akademik'=>[
                                            'Pendampingan pendidikan akademik/bergelar',
                                            'Pendampingan pendidikan non akademik/bergelar'
                                        ],
                                        'Penelitian'=>[
                                            'Kegiatan penelitian sesuai dengan peraturan perundang-undangan yang berlaku'
                                        ],
                                        'Pengabdian kepada masyarakat'=>[
                                            'Jasa konsultasi',
                                            'Jasa pelatihan atau kegiatan sejenis untuk capacity building',
                                            'Jasa implementasi teknologi',
                                            'Jasa proyek studi untuk kepentingan mitra dan/atau yang bersifat komersial',
                                            'Jasa proyek studi untuk kepentingan mitra dan/atau yang bersifat non-komersial yang menghasilkan luaran'
                                        ],
                                        'Sponsor'=>['Sponsor']
                                    ];
                                    foreach ($ketDPI as $optgroup => $jeniskerjasama) : ?>
                                    <optgroup label='<?= $optgroup;?>'>
                                        <?php foreach ($jeniskerjasama as $option) : ?>
                                            <option value="<?= $option; ?>" <?= $row->keterangan_dpi == $option ? 'selected' : ''; ?>><?= $option; ?></option>
                                        <?php endforeach ?>
                                    </optgroup>
                                    <?php endforeach ?>    
                            </select>
                            <span style="width:5%" class="text-center">=</span>
                            <input readonly class="form-control" type="text" style="width:8%" name="dpi" id="dpi" value="<?= $row->dpi; ?>">
                            <span style="width:5%" class="text-center">%</span>
                    </div>
                </div>


                <div class="form-group">
                    <label for="">Upload File Perjanjian (docx maks. 2MB)</label><span style="color:red;">*</span>
                    <input <?php if ($row->status_mou > 0) {
                                echo 'disabled';
                            } ?> type="file" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" name="dokumen-mou" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
                    <?php if ($row->status_mou > 0) {
                        echo '<span class="badge badge-pill badge-warning">Mohon gunakan halaman <b>Review</b> untuk merevisi kerjasama yang telah diajukan</span>';
                    } ?>
                </div>




                <div class="form-group" id="pic-input">
                    <label>PIC UGM</label>
                    <div class="card-body" style="background-color: #ddd; border:solid #aaa 1px;">
                        Cari: <input id="pic-search" class="form-control">
                    </div>
                    <input onfocus="blur();" required style="background-color: #e9ecef;" type="hidden" id="id_user_pic" name="id_user_pic" value="<?= $row->id_user_pic; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2 mt-2" type="text" name="nama_pic_ugm" id="nama_pic_ugm" placeholder="Nama PIC UGM" autocomplete="off" value="<?= $row->nama_pic_ugm; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2" type="text" name="jabatan_pic_ugm" id="jabatan_pic_ugm" placeholder="Jabatan PIC UGM" autocomplete="off" value="<?= $row->jabatan_pic_ugm; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2" type="text" name="alamat_pic_ugm" id="alamat_pic_ugm" placeholder="PIC UGM" autocomplete="off" value="<?= $row->alamat_pic_ugm; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2" type="number" name="no_telp_pic_ugm" id="no_telp_pic_ugm" placeholder="No Telp PIC UGM" autocomplete="off" value="<?= $row->no_telp_pic_ugm; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2" type="email" name="email_pic_ugm" id="email_pic_ugm" placeholder="Email PIC UGM" autocomplete="off" value="<?= $row->email_pic_ugm; ?>">
                    <input onfocus="blur();" required style="background-color: #e9ecef;" class="form-control mb-2" type="hidden" name="departemen_ugm" id="departemen_ugm" placeholder="Masukkan Email PIC Mitra" autocomplete="off" value="<?= $row->departemen_ugm; ?>">
                </div>



                <div class="mt-5">
                    <?php if ($row->status_mou == 0) : ?>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" id="draft" class="btn btn-success" name="status" value="preview">Simpan Draft dan Tampilkan Preview</button>
                        <button type="submit" id="ajukan" class="btn btn-primary" name="status" value="1">Ajukan Kerjasama</button>
                    <?php else : ?>
                        <button type="submit" id="simpan" class="btn btn-primary" name="status" value="simpan">Simpan data</button>
                    <?php endif; ?>
                </div>

        </form>
    </div>
</div>

<div class="modal fade" id="ModalDPI" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peraturan Rektor Nomor 8 Tahun 2021</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?= base_url('/user_manual/SK_DPI_2021_Final.jpg') ?>" alt="" style="max-height:600px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
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
    function nominalKerjasamaFormatting() {
        let USDollar = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        });

        let IDRupiah = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
        });

        let JapaneseYen = new Intl.NumberFormat('ja-JP', {
            style: 'currency',
            currency: 'JPY',
        });

        let Euro = new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR',
        });

        let currency = $("#currency").val();
        let nominal = $("#nominal_kerjasama").val();

        let text=''
        if (currency == '0') {
            text = IDRupiah.format(nominal)
        } else if (currency == '1') {
            text = USDollar.format(nominal)
        } else if (currency == '2') {
            text = Euro.format(nominal)
        } else if (currency == '3') {
            text = JapaneseYen.format(nominal)
        }

        $("#nominal_kerjasama_preview").val(text)

    }

    $(document).ready(function() {
        $("form").submit(function(e) {
            var statusValue = $('button[name=status]:focus').val();
            if (statusValue == '1' && $.trim($('input[type=file]').val()) == '') {
                e.preventDefault();
                $('input[type=file]').after(`<div class="alert alert-danger mt-2" role="alert">
                                                Mengajukan kerjasama <b> tanpa file kerjasama </b> tidak diperbolehkan.
                                                </div>`);
                $('html, body').animate({
                    scrollTop: $('input[type=file]').offset().top - 100
                }, 500);
            }
        });
        $('#ajukan').click(function() {
            $('div.alert').remove();
        });

        $('#nominal_kerjasama').change(function() {
            nominalKerjasamaFormatting();
        });
        $('#currency').change(function() {
            nominalKerjasamaFormatting();
        });
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

    function optionExists(select, value) {
        return select.find("option[value='" + value + "']").length > 0;
    }

    $(document).ready(function(e) {
        $('#keterangan_dpi').change(function() {
                var selectedOption = $(this).val(); // Get the selected option value

                // Set the input value based on the selected option
                if (selectedOption === 'Pendampingan pendidikan non akademik/bergelar' ||
                    selectedOption === 'Jasa pelatihan atau kegiatan sejenis untuk capacity building' ||
                    selectedOption === 'Jasa implementasi teknologi' ||
                    selectedOption === 'Jasa proyek studi untuk kepentingan mitra dan/atau yang bersifat komersial') {
                    $('#dpi').val('10');
                } else if (selectedOption === 'Jasa konsultasi') {
                    $('#dpi').val('5');
                } else {
                    $('#dpi').val('0');
                }
            });

        $("#addCustom").click(function(e) {
            e.preventDefault();
            var select = $("#bidKjsm");
            var customOptionInput = $("#customInput");
            var customOptionValue = customOptionInput.val().trim();

            // Check if the custom option is not empty and doesn't already exist in the select
            if (customOptionValue !== "" && !optionExists(select, customOptionValue)) {
                var option = $("<option></option>")
                    .val(customOptionValue)
                    .text(customOptionValue)
                    .prop("selected", true); // Make the custom option selected
                select.append(option);
            }

            // Clear the custom option input
            customOptionInput.val("");
        });

        // $('#bidKjsm').on('change', function() {
        //     $('.select2-selection__choice__display').removeClass('ml-3');
        //     $('.select2-selection__choice[title]').find('.select2-selection__choice__display').addClass('ml-3');
        // });
        //         $('#prodi').on('change', function() {
        //     $('.select2-selection__choice__display').removeClass('ml-3');
        //     $('.select2-selection__choice[title]').find('.select2-selection__choice__display').addClass('ml-3');
        //     });
        $("#mitra-search").autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url('perjanjian/mitrasearch'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: response,
                });
            },
            focus: function(event, ui) {
                $("#mitra-search").val(ui.item.nama_mitra);
                return false;
            },
            select: function(event, ui) {
                console.log(ui.item);
                $("#nama_mitra").val(ui.item.nama_mitra);
                $("#alamat_mitra").val(ui.item.alamat_mitra);
                $("#no_telp_mitra").val(ui.item.no_telp_mitra);
                $("#email_mitra").val(ui.item.email_mitra);
                return false;
            }
        })
        $("#mitra-search").autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div>" + item.nama_mitra + "<br>" + item.email_mitra + "</div>")
                .appendTo(ul);
        };

        $("#pic-search").autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url('perjanjian/picsearch'); ?>",
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
                $("#id_user_pic").val(ui.item.id_user_pic);
                $("#nama_pic_ugm").val(ui.item.nama_ugm);
                $("#jabatan_pic_ugm").val(ui.item.jabatan);
                $("#alamat_pic_ugm").val(ui.item.alamat_ugm);
                $("#no_telp_pic_ugm").val(ui.item.no_telp_ugm);
                $("#email_pic_ugm").val(ui.item.email);
                $("#departemen_ugm").val(ui.item.departemen_ugm);
                return false;
            }
        })
        $("#pic-search").autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div>" + item.nama_ugm + "<br>" + item.jabatan + "</div>")
                .appendTo(ul);
        };
        $('#list-rentang-tanggal_kegiatan').show();
        $('#list-tanggal_kegiatan').hide();
        $('#add-tanggal_kegiatan').hide();

        $('#opsi_tgl1').change(() => {
            if ($('#opsi_tgl1').is(':checked')) {
                $('#list-rentang-tanggal_kegiatan').show();
            }
        })
        $('#opsi_tgl2').change(() => {
            if ($('#opsi_tgl2').is(':checked')) {
                $('#list-rentang-tanggal_kegiatan').hide();
                $('#tahun').val('');
                $('#bulan').val('');
            }
        })


    });
</script>
<?= $this->endSection() ?>
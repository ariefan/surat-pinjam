<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h4>Penomoran Surat</h4>
    </div>
    <div class="card-body">


        <form action="<?= site_url('penomoransurat/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="post">
            <div class="card my-4" style="border:solid #aaa 1px;">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            Perihal <span style="color:red;"><b>*</b></span>
                        </label>
                        <input placeholder="Perihal Surat" autocomplete="off" type="text" class="form-control" name="dt[surat_keluar_perihal]"  value="<?= $row->perihal; ?>">
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Tujuan Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <input placeholder=" Instansi/Organisasi/Jabatan" autocomplete="off" type="text" class="form-control" name="dt[surat_keluar_instansi]"  value="<?= $row->tujuan_surat; ?>">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Sifat Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <select name="dt[sifat_id]" class="form-control" tabindex="-1" aria-hidden="true">
                                <option value="">- Pilih Sifat Surat -</option>
                                <option value="1" <?= $row->sifat_surat == 1 ? 'selected' : ''; ?>>Biasa </option>
                                <option value="2" <?= $row->sifat_surat == 2 ? 'selected' : ''; ?>>Segera </option>
                                <option value="3" <?= $row->sifat_surat == 3 ? 'selected' : ''; ?>>Rahasia </option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="row form-group">
                        <label class="col-sm-2 control-label" style="text-align:left;">
                            Tanggal Surat <span style="color:red;"><b>*</b></span>
                        </label>
                        <div class="col-sm-4">
                            <input type="date" autocomplete="off" name="dt[surat_keluar_tgl]" onchange="get_nomor()" class="form-control bsdatepicker"  value="<?= $row->tanggal_surat; ?>" placeholder="Tanggal Surat">
                        </div>
                    </div> -->
                    <div class="row form-group">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                    <!-- <div class="row form-group">
                        <label class="text-danger">*Nomor surat yang sudah diambil tidak bisa dibatalkan</label>
                    </div> -->
                    <!-- <div class="form-group">
                        <label>Nomor Surat</label>
                        <input type="text" class="form-control" name="no_surat" id="no_surat" value="">
                    </div> -->
                    <input type="hidden" name="no_surat" id="no_surat" value="">
                </div>
            </div>
        </form>



        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <!-- <h1 style="text-align:center;font-size:250%;">Mohon Maaf Fitur ini belum tersedia</h1>
        <h1 style="text-align:center;">
            <a style="text-align:center;" class="btn btn-warning" title="kembali" href="<?= base_url("penomoransurat"); ?>">
                <i class="fa-solid fa-back"></i> Kembali
            </a>
        </h1> -->
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
    </div>

    <?= $this->endSection() ?>

    <?= $this->section('js') ?>
    <script>

    </script>
    <?= $this->endSection() ?>
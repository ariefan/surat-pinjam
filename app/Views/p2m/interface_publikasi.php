<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Publikasi</h4>
    </div>

    <form action="<?= site_url("interfacep2m/interface_publikasi"); ?>" method="get">
        <div class="form-group row" style="margin-left: 20px;margin-top: 20px;">
            <div class="col-sm-3">
                <input type="date" name="start_date" class="form-control" placeholder="Tanggal Mulai" value="<?= $start_date; ?>">
            </div>
            <div class="col-sm-3">
                <input type="date" name="end_date" class="form-control" placeholder="Tanggal Akhir" value="<?= $end_date; ?>">
            </div>
            <div class="col-sm-1">
                <button class="btn btn-success" title="Filter" type="submit">Filter</a>
            </div>
        </div>
    </form>

    <div class="container text-center" id="result">
        <div class="row justify-content-md-center">
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah Publikasi</h5>
                <h2><?php echo ($countAll); ?></span></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#ffd4df; min-height: 115px;">
                <h5>Publikasi Jurnal Internasional Bereputasi</h5>
                <h2><?php echo ($jurnalQ); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Jurnal Nasional Terakreditasi</h5>
                <h2><?php echo ($jurnalS); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Jurnal Nasional</h5>
                <h2><?php echo ($publikasiNasional); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Jurnal Internasional</h5>
                <h2><?php echo ($jurnalInternasional); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Jurnal Nasional</h5>
                <h2><?php echo ($jurnalNasional); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Internasional</h5>
                <h2><?php echo ($publikasiInternasional); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Conference</h5>
                <h2><?php echo ($publikasiConference); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Publikasi Ditulis Dengan Mitra Luar Negeri</h5>
                <h2><?php echo ($pubMitra); ?></h2>
            </div>
        </div>
    </div>

    <div class="container" id="result" style="margin-top: 4%;">
        <div class="row justify-content-md-center">
            <div id="chart_1" style="width: 800px; height: 400px"></div>
            <div id="chart_2" style="width: 800px; height: 400px;"></div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?= $this->endSection() ?>
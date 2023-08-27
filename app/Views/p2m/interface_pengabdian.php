<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Pengabdian</h4>
    </div>


    <form action="<?= site_url("interfacep2m/interface_pengabdian"); ?>" method="get">
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

    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-lg-5 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah Dana</h5>
                <h2><?php echo($sum); ?></h2>
            </div>
        </div>
    </div>

    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Jumlah PkM</h5>
                <h2><?php echo($countAll); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#ffdccc; min-height: 115px;">
                <h5>IPTEKS yang dikembangkan FMIPA</h5>
                <h2><?php echo($countIptek); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cce0fe; min-height: 115px;">
                <h5>Jumlah Kabupaten</h5>
                <h2><?php echo($countCity); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah desa binaan dan komunitas</h5>
                <h2><?php echo($countBinaan); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#ffd4df; min-height: 115px;">
                <h5>Publikasi Berbasis Pengabdian kepada Masyarakat</h5>
                <h2><?php echo($countP2m); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah UMKM</h5>
                <h2><?php echo($countUmkm); ?></h2>
            </div>
            <!-- <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah Dana</h5>
                <h2 style="max-width: 95%; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;"><?php echo($sum); ?></h2>
            </div> -->
        </div>
    </div>
    
    <div class="container text-center" style="padding-top: 4%;">
        <div class="row justify-content-md-center">
            <!-- <div class="col-md-6">
                <canvas id="chart_5" style="max-height:300px;"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="chart_6" style="max-height:300px;"></canvas>
            </div> -->
            <div id="chart_1" style="width: 720px; height: 400px;"></div>
            <div id="chart_2" style="width: 720px; height: 400px;"></div>
            <div id="chart_a" style="width: 720px; height: 400px;"></div>
            <div id="piechart" style="width: 900px; height: 500px;"></div>
            <div id="piechart_2" style="width: 900px; height: 500px;"></div>
            <div id="piechart_3" style="width: 900px; height: 500px;"></div>
            <div id="piechart_4" style="width: 900px; height: 500px;"></div>
        </div>  
    </div>
    <p> <?php echo $pkm_fisdana['MANDAT2022']; ?></p>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var counts = <?php echo json_encode(array_values($counts)); ?>;
    var labels = <?php echo json_encode($label); ?>;
    var data = google.visualization.arrayToDataTable([
        ['Departemen', 'Fisika', 'Ilmu Komputer dan Elektronika', 'Kimia', 'Matematika', 'Fakultas', { role: 'annotation' }],
        [labels[0], parseInt(counts[0]), 0, 0, 0, 0, ''],
        [labels[1], 0, parseInt(counts[1]), 0, 0, 0, ''],
        [labels[2], 0, 0, parseInt(counts[2]), 0, 0, ''],
        [labels[3], 0, 0, 0, parseInt(counts[3]), 0, ''],
        [labels[4], 0, 0, 0, 0, parseInt(counts[4]), '']
    ]);
    
    var colors = ['#ff420e', 'rgb(255, 211, 32)', 'rgb(87, 157, 28)', 'rgb(126, 0, 33)', 'rgb(0, 69, 134)'];

    var options = {
        title: 'Jumlah PKM Berdasarkan Departemen',
        isStacked: true,
        series: {
            0: { color: colors[0] },
            1: { color: colors[1] },
            2: { color: colors[2] },
            3: { color: colors[3] },
            4: { color: colors[4] }
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_1'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>

<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var sum_departemen = <?php echo json_encode(array_values($sum_departemen)); ?>;
    var labels = <?php echo json_encode($label); ?>;
    var data = google.visualization.arrayToDataTable([
        ['Departemen', 'Fisika', 'IKE', 'Kimia', 'Matematika', 'Fakultas', { role: 'annotation' }],
        [labels[0], parseInt(sum_departemen[0]), 0, 0, 0, 0, ''],
        [labels[1], 0, parseInt(sum_departemen[1]), 0, 0, 0, ''],
        [labels[2], 0, 0, parseInt(sum_departemen[2]), 0, 0, ''],
        [labels[3], 0, 0, 0, parseInt(sum_departemen[3]), 0, ''],
        [labels[4], 0, 0, 0, 0, parseInt(sum_departemen[4]), '']
    ]);

    console.log(labels[0]);
    console.log(<?php echo json_encode(array_values($sum_departemen)); ?>);
    
    var colors = ['#ff420e', 'rgb(255, 211, 32)', 'rgb(87, 157, 28)', 'rgb(126, 0, 33)', 'rgb(0, 69, 134)'];

    var options = {
        title: 'Jumlah Dana Berdasarkan Departemen',
        // width: 600,
        // height: 400,
        // legend: { position: 'top', maxLines: 3 },
        // bar: { groupWidth: '75%' },
        isStacked: true,
        series: {
            0: { color: colors[0] },
            1: { color: colors[1] },
            2: { color: colors[2] },
            3: { color: colors[3] },
            4: { color: colors[4] }
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_2'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>

<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var pkm_fisdana = <?php echo json_encode(array_values($pkm_fisdana)); ?>;
    var labels = <?php echo json_encode($label); ?>;
    var data = google.visualization.arrayToDataTable([
        ['Departemen', 'Fisika', 'IKE', 'Kimia', 'Matematika', 'Fakultas', { role: 'annotation' }],
        ['MANDAT2022', pkm_fisdana[0], 24, 20, 32, 18, ''],
        ['LINTAS2022', pkm_fisdana[1], 22, 23, 30, 16, ''],
        ['BINAAN2022', pkm_fisdana[2], 19, 29, 30, 12, ''],
        ['LABKIM2022', pkm_fisdana[3], 19, 29, 30, 12, ''],
        ['PKMDM', pkm_fisdana[4], 19, 29, 30, 12, ''],
        ['SWADAYA2021', pkm_fisdana[5], 19, 29, 12, 13, ''],
        ['SWADAYA2022', pkm_fisdana[6], 19, 30, 12, 13, ''],
        ['Luar Negeri (UGM-UNUD-MFRI-MPO)', pkm_fisdana[7], 29, 30, 12, 13, ''],
    ]);

    var colors = ['#ff420e', 'rgb(255, 211, 32)', 'rgb(87, 157, 28)', 'rgb(126, 0, 33)', 'rgb(0, 69, 134)'];

    var options = {
        title: 'Jumlah PKM Berdasarkan Departemen',
        // width: 600,
        // height: 400,
        // legend: { position: 'top', maxLines: 3 },
        // bar: { groupWidth: '75%' },
        isStacked: true,
        series: {
            0: { color: colors[0] },
            1: { color: colors[1] },
            2: { color: colors[2] },
            3: { color: colors[3] },
            4: { color: colors[4] }
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_a'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var pkm_dana = <?php echo json_encode(array_values($pkm_dana)); ?>;
        var labels = <?php echo json_encode($label_pie); ?>;
        var data = google.visualization.arrayToDataTable([
            ['funding_scheme_short', 'Total PKM'],
            [labels[0], parseInt(pkm_dana[0])],
            [labels[1], parseInt(pkm_dana[1])],
            [labels[2], parseInt(pkm_dana[2])],
            [labels[3], parseInt(pkm_dana[3])],
            [labels[4], parseInt(pkm_dana[4])],
            [labels[5], parseInt(pkm_dana[5])],
            [labels[6], parseInt(pkm_dana[6])],
            [labels[7], parseInt(pkm_dana[7])],
            [labels[8], parseInt(pkm_dana[8])],
            
        ]);

        var options = {
            pieSliceText: 'value',
            legend: { position: 'labeled'},
            title: 'Jumlah PkM berdasarkan Skema Pendanaan'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    };
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var sum_dana = <?php echo json_encode(array_values($sum_dana)); ?>;
        var labels = <?php echo json_encode($label_pie); ?>;
        var data = google.visualization.arrayToDataTable([
            ['funding_scheme_short', 'Dana'],
            [labels[0], parseInt(sum_dana[0])],
            [labels[1], parseInt(sum_dana[1])],
            [labels[2], parseInt(sum_dana[2])],
            [labels[3], parseInt(sum_dana[3])],
            [labels[4], parseInt(sum_dana[4])],
            [labels[5], parseInt(sum_dana[5])],
            [labels[6], parseInt(sum_dana[6])],
            [labels[7], parseInt(sum_dana[7])],
            [labels[8], parseInt(sum_dana[8])],
            
        ]);

        console.log(sum_dana[0]);

        var options = {
            legend: { position: 'labeled'},
            // pieSliceText: 'label',
            title: 'Jumlah Dana berdasarkan Skema Pendanaan'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_2'));

        chart.draw(data, options);
    };
</script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var pkm_data = <?php echo json_encode(array_values($pkm_data)); ?>;
        var labels = <?php echo json_encode($label_pie2); ?>;
        var data = google.visualization.arrayToDataTable([
            ['funding_scheme_short', 'Dana'],
            [labels[0], parseInt(pkm_data[0])],
            [labels[1], parseInt(pkm_data[1])],
            [labels[2], parseInt(pkm_data[2])],
        ]);

        var options = {
            pieSliceText: 'value',
            legend: { position: 'labeled'},
            title: 'Jumlah PkM by Data Source'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3'));

        chart.draw(data, options);
    };
</script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var dana_data = <?php echo json_encode(array_values($dana_data)); ?>;
        var labels = <?php echo json_encode($label_pie2); ?>;
        var data = google.visualization.arrayToDataTable([
            ['funding_scheme_short', 'Dana'],
            [labels[0], parseInt(dana_data[0])],
            [labels[1], parseInt(dana_data[1])],
            [labels[2], parseInt(dana_data[2])],
        ]);

        var options = {
            // pieSliceText: 'value',
            legend: { position: 'labeled'},
            title: 'Jumlah Dana by Data Source'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_4'));

        chart.draw(data, options);
    };
</script>

<script>
const labels = <?= json_encode($label); ?>;
const label_pie = <?= json_encode($label_pie); ?>;
const label_pie2 = <?= json_encode($label_pie2); ?>;
<?php foreach($rows as $row) : ?>

var ctx = document.getElementById('chart_5').getContext('2d');
var chart_5 = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: '# of Results',
            data: [<?php echo implode(",", $counts); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

var ctx = document.getElementById('chart_6').getContext('2d');
var chart_6 = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: '# of Results',
            data: [<?php echo implode(",", $counts); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

<?php endforeach ?>
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Penelitian</h4>
    </div>

    <?php 
        $tahun = array(); 
        foreach ($periodes as $periode) {
            array_push($tahun, $periode->tahun);
        }
        $tahun_unik = array_unique($tahun);
    ?>

    <form action="<?= site_url("interfacep2m/interface_penelitian"); ?>" method="get">
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
                <h5>Jumlah Dana Penelitian</h5>
                <h2><?php echo($sum); ?></h2>
            </div>
        </div>
    </div>

    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah Penelitian</h5>
                <h2><?php echo($countAll); ?></h2>
            </div>
            <!-- <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Jumlah Dana Penelitian</h5>
                <h2 style="max-width: 95%; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;"><?php echo($sum); ?></h2>
            </div> -->
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#ffd4df; min-height: 115px;">
                <h5>Penelitian Departemen Kimia</h5>
                <h2><?php echo($penelitian_kim); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Penelitian Departemen FIsika</h5>
                <h2><?php echo($penelitian_fis); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cce0fe; min-height: 115px;">
                <h5>Penelitian Departemen Matematika</h5>
                <h2><?php echo($penelitian_mat); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Penelitian Departemen Ilmu Komputer dan Elektronik</h5>
                <h2><?php echo($penelitian_ike); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cce0fe; min-height: 115px;">
                <h5>Penelitian Kantor Tata Usaha</h5>
                <h2><?php echo($penelitian_tu); ?></h2>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row align-items-center">
            <div id="piechart" style="width: 900px; height: 500px;"></div>
            <div id="piechart_2" style="width: 900px; height: 500px;"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    const labels = <?= json_encode($label); ?>;
    <?php foreach($rows as $row) : ?>
        var ctx = document.getElementById('chart_1').getContext('2d');
        var chart_1 = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: <?php echo json_encode(array_values($counts)); ?>,
                    backgroundColor: ["#E90064","#16FF00","#0F6292","#FF1E1E","#FFED00"],
                    borderColor: 'rgba(0, 0, 0, 0)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: `Jumlah Penelitian`,
                        fullSize: false,
                    }
                }
            },
        });

    <?php endforeach ?>
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var counts = <?php echo json_encode(array_values($counts)); ?>;
        var labels = <?php echo json_encode($label); ?>;
        var data = google.visualization.arrayToDataTable([
            ['Departemen', 'Dana'],
            [labels[0], parseInt(counts[0])],
            [labels[1], parseInt(counts[1])],
            [labels[2], parseInt(counts[2])],
            [labels[3], parseInt(counts[3])],
            [labels[4], parseInt(counts[4])]
        ]);

        var options = {
            title: 'Jumlah Penelitian'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    };
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var dana_data = <?php echo json_encode(array_values($dana_data)); ?>;
        var labels = <?php echo json_encode($label); ?>;
        var data = google.visualization.arrayToDataTable([
            ['Departemen', 'Dana'],
            [labels[0], parseInt(dana_data[0])],
            [labels[1], parseInt(dana_data[1])],
            [labels[2], parseInt(dana_data[2])],
            [labels[3], parseInt(dana_data[3])],
            [labels[4], parseInt(dana_data[4])]
        ]);

        var options = {
            title: 'Jumlah Penelitian Berdasarkan Dana(Percentage)'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_2'));

        chart.draw(data, options);
    };
</script>
<?= $this->endSection() ?>
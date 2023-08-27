<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Rekap Dosen</h4>
    </div>

    <form action="<?= site_url("interfacep2m/interface_dosen"); ?>" method="GET">
        <div class="form-group">
            <label>Nama</label>
            <select id="lecturer_id" class="form-control" name="lecturer_id" required>
                <option value="" disabled selected>Pilih nama Dosen</option>
                <?php foreach ($rows as $row) : ?>
                    <option value="<?= $row->dosenID ?>"><?= $row->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-success" title="Cari" type="submit">Cari</a>
        </div>
    </form>

    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-lg-5 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Nama Dosen</h5>
                <?php foreach ($namaDosen as $row) : ?>
                <h2><?php echo $row->name; ?></h2>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="container text-center" id="result">
        <div class="row justify-content-md-center">
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#cef5d2; min-height: 115px;">
                <h5>Jumlah Publikasi</h5>
                <h2><?php echo ($jmlPublikasi); ?></span></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#ffd4df; min-height: 115px;">
                <h5>Jumlah Penelitian</h5>
                <h2><?php echo ($jmlPenelitian); ?></h2>
            </div>
            <div class="col-lg-3 rounded mx-2 my-2" style="background-color:#fcccf0; min-height: 115px;">
                <h5>Jumlah Pengabdian</h5>
                <h2><?php echo ($jmlPengabdian); ?></h2>
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
<script>
    $(document).ready(function() {
        $('#lecturer_id').change(function() {
            var lecturerID = $(this).val();

            $.ajax({
                url: '<?= site_url("interfacep2m/interface_dosen_ajax"); ?>',
                type: 'GET',
                data: {
                    lecturer_id: lecturerID
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var jmlPublikasi = data.jmlPublikasi;
                    var jmlPenelitian = data.jmlPenelitian;
                    var jmlPengabdian = data.jmlPengabdian;

                    // Lakukan apa pun yang diperlukan dengan data yang diterima
                    // Misalnya, tampilkan data di elemen HTML
                    $('#jml_publikasi').text(jmlPublikasi);
                    $('#jml_penelitian').text(jmlPenelitian);
                    $('#jml_pengabdian').text(jmlPengabdian);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var sinta = <?php echo json_encode($sinta); ?>;
    var tahun = <?php echo json_encode($tahun); ?>;
    console.log(tahun[0]);
    var data = google.visualization.arrayToDataTable([
        ['Tahun', 'Sinta Score', { role: 'annotation' }],
        [tahun[0], sinta[0],''],
        [tahun[1], sinta[1],''],
        [tahun[2], sinta[2],''],
        [tahun[3], sinta[3],''],

    ]);

    
    var colors = ['#ff420e', 'rgb(255, 211, 32)', 'rgb(87, 157, 28)', 'rgb(126, 0, 33)', 'rgb(0, 69, 134)'];

    var options = {
        title: 'Sinta Score (rentang 1 tahun)',
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
    var hIndex = <?php echo json_encode($h_index); ?>;
    var tahun = <?php echo json_encode($tahun); ?>;
    console.log(tahun[0]);
    var data = google.visualization.arrayToDataTable([
        ['Tahun', 'H Index Score', { role: 'annotation' }],
        [tahun[0], hIndex[0],''],
        [tahun[1], hIndex[1],''],
        [tahun[2], hIndex[2],''],
        [tahun[3], hIndex[3],''],

    ]);

    
    var colors = ['#ff420e', 'rgb(255, 211, 32)', 'rgb(87, 157, 28)', 'rgb(126, 0, 33)', 'rgb(0, 69, 134)'];

    var options = {
        title: 'H Index',
        isStacked: true,
        series: {
            0: { color: colors[3] },
            1: { color: colors[1] },
            2: { color: colors[0] },
            3: { color: colors[2] },
            4: { color: colors[4] }
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_2'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
</script>

<?= $this->endSection() ?>
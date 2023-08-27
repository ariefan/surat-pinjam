<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>Dashboard</h4>
  </div>
  <div class="card-body">
  <form class="mt-0 mb-4">
    <div class="form-check form-check-inline">
        <div class="dropdown">
            <label class="form-check-label pr-2">Tahun Capaian: </label>
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                <?= $q_tahun; ?> - <?= get_bulan($q_bulan); ?>
            </button>
            <div class="dropdown-menu">
                <?php foreach($periodes as $periode): ?>
                <a class="dropdown-item" href="?q_tahun=<?= $periode->tahun; ?>&q_bulan=<?= $periode->bulan; ?>"><?= $periode->tahun; ?> - <?= get_bulan($periode->bulan); ?></a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
  </form>

  <div class="row">

  <?php foreach($rows as $row) : ?>
    <div class="col-md-4">
        <div class="row">
            <div class="col-sm-12 pb-2 pr-4" style="font-size:80%;text-align:justify;"><?= $row->indikator; ?></div>
        </div>
        <div class="row">
            <div class="col-sm-8"><canvas id="chart_<?= $row->id; ?>" style="max-height:300px;"></canvas></div>
            <div class="col-sm-4">
                <small>Capaian: <?= $row->capaian; ?></small><br>
                <small>Target: <?= $row->target; ?></small>
            </div>
        </div>
    </div>
  <?php endforeach ?>

  </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>

<script>
const labels = <?= json_encode($label); ?>;
<?php foreach($rows as $row) : ?>
const chart_<?= $row->id; ?> = new Chart(
    document.getElementById('chart_<?= $row->id; ?>'),
    {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Capaian',
                backgroundColor: ["#93aded", "#f09e7f", "#fac278", "#a3ff9e"], 
                borderColor: '#aaa',
                data: [<?= $row->capaian_ike; ?>, <?= $row->capaian_fis; ?>, <?= $row->capaian_mat; ?>, <?= $row->capaian_kim; ?>],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    display: false,
                },
                title: {
                    display: false,
                    text: `<?= $row->indikator; ?>`,
                    fullSize: false,
                }
            }
        },
    }
);
<?php endforeach ?>
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Client</h4>
    </div>
    <div class="card-body">
        <iframe src="localhost:3000" title="client"></iframe>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>

</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Surat Pinjam</h4>
    </div>
    <div class="card-body">
        <div id="react-entry-point"></div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<?= view('surat-pinjam/js') ?>
<?= $this->endSection() ?>
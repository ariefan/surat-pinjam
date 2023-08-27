<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>Setting</h4>
  </div>
  <div class="card-body">

    <form action="<?= site_url('setting/update'); ?>" method="post">
        <?php foreach(session('setting') as $key => $setting) : ?>
            <?php list($group) = explode('_', $key); ?>
            <div class="form-group"> 
                <label><?= $setting['label']; ?></label>
                <?php if($group == 'PEGAWAI'): ?>
                    <select class="form-control" name="<?= $key; ?>">
                        <?php foreach($pegawais as $pegawai) : ?>
                            <option value="<?= $pegawai->id; ?>" <?= $setting['value'] == $pegawai->id ? 'selected' : ''; ?>><?= $pegawai->nama_publikasi; ?></option>
                        <?php endforeach ?>
                    </select>
                <?php else : ?>
                    <input type="text" class="form-control" name="<?= $key; ?>" value="<?= $setting['value']; ?>">
                <?php endif ?>
            </div>
        <?php endforeach ?>
        <div class="form-group">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>

  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

</script>
<?= $this->endSection() ?>
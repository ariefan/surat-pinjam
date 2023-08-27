<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card pl-4">
  <div class="card-header">
    <h4>Bagikan</h4>
  </div>
  <div class="card-body">
	
	<form method="post" action="<?= base_url('suratkeputusan/saveshare') . "/$row->id" ?>" enctype="multipart/form-data">
		<div class="form-group">
			<label for="">Pengguna</label>
			<input id="ms1" name="shares[]" value="<?= $row->shares; ?>" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</form>

  </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('css') ?>
<link href="<?= base_url('magicsuggest.css'); ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('magicsuggest.js'); ?>"></script>
<script>
	$(function() {
		var ms1 = $('#ms1').magicSuggest({
			data: <?= $users; ?>
		});
	});
</script>
<?= $this->endSection() ?>
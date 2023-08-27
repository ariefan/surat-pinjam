<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
	<h1>Bagikan Surat</h1>
	<form method="post" action="<?= base_url('buatsurat/saveshare') . "/$row->id" ?>" enctype="multipart/form-data">
		<div class="form-group">
			<label for="">Pengguna</label>
			<input id="ms1" name="shares[]" value="<?= $row->shares; ?>" class="form-control">
			<!-- <select class="js-example-basic-multiple form-control" name="shares[]" multiple="multiple">
				<?php foreach (json_decode($users) as $user) : ?>
					<option value="<?= $user->id; ?>"><?= $user->name; ?></option>
				<?php endforeach; ?>
			</select> -->
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</form>
</div>
<?= $this->endSection() ?>


<?= $this->section('css') ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<link href="<?= base_url('magicsuggest.css'); ?>" rel="stylesheet">
<style>
	.ms-ctn .ms-sel-item {
		color: black;
		font-size: 12pt;
		padding: 5px 10px;
		border: 1px solid #aaa;
	} 
	.ms-sel-ctn .ms-sel-item .ms-close-btn {
		background-position: 0 0;
	}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="<?= base_url('magicsuggest.js'); ?>"></script>
<script>
	$(function() {
		var ms1 = $('#ms1').magicSuggest({
			data: <?= $users; ?>
		});
	});

	// $(document).ready(function() {
	// 	$('.js-example-basic-multiple').select2();
	// });
</script>
<?= $this->endSection() ?>
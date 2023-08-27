<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
	<?php
	if (session()->getFlashData('message')) {
	?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= session()->getFlashData('message') ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php
	}
	?>
	<form method="post" action="<?= base_url('suratketeranganlulus/uploads') . "/$id" ?>" enctype="multipart/form-data">
		<div class="form-group">
			<!-- <label for="">Bukti/Laporan Pelaksanaan Tugas (wajib pdf)</label> -->
			<label for="">Laporan Pertanggungjawaban (pdf maks 2MB)</label>
			<input type="file" accept="application/pdf" name="berkas" required="" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Upload</button>
		</div>
	</form>
</div>

<?= $this->endSection() ?>
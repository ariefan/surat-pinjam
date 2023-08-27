<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card pl-4">
	<div class="card-header">
		<h4>Bagikan</h4>
	</div>
	<div class="card-body">

		<form method="post" action="<?= base_url('suratkeputusan/uploads') . "/$id" ?>" enctype="multipart/form-data">
			<div class="form-group">
				<!-- <label for="">Bukti/Laporan Pelaksanaan Tugas (wajib pdf)</label> -->
				<label for="">Laporan Pertanggungjawaban (pdf maks 2MB)</label>
				<input type="file" accept="application/pdf" name="berkas" required="" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
			</div>
			<div class=" form-group">
				<button type="submit" class="btn btn-primary">Upload</button>
			</div>
		</form>

	</div>
</div>

<?= $this->endSection() ?>
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
	<form method="post" action="<?= base_url('penomoransurat/uploads') . "/$id" ?>" enctype="multipart/form-data">
		<div class="form-group">
			<label for="">Upload Surat (pdf maks 2MB, zip maks 5MB)</label>
			<input id="berkas" type="file" accept="application/pdf,application/zip,application/vnd.rar" name="berkas"
				required="" class="form-control" onchange="validateFileSize(this)">
		</div>
		<div class=" form-group">
			<button type="submit" class="btn btn-primary">Upload</button>
		</div>
	</form>
</div>

<?= $this->endSection() ?>



<?= $this->section('js') ?>
<script>
	function validateFileSize(input) {
		if (input.files && input.files[0]) {
			let maxSizeInMB = 0;
			if (getFileExtension(input.value) == 'pdf') {
				maxSizeInMB = 2;
			} else {
				maxSizeInMB = 5;
			}
			var fileSizeInBytes = input.files[0].size;
			var fileSizeInMB = fileSizeInBytes / (1024 * 1024);
			if (fileSizeInMB > maxSizeInMB) {
				alert('Ukuran file ' + getFileExtension(input.value) + ' tidak boleh lebih dari ' + maxSizeInMB + ' MB.');
				input.value = '';
			}
		}
	}

	function getFileExtension(filename) {
		return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
	}
</script>
<?= $this->endSection() ?>
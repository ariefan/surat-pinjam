<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form User</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('user/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST">

            <?php if (session('jenis_user') == 'admin') : ?>
                <div class="form-group">
                    <label>Jenis User</label>
                    <select class="form-control" name="jenis_user">
                        <option value="admin" <?= $row->jenis_user == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="verifikator" <?= $row->jenis_user == 'verifikator' ? 'selected' : ''; ?>>Setdekanat</option>
                        <option value="departemen" <?= $row->jenis_user == 'departemen' ? 'selected' : ''; ?>>Departemen</option>
                        <option value="dekan" <?= $row->jenis_user == 'dekan' ? 'selected' : ''; ?>>Dekan</option>
                        <option value="wadek" <?= $row->jenis_user == 'wadek' ? 'selected' : ''; ?>>Wakil Dekan</option>
                        <option value="tendik" <?= $row->jenis_user == 'tendik' ? 'selected' : ''; ?>>Tendik</option>
                        <option value="dosen" <?= $row->jenis_user == 'dosen' ? 'selected' : ''; ?>>Dosen</option>
                        <option value="mahasiswa" <?= $row->jenis_user == 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                    </select>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Username</label><br />
                <input class="form-control" type="email" name="username" value="<?= $row->username; ?>">
            </div>

            <?php if (session('id') == $row->id) { ?>
                <div class="form-group">
                    <label>Password</label><br />
                    <input id="password" class="form-control" type="password" name="password" autocomplete="off">
                </div>
            <?php } ?>

            <div class="form-group">
                <label for="">Bawahan</label>
                <input id="ms1" name="bawahan[]" value="<?= $bawahans; ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Nama</label><br />
                <input class="form-control" type="text" name="nama" value="<?= $row->nama; ?>">
            </div>

            <div class="form-group">
                <label>Pangkat</label><br />
                <input class="form-control" type="text" name="pangkat" value="<?= $row->pangkat; ?>">
            </div>

            <br />
            <!-- <button type="reset" class="btn btn-warning">Reset Password</button> -->
            <!-- <button type="submit" class="btn btn-success" name="status" value="4">Simpan Draft</button> -->
            <button type="submit" class="btn btn-primary" name="status" value="1">Simpan</button>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('magicsuggest.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
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
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Daftar Anggota</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12">
                <a class="btn btn-success" title="Tambah Anggota" id="btnTambahST" href="<?= site_url('user/create'); ?>">Tambah</a>
            </div>
        </div>

        <form action="<?= site_url("user/index"); ?>" method="get">
            <div class="form-group row">
                <div class="col-sm-8">
                    <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                    <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                    <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-valign-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a href="<?= site_url('user-mahasiswa/index'); ?>?q=<?= $q; ?>&sort_column=username&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Username</a>
                                    <i class="pl-2 <?= $sort_column == 'username' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'username' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('user-mahasiswa/index'); ?>?q=<?= $q; ?>&sort_column=jenis_user&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Jenis User</a>
                                    <i class="pl-2 <?= $sort_column == 'jenis_user' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'jenis_user' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('user-mahasiswa/index'); ?>?q=<?= $q; ?>&sort_column=nama&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Nama</a>
                                    <i class="pl-2 <?= $sort_column == 'nama' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'nama' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('user-mahasiswa/index'); ?>?q=<?= $q; ?>&sort_column=aktif&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Aktif</a>
                                    <i class="pl-2 <?= $sort_column == 'aktif' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'aktif' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            foreach ($rows as $row) { ?>
                                <?php $i++; ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $row->username; ?></td>
                                    <td><?= ucwords($row->jenis_user ?? ""); ?></td>
                                    <td><?= $row->nama; ?></td>
                                    <td><?= $row->aktif == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="<?= site_url("mahasiswa/edit/" . $row->id); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                                            <a href="<?= site_url("mahasiswa/delete/" . $row->id); ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah anda yakin ingin menghapus anggota ini?');"><i class="fa-solid fa-times"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>


<?= $this->section('content') ?>
<script>

</script>
<?= $this->endSection() ?>
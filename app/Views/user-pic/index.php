<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<nav class="nav nav-pills nav-fill">
    <a class="nav-item nav-link <?= current_url() == site_url('pic') || current_url() == site_url('pic/index') ? ' active' : ''; ?>" href="<?= site_url("pic"); ?>">PIC Utama</a>
    <a class="nav-item nav-link <?= current_url() == site_url('pic/piclain') ? ' active' : ''; ?>" href="<?= site_url("pic/piclain"); ?>">PIC Tambahan</a>
</nav>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12"> 
                <h1 class="m-0">Daftar PIC Utama</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <!-- <div class="form-group row">
            <div class="col-lg-12">
                <a class="btn btn-success" title="Tambah Anggota" id="btnTambahST" href="<?= site_url('user/create'); ?>">Tambah</a>
            </div>
        </div> -->

        <form action="<?= site_url("pic/index"); ?>" method="get">
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
                                    <a href="<?= site_url('pic/index'); ?>?q=<?= $q; ?>&sort_column=nama_ugm&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Nama</a>
                                    <i class="pl-2 <?= $sort_column == 'nama_ugm' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'nama_ugm' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('pic/index'); ?>?q=<?= $q; ?>&sort_column=jabatan&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Jabatan</a>
                                    <i class="pl-2 <?= $sort_column == 'jabatan' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'jabatan' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
                                </th>
                                <th>
                                    <a href="<?= site_url('pic/index'); ?>?q=<?= $q; ?>&sort_column=email&sort_order=<?= $sort_order == 'asc' ? 'desc' : 'asc'; ?>">Email</a>
                                    <i class="pl-2 <?= $sort_column == 'email' ? '' : 'text-white'; ?> fa-solid fa-arrow-<?= $sort_column == 'email' && $sort_order == 'desc' ? 'down' : 'up'; ?>"></i>
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
                                    <td><?= $row->nama_ugm; ?></td>
                                    <td><?= $row->jabatan; ?></td>
                                    <td><?= $row->email; ?></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="<?= site_url("pic/edit/" . $row->id_list_pic); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                                            <!-- <a href="<?= site_url("mahasiswa/delete/" . $row->id); ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah anda yakin ingin menghapus anggota ini?');"><i class="fa-solid fa-times"></i></a> -->
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
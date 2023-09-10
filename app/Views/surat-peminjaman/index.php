<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Peraturan</h4>
    </div>
    <div class="card-body">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
                <a class="btn btn-success" href="<?= site_url('suratpeminjaman/create'); ?>">Tambah</a>
            </div>
        </div>

        <table class="table table-sm mb-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">No Surat</th>
                    <th scope="col">Judul Surat</th>
                    <th scope="col">Pengaju</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <th scope="row">
                            <?= empty($no) ? $no = 1 : ++$no ?>
                        </th>
                        <td>
                            <?= $row->no_surat ?>
                        </td>
                        <td>
                            <?= $row->nama_surat; ?>
                        </td>
                        <td>
                            <?= $row->user->nama; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a class="btn btn-warning" title="edit"
                                    href="<?= site_url("suratpeminjaman/edit/" . $row->id); ?>"><i
                                        class="fa-solid fa-pencil"></i></a>
                                <a class="btn btn-danger" title="delete"
                                    href="<?= site_url("suratpeminjaman/delete/" . $row->id); ?>"
                                    onclick="return confirm('Apakah anda yakin ingin menghapus surat-peminjaman ini?');"><i
                                        class="fa-solid fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

</script>
<?= $this->endSection() ?>
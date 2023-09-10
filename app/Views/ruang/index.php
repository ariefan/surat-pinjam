<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Ruang</h4>
    </div>
    <div class="card-body">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
                <a class="btn btn-success" href="<?= site_url('ruang/create'); ?>">Tambah</a>
            </div>
        </div>


        <table class="table table-sm mb-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Gedung</th>
                    <th scope="col">Nama Ruang</th>
                    <th scope="col">Kapasitas</th>
                    <th scope="col">Fasilitas</th>
                    <th scope="col">Dapat Disewa</th>
                    <th scope="col">Harga Sewa</th>
                    <th scope="col">Catatan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td scope="row">
                            <?= empty($no) ? $no = 1 : ++$no; ?>
                        </td>
                        <td>
                            <?= $row->gedung->nama_gedung ?>
                        </td>
                        <td>
                            <?= $row->nama_ruang ?>
                        </td>
                        <td>
                            <?= $row->kapasitas ?>
                        </td>
                        <td>
                            <?= $row->fasilitas ?>
                        </td>
                        <td>
                            <?= $row->dapat_disewa == 1 ? 'YA' : 'TIDAK' ?>
                        </td>
                        <td>
                            <?= $row->harga_sewa ?>
                        </td>
                        <td>
                            <?= $row->catatan ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a class="btn btn-warning" title="edit" href="<?= site_url("ruang/edit/" . $row->id); ?>"><i
                                        class="fa-solid fa-pencil"></i></a>
                                <a class="btn btn-danger" title="delete" href="<?= site_url("ruang/delete/" . $row->id); ?>"
                                    onclick="return confirm('Apakah anda yakin ingin menghapus ruang ini?');"><i
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
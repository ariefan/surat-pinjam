<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>Peraturan</h4>
  </div>
  <div class="card-body">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
            <a class="btn btn-success" href="<?= site_url('peraturan/create'); ?>">Tambah</a>
        </div>
    </div>


    <table class="table table-sm mb-4">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal Berlaku</th>
            <th scope="col" style="width:75%;">Peraturan</th>
            <th scope="col">Aktif</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rows as $row) : ?>
        <tr>
            <th scope="row"><?= empty($no) ? $no = 1 : ++$no; ?></th>
            <td><?= date('d F Y', strtotime($row->tanggal_berlaku)); ?></td>
            <td><?= $row->peraturan; ?></td>
            <td><?= (bool)$row->aktif ? 'Ya' : 'Tidak'; ?></td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-warning" title="edit" href="<?= site_url("peraturan/edit/".$row->id); ?>"><i class="fa-solid fa-pencil"></i></a>
                    <a class="btn btn-danger" title="delete" href="<?= site_url("peraturan/delete/".$row->id); ?>" onclick="return confirm('Apakah anda yakin ingin menghapus peraturan ini?');"><i class="fa-solid fa-times"></i></a>
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
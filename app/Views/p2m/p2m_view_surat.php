<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Surat</h4>
    </div>
    <div class="card-body">
        <form action="<?= site_url("p2m/p2m_view_surat"); ?>" method="get">
            <div class="form-group row">
                <div class="col-sm-8">
                    <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                </div>
            </div>
        </form>

        <?php
        $print_header = function ($label, $q) {
            $url = site_url('p2m/p2m_view_surat') . "?q=$q";
            return "<a href=\"$url\">$label</a>";
        }
        ?>
        <table class="table table-responsive table-bordered" style="height: 60vh;">
            <thead>
                <tr>
                    <th>No</th>
                    <th><?= $print_header('Nama File', 'nama_file', $q); ?></th>
                    <th><?= $print_header('Jenis Surat', 'jenis_surat', $q); ?></th>
                    <th><?= $print_header('Tanggal Mulai', 'tanggal_mulai', $q); ?></th>
                    <th><?= $print_header('Tanggal Selesai', 'tanggal_selesai', $q); ?></th>
                    <th><?= $print_header('Tempat', 'tempat', $q); ?></th>
                    <th><?= $print_header('Download Surat', $q); ?></th>
                    <th><?= $print_header('Detail Surat', $q); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($row as $row) : ?>
                    <tr>
                        <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                        <td><?= $row->nama_file; ?></td>
                        <td><?= $row->jenis_surat; ?></td>
                        <td><?= $row->tanggal_mulai; ?></td>
                        <td><?= $row->tanggal_selesai; ?></td>
                        <td><?= $row->tempat; ?></td>
                        <td><a href="<?= site_url('p2m/download_pdf_surat/' . $row->id_surat) ?>" class="btn btn-success">Download</a></td>
                        <td><a href="<?= site_url('p2m/edit_surat/' . $row->id_surat) ?>" class="btn btn-primary">Edit</a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>
    </div>


</div>

<?= $this->endSection() ?>
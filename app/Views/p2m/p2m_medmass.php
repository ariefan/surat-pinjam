<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Media Massa</h4>
    </div>
        <div class="card-body">
            <form action="<?= site_url("p2m/p2m_dosen"); ?>" method="get">
                <div class="form-group row">
                    <div class="col-sm-9">
                        <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                        <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                        <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                    </div>
                    <?php if (!in_array($jenis_user, ['verifikator', 'departemen'])) { ?>
                    <?php } ?>
                    <div class="col-sm-1">
                        <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                    </div>
                    <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>
                        <a class="btn btn-success" title="Tambah" id="btnTambahST" href="<?= site_url('p2m/create_medmass'); ?>">Tambah</a>
                    <?php } ?>
                </div>
            </form>

            <?php
            $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                $url = site_url('p2m/p2m_dosen') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                $is_selected = $sort_column == $column_name ? '' : 'text-white';
                $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
            }
            ?>
            <table class="table table-responsive table-striped table-bordered table-hover table-valign" style="vertical-align:middle;height: 60vh;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?= $print_header('Nama', 'Name', $q); ?></th>
                        <th><?= $print_header('URL', 'url', $q); ?></th>
                        <th><?= $print_header('Level', 'level', $q); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <?php
                        $akses = [
                            'edit' => $row->status,
                            'delete' => $row->status,
                        ];
                        ?>
                        <tr>
                            <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                            <td><?= $row->Name; ?></td>
                            <td><?= $row->url; ?></td>
                            <td><?= $row->level; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) : ?>
                                        <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit" href="<?= base_url("p2m/edit_medmass/" . $row->medmass_id); ?>"><i class="fa-solid fa-pencil"></i></a>
                                        <a class="btn btn-danger <?= $akses['delete']; ?>" title="delete" href="<?= base_url("p2m/delete_medmass/" . $row->medmass_id); ?>" onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i class="fa-solid fa-times"></i></a>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>

    </div>

<?= $this->endSection() ?>
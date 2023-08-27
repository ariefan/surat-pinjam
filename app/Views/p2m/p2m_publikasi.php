<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Publikasi</h4>
    </div>
        <div class="card-body">
            <form action="<?= site_url("p2m/p2m_publikasi"); ?>" method="get">
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

            <?php
            $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                $url = site_url('p2m/p2m_publikasi') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                $is_selected = $sort_column == $column_name ? '' : 'text-white';
                $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
            }
            ?>
            <table class="table table-responsive table-bordered table-valign-middle" style="height: 60vh;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?= $print_header('Judul Publikasi', 'judul_publikasi', $q); ?></th>
                        <th><?= $print_header('Departemen', 'department', $q); ?></th>
                        <th><?= $print_header('Nama Dosen', 'dosen_name', $q); ?></th>
                        <th><?= $print_header('Tanggal Publikasi', 'tanggal_publikasi', $q); ?></th>
                        <th><?= $print_header('DOI', 'doi', $q); ?></th>
                        <th><?= $print_header('Link Scopus', 'link_scopus', $q); ?></th>
                        <th><?= $print_header('Link WOS', 'link_wos', $q); ?></th>
                        <th><?= $print_header('Link Garuda', 'link_garuda', $q); ?></th>
                        <th><?= $print_header('Link Scholar', 'link_scholar', $q); ?></th>
                        <th><?= $print_header('Volume', 'volume', $q); ?></th>
                        <th><?= $print_header('Issue', 'issue', $q); ?></th>
                        <th><?= $print_header('Halaman', 'halaman', $q); ?></th>
                        <th><?= $print_header('Deskripsi', 'deskripsi', $q); ?></th>
                        <th><?= $print_header('Sitasi Per Tahun', 'sitasi_per_tahun', $q); ?></th>
                        <th><?= $print_header('Is Journal', 'is_journal', $q); ?></th>
                        <th><?= $print_header('Is Conference', 'is_conference', $q); ?></th>
                        <th><?= $print_header('Is Mass Media', 'is_mass_media', $q); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                            <td><?= $row->judul_publikasi; ?></td>
                            <td><?= $row->department; ?></td>
                            <td><?= $row->dosen_name; ?></td>
                            <td><?= $row->tanggal_publikasi; ?></td>
                            <td><?= $row->doi; ?></td>
                            <td><?= $row->link_scopus; ?></td>
                            <td><?= $row->link_wos; ?></td>
                            <td><?= $row->link_garuda; ?></td>
                            <td><?= $row->link_scholar; ?></td>
                            <td><?= $row->volume; ?></td>
                            <td><?= $row->issue; ?></td>
                            <td><?= $row->halaman; ?></td>
                            <td><?= $row->deskripsi; ?></td>
                            <td><?= $row->sitasi_per_tahun; ?></td>
                            <td><?php if ($row->is_journal == "checked"): ?>
                            <i class="fa fa-check"></i>
                            <?php endif; ?></td>
                            <td><?php if ($row->is_conference == "checked"): ?>
                            <i class="fa fa-check"></i>
                            <?php endif; ?></td>
                            <td><?php if ($row->is_mass_media == "checked"): ?>
                            <i class="fa fa-check"></i>
                            <?php endif; ?></td>

                            
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>
        </div>


    </div>

<?= $this->endSection() ?>
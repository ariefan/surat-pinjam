<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Draft Publikasi</h4>
    </div>
        <div class="card-body">
            <form action="<?= site_url("p2m/p2m_draft"); ?>" method="get">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                        <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                        <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                    </div>
                    <?php if (!in_array($jenis_user, ['verifikator', 'departemen'])) { ?>
                        <div class="col-sm-2">
                            <select class="form-control" name="status">
                                <option value="">Semua</option>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="col-sm-2">
                        <button class="btn btn-success" title="Cari" type="submit">Cari</a>
                    </div>
                </div>
            </form>

            <?php
            $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                $url = site_url('p2m/p2m_draft') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                $is_selected = $sort_column == $column_name ? '' : 'text-white';
                $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
            }
            ?>
            <div class="row">
                <table class="table table-responsive table-bordered table-valign-middle table-hover table-sm mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="col-4"><?= $print_header('Nama', 'name', $q); ?></th>
                            <th class="col-4"><?= $print_header('Judul', 'title', $q); ?></th>
                            <th class="col-4"><?= $print_header('Author', 'first_author', $q); ?></th>
                            <th class="col-4"><?= $print_header('Co-author', 'co_author', $q); ?></th>
                            <th class="col-4"><?= $print_header('Scopus', 'link_scopus', $q); ?></th>
                            <th class="col-4"><?= $print_header('WoS', 'link_wos', $q); ?></th>
                            <th class="col-4"><?= $print_header('Garuda', 'link_garuda', $q); ?></th>
                            <th class="col-4"><?= $print_header('Scholar', 'link_scholar', $q); ?></th>
                            <th class="col-4"><?= $print_header('Tanggal Publikasi', 'date', $q); ?></th>
                            <th class="col-4"><?= $print_header('Jurnal', 'journal', $q); ?></th>
                            <th class="col-4"><?= $print_header('DOI', 'doi', $q); ?></th>
                            <th class="col-4"><?= $print_header('Rank', 'rank', $q); ?></th>
                            <th class="col-4"><?= $print_header('Volume', 'volume', $q); ?></th>
                            <th class="col-4"><?= $print_header('Issue', 'issue', $q); ?></th>
                            <th class="col-4"><?= $print_header('Jml Halaman', 'pages', $q); ?></th>
                            <th class="col-4"><?= $print_header('Penerbit', 'publisher', $q); ?></th>
                            <th class="col-4"><?= $print_header('Deskripsi', 'description', $q); ?></th>
                            <th class="col-4"><?= $print_header('Jml Sitasi', 'citation', $q); ?></th>
                            <th class="col-4"><?= 'Aksi' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                                <td><?= $row->name; ?></td>
                                <td><?= $row->title; ?></td>
                                <td><?= $row->first_author; ?></td>
                                <td><?= $row->co_author; ?></td>
                                <td><?= $row->link_scopus; ?></td>
                                <td><?= $row->link_wos; ?></td>
                                <td><?= $row->link_garuda; ?></td>
                                <td><?= $row->link_scholar; ?></td>
                                <td><?= $row->date; ?></td>
                                <td><?= $row->journal; ?></td>
                                <td><?= $row->doi; ?></td>
                                <td><?= $row->rank; ?></td>
                                <td><?= $row->volume; ?></td>
                                <td><?= $row->issue; ?></td>
                                <td><?= $row->pages; ?></td>
                                <td><?= $row->publisher; ?></td>
                                <td class="text-wrap text-truncate">
                                    <?= $row->description; ?>
                                </td>
                                <td><?php foreach ($row->citation as $citation) { echo '<span class="text-sm badge badge-primary ml-1">' . $citation . '</span>';} ?></td>
                                <td>
                                    <a href="<?= site_url('p2m/p2m_draft/#' . $row->id); ?>" class="btn btn-sm btn-primary">Setujui</a>
                                    <a href="<?= site_url('p2m/p2m_draft/#' . $row->id); ?>" class="btn btn-sm btn-danger">Tolak</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>

    </div>

<?= $this->endSection() ?>
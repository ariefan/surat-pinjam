<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Jurnal Interal FMIPA</h4>
    </div>
        <div class="card-body">
            <form action="<?= site_url("p2m/p2m_publikasi"); ?>" method="get">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <input type="text" name="q" class="form-control" placeholder="Pencarian" value="<?= $q; ?>">
                        <input type="hidden" name="sort_column" value="<?= $sort_column; ?>">
                        <input type="hidden" name="sort_order" value="<?= $sort_order; ?>">
                    </div>
                    <?php if (!in_array($jenis_user, ['verifikator', 'departemen'])) { ?>
                    <?php } ?>
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
                        <th><?= $print_header('Judul Jurnal', 'Title', $q); ?></th>
                        <th><?= $print_header('Volume', 'Volume', $q); ?></th>
                        <th><?= $print_header('Tahun', 'years', $q); ?></th>
                        <th><?= $print_header('Halaman', 'Page', $q); ?></th>
                        <th><?= $print_header('doi', 'doi', $q); ?></th>
                        <th><?= $print_header('Main Author (Dosen)', 'first_author_lecturer', $q); ?></th>
                        <th><?= $print_header('Main Author (Mahasiswa)', 'first_author_student', $q); ?></th>
                        <th><?= $print_header('Main Author (Eksternal)', 'first_author_external', $q); ?></th>
                        <th><?= $print_header('Sesuai Dosen Penulis', 'corresponding_author_lecturer', $q); ?></th>
                        <th><?= $print_header('Sesuai Mahasiswa Penulis', 'corresponding_author_student', $q); ?></th>
                        <th><?= $print_header('Sesuai External Penulis', 'corresponding_author_external', $q); ?></th>
                        <th><?= $print_header('Dosen Lainnya', 'other_lecturer', $q); ?></th>
                        <th><?= $print_header('Mahasiswa Lainnya', 'other_student', $q); ?></th>
                        <th><?= $print_header('Eksternal Lainnya', 'other_external', $q); ?></th>
                        <th><?= $print_header('Kabupaten Afiliasi', 'affiliation_regency', $q); ?></th>
                        <th><?= $print_header('Negara Afiliasi', 'affiliation_country', $q); ?></th>
                        <th><?= $print_header('Institusi Afiliasi', 'affiliation_institute', $q); ?></th>
                        <th><?= $print_header('Nama Journal', 'journal_name', $q); ?></th>
                        <th><?= $print_header('Keyword', 'keyword', $q); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                            <td><?= $row->Title; ?></td>
                            <td><?= $row->Volume; ?></td>
                            <td><?= $row->years; ?></td>
                            <td><?= $row->Page; ?></td>
                            <td><?= $row->doi; ?></td>
                            <td><?= $row->first_author_lecturer; ?></td>
                            <td><?= $row->first_author_student; ?></td>
                            <td><?= $row->first_author_external; ?></td>
                            <td><?= $row->corresponding_author_lecturer; ?></td>
                            <td><?= $row->corresponding_author_student; ?></td>
                            <td><?= $row->corresponding_author_external; ?></td>
                            <td><?= $row->other_lecturer; ?></td>
                            <td><?= $row->other_student; ?></td>
                            <td><?php if(count((array)$row->other_external) < 2):?>
                                <?php foreach((array)$row->other_external as $other_external): ?>     
                                    <?= $other_external; ?>
                                    <?php endforeach ?>
                            <?php else:?>
                                    <?php foreach($row->other_external as $other_external): ?>     
                                    <?= $other_external. ","; ?>
                                    <?php endforeach ?> 
                            <?php endif ?></td>
                            <td><?= $row->affiliation_regency; ?></td>
                            <td><?= $row->affiliation_country; ?></td>
                            <td><?= $row->affiliation_institute; ?></td>
                            <td><?= $row->journal_name; ?></td>
                            <td><?= $row->keyword; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>
        </div>


    </div>

<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Penelitian</h4>
    </div>

    <div class="card-body">
            <form action="<?= site_url("p2m/p2m_penelitian"); ?>" method="get">
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
                $url = site_url('p2m/p2m_penelitian') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                $is_selected = $sort_column == $column_name ? '' : 'text-white';
                $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
            }
            ?>
            <table class="table table-responsive table-bordered table-valign-middle" style="height: 60vh;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?= $print_header('Judul', 'title', $q); ?></th>
                        <th><?= $print_header('Lokasi', 'location', $q); ?></th>
                        <th><?= $print_header('Team Leader', 'team_leader', $q); ?></th>
                        <th><?= $print_header('Team Leader(Staff Akademik)', 'team_leader_academic_staff', $q); ?></th>
                        <th><?= $print_header('Departemen', 'department', $q); ?></th>
                        <th><?= $print_header('Reference In Thesis', 'referenced_in_thesis', $q); ?></th>
                        <th><?= $print_header('Reference In Publication', 'referenced_in_publication', $q); ?></th>
                        <th><?= $print_header('Tahun', 'years', $q); ?></th>
                        <th><?= $print_header('Report', 'report', $q); ?></th>
                        <th><?= $print_header('Kata Kunci', 'keyword', $q); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <th scope="row"><?= empty($no) ? $no = 1 + (($pager->getCurrentPage() - 1) * $pager->GetPerPage()) : ++$no; ?></th>
                            <td><?= $row->title; ?></td>
                            <td><?= $row->location; ?></td>
                            <td><?= $row->team_leader; ?></td>
                            <td><?= $row->team_leader_academic_staff; ?></td>
                            <td><?= $row->department; ?></td>
                            <td><?= $row->referenced_in_thesis; ?></td>
                            <td><?= $row->referenced_in_publication; ?></td>
                            <td><?= $row->years; ?></td>
                            <td><?php if ($row->report == "checked"): ?>
                            <i class="fa fa-check"></i>
                            <?php endif; ?>
                            </td>
                            <td><?= $row->keyword; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?= str_replace('<a ', '<a class="page-link" ', str_replace('<li class="', '<li class="page-item ', $pager->links())); ?>

 
       


    </div>

<?= $this->endSection() ?>
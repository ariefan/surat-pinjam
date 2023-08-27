<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Dosen</h4>
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
                        <a class="btn btn-success" title="Tambah" id="btnTambahST" href="<?= site_url('p2m/create_dosen'); ?>">Tambah</a>
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
                        <th><?= $print_header('Nama', 'name', $q); ?></th>
                        <th><?= $print_header('Profesor', 'is_prof', $q); ?></th>
                        <th><?= $print_header('Departemen', 'department', $q); ?></th>
                        <th><?= $print_header('Gelar', 'degree', $q); ?></th>
                        <th><?= $print_header('Aktif', 'is_active', $q); ?></th>
                        <th><?= $print_header('Mulai Aktif', 'active_start', $q); ?></th>
                        <th><?= $print_header('Selesai Aktif', 'active_end', $q); ?></th>
                        <th><?= $print_header('NIDN', 'nidn', $q); ?></th>
                        <th><?= $print_header('Sinta ID', 'sinta_id', $q); ?></th>
                        <th><?= $print_header('Sinta Score', 'sinta_score_2023_01', $q); ?></th>
                        <th><?= $print_header('Google Scholar ID', 'Google_Scholar_ID', $q); ?></th>
                        <th><?= $print_header('Scopus ID', 'Scopus_ID', $q); ?></th>
                        <th><?= $print_header('H-Index', 'H_index_2023_01', $q); ?></th>
                        <th><?= $print_header('WoS ID', 'WoS_ID', $q); ?></th>
                        <th><?= $print_header('Publons ID', 'publons_id', $q); ?></th>
                        <th><?= $print_header('Orcid ID', 'orcid_id', $q); ?></th>
                        <th><?= $print_header('Laboratorium', 'laboratorium', $q); ?></th>
                        <th><?= $print_header('Program Studi', 'study_programmes', $q); ?></th>
                        <th><?= $print_header('Expertise Group', 'expertise_group', $q); ?></th>
                        <th><?= $print_header('Acad Staff', 'acad_staff', $q); ?></th>
                        <th>Aksi</th>
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
                            <td><?= $row->name; ?></td>
                            <td><?php if ($row->is_prof == "checked"): ?>
                            <i class="fa fa-check"></i>
                            <?php endif; ?>
                            </td>
                            <td><?= $row->department; ?></td>
                            <td><?= $row->degree; ?></td>
                            <td><?= $row->is_active; ?></td>
                            <td><?= $row->active_start; ?></td>
                            <td><?= $row->active_end; ?></td>
                            <td><?= $row->nidn; ?></td>
                            <td><?= $row->sinta_id; ?></td>
                            <td><?= $row->sinta_score_2023_01; ?></td>
                            <td><?= $row->Google_Scholar_ID; ?></td>
                            <td><?= $row->Scopus_ID; ?></td>
                            <td><?= $row->H_index_2023_01; ?></td>
                            <td><?= $row->WoS_ID; ?></td>
                            <td><?= $row->publons_id; ?></td>
                            <td><?= $row->orcid_id; ?></td>
                            <td><?= $row->laboratorium; ?></td>
                            <td><?= $row->study_programmes; ?></td>
                            <td><?= $row->expertise_group; ?></td>
                            <td><?= $row->acad_staff; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) : ?>
                                        <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit" href="<?= base_url("p2m/edit/" . $row->dosenID); ?>"><i class="fa-solid fa-pencil"></i></a>
                                        <a class="btn btn-danger <?= $akses['delete']; ?>" title="delete" href="<?= base_url("p2m/delete/" . $row->dosenID); ?>" onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i class="fa-solid fa-times"></i></a>
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
<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<?php
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
?>

<div class="card">
    <div class="card-header">
        <h4>Pengabdian</h4>
    </div>

    <div class="card-body">
            <form action="<?= site_url("p2m/p2m_pengabdian"); ?>" method="get">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="date" name="start_date" class="form-control" placeholder="Tanggal Mulai" value="<?= $start_date; ?>">
                    </div>
                    <div class="col-sm-3">
                        <input type="date" name="end_date" class="form-control" placeholder="Tanggal Akhir" value="<?= $end_date; ?>">
                    </div>
                </div>
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
                        <a class="btn btn-success" title="Tambah" id="btnTambahST" href="<?= site_url('p2m/create_pengabdian'); ?>">Tambah</a>
                    <?php } ?>
                    <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) { ?>
                        <a class="btn btn-info" title="Export" id="btnExport" style="margin-left: 20px" href="<?= site_url('p2m/exportToExcel'); ?>">Export</a>
                    <?php } ?>
                </div>
            </form>

            <?php
            $print_header = function ($label, $column_name, $q) use ($sort_column, $sort_order) {
                $url = site_url('p2m/p2m_pengabdian') . "?q=$q&sort_column=$column_name&sort_order=" . ($sort_order == 'asc' ? 'desc' : 'asc');
                $is_selected = $sort_column == $column_name ? '' : 'text-white';
                $icon = $sort_column == $column_name && $sort_order == 'desc' ? 'down' : 'up';
                return "<a href=\"$url\">$label</a><i class=\"pl-2 $is_selected fa-solid fa-arrow-$icon\"></i>";
            }
            ?>
            <table class="table table-responsive table-bordered table-valign-middle" style="height: 60vh;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?= $print_header('Departemen', 'department', $q); ?></th>
                        <th><?= $print_header('Team Leader', 'team_leader', $q); ?></th>
                        <th><?= $print_header('Anggota (Dosen)', 'member_lecturer', $q); ?></th>
                        <th><?= $print_header('Anggota (Staf Akademik)', 'member_academic_staff', $q); ?></th>
                        <th><?= $print_header('Anggota (Mahasiswa)', 'member_student', $q); ?></th>
                        <th><?= $print_header('Judul', 'title', $q); ?></th>
                        <th><?= $print_header('Funding Scheme Long', 'funding_scheme_long', $q); ?></th>
                        <th><?= $print_header('Funding Scheme Short', 'funding_scheme_short', $q); ?></th>
                        <th><?= $print_header('Jumlah Dana', 'jUmlah_dana', $q); ?></th>
                        <th><?= $print_header('Sumber Dana', 'sumber_dana', $q); ?></th>
                        <th><?= $print_header('Delivery', 'delivery', $q); ?></th>
                        <th><?= $print_header('Kota', 'kota', $q); ?></th>
                        <th><?= $print_header('Provinsi', 'provinsi', $q); ?></th>
                        <th><?= $print_header('Waktu Mulai', 'time_start', $q); ?></th>
                        <th><?= $print_header('Waktu Berakhir', 'time_end', $q); ?></th>
                        <th><?= $print_header('Sumber Data', 'data_source', $q); ?></th>
                        <th><?= $print_header('Tanggal Proposal', 'proposal_date', $q); ?></th>
                        <th><?= $print_header('Usulan Dana', 'fund_proposed', $q); ?></th>
                        <th><?= $print_header('Dana Disetujui', 'fund_accepted', $q); ?></th>
                        <th><?= $print_header('Status', 'acceptance_status', $q); ?></th>
                        <th><?= $print_header('No Surat Tugas', 'No_surat_tugas', $q); ?></th>
                        <th><?= $print_header('Dokumen Pendukung', 'supporting_document', $q); ?></th>
                        <th><?= $print_header('Terakhir Di Edit Oleh', 'Last_modified_by', $q); ?></th>
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
                            <td>
                            <?= implode(",", (array) $row->department); ?>
                            </td>
                            <td><?= $row->team_leader; ?></td>
                            <td>
                            <?php if(count((array)$row->member_lecturer) < 2):?>
                                <?php foreach((array)$row->member_lecturer as $member_lecturer): ?>     
                                    <?= $member_lecturer; ?>
                                    <?php endforeach ?>
                            <?php else:?>
                                    <?php foreach((array)$row->member_lecturer as $member_lecturer): ?>     
                                    <?= $member_lecturer. ","; ?>
                                    <?php endforeach ?> 
                            <?php endif ?>
                            </td>
                            <td>
                            <?= implode(",", (array) $row->member_academic_staff); ?>
                            </td>
                            <td>
                            <?= implode(",", (array) $row->member_student); ?>
                            </td>
                            <td><?= $row->title; ?></td>
                            <td><?= $row->funding_scheme_long; ?></td>
                            <td><?= $row->funding_scheme_short; ?></td>
                            <td><?= $row->jUmlah_dana; ?></td>
                            <td><?= $row->sumber_dana; ?></td>
                            <td><?= $row->delivery; ?></td> 
                            <td><?= $row->kota; ?></td>
                            <td><?= $row->provinsi; ?></td>
                            <td><?php if ($row->time_start == "0000-00-00"): ?>
                                    <?php echo "" ?>
                                <?php else: ?>                                    
                                    <?= $formatter->format(strtotime($row->time_start ?? '')); ?>
                                <?php endif ?></td>
                            <td><?php if ($row->time_end == "0000-00-00"): ?>
                                    <?php echo "" ?>
                                <?php else: ?>                                    
                                    <?= $formatter->format(strtotime($row->time_end ?? '')); ?>
                                <?php endif ?></td>
                            <td><?= $row->data_source; ?></td>
                            <td><?php if ($row->proposal_date == "0000-00-00"): ?>
                                    <?php echo "" ?>
                                <?php else: ?>                                    
                                    <?= $formatter->format(strtotime($row->proposal_date ?? '')); ?>
                                <?php endif ?></td>
                            <td><?= $row->fund_proposed; ?></td>
                            <td><?= $row->fund_accepted; ?></td>
                            <td><?= $row->acceptance_status; ?></td>
                            <td><?= $row->No_surat_tugas; ?></td>
                            <td><?= $row->supporting_document; ?></td>
                            <td><?= $row->Last_modified_by; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (!in_array($jenis_user, ['dekan', 'wadek'])) : ?>
                                        <a class="btn btn-warning <?= $akses['edit']; ?>" title="edit" href="<?= base_url("p2m/edit_pengabdian/" . $row->pengabdianID); ?>"><i class="fa-solid fa-pencil"></i></a>
                                        <a class="btn btn-danger <?= $akses['delete']; ?>" title="delete" href="<?= base_url("p2m/delete_pengabdian/" . $row->pengabdianID); ?>" onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i class="fa-solid fa-times"></i></a>
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
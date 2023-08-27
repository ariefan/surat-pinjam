<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h4>Hutang</h4>
    </div>
    <div class="card-body">
        <h3>Penandatangan</h3>
        <table class="table table-sm" style="width: 50%;">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <!-- <th scope="col">Belum verifikasi</th> -->
                    <th scope="col">Belum approve/ttd</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td>
                            <?= $row->nama; ?>
                        </td>
                        <!-- <td>
                                <?= $row->jumlah_approval; ?>
                            </td> -->
                        <td>
                            <?= $row->jumlah_verifikasi; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br><br><br><br><br><br>

        <h3>Departemen</h3>
        <table class="table table-sm" style="width: 50%;">
            <thead>
                <tr>
                    <th scope="col">Nama Ketua Departemen</th>
                    <th scope="col">Nama Sekretaris Departemen</th>
                    <th scope="col">Belum Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drows as $row): ?>
                    <tr>
                        <td>
                            <?= $row->nama_ketua_departemen; ?>
                        </td>
                        <td>
                            <?= $row->nama_sekretaris_departemen; ?>
                        </td>
                        <td>
                            <?= $row->jml; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
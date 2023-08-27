<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Pengabdian</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('p2m/' . $action) . ($action == 'update_pengabdian' ? '/' . $row->pengabdianID : '') ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Departemen</label>
                <select id="department" class="form-control" name="department" value="<?= $row->department; ?>" required>
                    <option value="" disabled selected>Pilih departemen</option>
                    <option value="['Fisika']">Fisika</option>
                    <option value="Ilmu Komputer dan Elektronika">Ilmu Komputer dan Elektronika</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Matematika">Matematika</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Team Leader</label>
                <input placeholder="Nama Lengkap" autocomplete="off" type="text" class="form-control" name="team_leader" value="<?= $row->team_leader; ?>" required>
            </div>

            <div class="form-group">
                <label>Nama Anggota (Dosen)</label>
                <input placeholder="Nama Anggota" autocomplete="off" type="text" class="form-control" name="member_lecturer" value="<?= $row->member_lecturer; ?>">
            </div>

            <div class="form-group">
                <label>Nama Anggota (Akademik Staff)</label>
                <input placeholder="Nama Anggota" autocomplete="off" type="text" class="form-control" name="member_academic_staff" value="<?= $row->member_academic_staff; ?>">
            </div>

            <div class="form-group">
                <label>Nama Anggota (Mahasiswa)</label>
                <input placeholder="Nama Anggota" autocomplete="off" type="text" class="form-control" name="member_student" value="<?= $row->member_student; ?>">
            </div>

            <div class="form-group">
                <label>Judul</label>
                <input placeholder="Judul Pengabdian" autocomplete="off" type="text" class="form-control" name="title" value="<?= $row->title; ?>" required>
            </div>

            <div class="form-group">
                <label>Funding Scheme Long</label>
                <input placeholder="Funding Scheme Long" autocomplete="off" type="text" class="form-control" name="funding_scheme_long" value="<?= $row->funding_scheme_long; ?>">
            </div>

            <div class="form-group">
                <label>Funding Scheme Short</label>
                <input placeholder="Funding Scheme Short" autocomplete="off" type="text" class="form-control" name="funding_scheme_short" value="<?= $row->funding_scheme_short; ?>">
            </div>

            <div class="form-group">
                <label>Jumlah Dana</label>
                <input placeholder="Angka Saja (Tanpa Rp)" autocomplete="off" type="text" class="form-control" name="jUmlah_dana" value="<?= $row->jUmlah_dana; ?>">
            </div>

            <div class="form-group">
                <label>Sumber Dana</label>
                <input placeholder="Sumber Dana" autocomplete="off" type="text" class="form-control" name="sumber_dana" value="<?= $row->sumber_dana; ?>">
            </div>

            <div class="form-group">
                <label>Delivery</label>
                <input placeholder="Delivery" autocomplete="off" type="text" class="form-control" name="delivery" value="<?= $row->delivery; ?>">
            </div>

            <div class="form-group">
                <label>Kota</label>
                <input placeholder="Kota" autocomplete="off" type="text" class="form-control" name="kota" value="<?= $row->kota; ?>">
            </div>

            <div class="form-group">
                <label>Provinsi</label>
                <input placeholder="Provinsi" autocomplete="off" type="text" class="form-control" name="provinsi" value="<?= $row->provinsi; ?>">
            </div>

            <div class="form-group">
                <label>Waktu Mulai</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="time_start" onchange="get_nomor()" value="<?= $row->time_start; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Waktu Berakhir</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="time_end" onchange="get_nomor()" value="<?= $row->time_end; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Sumber Data</label>
                <input placeholder="Sumber Data" autocomplete="off" type="text" class="form-control" name="data_source" value="<?= $row->data_source; ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Proposal</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="proposal_date" onchange="get_nomor()" value="<?= $row->proposal_date; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Usulan Dana</label>
                <input placeholder="Angka Saja(tanpa Rp)" autocomplete="off" type="text" class="form-control" name="fund_proposed" value="<?= $row->fund_proposed; ?>">
            </div>

            <div class="form-group">
                <label>Dana Disetujui</label>
                <input placeholder="Angka Saja(tanpa Rp)" autocomplete="off" type="text" class="form-control" name="fund_accepted" value="<?= $row->fund_accepted; ?>">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select id="acceptance_status" class="form-control" name="acceptance_status" value="<?= $row->acceptance_status; ?>" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="accepted">Accepted</option>
                    <option value="">Not Accepted</option>
                </select>
            </div>

            <div class="form-group">
                <label>No Surat Tugas</label>
                <input placeholder="No Surat Tugas" autocomplete="off" type="text" class="form-control" name="No_surat_tugas" value="<?= $row->No_surat_tugas; ?>">
            </div>

            <div class="form-group">
                <label>Dokumen Pendukung</label>
                <input placeholder="Dokumen Pendukung" autocomplete="off" type="text" class="form-control" name="supporting_document" value="<?= $row->supporting_document; ?>">
            </div>

            <div class="mt-5">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success" name="status" value="preview">Simpan</button>
            </div>

        </form>
    </div>
</div>

<!-- <button id="button-test">Test</button>
<div id="test"> -->

</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<style>
    .cf::before,
    .cf::after {
        display: table;
        content: '';
    }

    .cf::after {
        clear: both;
    }

    .hiddenContent {
        display: none;
    }

    .margin-bottom {
        margin-bottom: 5px;
    }

    .margin-left {
        margin-left: 5px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>

</script>
<?= $this->endSection() ?>
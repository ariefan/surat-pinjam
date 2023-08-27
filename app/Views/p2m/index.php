<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <?php if (session('jenis_user') == 'admin' || session('jenis_user') == 'tendik') : ?>
                <div class="col-lg-12" style="text-align: right; margin-top:25px;">
                    <a class="btn btn-success" title="inputHTML" id="btnTambahST" href="<?= site_url('p2m/p2m_html'); ?>">Input HTML</a>
                    <a class="btn btn-success" title="inputHTML" id="btnTambahST" href="<?= site_url('p2m/p2m_pdf'); ?>">Scraping PDF</a>
                </div>
            <?php endif ?>
            <div class="col-lg-12" style="text-align: center; margin-top: 105px">
                <h4>Tabel Program Pengabdian Masyarakat</h4>
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <a class="btn btn-success" title="Dosen" id="btnTambahST" href="<?= site_url('p2m/p2m_dosen'); ?>">Dosen</a>
                        <a class="btn btn-success" title="Jurnal" id="btnTambahST" href="<?= site_url('p2m/p2m_jurnal'); ?>">Jurnal</a>
                        <a class="btn btn-success" title="Penelitian" id="btnTambahST" href="<?= site_url('p2m/p2m_penelitian'); ?>">Penelitian</a>
                        <a class="btn btn-success" title="Pengabdian" id="btnTambahST" href="<?= site_url('p2m/p2m_pengabdian'); ?>">Pengabdian</a>
                        <a class="btn btn-success" title="lecturer" id="btnTambahST" href="<?= site_url('p2m/p2m_publikasi'); ?>">Publikasi</a>
                        <a class="btn btn-success" title="external" id="btnTambahST" href="<?= site_url('p2m/p2m_external'); ?>">Eksternal</a>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-top: 30px">
                    <div class="col-lg-7">
                        <a class="btn btn-success" title="konferensi" id="btnTambahST" href="<?= site_url('p2m/p2m_konferensi'); ?>">Konferensi</a>
                        <a class="btn btn-success" title="acad" id="btnTambahST" href="<?= site_url('p2m/p2m_acadStaf'); ?>">Staff Akademik</a>
                        <a class="btn btn-success" title="mahasiswa" id="btnTambahST" href="<?= site_url('p2m/p2m_mahasiswa'); ?>">Mahasiswa</a>
                        <a class="btn btn-success" title="medmass" id="btnTambahST" href="<?= site_url('p2m/p2m_medmass'); ?>">Media Massa</a>
                        <a class="btn btn-success" title="penerbit" id="btnTambahST" href="<?= site_url('p2m/p2m_penerbit'); ?>">Penerbit</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12" style="text-align: center; margin-top:50px">
                <h4>Grafik (Interface) Program Pengabdian Masyarakat</h4>

                <a class="btn btn-primary" title="Dosen" id="btnTambahST" href="<?= site_url('interfacep2m/interface_dosen'); ?>">Dosen</a>
                <a class="btn btn-primary" title="Penelitian" id="btnTambahST" href="<?= site_url('interfacep2m/interface_penelitian'); ?>">Penelitian</a>
                <a class="btn btn-primary" title="Pengabdian" id="btnTambahST" href="<?= site_url('interfacep2m/interface_pengabdian'); ?>">Pengabdian</a>
                <a class="btn btn-primary" title="Lecturer" id="btnTambahST" href="<?= site_url('interfacep2m/interface_publikasi'); ?>">Publikasi</a>        
                <a class="btn btn-primary" title="Map" id="btnTambahST" href="<?= site_url('p2m/p2m_map'); ?>">Map</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
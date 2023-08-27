<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="container-fluid">
        <div class="form-group row">
            <div class="col-lg-12" style="text-align: center; margin-top: 250px">
                <h4>Update Data</h4> 
                <a class="btn btn-success" title="update" id="btnTambahST" data-toggle="modal" data-target="#startModal">Start</a>
                <a class="btn btn-danger" title="update" id="btnTambahST" data-toggle="modal" data-target="#cancelModal">Cancel</a>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="startModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Apakah anda yakin akan mengupdate data? (Proses ini membutuhkan waktu yang cukup lama)
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="location.href='<?= site_url('p2m/run_script'); ?>'" data-dismiss="modal">Yakin</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Update</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Apakah anda yakin akan mengcancel update? (Apabila update dibatalkan untuk update selanjutnya kembali dari awal)
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="location.href='<?= site_url('p2m/stop_script'); ?>'" data-dismiss="modal">Yakin</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
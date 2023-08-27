<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>
<div class="page-signin-modal modal">
    <div class="modal-dialog">
        <div class="box m-a-0">
            <div class="box-row">
                <div class="box-cell col-md-5 bg-primary p-a-4">
                    <div class="text-xs-center text-md-left">
                        <a class="px-demo-brand px-demo-brand-lg" href="#"><img src="https://simaster.ugm.ac.id/ugmfw-assets/images/ugm.png" alt="Logo UGM" width="70px" height="70px" /></a>
                        <div class="font-size-18 m-t-1 line-height-1">
                            <strong>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</strong>
                        </div>
                        <div class="font-size-18 m-t-1 line-height-1">
                            Universitas Gadjah Mada<br />
                        </div>
                    </div>
                </div>
                <style type="text/css">
                    .center {
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                        width: 50%;
                    }
                </style>
                <div class="box-cell col-md-7">
                    <div class="p-x-4 p-t-2 p-b-3" id="page-signin-form">
                        <h2>Form Pendaftaran Mahasiswa</h2>
                        <div style="color:red;"><?php echo session()->getFlashdata('error'); ?></div>
                        <div style="color:green;"><?php echo session()->getFlashdata('success'); ?></div>
                        <form method="post" action="<?= base_url(); ?>/auth/simpan_mhs" onsubmit="return validateForm()">
                            <div class="form-group row">
                                <label for="inputNama" class="col-sm-3 col-form-label"><b>Nama</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama" class="form-control" id="inputNama" placeholder="Masukkan Nama Lengkap">
                                    <span id="blankMsg" style="color:red"> </span>
                                    <span id="charMsg" style="color:red"> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-3 col-form-label"><b>Email</b></label>
                                <div class="col-sm-9">
                                    <input type="email" name="username" class="form-control" id="username" placeholder="Masukkan Email Lengkap">
                                    <span id="blankMsg1" style="color:red"> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label"><b>Password</b></label>
                                <div class="col-sm-9">
                                    <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Masukkan Password">
                                    <!-- <input type="password" name="password" class="form-control mt-2" id="konfirmasiPassword" placeholder="Konfirmasi Password"> -->
                                    <span id="message1" style="color:red"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <!-- <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Masukkan Password"> -->
                                    <input type="password" name="password" class="form-control mt-2" id="konfirmasiPassword" placeholder="Konfirmasi Password">
                                    <span id="message2" style="color:red"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputNim" class="col-sm-3 col-form-label" name="nim"><b>Nim</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nim" class="form-control" id="inputNim" placeholder="Masukkan Nim Lengkap">
                                    <span id="blankMsg2" style="color:red"> </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputProdi" class="col-sm-3 col-form-label"><b>Prodi</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="prodi" class="form-control" id="inputProdi" placeholder="Masukkan Nama Prodi">
                                    <span id="blankMsg3" style="color:red"> </span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3"><b>Daftar</b></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-xs-center m-t-2 font-weight-bold font-size-14 text-white" id="px-demo-signup-link">
            Copyright &#0169; Fakultas Matematika Dan Ilmu Pengetahuan Alam 2023<br />
            <small><b>UGM</b> Framework 3.2</small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    function validateForm() {
        //mengumpulkan data formulir dalam variabel JavaScript
        var pw1 = document.getElementById("inputPassword").value;
        var pw2 = document.getElementById("konfirmasiPassword").value;
        var name1 = document.getElementById("inputNama").value;
        var name2 = document.getElementById("inputNama").value;
        var email = document.getElementById("username").value;
        var nim = document.getElementById("inputNim").value;
        var prodi = document.getElementById("inputProdi").value;

        //cek inputan nama lengkap
        if (name1 == "") {
            document.getElementById("blankMsg").innerHTML = "**Isi nama lengkap anda";
            return false;
        }

        //validasi data karakter 
        if (!isNaN(name1)) {
            document.getElementById("blankMsg").innerHTML = "**Hanya karakter yang diperbolehkan";
            return false;
        }

        //validasi data karakter 
        if (!isNaN(name2)) {
            document.getElementById("charMsg").innerHTML = "**Inputan nama hanya boleh menggunakan karakter";
            return false;
        }

        //cek inputan email
        if (email == "") {
            document.getElementById("blankMsg1").innerHTML = "**Isi email anda";
            return false;
        }

        //cek password
        if (pw1 == "") {
            document.getElementById("message1").innerHTML = "**Tolong isi password";
            return false;
        }

        //cek konfirmasi password
        if (pw2 == "") {
            document.getElementById("message2").innerHTML = "**Tolong konfirmasi password";
            return false;
        }

        //validasi panjang minimum password
        if (pw1.length < 8) {
            document.getElementById("message1").innerHTML = "**Panjang password harus minimal 8 karakter";
            return false;
        }

        //validasi panjang maksimum password
        if (pw1.length > 15) {
            document.getElementById("message1").innerHTML = "**Panjang password tidak boleh melebihi 15 karakter";
            return false;
        }

        //konfirmasi kesamaan password
        if (pw1 != pw2) {
            document.getElementById("message2").innerHTML = "**Password tidak sama";
            return false;
        }

        //cek inputan nim
        if (nim == "") {
            document.getElementById("blankMsg2").innerHTML = "**Isi NIM anda";
            return false;
        }

        //cek inputan prodi
        if (prodi == "") {
            document.getElementById("blankMsg3").innerHTML = "**Isi prodi anda";
            return false;
        }
    }
</script>

<?= $this->endSection() ?>
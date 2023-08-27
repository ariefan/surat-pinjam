<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>
<div class="page-signin-modal modal">
    <div class="modal-dialog">
        <div class="box m-a-0">
            <div class="box-row">
                <div class="box-cell col-md-5 bg-primary p-a-4" style="background-color:#004366!important;">
                    <div class="text-xs-center text-md-left">
                        <a class="px-demo-brand px-demo-brand-lg" href="#"><img
                                src="https://simaster.ugm.ac.id/ugmfw-assets/images/ugm.png" alt="Logo UGM" width="70px"
                                height="70px" /></a>
                        <div class="font-size-14 m-t-4 line-height-1">
                            <strong>FAKULTAS <br>MATEMATIKA DAN ILMU PENGETAHUAN ALAM</strong>
                        </div>
                        <div class="font-size-16 m-t-4 line-height-1">
                            Universitas Gadjah Mada<br /><br><br><br>
                        </div>
                        <a href="<?= base_url('app.apk'); ?>" class="btn btn-lg btn-block btn-success" style="">
                            <i class="fas fa-download"></i> Unduh file .apk
                        </a>
                        <a href="<?= base_url('Panduan Sistem Persuratan FMIPA.pdf'); ?>"
                            class="btn btn-lg btn-block btn-info" style="">
                            <i class="fas fa-download"></i> Unduh Panduan
                        </a>
                        <div class="font-size-13 m-t-4 line-height-1">
                            Jika terjadi masalah harap hubungi kontak di bawah:<br />
                        </div>
                        <div class="font-size-12 m-t-4 line-height-1">
                            <a href="https://wa.me/6281392651260"><u>+62813-9265-1260</u> (Eko Priyanto)</a><br />
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
                        <?php if (session()->getFlashData('success')): ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->getFlashData('success') ?>
                            </div>
                        <?php endif ?>

                        <?php
                        if (session()->getFlashData('danger')) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashData('danger') ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="font-size-20 m-t-4 line-height-1">
                            <strong>Form Login</strong><br /><br>
                        </div>
                        <form method="post" action="<?= base_url(); ?>/auth/login">
                            <input type="text" name="username" class="form-control m-t-3"
                                placeholder="Username (email ugm lengkap dengan @)">
                            <input type="password" name="password" class="form-control m-t-3" placeholder="Password">
                            <!-- <div class="box-cell col-md-7">
                                <input type="hidden" name="simasterUGM_token"
                                    value="871342879b88c2e907305fdac7ae14d3" />
                                <h4 class="m-t-2 m-b-2 text-xs-center font-weight-semibold">
                                    Masukkan Captcha
                                </h4>
                                <div class="page-signin-form-group form-group from-group-lg clearfix">
                                    <span id="captcha-img" class="pull-xs-left"><img
                                            src="<?php echo $builder->inline(); ?>" /></span>
                                    <a class="pull-xs-right xhrd dest_captcha-img btn btn-lg bg-primary"
                                        href="<?= site_url('auth/reload_captcha'); ?>"><span
                                            class="fa fa-refresh"></span></a>
                                </div>
                                <fieldset class="page-signin-form-group form-group form-group-lg">
                                    <div class="page-signin-icon text-muted"><i class="fa fa-barcode"></i></div>
                                    <input type="text" class="page-signin-form-control form-control" id="captcha"
                                        name="captcha" placeholder="Captcha" value="<?php $_SESSION['phrase']; ?>" />
                                </fieldset>
                            </div> -->
                            <div style="color:red;">
                                <?php echo session()->getFlashdata('error'); ?>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">Login</button>
                            <!-- <a href="https://simaster.ugm.ac.id/ugmfw/signin_simaster/signin_proses" class="btn btn-block btn-lg btn-primary m-t-3"> Login </a> -->
                            <a href="<?= site_url('auth/forgot_password'); ?>"
                                class="btn btn-block btn-lg btn-info m-t-3"> Lupa Kata Sandi</a>
                            <a href="<?= site_url('auth/daftar'); ?>" class="btn btn-block btn-lg btn-success m-t-3">
                                Daftar (<i>Khusus Mahasiswa</i>)</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-xs-center m-t-2 font-weight-bold font-size-14 text-white" id="px-demo-signup-link">
            Copyright &#0169; Fakultas Matematika Dan Ilmu Pengetahuan Alam 2023<br />
            <small><b>UGM</b> Framework 4.2.5</small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
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
                                <h2>Lupa Kata Sandi</h2>                                
                                <div style="color:red;"><?php echo session()->getFlashdata('error'); ?></div>
                                <div style="color:green;"><?php echo session()->getFlashdata('success'); ?></div>
                                <form method="post" action="<?= base_url(); ?>/auth/process_forgot_password">
                                    <input type="email" name="username" class="form-control m-t-3"  placeholder="Email">
                                    <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">PROSES</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-xs-center m-t-2 font-weight-bold font-size-14 text-white" id="px-demo-signup-link">
                    Copyright &#0169; Fakultas Matematika Dan Ilmu Pengetahuan Alam 2023<br/>
                    <small><b>UGM</b> Framework 3.2</small>
                </div>
            </div>
        </div>

<?= $this->endSection() ?>
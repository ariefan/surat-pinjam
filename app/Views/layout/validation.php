<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SURAT</title>

  <link rel="shortcut icon" href="https://www.ugm.ac.id/images/ugm_favicon.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!-- IonIcons -->
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('adminlte.min.css'); ?>">
  <?= $this->renderSection('css') ?>
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <?php if (session()->getFlashData('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif ?>

    <?php
    if (session()->getFlashData('danger')) {
    ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('danger') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    ?>

    <?php
    if (session()->getFlashData('info')) {
    ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('info') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    ?>


    <?= $this->renderSection('content') ?>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <!-- <footer class="main-footer" style="margin:0;">
      <strong>Copyright &copy; 2022 <a href="https://mipa.ugm.ac.id/">FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</a>.</strong>
      UGM
      <div class="float-right d-none d-sm-inline-block">
        <b>Versi</b> 1.1.0
      </div>
    </footer> -->

  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="<?= base_url('/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap -->
  <script src="<?= base_url('/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- AdminLTE -->
  <script src="<?= base_url('/adminlte.js'); ?>"></script>

  <!-- OPTIONAL SCRIPTS -->
  <?= $this->renderSection('js') ?>

</body>

</html>
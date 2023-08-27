<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cetak</title>

  <link rel="stylesheet" href="bootstrap.min.css">
</head>

<style>

.table-print thead tr td,.table-print tbody tr td,.table-print thead tr th,.table-print tbody tr th{
    border-width: 1px !important;
    border-style: solid !important;
    border-color: #000 !important;
    padding: 5px;
}
.table-print{
  width:90%;
}
</style>

<body class="hold-transition sidebar-mini">
<div class="wrapper print">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">
          <?= $this->renderSection('content') ?>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

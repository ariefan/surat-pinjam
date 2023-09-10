<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SURAT</title>

  <link rel="shortcut icon" href="https://www.ugm.ac.id/images/ugm_favicon.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!-- IonIcons -->
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('adminlte.min.css'); ?>">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

  <style>
    .select2-container .select2-selection--single {
      height: 35px !important;
    }
  </style>
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
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link bg-warning" data-toggle="dropdown" href="#" aria-expanded="false"><b>To Do</b><i
              class="fas fa-angle-down ml-2"></i>
            <!-- <i class="fas fa-check"></i> To Do <span class="badge badge-danger">3</span> -->
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            <?php
            $todo = new \App\Models\TodoModel();
            $rows = $todo->where('user_id', session('id'))->where('NOW() <= DATE_ADD(created_at, INTERVAL 10 DAY)')->orderBy('created_at', 'desc')->get()->getResult();
            ?>

            <?php foreach ($rows as $row): ?>
              <div href="#" class="dropdown-item" style="white-space:normal;">
                <div class="row">
                  <div class="col-sm-1">
                    <input id="todo-<?= $row->id; ?>" class="todo-check" type="checkbox" data-id="<?= $row->id; ?>"
                      value="1" <?= $row->status_tugas ? 'checked' : ''; ?>>
                  </div>
                  <div class="col-sm-11">
                    <label class="form-check-label text-sm" for="todo-<?= $row->id; ?>"
                      style="<?= $row->status_tugas ? 'text-decoration:line-through;' : ''; ?>" id="">
                      <?= $row->tugas; ?>   <?= empty($row->link) ? '' : '<a href="' . $row->link . '">link</a>'; ?>
                    </label>
                    <p class="text-sm text-muted"><i class="far fa-clock m-0 p-0"></i>
                      <?= $row->deadline; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer bg-success" data-toggle="modal"
              data-target="#modal-todo">Tambah To Do</a>
          </div>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li> -->

        <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
            <a href="#" class="dropdown-item">

              <div class="media">
                <img src="https://m.media-amazon.com/images/I/71hinjs7hpL._AC_SL1316_.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Muhammad
                  </h3>
                  <p class="text-sm">itu benar itu benar...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 jam yang lalu</p>
                </div>
              </div>

            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">

              <div class="media">
                <img src="https://m.media-amazon.com/images/I/71hinjs7hpL._AC_SL1316_.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Eko
                  </h3>
                  <p class="text-sm">itu benar itu benar itu benar </p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 jam yang lalu</p>
                </div>
              </div>

            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">

              <div class="media">
                <img src="https://m.media-amazon.com/images/I/71hinjs7hpL._AC_SL1316_.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Priyanto
                  </h3>
                  <p class="text-sm">itu benar itu benar itu benar itu benar</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 jam yang lalu</p>
                </div>
              </div>

            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Lihat semua pesan</a>
          </div>
        </li> -->
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url(); ?>" class="brand-link">
        <img src="https://mipa.ugm.ac.id/wp-content/uploads/sites/1769/ugm_logo.png" alt="UGM" class="brand-image"
          style="opacity: .8">
        <span class="brand-text font-weight-light"><b>Persuratan FMIPA</b></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img
              src="https://thumbs.dreamstime.com/b/user-icon-trendy-flat-style-isolated-grey-background-user-symbol-user-icon-trendy-flat-style-isolated-grey-background-123663211.jpg"
              class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <div class="text-white">
              <?= session('nama'); ?>
            </div>
            <small class="text-white">
              <?= !empty(session('jenis_user')) ? ucwords(session('jenis_user')) : ''; ?>
            </small><br>
            <small class="text-white">
              <?= !empty(session('nama_departemen')) ? ucwords(session('nama_departemen')) : ''; ?>
            </small><br>
            <a href="<?= site_url('user/edit/' . session('id')); ?>" class="btn btn-info btn-sm py-0 text-white"
              title="Edit">Edit</a>
            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-info btn-sm py-0 text-white"
              title="Keluar">Keluar</a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

        <!-- Sidebar Menu -->
        <?php $db = \Config\Database::connect(); ?>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= site_url('gedung'); ?>"
                class="nav-link <?= service('router')->controllerName() == '\App\Controllers\Gedung' && service('router')->methodName() == 'index' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-circle"></i>
                <p>Gedung</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('ruang'); ?>"
                class="nav-link <?= service('router')->controllerName() == '\App\Controllers\Ruang' && service('router')->methodName() == 'index' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-circle"></i>
                <p>Ruang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('suratpeminjaman'); ?>"
                class="nav-link <?= service('router')->controllerName() == '\App\Controllers\SuratPeminjaman' && service('router')->methodName() == 'index' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-circle"></i>
                <p>Surat Pinjam</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <?php if (session()->getFlashData('success')): ?>
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

      <!-- <div id="app"></div> -->

      <?= $this->renderSection('content') ?>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2023 <a href="https://mipa.ugm.ac.id/">FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN
          ALAM</a>.</strong>
      UGM
      <div class="float-right d-none d-sm-inline-block">
        <b>Versi</b> 1.1.0
      </div>
    </footer>


    <!-- Modal Todo -->
    <div class="modal fade" id="modal-todo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('todo/store'); ?>" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Todo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tugas</label>
                <input class="form-control" type="text" id="tugas" name="tugas" placeholder="Masukkan deskripsi tugas"
                  value="" autocomplete="off">
                <input class="form-control" type="hidden" id="link" name="link" value="" autocomplete="off">
              </div>

              <div class="form-group" id="deadline-input">
                <label>Deadline</label>
                <input class="form-control" type="date" id="deadline" name="deadline"
                  value="<?= date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days')); ?>" autocomplete="off">
              </div>

              <div class="form-group">
                <label>Beri tugas ke</label>
                <?php
                $users = (new \App\Models\UserModel())->get()->getResult();
                ?>
                <select class="form-control" name="user_id">
                  <option value="<?= session('id'); ?>"><?= session('nama'); ?> - (Saya)</option>
                  <?php if (!empty(session('bawahan')))
                    foreach (session('bawahan') as $user): ?>
                      <option value="<?= $user->id; ?>"><?= $user->username; ?> - <?= $user->nama; ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>


  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="<?= base_url('/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap -->
  <script src="<?= base_url('/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- AdminLTE -->
  <script src="<?= base_url('/adminlte.js'); ?>"></script>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js">
  </script>
  <script>
    config = {
      headers: {
        Authorization: "Bearer " + "<?= session('token'); ?>"
      }
    }

    axios.get('/api/todo', config)
      .then(function(response) {
        console.log(response);
      })
      .catch(function(error) {
        console.log(error);
      })
  </script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>


  <script>
    $(document).ready(function () {
      $('select:not(.no-select2)')
    });

    // $(function () {
    //   var conn = new WebSocket('ws://117.53.46.140:6969?access_token=<?= session()->get('id') ?>');
    // })   

    setInterval(() => {
      fetch('<?= base_url('chat/total_unread'); ?>', {
        cache: 'no-store'
      }).then(response => response.text()).then(data => {
        if (data > 0) {
          document.querySelector('#total_unread').innerHTML = `<span class="badge badge-danger">${data}</b></span>`
        } else {
          document.querySelector('#total_unread').innerHTML = ``
        }
      });
    }, 5000);
  </script>
  <script>
    // $(document).ready(function() {
    //   $("#todo-body").hide();
    //   $("#todo-header").click(function() {
    //     $("#todo-body").toggle();
    //   });
    //   $(".todo-check").change(function() {
    //     var id = $(this).data('id');
    //     fetch('<?= base_url('todo/toggle'); ?>/' + id, {
    //       cache: 'no-store'
    //     }).then(response => response.text()).then(data => {
    //       if (data === 'true') {
    //         $("label[for='todo-" + id + "']").css("text-decoration", "line-through");
    //       } else {
    //         $("label[for='todo-" + id + "']").css("text-decoration", "");
    //       }
    //     });
    //   });
    // });
  </script>
  <?= $this->renderSection('js') ?>

</body>

</html>
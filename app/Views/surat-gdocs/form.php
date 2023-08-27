<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header border-0">
    <h3 class="card-title">Form Surat</h3>
  </div>

  <div class="card-body">
    <form action="<?= site_url('suratgdocs/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>" method="POST"
      enctype="multipart/form-data">

      <?php if (session('jenis_user') == 'verifikator'): ?>
        <div class="card my-4" style="border:solid #aaa 1px;">
          <div class="card-body">
            <div class="form-group">
              <label>Kode Pengolah</label><span style="color:red;">*</span>
              <?= view('data-nomor-surat/pengolah_surat.html'); ?>
            </div>
            <div class="form-group">
              <label>Kode Perihal</label><span style="color:red;">*</span>
              <?= view('data-nomor-surat/kode_perihal.html'); ?>
            </div>
            <div class="form-group">
              <label>Klasifikasi</label><span style="color:red;">*</span>
              <span id="klasifikasi"></span>
            </div>
            <div class="form-group">
              <label>Penandatangan</label><span style="color:red;">*</span>
              <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
                <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan): ?>
                  <option value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]</option>
                <?php endforeach ?>
              </select>
            </div>
            <!-- <div class="form-group">
                            <label>Nomor Surat</label>
                            <input type="text" class="form-control" readonly name="no_surat" id="no_surat" value="<?= $row->no_surat; ?>">
                        </div> -->
            <input type="hidden" class="form-control" readonly name="no_surat" id="no_surat"
              value="<?= $row->no_surat; ?>">
          </div>
        </div>
      <?php endif ?>





      <div class="form-group">
        <label>
          Perihal <span style="color:red;"><b>*</b></span>
        </label>
        <input required placeholder="Perihal Surat" autocomplete="off" type="text" class="form-control"
          name="nama_surat" value="<?= $row->nama_surat; ?>">
      </div>

      <div class="form-group">
        <label>Tanggal Surat</label><span style="color:red;">*</span><br />
        <div style="width: 150px">
          <input required type="date" class="form-control" name="tanggal_pengajuan" onchange="get_nomor()"
            value="<?= $row->tanggal_pengajuan; ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Penandatangan</label><span style="color:red;">*</span>
        <select class="form-control" name="penandatangan_pegawai_id" onchange="get_nomor()">
          <?php foreach ((new \App\Models\PenandatanganModel())->get()->getResult() as $penandatangan): ?>
            <option <?= $row->penandatangan_pegawai_id == $penandatangan->pegawai_id ? 'selected' : ''; ?>
              value="<?= $penandatangan->pegawai_id; ?>"><?= $penandatangan->nama_penandatangan; ?> [<?= $penandatangan->kode; ?>]
            </option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="form-group">
        <label>Verifikator</label><span style="color:red;">*</span>
        <select required class="form-control" name="departemen_pegawai_id">
          <?php foreach ($departemens as $departemen): ?>
            <option value="<?= $departemen->kepala_pegawai_id; ?>"
              <?= $row->departemen_pegawai_id == $departemen->kepala_pegawai_id ? 'selected' : ''; ?>> <?= $departemen->nama_departemen; ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="form-group">
        <label>Kategori Kegiatan</label><span style="color:red;">*</span>
        <?php
        $kategories = [
          'Bidang Umum',
          'Bidang Pendidikan, Pengajaran, dan Kemahasiswaan',
          'Bidang Keuangan, Aset, dan Sumber Daya Manusia',
          'Bidang Penelitian dan Pengabdian kepada Masyarakat',
          'Bidang Alumni, Kerjasama, dan Inovasi',
        ];
        ?>
        <select required class="form-control" name="kategori">
          <?php foreach ($kategories as $kat): ?>
            <option value="<?= $kat; ?>" <?= $row->kategori == $kat ? 'selected' : ''; ?>><?= $kat; ?></option>
          <?php endforeach ?>
        </select>
      </div>


      <div class="form-group">
        <label for="">Upload File Dasar Penerbitan Surat (pdf maks 2MB)</label><span style="color:red;">*</span>
        <input type="file" accept="application/pdf" name="berkas" class="form-control"
          onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }">
        <?php if (file_exists('upload/dasar_penerbitan_surat/' . $row->id . '.pdf')): ?>
          <a target="__blank" href="<?= base_url('upload/dasar_penerbitan_surat/' . $row->id . '.pdf') ?>">Dasar
            Penerbitan</a>
        <?php endif ?>
      </div>

      <div class="form-group">
        <a class="btn btn-outline-primary" target="__blank"
          href="<?= "https://docs.google.com/document/d/" . $row->gdocs_id . "/edit" ?>">Edit GDocs</a>
      </div>


      <div class="mt-5">
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-success" name="status" value="preview">Preview</button>
        <button type="submit" class="btn btn-primary" name="status" value="1">Simpan Surat</button>

      </div><br>

      <!-- <h3>Chat</h3>
      <div class="row">
        <div class="col-sm-10 pb-2">
          <ul class="list-group" id="chat-list">
            <li class="list-group-item list-group-item-info">ini adalah chat</li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-8"><input type="text" class="form-control" id="chat-input" /></div>
        <div class="col-sm-4">
          <button type="button" class="btn btn-primary" id="chat-send">Kirim</button>
          <button type="button" class="btn btn-warning" onclick="$('#modal-todo-suratgdocs').modal('show'); $('#tugas').val($('#chat-input').val())">Todo list</button>
        </div>
      </div> -->

    </form>

  </div>
</div>

<?php
$todo = new \App\Models\TodoModel();
$rows = $todo->where('user_id', session('id'))->where('NOW() <= deadline')->get()->getResult();
?>
<div class="card" style="z-index:9999;width:300px;position:fixed;top:5px;right:5px;">
  <div class="card-header bg-warning">
    <span style="cursor:pointer" id="todo-header"><b>To do list</b> <i class="fas fa-angle-down ml-2"></i></span>
    <button class="btn btn-sm btn-success py-0 float-right" data-toggle="modal" data-target="#modal-todo-suratgdocs"><i
        class="fas fa-plus"></i></button>
  </div>
  <div class="card-body bg-warning" id="todo-body">
    <?php foreach ($rows as $row): ?>
      <div class="form-check">
        <input id="todo-<?= $row->id; ?>" class="form-check-input todo-check" type="checkbox" data-id="<?= $row->id; ?>"
          value="1" <?= $row->status_tugas ? 'checked' : ''; ?>>
        <label class="form-check-label text-sm" for="todo-<?= $row->id; ?>"
          style="<?= $row->status_tugas ? 'text-decoration:line-through;' : ''; ?>" id="">
          <?= $row->tugas; ?><a class="btn btn-link" title="delete" href="<?= base_url("todo/delete/" . $row->id); ?>"
            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">hapus</a>
        </label>
        <p class="text-sm text-muted"><i class="far fa-clock m-0 p-0"></i>
          <?= $row->deadline; ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>
</div>


<!-- Modal Todo -->
<div class="modal fade" id="modal-todo-suratgdocs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Todo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('todo/store'); ?>" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label>Pesan</label>
            <input class="form-control" type="text" id="tugas" name="tugas" placeholder="Masukkan deskripsi tugas"
              value="" autocomplete="off">
            <input class="form-control" type="hidden" name="deadline" value="<?= '2099-01-01'; ?>" autocomplete="off">
          </div>

          <div class="form-group">
            <label>Kirim to do list ke</label>
            <?php
            $users = (new \App\Models\UserModel())->get()->getResult();
            ?>
            <select class="form-control" name="user_id">
              <option value="<?= session('id'); ?>"><?= session('nama'); ?> - (Saya)</option>
              <?php foreach ($shares as $user): ?>
                <option value="<?= $user->id; ?>"><?= $user->nama; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
  /* For other boilerplate styles, see: /docs/general-configuration-guide/boilerplate-content-css/ */
  /*
* For rendering images inserted using the image plugin.
* Includes image captions using the HTML5 figure element.
*/

  figure.image {
    display: inline-block;
    border: 1px solid gray;
    margin: 0 2px 0 1px;
    background: #f5f2f0;
  }

  figure.align-left {
    float: left;
  }

  figure.align-right {
    float: right;
  }

  figure.image img {
    margin: 8px 8px 0 8px;
  }

  figure.image figcaption {
    margin: 6px 8px 6px 8px;
    text-align: center;
  }


  /*
Alignment using classes rather than inline styles
check out the "formats" option
*/

  img.align-left {
    float: left;
  }

  img.align-right {
    float: right;
  }

  /* Basic styles for Table of Contents plugin (toc) */
  .mce-toc {
    border: 1px solid gray;
  }

  .mce-toc h2 {
    margin: 4px;
  }

  .mce-toc li {
    list-style-type: none;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
  $(document).ready(function () {
    $("#todo-body").hide();
    $("#todo-header").click(function () {
      $("#todo-body").toggle();
    });
    $(".todo-check").change(function () {
      var id = $(this).data('id');
      fetch('<?= base_url('todo/toggle'); ?>/' + id, {
        cache: 'no-store'
      }).then(response => response.text()).then(data => {
        if (data === 'true') {
          $("label[for='todo-" + id + "']").css("text-decoration", "line-through");
        } else {
          $("label[for='todo-" + id + "']").css("text-decoration", "");
        }
      });
    });
  });
</script>
<script>
  var useDarkMode = false; //window.matchMedia('(prefers-color-scheme: dark)').matches;




  //NOMOR SURAT PARSING =======================================================================================================
  let get_nomor = () => {
    let el = event.target
    let regExp = /\[([^)]+)\]/
    // if (el.name === 'dt[kode_dokumen_id]') {
    if (el.id.includes("kode_dokumen_id")) {
      // let label = el.options[el.selectedIndex].innerHTML
      // let kode = regExp.exec(label)[1]
      let kode = regExp.exec(el.innerHTML)[1]
      fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_' + kode + '.html'), {
        cache: 'no-store'
      }).then(response => response.text()).then(data => {
        if (data != 'error') {
          document.querySelector('#klasifikasi').innerHTML = data
          $('select[name="dt[klasifikasi_id]"]').select2()
        }
      })
    }
    let no_surat = document.querySelector("input[name=no_surat]")
    try {
      let penandatangan = document.querySelector("select[name='penandatangan_pegawai_id']")
      let pengolah = document.querySelector("select[name='dt[pengolah_surat_id]']")
      let kode_perihal = document.querySelector("select[name='dt[kode_dokumen_id]']")
      let klasifikasi = document.querySelector("select[name='dt[klasifikasi_id]']")
      // let tahun = document.querySelector("#no_surat-tahun").value
      let tahun = (new Date($("input[name=tanggal_pengajuan]").val())).getFullYear()
      pengolah = regExp.exec(pengolah.options[pengolah.selectedIndex].innerHTML)[1]
      kode_perihal = regExp.exec(kode_perihal.options[kode_perihal.selectedIndex].innerHTML)[1]
      klasifikasi = klasifikasi.options[klasifikasi.selectedIndex].innerHTML.split(" - ")[0].replace(' ', '')
      penandatangan = regExp.exec(penandatangan.options[penandatangan.selectedIndex].innerHTML)[1]
      no_surat.value = `/UN1/${penandatangan}/${pengolah}/${kode_perihal}.${klasifikasi}/${tahun}`

    } catch (e) { }
  }

  try {
    let arr = $("input[name=no_surat]").val().split("/")
    let penandatangan = arr[2]
    let pengolah = arr[3]
    let kode_perihal = arr[4].split('.')[0]
    let klasifikasi = arr[4].split('.')[1] + (arr[4].split('.').length > 2 ? '.' + arr[4].split('.')[2].replace(' ', '') : '')
    let tahun = arr[5]
    $("select[name='penandatangan_pegawai_id'] > option").each(function () {
      if (this.text.includes('[' + penandatangan + ']')) {
        $("select[name='penandatangan_pegawai_id']").val(this.value)
      }
    });
    $("select[name='dt[pengolah_surat_id]'] > option").each(function () {
      if (this.text.includes(pengolah)) {
        $("select[name='dt[pengolah_surat_id]']").val(this.value)
      }
    });
    $("select[name='dt[kode_dokumen_id]'] > option").each(function () {
      if (this.text.includes(kode_perihal)) {
        $("select[name='dt[kode_dokumen_id]']").val(this.value)
        fetch('<?= base_url('home/getview'); ?>/' + btoa('data-nomor-surat/klasifikasi_' + kode_perihal + '.html'), {
          cache: 'no-store'
        }).then(response => response.text()).then(data => {
          if (data != 'error') {
            document.querySelector('#klasifikasi').innerHTML = data
            if (klasifikasi.split('.').length == 1) {
              $("select[name='dt[klasifikasi_id]'] > option").each(function () {
                if (this.text.includes(klasifikasi)) {
                  $("select[name='dt[klasifikasi_id]']").val(this.value)
                }
              });
            } else {
              $("select[name='dt[klasifikasi_id]'] > optgroup > option").each(function () {
                if (this.text.includes(klasifikasi)) {
                  $("select[name='dt[klasifikasi_id]']").val(this.value)
                }
              });
            }
          }
        })
      }
    })
    document.querySelector("#no_surat-tahun").value = tahun
  } catch (e) { }
  //=======================================================================================================================

  $("#add-tembusan").click(function () {
    $('#list-tembusan').append(`
            <div class="input-group input-group-sm mb-1 tembusan-item col-sm-5">
                <input type="text" class="form-control" placeholder="Tembusan" name="tembusan[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger tembusan-btn-delete" type="button"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            `);

    $(".tembusan-btn-delete").click(function () {
      console.log($(this).closest('.tembusan-item'));
      $(this).closest('.tembusan-item').remove();
    });
  });

  $("#chat-send").click(function () {
    $('#chat-list').append(`<li class="list-group-item list-group-item-info">` + $('#chat-input').val() + `</li>`);
  });
</script>
<?= $this->endSection() ?>
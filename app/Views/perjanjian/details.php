<?= $this->extend('layout/app') ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
  .toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #000;
    color: #fff;
    padding: 5px 20px;
    border-radius: 5px;
    z-index: 1;
    opacity: 0;
    transition: opacity 0.4s ease-out;
  }

  .toast.show {
    opacity: 1;
  }

  .toast-body {
    font-size: 16px;
    line-height: 1.5;
  }

  table.table-bordered {
    border: 1px solid #002ca3 !important;
  }

  table.table-bordered>thead>tr>th {
    border: 1px solid #002ca3 !important;
  }

  table.table-bordered>tbody>tr>td {
    border: 1px solid #002ca3 !important;
  }

  .table-responsive {
    opacity: 1 !important;
  }
</style>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<nav class="nav nav-pills nav-fill">
  <?php
  $akses = [
    'details' => $gol_pic_mou == 2 || $gol_pic_mou == 1 ? '' : 'disabled',
    'reviews' => ($gol_pic_mou == 2 && $status_mou >= 1) || ($gol_pic_mou == 1 && $status_mou >= 1) ? '' : 'disabled',
    'luaran' => ($gol_pic_mou == 2 && $status_mou >= 3) || ($gol_pic_mou == 1 && $status_mou >= 3) ? '' : 'disabled',
    'monev' => ($gol_pic_mou == 2 && $status_mou >= 3) || ($gol_pic_mou == 1 && $status_mou >= 3) ? '' : 'disabled',
  ];
  $active = [
    'details' => $active == 1 ? 'active' : '',
    'reviews' => $active == 2 ? 'active' : '',
    'luaran' => $active == 3 ? 'active' : '',
    'monev' => $active == 4 ? 'active' : '',
  ];
  ?>
  <a class="nav-item nav-link" href="<?= site_url("perjanjian"); ?>"><i class="fa-solid fa-arrow-left"></i></a>
  <a class="nav-item nav-link <?= $akses['details'] ?> <?= $active['details'] ?>"
    href="<?= site_url("perjanjian/details/" . $id_mou); ?>">Detail</a>
  <a class="nav-item nav-link <?= $akses['reviews'] ?> <?= $active['reviews'] ?>"
    href="<?= site_url("perjanjian/reviews/" . $id_mou); ?>">Review</a>
  <a class="nav-item nav-link <?= $akses['luaran'] ?> <?= $active['luaran'] ?>"
    href="<?= site_url("perjanjian/luaran/" . $id_mou); ?>">Luaran</a>
  <a class="nav-item nav-link <?= $akses['monev'] ?> <?= $active['monev'] ?>"
    href="<?= site_url("perjanjian/monev/" . $id_mou); ?>">Monitoring dan Evaluasi</a>

</nav>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12" style="display: inline-block;
  vertical-align: middle;">
        <div class="h1" id="status_mou">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="text text-lg font-weight-bold m-0">Detail

                <div class="badge" id="status-badge">
                  <?php
                  switch ($row->status_mou ?? 0) {
                    case 0:
                      echo ('<span class="text-sm badge badge-secondary">Draft</span>');
                      break;
                    case 1:
                      echo '<span class="text-sm badge badge-warning">Proses Review</span>';
                      break;
                    case 2:
                      echo '<span class="text-sm badge badge-info">Review Selesai</span>';
                      break;
                    case 3:
                      echo '<span class="text-sm badge badge-success">Ditandatangani</span>';
                      break;
                    case 4:
                      echo '<span class="text-sm badge badge-success">Aktif</span>';
                      break;
                    case 5:
                      echo '<span class="text-sm badge badge-danger">Nonaktif</span>';
                      break;
                    default:
                      echo '<span class="text-sm badge badge-secondary">Draft</span>';
                  }

                  ?>
                </div>

              </h1>
            </div>
            <div class="col-sm-12">
              <h3 class="text text-md font-weight-light m-0">Kerja Sama <span class="text text-md font-weight-normal">
                  <?= $judul_kerjasama ?>
                </span> </h3>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>





<div class="content">
  <div class="container-fluid">
    <div id="menuDetails" class="form-group row ml-1">
      <?php if ($row->status_mou == 0): ?>
        <a class="btn btn-warning btn-md" title="Edit" href="<?= site_url("perjanjian/edit/" . $id_mou); ?>">Edit</a>
      <?php elseif ($row->status_mou == 1): ?>
        <a class="btn btn-info btn-md" title="Lihat Review" href="<?= site_url("perjanjian/reviews/" . $row->id_mou); ?>">
          <i class="fa-regular fa-file-lines"></i> Reviews</a>
        <a class="btn btn-warning btn-md ml-2" title="Edit"
          href="<?= site_url("perjanjian/edit/" . $row->id_mou); ?>">Edit</a>
      <?php elseif ($row->status_mou == 2): ?>
        <a class="btn btn-info btn-md" title="Lihat Review" href="<?= site_url("perjanjian/reviews/" . $row->id_mou); ?>">
          <i class="fa-regular fa-file-lines"></i> Reviews</a>
        <button id="btnUploadFileDitandatangani" type="button" class="btn btn-md btn-success ml-2" data-toggle="modal"
          data-target="#uploadModal">
          <i class="fa-solid fa-feather"></i> Upload file ditandatangani </button>
        <div style="float: left;" class="btn-group dropdown " id="btnDownloadDPI">
          <a id="linkDownloadDPI" class="btn btn-danger ml-2"
            href="<?php echo base_url('perjanjian/download_dpi/' . $id_mou . '/' . 1) ?>" title="Download Dokumen DPI"><i
              class=" fa-solid fa-download">
            </i> Download Dokumen DPI</a>
        </div>

      <?php elseif ($row->status_mou ?? 0 >= 3): ?>
        <a class="btn btn-info btn-md" title="Lihat Review" href="<?= site_url("perjanjian/reviews/" . $row->id_mou); ?>">
          <i class="fa-regular fa-file-lines"></i> Reviews</a>
        <button id="btnUploadFileDitandatangani" type="button" class="btn btn-md btn-success ml-2" data-toggle="modal"
          data-target="#uploadModal">
          <i class="fa-solid fa-feather"></i> Upload file ditandatangani </button>
        <div class="btn-group dropdown" id="btnDownloadDPI">
          <a id="linkDownloadDPI" class="btn btn-danger ml-2"
            href="<?php echo base_url('perjanjian/download_dpi/' . $id_mou) ?>" title="Download Dokumen DPI"><i
              class=" fa-solid fa-download">
            </i> Download Dokumen DPI</a>
          <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item"
              href="<?php echo base_url('perjanjian/download_dpi/' . $id_mou . '/' . 1) ?>">Download Template DPI
              (.docx)</a>
          </div>
        </div>

        <a id="btnDownloadFinal" class="btn btn-success btn-md ml-2" title="Download file kerjasama"
          href="<?= site_url("perjanjian/download_pdf_mou/" . $row->id_gdrive_dokumen); ?>">Download File Kerjasama</a>


      <?php endif; ?>

    </div>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th class="col-2">No Dokumen UGM</th>
                  <th class="col-2">No Dokumen Mitra</th>
                  <th class="col-2">Periode Kerjasama</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $sisa_waktu = $row->tanggal_akhir_kerjasama == "0000-00-00" || $row->tanggal_akhir_kerjasama == null ? '' : date_diff(date_create(date('Y-m-d')), date_create($row->tanggal_akhir_kerjasama))->format('%a');

                  $kuning = '<span class="text-sm badge badge-warning">Sisa ' . $sisa_waktu . ' Hari </span>';
                  $merah = '<span class="text-sm badge badge-danger">Sisa ' . $sisa_waktu . ' Hari </span>';
                  $selesai = '<span class="text-sm badge badge-danger">Telah Berakhir</span>';

                  $sisa_waktu = $sisa_waktu != '' && (int) $sisa_waktu <= 360 ? ((int) $sisa_waktu <= 30 ? ((int) $sisa_waktu <= 0 ? $selesai : $merah) : $kuning) : '';
                  $sisa_waktu = $row->tanggal_akhir_kerjasama < date('Y-m-d') ? $selesai : $sisa_waktu;
                  ?>
                  <td id="no_dokumen_ugm">
                    <?= $row->no_dokumen_ugm ?>
                  </td>
                  <td id="no_dokumen_mitra">
                    <?= $row->no_dokumen_mitra ?>
                  </td>
                  <td id="periode_kerjasama">
                    <?php if ($status_mou >= 3): ?>
                      <?php if ($row->tanggal_akhir_kerjasama == NULL): ?>
                        <?= $row->tanggal_mulai_kerjasama ?> s/d Sekarang (Tidak DIbatasi)
                      <?php else: ?>
                        <?= $row->tanggal_mulai_kerjasama ?> s/d
                        <?= $row->tanggal_akhir_kerjasama ?> <br>
                        <?= $sisa_waktu ?>
                      <?php endif ?>
                    <?php else: ?>
                      <?= $periode_kerjasama ?> <br>
                      <?= $durasi_kerjasama ?> <br>

                    <?php endif ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered" style="border-color: #002ca3;">
              <thead>
                <tr>
                  <th class="col-2">Judul Kerjasama</th>
                  <th class="col-2">Tipe Dokumen</th>
                  <th class="col-2">Bidang Kerjasama</th>
                  <th class="col-2">Nominal Kerjasama</th>
                  <th class="col-2">DPI</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?= $row->judul_kerjasama ?>
                  </td>
                  <td>
                    <?= $row->tipe_dokumen ?>
                  </td>
                  <!-- <span >Ditandatangani</span> -->
                  <td>
                    <?php foreach ($row->bidang_kerjasama as $bidang) {
                      echo '<span class="text-sm badge badge-primary ml-1">' . $bidang . '</span>';
                    }
                    ?>
                  </td>
                  <td class="text text-lg font-weight-bold" id="nominal">
                    <?= $row->nominal_kerjasama ?>
                  </td>
                  <td>
                    <?= $row->dpi ?>%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col-2">Program Studi Terlibat</th>
                  <th class="col-2">Pejabat Penandatangan</th>
                  <th class="col-2">Pejabat Penandatangan Mitra</th>
                  <th class="col-2">Tanggal Penandatanganan</th>
                  <th class="col-2">Tanggal Pengajuan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?php foreach ($row->program_studi_terlibat as $prodi) {
                      echo '<span class="text-sm badge badge-primary ml-1">' . $prodi . '</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <?= $row->pejabat_penandatanganan_ugm ?>
                  </td>
                  <td>
                    <?= $row->pejabat_penandatanganan_mitra ?>
                  </td>
                  <td id="tanggal_penandatanganan">
                    <?= $row->tanggal_penandatanganan ?>
                  </td>
                  <td>
                    <?= $row->tanggal_pengajuan ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col-2">Nama Mitra</th>
                  <th class="col-2">Alamat Mitra</th>
                  <th class="col-2">No Telp Mitra</th>
                  <th class="col-2">Email Mitra</th>

                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?= $row->nama_mitra ?>
                  </td>
                  <td>
                    <?= $row->alamat_mitra ?>
                  </td>
                  <td>
                    <?= $row->no_telp_mitra ?>
                  </td>
                  <td>
                    <?= $row->email_mitra ?>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col-2">Nama PIC Mitra</th>
                  <th class="col-2">Jabatan</th>
                  <th class="col-2">Alamat</th>
                  <th class="col-2">No telp</th>
                  <th class="col-2">Email</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?= $row->nama_pic_mitra ?>
                  </td>
                  <td>
                    <?= $row->jabatan_pic_mitra ?>
                  </td>
                  <td>
                    <?= $row->alamat_pic_mitra ?>
                  </td>
                  <td>
                    <?= $row->no_telp_pic_mitra ?>
                  </td>
                  <td>
                    <?= $row->email_pic_mitra ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col-2">Nama PIC UGM</th>
                  <th class="col-2">Jabatan</th>
                  <th class="col-2">Alamat</th>
                  <th class="col-2">No telp</th>
                  <th class="col-2">Email</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?= $row->nama_pic_ugm ?>
                  </td>
                  <td>
                    <?= $row->jabatan_pic_ugm ?>
                  </td>
                  <td>
                    <?= $row->alamat_pic_ugm ?>
                  </td>
                  <td>
                    <?= $row->no_telp_pic_ugm ?>
                  </td>
                  <td>
                    <?= $row->email_pic_ugm ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modalClass" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form enctype="multipart/form-data" id="ttd-form">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Upload File Ditandatangani</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>No Dokumen UGM</label><span style="color:red;">*</span><br />
                <div class="col">
                  <input type="text" class="form-control" required id="no_dokumen_ugm_form" name="no_dokumen_ugm">
                </div>
                <label>No Dokumen Mitra</label><span style="color:red;">*</span><br />
                <div class="col">
                  <input type="text" class="form-control" required id="no_dokumen_mitra_form" name="no_dokumen_mitra">
                </div>
              </div>
              <label>Tanggal Penandatanganan</label><span style="color:red;">*</span><br />
              <div class="col">
                <input type="date" class="form-control" required id="tanggal_ttd" name="tanggal_penandatanganan">
              </div>

              <div class="form-group">
                <label for="">File ditandatangani (pdf maks. 2MB)</label><span style="color:red;">*</span>
                <input type="file" accept="application/pdf" id="dokumen-mou" name="dokumen-mou" class="form-control"
                  onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }"
                  required>
              </div>
              <div class="form-group">
                <label for="">File Surat Pernyataan DPI (pdf maks. 2MB)</label><span style="color:red;">*</span>
                <input type="file" accept="application/pdf" id="dokumen-mou-dpi" name="dokumen-mou-dpi"
                  class="form-control"
                  onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }"
                  required>
              </div>
              <!-- <div class="form-group">
                <label for="">File ditandatangani (docx maks. 2MB)</label><span style="color:red;">*</span>
                <input type="file" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" id="dokumen-mou" name="dokumen-mou" class="form-control" onchange="if(this.files[0].size/1024/1024 > 2){ alert('Ukuran file tidak boleh lebih dari 2MB'); this.value = null; }" required>
              </div> -->
            </div>
            <div class="modal-footer">

              <div id="ttd-upload-loading-bar" style="display: none;">
                <div class="h6 mr-1 text-secondary" style="float: left;"> Mohon Tunggu...
                </div>
                <div class="spinner-border spinner-border-sm text-secondary" role="status" style="float: left;">
                  <span class="sr-only">Loading...</span>
                </div>

              </div>

              <div id="btnUploadTTD">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="submitPopover" type="button" class="btn btn-primary my-popover"
                  data-bs-toggle="popover">Submit</button>
              </div>



            </div>
          </div>
        </form>

      </div>
    </div>






    <div class="toast" id="notice">
      <div class="toast-body"></div>
    </div>
  </div>
</div>

<!-- Preview Modal -->

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
  integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="<?= base_url('pdf.js'); ?>"></script>
<script>
  $(function () {
    $('[data-toggle="popover"]').popover()
  })



  const toast = document.getElementById('notice');

  // Add hover effect to cells
  const cells = document.getElementsByTagName('td');
  for (let i = 0; i < cells.length; i++) {
    cells[i].addEventListener('mouseover', function () {
      this.style.backgroundColor = '#daebff';
      this.style.cursor = 'pointer';
    });
    cells[i].addEventListener('mouseout', function () {
      this.style.backgroundColor = '';
      this.style.cursor = '';
    });
  }

  // Add click effect to cells
  for (let i = 0; i < cells.length; i++) {
    cells[i].addEventListener('click', function () {
      // Copy the cell's content to the clipboard
      const range = document.createRange();
      range.selectNode(this);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);
      document.execCommand('copy');
      window.getSelection().removeAllRanges();



      // Show toast message that content has been copied
      const toastBody = toast.querySelector('.toast-body');
      toastBody.innerHTML = `Copied`;
      toast.classList.add('show');
      setTimeout(function () {
        toast.classList.remove('show');
      }, 2000);
    });
  }

  function viewpdf(id, akses) {
    $('#modal-pdf').modal('show')
    $('#form-komentar').attr('action', "<?= site_url('suratkp/update/') ?>" + id);
    if (akses) {
      $('#preview-panel').show()
    } else {
      $('#preview-panel').hide()
    }
    var url = "<?= site_url('suratkp/topdf/'); ?>" + id
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= base_url('
    pdf.worker.js '); ?>';
    var loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function (pdf) {
      console.log('PDF loaded');
      var container = document.getElementById('pdf-container');
      container.innerHTML = '';

      for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
        pdf.getPage(pageNumber).then(function (page) {
          console.log('Page loaded');

          var scale = 1.25;
          var viewport = page.getViewport({
            scale: scale
          });

          var wrapper = document.createElement("div");
          var canvas = document.createElement("canvas");
          var context = canvas.getContext('2d');
          wrapper.style.marginBottom = "16px";
          canvas.height = viewport.height;
          canvas.width = viewport.width;
          canvas.style.margin = "0 auto";
          canvas.style.display = "block";
          canvas.style.border = "solid #ccc 1px";

          wrapper.appendChild(canvas)
          container.appendChild(wrapper);

          var renderContext = {
            canvasContext: context,
            viewport: viewport
          };
          var renderTask = page.render(renderContext);
          renderTask.promise.then(function () {
            console.log('Page rendered');
          });
        });
      }
    }, function (reason) {
      console.error(reason);
    });
  }

  function nominalKerjasamaFormatting() {
    let USDollar = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
    });

    let IDRupiah = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
    });

    let JapaneseYen = new Intl.NumberFormat('ja-JP', {
      style: 'currency',
      currency: 'JPY',
    });

    let Euro = new Intl.NumberFormat('de-DE', {
      style: 'currency',
      currency: 'EUR',
    });

    let currency = '<?php echo $row->currency ?>'

    if (currency == '0') {
      $("#nominal").html(IDRupiah.format($("#nominal").html()))
    } else if (currency == '1') {
      $("#nominal").html(USDollar.format($("#nominal").html()))
    } else if (currency == '2') {
      $("#nominal").html(Euro.format($("#nominal").html()))
    } else if (currency == '3') {
      $("#nominal").html(JapaneseYen.format($("#nominal").html()))
    }

  }

  $(document).ready(function (e) {


    var today = new Date();

    // Format the date as "YYYY-MM-DD"
    var yyyy = today.getFullYear();
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var dd = String(today.getDate()).padStart(2, '0');
    var formattedDate = yyyy + '-' + mm + '-' + dd;

    // Set the default value of the date input to today's date
    $('#tanggal_ttd').val(formattedDate);


    let id = '<?= (session()->getFlashData('
    preview ')); ?> ';
  if (id.length > 0) {
    viewpdf(id, false);
  }

  nominalKerjasamaFormatting()

  var popover = new bootstrap.Popover($('.my-popover'), {
    container: 'body',
    placement: 'top',
    trigger: 'manual',
    html: true,
    title: 'FIle yang diupload tidak bisa diubah kembali. Apakah anda yakin?',
    content: '<a href="javascript:void(0)" id="btnYes" class="btn btn-sm btn-success">Submit</a>',
    sanitize: false
  });

  $('#submitPopover').on('click', function () {
    popover.show();
  });

  $(popover._element).on('shown.bs.popover', function () {
    $('#btnYes').on('click', function () {
      popover.hide();
      ttdSubmit();
    });
  });

  $('#uploadModal').on('hide.bs.modal', function () {
    popover.hide();
  });

  function ttdSubmit() {
    $('#ttd-form').submit();

  }

  function updateView() {
    $.ajax({
      type: 'POST',
      url: "<?php echo site_url('perjanjian/details_get_json/' . $row->id_mou) ?>",
      async: true,
      dataType: 'json',
      success: function (data) {

        var row = data['row']
        var status_mou = {
          '0': '<span class="text-sm badge badge-secondary">Draft</span>',
          '1': '<span class="text-sm badge badge-warning">Proses Review</span>',
          '2': '<span class="text-sm badge badge-info">Review Selesai</span>',
          '3': '<span class="text-sm badge badge-success">Ditandatangani</span>',
          '4': '<span class="text-sm badge badge-success">Aktif</span>',
          '5': '<span class="text-sm badge badge-danger">Nonaktif</span>',
        };

        $('#no_dokumen_ugm').html(`${row['no_dokumen_ugm']}`)
        $('#no_dokumen_mitra').html(`${row['no_dokumen_mitra']}`)
        $('#tanggal_penandatanganan').html(`${row['tanggal_penandatanganan']}`)
        $('#status-badge').html(`${status_mou[row['status_mou']]}`)
        // $('#btnUploadFileDitandatangani').hide()
        $('#btnEdit').hide()

        var btnDownloadFinal = `
          <a id="btnDownloadFinal" class="btn btn-success btn-md ml-2" title="Download file kerjasama"
        href="<?php echo site_url("perjanjian/download_pdf_mou/"); ?>${row['id_gdrive_dokumen']}">Download file kerjasama</a>
          `
        $('#menuDetails').append(btnDownloadFinal)


        if (row['status_mou'] >= 3) {
          let tanggal_mulai_kerjasama = data['row']['tanggal_mulai_kerjasama']
          let tanggal_akhir_kerjasama = data['row']['tanggal_akhir_kerjasama'] == NULL ? 'Sekarang (Tidak Dibatasi)' : data['row']['tanggal_akhir_kerjasama']
          let periode_kerjasama = tanggal_mulai_kerjasama + ' s / d ' + tanggal_akhir_kerjasama
          $('#periode_kerjasama').html(periode_kerjasama)
        }

      },
      error: function (e) {
        console.log(e);
      },
    })
  }

  function validateForm() {
    var isValid = true;
    $('div.alert').remove();
    // validate No Dokumen UGM
    var noDokumenUgm = $("#no_dokumen_ugm_form").val();
    if (!noDokumenUgm) {
      $("#no_dokumen_ugm_form").addClass("is-invalid");
      isValid = false;
      console.log('false')
    } else {
      $("#no_dokumen_ugm_form").removeClass("is-invalid");
    }

    // validate No Dokumen Mitra
    var noDokumenMitra = $("#no_dokumen_mitra_form").val();
    if (!noDokumenMitra) {
      $("#no_dokumen_mitra_form").addClass("is-invalid");
      isValid = false;
      console.log('false')
    } else {
      $("#no_dokumen_mitra_form").removeClass("is-invalid");
    }

    // validate file input
    var fileInput = document.getElementById("dokumen-mou");
    if (fileInput.files.length == 0) {
      $("#dokumen-mou").addClass("is-invalid");
      isValid = false;
    } else {
      $("#dokumen-mou").removeClass("is-invalid");
      var filesize = fileInput.files[0].size;
      if (filesize > 2097152) {
        $("#dokumen-mou").addClass("is-invalid");
        $('div.alert').remove();
        $('#dokumen-mou').after(`<div class="alert alert-danger mt-2" role="alert">
                                                File tidak bisa lebih dari 2 MB
                                                </div>`);
        isValid = false
      }

    }

    var fileInput = document.getElementById("dokumen-mou-dpi");
    if (fileInput.files.length == 0) {
      $("#dokumen-mou-dpi").addClass("is-invalid");
      isValid = false;
    } else {
      $("#dokumen-mou-dpi").removeClass("is-invalid");
      var filesize = fileInput.files[0].size;
      if (filesize > 2097152) {
        $("#dokumen-mou-dpi").addClass("is-invalid");
        $('div.alert').remove();
        $('#dokumen-mou-dpi').after(`<div class="alert alert-danger mt-2" role="alert">
                                                File tidak bisa lebih dari 2 MB
                                                </div>`);
        isValid = false
      }

    }

    return isValid;

  }


  $('#ttd-form').on('submit', function (e) {
    e.preventDefault();

    //ambil data dari modal
    var form_data = new FormData(this);
    var validate = validateForm();
    if (!validate) {
      console.log('validasi gagal')
      // event.preventDefault();
      return false;
    }
    $('div.alert').remove();
    $("#ttd-upload-loading-bar").show();
    $('#btnUploadTTD').hide();

    $.ajax({
      type: "POST",
      url: "<?php echo site_url('perjanjian/upload_mou_ditandatangani/' . $row->id_mou) ?>",
      data: form_data,
      processData: false,
      contentType: false,
      dataType: 'JSON',
      success: function (data) {
        // console.log('berhasil')
        try {
          $('.modalClass').fadeOut(350);
          $('.modal-backdrop').fadeOut(350);
          $('body').css('overflow', 'auto');
          // $('.modal-backdrop').hide();
          // $('#uploadModal').modal('show');
          // $('#uploadModal').on('shown.bs.modal', function () {
          //     $('#uploadModal').modal('hide');
          //     $('.modal-backdrop').hide();
          // })
        } catch (e) {
          console.log(e)
        }
        $("#ttd-upload-loading-bar").hide();
        $('#btnUploadTTD').show();
        $('[name="no_dokumen_mou"').val("");
        $('[name="no_dokumen_ugm"]').val("");
        $('[name="dokumen-mou"]').val("");
        // $('#revisi-upload-loading-bar').hide();
        // $('#btn-upload-revisi').show();
        // console.log(data);
        // console.log('SUCCESS');
        // updateView();
        location.reload()

      },
      error: function (e) {
        console.log(e);
      }
    })

    // e.preventDefault();
  })







  });

  function copyInnerHTML(elementId) {
    // Get the element
    const element = document.getElementById(elementId);

    // Create a temporary input element
    const tempInput = document.createElement("input");

    // Set the value of the input element to the innerHTML of the original element
    tempInput.value = element.innerHTML;

    // Add the input element to the document
    document.body.appendChild(tempInput);

    // Select the text in the input element
    tempInput.select();

    // Copy the selected text to the clipboard
    document.execCommand("copy");

    // Remove the input element from the document
    document.body.removeChild(tempInput);
  }
</script>
<?= $this->endSection() ?>
<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<style>
  .btn:hover {
    background-color: #dfd;
    color: green;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h4>
      <?= $action == 'update' ? 'Edit' : 'Tambah'; ?> Surat Peminjaman
    </h4>
  </div>
  <div class="card-body">

    <form action="<?= site_url('suratpeminjaman/' . $action) . ($action == 'update' ? '/' . $row->id : '') ?>"
      method="post">
      <div class="form-group">
        <label>No Surat</label>
        <input type="text" class="form-control" name="no_surat" value="<?= $row->no_surat; ?>">
      </div>
      <div class="form-group">
        <label>Judul Surat</label>
        <input type="text" class="form-control" name="nama_surat" value="<?= $row->nama_surat; ?>">
      </div>
      <div class="form-group">
        <label>Tembusan</label>
        <textarea class="form-control" name="tembusan"><?= $row->tembusan; ?></textarea>
      </div>
      <div class="form-group">
        <label>Ruang yang akan dipinjam</label>
        <div class="">
          <table class="table table-sm">
            <thead>
              <tr>
                <th style="width:200px;">Gedung</th>
                <th style="width:200px;">Ruang</th>
                <!-- <th>Harga Sewa</th> -->
                <th style="width:150px;">Tanggal Mulai</th>
                <th style="width:150px;">Tanggal Selesai</th>
                <th style="width:300px;">Hari</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><select class="form-control gedung-select no-select2" name="gedung[]"></select></td>
                <td><select class="form-control ruang-select no-select2" name="ruang[]"></select></td>
                <!-- <td><input type="text" class="form-control" name="harga_sewa[]"></td> -->
                <td><input type="date" class="form-control" name="tanggal_mulai[]"></td>
                <td><input type="date" class="form-control" name="tanggal_selesai[]"></td>
                <td>
                  <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Senin"> Senin
                    </label>
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Selasa"> Selasa
                    </label>
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Rabu"> Rabu
                    </label>
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Kamis"> Kamis
                    </label>
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Jumat"> Jumat
                    </label>
                    <label class="btn btn-outline-success">
                      <input class="btn-check" type="checkbox" name="hari[]" value="Sabtu"> Sabtu
                    </label>
                    <!-- <label class="btn btn-outline-success">
                      <input type="checkbox" name="hari[]" value="Minggu"> Minggu
                    </label> -->
                  </div>
                </td>

                <td><button type="button" class="btn btn-danger btn-sm delete-btn">Delete</button></td>
              </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-success btn-sm" id="add-row">Add</button>

        </div>
      </div>
      <div class="form-group">
        <button type="reset" class="btn btn-danger">Reset</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script src="<?= base_url('/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists help charmap quickbars emoticons',
    menubar: '',
    toolbar: 'undo redo table numlist bullist alignleft aligncenter alignright alignjustify',
    toolbar_sticky: true,
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  });

  $(document).ready(function () {
    // Add a click event handler for the delete button
    $('.delete-btn').on('click', function () {
      // Find the parent row and remove it
      $(this).closest('tr').remove();
    });

    // Add a click event handler for the add button
    $('#add-row').on('click', function () {
      // Clone the first row and append it to the table body
      var newRow = $('tbody tr:first').clone();
      $('tbody').append(newRow);

      // Clear input values in the new row
      newRow.find('input').val('');
    });


    var $gedung = $('.gedung-select');
    // Make an AJAX request to retrieve data from the API
    $.ajax({
      url: '<?= site_url('api/gedung') ?>', // Replace with the actual API endpoint URL
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        // Iterate through the received data and add options to the <select> element
        $.each(response.data, function (index, item) {
          $gedung.append($('<option>', {
            value: item.id, // Replace with the actual value property from your API response
            text: item.nama_gedung,   // Replace with the actual text property from your API response
          }));
        });
      },
      error: function (xhr, status, error) {
        console.error('Error fetching data from API:', error);
      }
    });

    var $ruang = $('.ruang-select');
    // Make an AJAX request to retrieve data from the API
    $.ajax({
      url: '<?= site_url('api/ruang') ?>', // Replace with the actual API endpoint URL
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        // Iterate through the received data and add options to the <select> element
        $.each(response.data, function (index, item) {
          $ruang.append($('<option>', {
            value: item.id, // Replace with the actual value property from your API response
            text: item.nama_ruang,   // Replace with the actual text property from your API response
          }));
        });
      },
      error: function (xhr, status, error) {
        console.error('Error fetching data from API:', error);
      }
    });
  });
</script>
<?= $this->endSection() ?>
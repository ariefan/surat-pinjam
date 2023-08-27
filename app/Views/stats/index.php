<?= $this->extend('layout/app') ?>


<?= $this->section('css') ?>
<style>
  table tr td input[type=number] {
    width: 60px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card pl-4">
  <div class="card-header">
    <h4>Indikator</h4>
  </div>
  <div class="card-body p-0">
    <form>
      <div class="form-check form-check-inline">
        <div class="dropdown">
          <label class="form-check-label pr-2">Tahun Capaian: </label>
          <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
            <?= $q_tahun; ?> -
            <?= $q_periode; ?>
          </button>
          <div class="dropdown-menu">
            <?php foreach ($periodes as $periode): ?>
              <a class="dropdown-item" href="?q_periode=<?= $periode->periode; ?>"><?= $periode->tahun; ?>, <?= $periode->periode; ?></a>
            <?php endforeach ?>
          </div>
        </div>
      </div>
      <div class="form-check form-check-inline ml-4">
        <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#modal-capaian">Tambah
          Data</button>
      </div>
      <div class="form-check form-check-inline">
        <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#modal-indikator">Tambah
          Indikator</button>
      </div>
    </form>

    <br>
    <!-- <marquee> -->
    <a class="btn btn-sm btn-danger" href="<?= base_url('stats/deleteall/' . $q_tahun . '/' . $q_periode); ?>"
      onclick="return confirm('Anda Yakin Akan Menghapus Data Capaian periode ini?!');">
      <h4> Hapus Capaian </h4>
    </a>
    <!-- </marquee> -->

    <form action="<?= base_url('stats/update'); ?>" method="post">
      <table class="table table-sm mb-4" style="font-size:80%;">
        <thead>
          <tr>
            <th scope="col" rowspan="2">No Urut</th>
            <th scope="col" style="min-width:400px;" rowspan="2">Indikator</th>
            <th scope="col" rowspan="2">Satuan</th>
            <th scope="col" rowspan="2">Target
              <?= $rows[0]->tahun; ?>
            </th>
            <th scope="col" rowspan="2">Jumlah Prodi</th>
            <th scope="col" colspan="5" style="text-align:center;">Capaian Target
              <?= $rows[0]->tahun; ?>
            </th>
            <th scope="col" rowspan="2">Keterangan</th>
            <th scope="col" rowspan="2">Sumber Data</th>
            <th scope="col" rowspan="2">Aksi</th>
          </tr>
          <tr>
            <th scope="col">IKE</th>
            <th scope="col">FIS</th>
            <th scope="col">MAT</th>
            <th scope="col">KIM</th>
            <th scope="col">Capaian</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr class="indikator">
              <td><input type="number" name="data[<?= $row->id; ?>][no]" value="<?= $row->no; ?>" style="width:40px;" />
              </td>
              <td><textarea rows="3" name="data[<?= $row->id; ?>][indikator]"
                  style="width:100%;"><?= $row->indikator; ?></textarea></td>
              <td><input name="data[<?= $row->id; ?>][satuan]" value="<?= $row->satuan; ?>" style="width:100px;" /></td>
              <td><input type="number" name="data[<?= $row->id; ?>][target]" value="<?= $row->target; ?>" /></td>
              <td><input type="number" name="data[<?= $row->id; ?>][jumlah_prodi]" value="<?= $row->jumlah_prodi; ?>" />
              </td>
              <td><input class="capaian-item" type="number" name="data[<?= $row->id; ?>][capaian_ike]"
                  value="<?= $row->capaian_ike; ?>" /></td>
              <td><input class="capaian-item" type="number" name="data[<?= $row->id; ?>][capaian_fis]"
                  value="<?= $row->capaian_fis; ?>" /></td>
              <td><input class="capaian-item" type="number" name="data[<?= $row->id; ?>][capaian_mat]"
                  value="<?= $row->capaian_mat; ?>" /></td>
              <td><input class="capaian-item" type="number" name="data[<?= $row->id; ?>][capaian_kim]"
                  value="<?= $row->capaian_kim; ?>" /></td>
              <td><input class="capaian-item" type="number" name="data[<?= $row->id; ?>][capaian]"
                  value="<?= $row->capaian; ?>" /></td>
              <td><textarea rows="3" name="data[<?= $row->id; ?>][keterangan]"><?= $row->keterangan; ?></textarea></td>
              <td><textarea rows="3" name="data[<?= $row->id; ?>][sumber_data]"><?= $row->sumber_data; ?></textarea></td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a class="btn btn-danger" title="delete" href="<?= base_url("stats/delete/" . $row->id); ?>"
                    onclick="return confirm('Apakah anda yakin ingin menghapus surat ini?');"><i
                      class="fa-solid fa-times"></i></a>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <hr />
      <button class="btn btn-success" type="submit">Simpan</button>
      <br />
      <br />
    </form>
  </div>
</div>





<!-- Tambah Capaian -->
<form action="<?= base_url('stats/store'); ?>" method="post">
  <div class="modal" id="modal-capaian" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Buat baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tahun</label>
            <input type="number" class="form-control" name="tahun" value="<?= date('Y'); ?>">
          </div>
          <!-- <div class="form-group">
            <label>Bulan</label>
            <select class="form-control" name="bulan">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i; ?>" <?= $i == date('m') ? 'selected' : ''; ?>><?= get_bulan($i); ?></option>
              <?php endfor ?>
            </select>
          </div> -->
          <div class="form-group">
            <label>Periode</label>
            <select class="form-control" name="periode">
              <option value="Januari - Maret">Januari - Maret</option>
              <option value="April - Juni">April - Juni</option>
              <option value="Juli - September">Juli - September</option>
              <option value="Oktober - Desember">Oktober - Desember</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Buat baru</button>
        </div>
      </div>
    </div>
  </div>
</form>


<!-- Tambah Indikator -->
<form action="<?= base_url('stats/storeindikator'); ?>" method="post">
  <div class="modal" id="modal-indikator" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Indikator</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>No Urut</label>
            <input type="number" class="form-control" name="no" value="<?= $rows[count($rows) - 1]->no + 1; ?>">
          </div>
          <div class="form-group">
            <label>Indikator</label>
            <textarea class="form-control" name="indikator"></textarea>
            <input type="hidden" name="tahun" value="<?= $q_tahun; ?>">
            <input type="hidden" name="bulan" value="<?= $q_periode; ?>">
          </div>
          <div class="form-group">
            <label>Satuan</label>
            <input type="text" class="form-control" name="satuan" value="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah Indikator</button>
        </div>
      </div>
    </div>
  </div>
</form>

<?= $this->endSection() ?>


<!-- <?= $this->section('js') ?>
<script>
$(document).ready((e) => {
    $('.capaian-item').each(function(index) {
        let total = 0
        $(this).parents('tr').find('.capaian-item').each(function(index) {
          if($(this).hasClass('capaian-total')){
            $(this).val(total)
          }else{
            total += parseInt($(this).val())
          }
        })
        $(this).on('input', function(){
          let total = 0
          $(this).parents('tr').find('.capaian-item').each(function(index) {
            if($(this).hasClass('capaian-total')){
              $(this).val(total)
            }else{
              total += parseInt($(this).val())
            }
          })
        })
    })
})
</script>
<?= $this->endSection() ?> -->
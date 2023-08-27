<?= $this->extend('layout/app'); ?>

<?= $this->section('content'); ?>

<div class="container">
  <h2 class="d-flex justify-content-center m-3">Tambah Ruangan</h2>

  <form action="/tambah_ruangan/tambah" method="post" onsubmit="return validateForm()">
    <?= csrf_field(); ?>
    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="nama" class="col-form-label">Nama ruangan</label>
      </div>
      <div class="col-4">
        <input type="text" id="nama" class="form-control" name="nama" required autofocus>
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="lokasi" class="col-form-label">Penjelasan lokasi</label>
      </div>
      <div class="col-4">
        <textarea class="form-control" placeholder="" id="lokasi" name="lokasi" required></textarea>
      </div>
    </div>

    <fieldset class="row mb-2">
      <legend class="col-form-label col-sm-2 pt-0">Akses</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="umum" id="umum">
          <label class="form-check-label" for="umum">
            Umum
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="fakultas" id="fakultas">
          <label class="form-check-label" for="fakultas">
            Fakultas
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="dike" id="dike">
          <label class="form-check-label" for="dike">
            DIKE
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="kimia" id="kimia">
          <label class="form-check-label" for="kimia">
            Dept. Kimia
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="fisika" id="fisika">
          <label class="form-check-label" for="fisika">
            Dept. Fisika
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" onchange="handleCheck(this)" name="matematika" id="matematika">
          <label class="form-check-label" for="matematika">
            Dept. Matematika
          </label>
        </div>
      </div>
    </fieldset>

    <a href="tambah_ruangan/tambah">
      <button type="submit" class="btn btn-success">Simpan</button>
    </a>
  </form>
</div>

<script>
  const checkboxes = document.querySelectorAll('input[type="checkbox"]')

  const validateForm = () => {
    let checked = false

    for (let i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        checked = true;
        break;
      }
    }

    if (!checked) {
      alert('Pilih minimal satu akses')
      return false;
    }

    return true;
  }

  const handleCheck = (e) => {
    if (e.name == 'umum' && e.checked) {
      checkboxes.forEach(box => {
        if (box.name == umum) return
        box.checked = true
      });
    } else if (e.name == 'fakultas' && e.checked) {
      checkboxes.forEach(box => {
        if (box.name == 'fakultas' || box.name == 'umum') return
        box.checked = true
      });

    }
  }
</script>

<?= $this->endSection(); ?>
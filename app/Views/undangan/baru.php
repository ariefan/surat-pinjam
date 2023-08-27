<?= $this->extend('layout/app'); ?>

<?= $this->section('content'); ?>

<div class="container">
  <h2 class="d-flex justify-content-center m-3">Menu Undangan</h2>

  <form action="<?= base_url('undangan/' . $action . ($action == 'update/' ? 'ajukan/' : ($undangan ? '/' . $undangan['id'] : ''))); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Tanggal undangan" class="col-form-label">Tanggal undangan</label>
      </div>
      <div class="col-2">
        <input required type="date" id="Tanggal undangan" name="tanggal_undangan" class="form-control" value="<?= $undangan ? $undangan['tanggal_undangan'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Hal" class="col-form-label">Hal</label>
      </div>
      <div class="col-2">
        <input required type="text" name="hal" class="form-control" value="<?= $undangan ? $undangan['hal'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Lampiran" class="col-form-label">Lampiran</label>
      </div>
      <div class="col-2">
        <input required type="text" name="lampiran" class="form-control" value="<?= $undangan ? $undangan['lampiran'] : ''  ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Pengundang" class="col-form-label">Pengundang</label>
      </div>
      <div class="col-2">
        <input required type="text" name="pengundang" class="form-control" value="<?= $undangan ? $undangan['pengundang'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Sehubungan dengan" class="col-form-label">Sehubungan dengan</label>
      </div>
      <div class="col-2">
        <input required type="text" name="sehubungan_dengan" class="form-control" value="<?= $undangan ? $undangan['sehubungan_dengan'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Hari" class="col-form-label">Hari</label>
      </div>
      <div class="col-2">
        <input required type="text" name="hari" class="form-control" value="<?= $undangan ? $undangan['hari'] : ''  ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Tanggal" class="col-form-label">Tanggal</label>
      </div>
      <div class="col-2">
        <input required type="date" id="Tanggal" name="tanggal" class="form-control" value="<?= $undangan ? $undangan['tanggal'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Pukul" class="col-form-label">Pukul</label>
      </div>
      <div class="col-2">
        <input required type="time" name="pukul" class="form-control" value="<?= $undangan ? $undangan['pukul'] : '' ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Tempat" class="col-form-label">Tempat</label>
      </div>
      <div class="btn-group col-2">
        <select required class="form-select" id="floatingSelect" aria-label="Floating label select example" name='tempat'>
          <option selected value="<?= $undangan ? $undangan['tempat'] : ""; ?>"><?= $undangan ? $undangan['tempat'] : ""; ?></option>
          <option value="Tempat A">Tempat A</option>
          <option value="Tempat B">Tempat B</option>
          <option value="Tempat C">Tempat C</option>
        </select>
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Acara" class="col-form-label">Acara</label>
      </div>
      <div class="col-2">
        <input required type="text" name="acara" class="form-control" value="<?= $undangan ? $undangan['acara'] : ''  ?>">
      </div>
    </div>

    <div class="row g-3 align-items-center mb-2">
      <div class="col-2">
        <label for="Agenda" class="col-form-label">Agenda</label>
      </div>
      <div class="col-2">
        <input required type="text" name="agenda" class="form-control" value="<?= $undangan ? $undangan['agenda'] : '' ?>">
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="daftarPenerima" class="form-label">Daftar Penerima Undangan</label>
        <div id="penerima-field">
          <div id="penerima1" class=" d-flex column-gap-2 mb-2 penerima-fields position-relative">
            <input type="text" class="form-control penerima" id="penerima1" name="penerima1" list="list-penerima" required>
            <datalist id="list-penerima">
              <?php foreach ($users as $user) : ?>
                <option value="<?= $user->nama; ?>"></option>
              <?php endforeach ?>
            </datalist>
            <input type="hidden" name="total-penerima" id="total-penerima" value="1">
            <input type="hidden" name="total-absen" id="total-absen" value="1">

            <!-- <div class="nameList position-absolute top-100 mt-1 bg-light rounded"></div> -->

            <button type="button" class="btn btn-primary" id="addAbsen1">A</button>
            <button type="button" class="btn btn-warning notul-button" id="addNotul1">N</button>
            <!-- <button type="button" class="btn btn-warning" onclick=''>N</button> -->
            <button type="button" class="btn btn-success" onclick='addPenerima(this)'>+</button>
            <!-- <button type="button" class="btn btn-danger" id="remove-penerima" onclick="removePenerima()">-</button> -->
          </div>
        </div>
      </div>

      <div class="col">
        <label for="absen" class="form-label">Petugas Absen</label>
        <input type="text" class="form-control mb-2 absen" id="absen1" name='absen1' onkeydown="return false" autocomplete="off" required>
        <div id="absen-field"></div>
        <label for="notulen" class="form-label">Notulen</label>
        <input type="text" class="form-control mb-2" id="notulen" name="notulen" onkeydown="return false" autocomplete="off" required value="<?= $undangan ? $undangan['notulen'] : ''; ?>">
      </div>
    </div>

    <div class="row">
      <div class="col">

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="benar" id="benar" required>
          <label class="form-check-label" for="benar">
            Undangan ini adalah benar
          </label>
        </div>
        <button type="submit" class="btn btn-success mb-2"><?= $undangan ? "Simpan" : "Ajukan"; ?></button>
      </div>
    </div>
  </form>

  <h6 class="mt-5">Ketentuan</h6>
  <ul>
    <li>
      Semua penerima undangan akan diundang melalui Google Calendar sesuai email yang didaftarkan di sistem persuratan. Undangan dikirimkan setelah disetujui/ditandatangani oleh pengundang.
    </li>
  </ul>
</div>

<script>
  // PENERIMA UNDANGAN

  let penerimaInputs = 1
  let i = 0

  const handlePenerima = (e) => {
    let found = 0
    const calonPenerima = e.target.value
    console.log(document.getElementsByClassName('penerima'))
    const penerimas = document.getElementsByClassName('penerima')
    for (let p = 0; p < penerimaInputs; p++) {
      if (penerimas[p]) {
        if (calonPenerima == penerimas[p].value) found++
      }
    }

    if (found >= 2) {
      alert('Penerima sudah pernah ditambahkan')
      e.target.value = ''
    }
  }

  const penerimaField = document.getElementById('penerima-field')

  document.getElementById('penerima1').addEventListener('change', (e) => {
    handlePenerima(e)
  })
  document.getElementById('addAbsen1').addEventListener('click', (e) => {
    addAbsen(e)
  })
  document.getElementById('addNotul1').addEventListener('click', (e) => {
    addNotul(e)
  })

  const totalPenerima = document.getElementById('total-penerima')

  const addPenerima = (e) => {
    penerimaInputs++

    const newField = document.createElement('div')
    newField.id = `penerima${penerimaInputs}`
    newField.classList.add(...['d-flex', 'column-gap-2', 'mb-2', 'penerima-fields'])

    const newInputField = document.createElement('input')
    newInputField.type = "text"
    newInputField.classList.add(...['form-control', 'penerima'])
    newInputField.id = `penerima${penerimaInputs}`
    newInputField.name = `penerima${penerimaInputs}`
    newInputField.setAttribute('list', 'list-penerima')
    newInputField.addEventListener('change', (e) => handlePenerima(e))
    newField.appendChild(newInputField)

    const newAddAbsenButton = document.createElement('button')
    newAddAbsenButton.id = `addAbsen${penerimaInputs}`
    newAddAbsenButton.type = 'button'
    newAddAbsenButton.innerText = 'A'
    newAddAbsenButton.classList.add(...['btn', 'btn-primary'])
    newAddAbsenButton.addEventListener('click', (e) => addAbsen(e))
    newField.appendChild(newAddAbsenButton)

    const newNotulButton = document.createElement('button')
    newNotulButton.type = 'button'
    newNotulButton.innerText = 'N'
    newNotulButton.classList.add(...['btn', 'btn-warning', 'notul-button'])
    newNotulButton.addEventListener('click', (e) => addNotul(e))
    newField.appendChild(newNotulButton)

    const newRemoveButton = document.createElement('button')
    newRemoveButton.type = 'button'
    newRemoveButton.innerText = '-'
    newRemoveButton.classList.add(...['btn', 'btn-danger'])
    newRemoveButton.addEventListener('click', (e) => removePenerima(e))
    newField.appendChild(newRemoveButton)

    penerimaField.appendChild(newField)

    totalPenerima.value = penerimaInputs
  }

  petugasAbsens = document.getElementsByClassName('absen')

  const removePenerima = (e) => {
    const calonPetugas = document.querySelector(`#${e.target.parentNode.id}>input`).value
    const notulen = document.getElementById('notulen')
    if (notulen.value == calonPetugas) notulen.value = null

    for (let p of petugasAbsens) {
      if (p.value == calonPetugas && calonPetugas != '') {
        p.remove()
      }
    }

    e.target.parentNode.remove()
  }

  let absenId = 1
  const absens = []
  const totalAbsen = document.getElementById('total-absen')

  const addAbsen = (e) => {
    const calonAbsen = document.querySelector(`#${e.target.parentNode.id}>input`).value
    if (!calonAbsen) return

    let found = false
    for (let item of petugasAbsens) {
      if (item.value == calonAbsen) {
        found = true
      }
    }

    if (!found) {
      absens[absens.length] = calonAbsen

      const targetAbsenInput = document.getElementById(`absen${absenId}`)
      targetAbsenInput.value = calonAbsen

      const targetAbsenField = document.getElementById(`absen-field`)
      const newAbsenInput = document.createElement('input')
      newAbsenInput.type = 'text'
      newAbsenInput.classList.add(...['form-control', 'mb-2', 'absen'])
      newAbsenInput.id = `absen${++absenId}`
      newAbsenInput.name = `absen${absenId}`
      newAbsenInput.addEventListener('keydown', (e) => {
        e.preventDefault()
        return false
      })
      targetAbsenField.appendChild(newAbsenInput)
      e.target.classList.toggle('btn-primary')
      e.target.classList.toggle('btn-outline-primary')
    } else {
      for (let item of petugasAbsens) {
        if (item.value == calonAbsen) {
          item.remove()
          e.target.classList.toggle('btn-primary')
          e.target.classList.toggle('btn-outline-primary')
        }
      }
    }

    totalAbsen.value = absenId
  }

  const addNotul = (e) => {
    const calonNotul = document.querySelector(`#${e.target.parentNode.id}>input`).value
    const notulButtons = document.getElementsByClassName('notul-button')

    if (!calonNotul) return

    const notulen = document.getElementById('notulen')
    if (calonNotul == notulen.value) notulen.value = null
    else notulen.value = calonNotul

    if (notulen.value) {
      for (let n = 0; n < penerimaInputs; n++) {
        if (notulButtons[n]) {
          notulButtons[n].classList.add('btn-warning')
          notulButtons[n].classList.remove('btn-outline-warning')
        }
      }
      e.target.classList.toggle('btn-warning')
      e.target.classList.toggle('btn-outline-warning')
    } else {
      e.target.classList.toggle('btn-warning')
      e.target.classList.toggle('btn-outline-warning')
    }
  }

  // AUTOCOMPLETE

  //   let names = [
  //   "Ayla",
  //   "Jake",
  //   "Sean",
  //   "Henry",
  //   "Brad",
  //   "Stephen",
  //   "Taylor",
  //   "Timmy",
  //   "Cathy",
  //   "John",
  //   "Amanda",
  //   "Amara",
  //   "Sam",
  //   "Sandy",
  //   "Danny",
  //   "Ellen",
  //   "Camille",
  //   "Chloe",
  //   "Emily",
  //   "Nadia",
  //   "Mitchell",
  //   "Harvey",
  //   "Lucy",
  //   "Amy",
  //   "Glen",
  //   "Peter",
  // ];

  // let sortedNames = names.sort();
  // let inputPenerima = document.getElementById('daftarPenerima')
  // inputPenerima.addEventListener('keyup', (e) => {
  //   removeElements()

  //   for(let name of sortedNames) {
  //     if(name.toLowerCase().startsWith(inputPenerima.value.toLowerCase()) && inputPenerima.value != '') {
  //       let item = document.createElement('h6')
  //       item.classList.add(...['my-2', 'mx-4', 'nameItem'])
  //       item.style.cursor = 'pointer'
  //       item.setAttribute('onclick',"displayNames('" + name + "')")
  //       let word = '<span>' + name.substr(0, inputPenerima.value.length) + '</span>'
  //       word += name.substr(inputPenerima.value.length)
  //       item.innerHTML = word
  //       document.querySelector('.nameList').appendChild(item)
  //     }
  //   }
  // })

  // const displayNames = (name) => {
  //   inputPenerima.value = name
  //   removeElements()
  // }

  // function removeElements() {
  //   let items = document.querySelectorAll(".nameItem");
  //   items.forEach((item) => {
  //     item.remove();
  //   });
  // }
</script>

<?= $this->endSection(); ?>
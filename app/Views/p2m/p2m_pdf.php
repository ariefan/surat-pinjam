<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .cf::before,
    .cf::after {
        display: table;
        content: '';
    }

    .cf::after {
        clear: both;
    }

    .hiddenContent {
        display: none;
    }

    .margin-bottom {
        margin-bottom: 5px;
    }

    .margin-left {
        margin-left: 5px;
    }

    #drop-zone {
        width: 300px;
        height: 200px;
        border: 2px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
    }

    #drop-zone.dragover {
        background-color: #f2f2f2;
    }

    #file-input {
        display: none;
    }

    form button[type="submit"] {
        margin-top: 20px;
    }

    .card-body {
        display: flex;
        justify-content: center;
    }

    i {
        font-size: 10px;

    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileList = document.getElementById('file-list');
    const uploadButton = document.getElementById('upload-button');
    const uploadedFiles = [];

    // Prevent default behavior when files are dragged over the drop zone
    dropZone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropZone.classList.add('dragover');
    });

    // Handle file drop event
    dropZone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropZone.classList.remove('dragover');

        // Get the dropped files
        const newFiles = event.dataTransfer.files;

        // Append the new files to the existing file list
        for (let i = 0; i < newFiles.length; i++) {
            const fileName = newFiles[i].name;
            if (!uploadedFiles.includes(fileName)) {
                const listItem = document.createElement('li');
                listItem.textContent = fileName;
                fileList.appendChild(listItem);
                uploadedFiles.push(fileName);
            }
        }

        // Merge the new files with the existing file input files
        const mergedFiles = mergeFileLists(fileInput.files, newFiles);
        fileInput.files = mergedFiles;
    });

    // Handle file input change event
    fileInput.addEventListener('change', (event) => {
        const newFiles = event.target.files;

        // Append the new files to the existing file list
        for (let i = 0; i < newFiles.length; i++) {
            const fileName = newFiles[i].name;
            if (!uploadedFiles.includes(fileName)) {
                const listItem = document.createElement('li');
                listItem.textContent = fileName;
                fileList.appendChild(listItem);
                uploadedFiles.push(fileName);
            }
        }
    });

    // Handle upload button click event
    uploadButton.addEventListener('click', () => {
        // Access the uploaded files from fileInput.files
        const files = fileInput.files;
        const formData = new FormData();

        const keyPenelitian = document.getElementById('research_keyword').value;
        const keyPengabdian = document.getElementById('cservice_keyword').value;
        formData.append('key_penelitian', keyPenelitian);
        formData.append('key_pengabdian', keyPengabdian);
        // Append each file to the FormData object
        for (let i = 0; i < files.length; i++) {
            formData.append('folder[]', files[i]);
        }

        // Make an AJAX request to the server
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= site_url('p2m/pdf_scraping') ?>');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error('Error:', xhr.status);
            }
        };
        xhr.send(formData);

        // Clear the file list and reset the file input
        fileList.innerHTML = '';
        fileInput.value = '';
    });

    function mergeFileLists(list1, list2) {
        const mergedList = new DataTransfer();

        // Add files from list1
        for (let i = 0; i < list1.length; i++) {
            mergedList.items.add(list1[i]);
        }

        // Add files from list2
        for (let i = 0; i < list2.length; i++) {
            mergedList.items.add(list2[i]);
        }

        return mergedList.files;
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Scraping PDF</h3>
        <a href="<?= site_url('p2m/p2m_view_surat') ?>" style="float: right;" class="btn btn-success">Daftar Surat</a>
        <div class="card-body mt-5">
            <form method="post" action="<?= site_url('p2m/pdf_scraping') ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Keyword Penelitian</label>
                    <button type="button" style="float:right" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#research_keyword"><i class="fa-solid fa-plus"></i></button>
                    <input type="text" id="research_keyword" name="key_penelitian" class="form-control collapse">
                </div>
                <div class="form-group">
                    <label>Keyword Pengabdian</label>
                    <button type="button" style="float:right" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#cservice_keyword"><i class="fa-solid fa-plus"></i></button>
                    <input type="text" id="cservice_keyword" name="key_pengabdian" class="form-control collapse">
                </div>
                <div id="drop-zone">Drop files here</div>
                <input type="file" id="file-input" name="folder" accept="application/pdf" multiple style="display: none;" />
                <button type="submit" id="upload-button" class="btn btn-success" style="float: right;">Mulai Scrape</button>
            </form>
            <ul id="file-list"></ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
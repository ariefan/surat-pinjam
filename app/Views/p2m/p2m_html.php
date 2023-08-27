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

    textarea {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        width: 100%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    function addTextarea() {
        var html = document.createElement('textarea');
        html.placeholder = "Masukkan HTML";
        html.name = "html[]";

        document.getElementById('form-html').appendChild(html);

        var selectElement = document.querySelector("#id");
        selectElement.setCustomValidity("");

        $(selectElement).removeClass("is-invalid");
    }

    $(document).ready(function() {
        $('#scrape-form').on('submit', function(e) {
            // e.preventDefault();
            var textarea_values = [];
            $('#form-html textarea').each(function() {
                textarea_values.push($(this).val());
            });

            var dosen = $('[name=dosen]').val();
            var formData = new FormData(this);
            console.log(textarea_values);
            console.log(dosen);
            // formData.append('html', textarea_values);
            formData.append('dosen', dosen);
            for (var i = 0; i < textarea_values.length; i++) {
                formData.append('html[]', textarea_values[i]);
            }
            return true;
        })
    })

    $(document).ready(function() {
        // Capture change event of the select element
        $('#dosen').change(function() {
            var selectedId = $(this).val(); // Get the selected ID

            // Update the hidden input field value
            $('[name="selectedId"]').val(selectedId);

            // console.log(selectedId);
            var formData = new FormData();

            formData.append('selectedId', selectedId);
            $.ajax({
                url: '<?= site_url('p2m/p2m_html_get_json') ?>',
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(data) {
                    let result = '';
                    data['link_scholar'].forEach(element => {
                        if (element['link_scholar'] != null) {
                            result += `<h5>${element['judul_publikasi']}</h5><a href=${element['link_scholar']}>${element['link_scholar']}</a>` + '<br>';
                        }
                        // console.log(element);
                    });
                    $('#link').html(result);
                    // console.log('test');
                },
                error: function(e) {
                    console.log(e);
                }
            })
        });

    });
</script>
<?= $this->endSection() ?>;

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Form Scraping</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('p2m/p2m_scraping') ?>" method="POST" id="scrape-form" enctype="multipart/form-data">
            <div class="form-group">
                <label>Dosen</label>
                <select id="dosen" class="form-control" name="dosen" required>
                    <option value="" disabled selected>Pilih nama dosen</option>
                    <?php foreach ($row as $row) : ?>
                        <option value="<?= $row->dosenID ?>"><?= $row->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="selectedId" value="">
            <div class="container" id="link">
            </div>
            <div class="form-group" id="form-html">
                <label style="padding-right: 10px;">HTML (Note: SETIAP PAGE DIMASUKKAN KE FORM BARU)</label><button type="button" onclick="addTextarea()" class="btn btn-sm btn-success">&nbsp;+&nbsp;</button>
                <textarea placeholder="Masukkan HTML" name="html[]"></textarea>
            </div>
            <button type="submit" style="float: right" class="btn btn-success">Mulai Scraping</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
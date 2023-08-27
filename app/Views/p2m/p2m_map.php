<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Sebaran Lokasi</h4>
        </div>
        <div class="card-body">
            <h6>Sebaran Lokasi Pengabdian</h6>
            <div id="pengabdian_map" style="width: 100%; height: 400px;"></div>
        </div>
        <div class="card-body">
            <h6>Sebaran Lokasi Author Publikasi</h6>
            <div id="publikasi_map" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Map persebaran pengabdian
    var pengabdian_map = L.map('pengabdian_map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(pengabdian_map);

    function geocodeAddress(address) {
        var geocodeUrl = 'https://www.mapquestapi.com/geocoding/v1/address';
        var apiKey = 'Ad6mC6FakHjz9iunHIkPbrROJcthhzTO';
        var url = `${geocodeUrl}?key=${apiKey}&location=${encodeURIComponent(address)}`;

        var pengabdianIcon = L.icon({
            iconUrl: '<?= base_url('img/placeholder_main.png'); ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        $.ajax({
            url: url,
            dataType: 'json',
            success: function (data) {
                var location = data.results[0].locations[0];

                if (location && location.latLng) {
                    var lat = location.latLng.lat;
                    var lon = location.latLng.lng;
                    var marker = L.marker([lat, lon]).addTo(pengabdian_map);

                    marker.bindPopup('<h5>' + address + '</h5>');

                    // Menyimpan informasi lokasi dalam properti options marker
                    marker.options.location = address;

                    marker.on('click', function () {
                        var alamat = marker.options.location;

                        // Retrieve data from the server based on the clicked location (alamat)
                        var url = "<?= site_url('P2M/mapData'); ?>?alamat=" + encodeURIComponent(alamat);

                        $.ajax({
                            url: url,
                            dataType: 'json',
                            success: function (data) {
                                var dataInfo = data.dataInfo;
                                var totalPengabdian = data.totalPengabdian;
                                
                                console.log(dataInfo);

                                // Update konten pop-up dengan informasi pengabdian
                                var popupContent = '<h3>' + alamat + '</h3><p>Data information: ' + dataInfo + '</p>';
                                popupContent += '<p>Total Pengabdian: ' + totalPengabdian + '</p>';
                                marker.setPopupContent(popupContent);
                            },
                            error: function (error) {
                                console.error('Error:', error);
                            }
                        });
                    });

                    pengabdian_map.setView([lat, lon], 4);
                } else {
                    console.error('Location or latLng is undefined:', location);
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    var uniqueAddresses = new Set();

    <?php foreach ($pengabdian as $row) : ?>
        <?php if ($row !== null && $row->kota !== null && $row->provinsi !== null && $row->kota !== '' && $row->provinsi !== '') : ?>
            var alamat = "<?= $row->kota . ', ' . $row->provinsi; ?>";
            uniqueAddresses.add(alamat);
        <?php else : ?>
            // console.error('Invalid row:', <?= json_encode($row); ?>);
        <?php endif; ?>
    <?php endforeach; ?>

    uniqueAddresses.forEach(function (alamat) {
        geocodeAddress(alamat);
    });

</script>

<script>
    // Map publikasi
    var publikasi_map = L.map('publikasi_map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(publikasi_map);

    function geocodeAddress(address, type) {
        var geocodeUrl = 'https://www.mapquestapi.com/geocoding/v1/address';
        var apiKey = 'Ad6mC6FakHjz9iunHIkPbrROJcthhzTO'; 
        var url = `${geocodeUrl}?key=${apiKey}&location=${encodeURIComponent(address)}`;

        var coAuthorsIcon = L.icon({
            iconUrl: '<?= base_url('img/placeholder_co.png'); ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        fetch(url)
            .then(response => response.json())
            .then(data => {
                var location = data.results[0].locations[0];
                var lat = location.latLng.lat;
                var lon = location.latLng.lng;
                var marker = L.marker([lat, lon]).addTo(publikasi_map);
                
                marker.setIcon(coAuthorsIcon);
               
                publikasi_map.setView([lat, lon], 2);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    var uniqueAddresses = new Set();

    <?php foreach ($publikasi as $row) : ?>
        <?php if ($row !== null && $row->external_country !== null) : ?>
            var alamat = "<?= $row->external_country; ?>";
            uniqueAddresses.add(alamat);
        <?php else : ?>
            console.error('Invalid row:', <?= json_encode($row); ?>);
        <?php endif; ?>
    <?php endforeach; ?>

    uniqueAddresses.forEach(function (alamat) {
        geocodeAddress(alamat, 'main_author');
    });
</script>

<?= $this->endSection() ?>

<?php
// Lakukan koneksi ke database
$host = '10.17.101.46';
$username = 'app_mipapersuratan_read';
$password = '#peRsuR4T4n_re4d_';
$database = 'mipa_persuratan';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Query untuk mengambil data PDF dari tabel
$id = $_GET['id']; // Ganti dengan ID data yang ingin Anda ambil
if (!empty($id) && preg_match('/^[a-zA-Z0-9_-]{1,16}$/', $id)) {
    // Pastikan variabel $connection berisi koneksi yang valid ke database sebelumnya

    // Gunakan prepared statement
    $query = "SELECT pdf FROM validasi WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $id); // Mengikat parameter ke statement

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ambil data PDF dari hasil query
        $row = mysqli_fetch_assoc($result);
        $pdf_data = $row['pdf'];

        // Tentukan tipe konten
        header("Content-type: application/pdf");

        // Tampilkan data PDF di browser
        echo $pdf_data;
    } else {
        echo "Kode Surat Yang Anda Masukkan Salah.";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    echo "ID tidak valid. Pastikan ID hanya terdiri dari huruf besar dan kecil, angka, '-' dan '_', dan tidak lebih dari 16 karakter.";
}

// if (!empty($id) && preg_match('/^[a-zA-Z0-9_-]{1,16}$/', $id)) {
//     $query = "SELECT pdf FROM validasi WHERE id = '$id'";
//     $result = mysqli_query($connection, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         // Ambil data PDF dari hasil query
//         $row = mysqli_fetch_assoc($result);
//         $pdf_data = $row['pdf'];

//         // Tentukan tipe konten
//         header("Content-type: application/pdf");

//         // Tampilkan data PDF di browser
//         echo $pdf_data;
//     } else {
//         echo "Kode Surat Yang Anda Masukkan Salah.";
//     }
// }

// // Tutup koneksi ke database
// mysqli_close($connection);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Nomor Surat</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-header">
            <div class="row">
                <div class="card">
                    <div class="card-body" id="kotakan" style="border:solid blue 0.1px;">
                        <form method="get">
                            <div class="form-group">
                                <label for="kodesurat">Masukkan kode surat</label>
                                <input type="" class="form-control" id="" value="" name="id">
                            </div>
                            <button type=" submit" class="btn btn-success btn-block">Verifikasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
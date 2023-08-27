<?php

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_status($code = '', $verifikasi_verifikator = '', $verifikasi_departemen = '')
{
    $status = '';
    switch ($code) {
        case 1:
            $status = 'Baru';
            break;
        case 2:
            $status = 'Terverifikasi';
            break;
        case 3:
            $status = 'Sudah Ditandatangani';
            break;
        default:
            $status = 'Masih Draft';
            break;
    }
    return $status;
}

function get_bulan($b)
{
    if (!empty($b)) {
        $label = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        return $label[$b];
    } else {
        return '';
    }
}

function get_status_surat($status)
{
    $label = [
        0 => 'Draft',
        1 => 'Baru',
        2 => 'Terverifikasi',
        3 => 'Sudah Ditandatangani',
    ];
    return $label[$status];
}

function dump($code)
{
    echo '<pre style="background-color:black;color:lime;padding:10px;margin:0;">' . "\n";
    var_dump($code);
    echo '</pre>' . "\n";
    exit;
}

function color_from_string($string)
{
    // The list of hex colors
    $colors = ['#34568B', '#FF6F61', '#6B5B95', '#88B04B', '#F7CAC9', '#92A8D1', '#955251', '#B565A7', '#009B77', '#DD4124', '#D65076', '#45B8AC', '#EFC050', '#5B5EA6', '#9B2335', '#DFCFBE', '#BC243C', '#C3447A', '#363945', '#939597', '#E0B589', '#926AA6', '#0072B5', '#E9897E', '#B55A30', '#4B5335', '#798EA4', '#00758F', '#FA7A35', '#6B5876', '#B89B72', '#282D3C', '#C48A69', '#A2242F', '#006B54', '#6A2E2A', '#6C244C', '#755139', '#615550', '#5A3E36', '#264E36', '#577284', '#6B5B95', '#944743', '#00A591', '#6C4F3D', '#BD3D3A', '#7F4145', '#485167', '#5A7247', '#D2691E', '#F7786B', '#91A8D0', '#4C6A92', '#838487', '#AD5D5D', '#006E51', '#9E4624'];
    // Find color based on the string
    $colorIndex = hexdec(substr(sha1($string), 0, 10)) % count($colors);
    // Return the hex color
    return $colors[$colorIndex];
}
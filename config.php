
<?php

// Host
$databaseHost = 'localhost';

// Nama database
$databaseName = 'simrs';

// Username MySQL
$databseUsername = 'root';

// Password MySQL
$databasePassword = '';

// Koneksi database
$mysqli = mysqli_connect(
    $databaseHost,
    $databseUsername,
    $databasePassword,
    $databaseName
);

// Cek koneksi
if (!$mysqli) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>

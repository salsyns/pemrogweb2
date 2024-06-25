<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'dodol';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal.']));
}
?>

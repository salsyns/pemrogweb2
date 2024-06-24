<?php
$host = getenv('localhost');
$username = getenv('root');
$password = getenv('');
$dbname = getenv('dodol');

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal.']));
}
?>

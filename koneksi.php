<?php
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo json_encode(['success' => true, 'message' => 'Koneksi database berhasil.']);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $e->getMessage()]));
}
?>

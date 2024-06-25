<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = json_decode(file_get_contents('php://input'), true);

    $namaPelanggan = $input['namaPelanggan'];
    $emailPelanggan = $input['emailPelanggan'];
    $nomorHpPelanggan = $input['nomorHpPelanggan'];
    $alamatPelanggan = $input['alamatPelanggan'];
    $pesanan = $input['pesanan'];

    // Log data yang diterima untuk debugging
    file_put_contents('log.txt', print_r($input, true), FILE_APPEND);

    // Simpan data pelanggan
    $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, email, nomor_hp, alamat) VALUES (:nama_pelanggan, :email, :nomor_hp, :alamat)");
    $stmt->bindParam(':nama_pelanggan', $namaPelanggan);
    $stmt->bindParam(':email', $emailPelanggan);
    $stmt->bindParam(':nomor_hp', $nomorHpPelanggan);
    $stmt->bindParam(':alamat', $alamatPelanggan);
    $stmt->execute();

    $idPelanggan = $pdo->lastInsertId();

    // Simpan data pesanan
    foreach ($pesanan as $item) {
        $stmt = $pdo->prepare("INSERT INTO pesanan (id_pelanggan, nama_produk, jumlah, harga_per_pcs, total) VALUES (:id_pelanggan, :nama_produk, :jumlah, :harga_per_pcs, :total)");
        $stmt->bindParam(':id_pelanggan', $idPelanggan);
        $stmt->bindParam(':nama_produk', $item['nama']);
        $stmt->bindParam(':jumlah', $item['jumlah']);
        $stmt->bindParam(':harga_per_pcs', $item['harga']);
        $stmt->bindParam(':total', $total = $item['harga'] * $item['jumlah']);
        $stmt->execute();
    }

    echo json_encode(['success' => true, 'message' => 'Pesanan berhasil disimpan.']);
} catch (PDOException $e) {
    file_put_contents('log.txt', $e->getMessage(), FILE_APPEND); // Log error message
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
}
?>

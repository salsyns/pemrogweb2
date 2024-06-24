<?php
include('koneksi.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['namaPelanggan']) || !isset($data['emailPelanggan']) || !isset($data['alamatPelanggan']) || !isset($data['nomorHpPelanggan']) || !isset($data['pesanan'])) {
    die(json_encode(['success' => false, 'message' => 'Data tidak lengkap.']));
}

$namaPelanggan = $data['namaPelanggan'];
$emailPelanggan = $data['emailPelanggan'];
$alamatPelanggan = $data['alamatPelanggan'];
$nomorHpPelanggan = $data['nomorHpPelanggan'];

$sqlPelanggan = "INSERT INTO pelanggan (nama_pelanggan, email, alamat, nomor_hp) VALUES ('$namaPelanggan', '$emailPelanggan', '$alamatPelanggan', '$nomorHpPelanggan')";
if (mysqli_query($conn, $sqlPelanggan)) {
    $idPelanggan = mysqli_insert_id($conn);

    $pesanan = $data['pesanan'];
    foreach ($pesanan as $item) {
        $namaProduk = $item['nama'];
        $jumlah = $item['jumlah'];
        $hargaPerPcs = $item['harga'];
        $total = $hargaPerPcs * $jumlah;

        $sqlPesanan = "INSERT INTO pesanan_produk (id_pelanggan, nama_produk, jumlah, harga_per_pcs, total) VALUES ($idPelanggan, '$namaProduk', $jumlah, $hargaPerPcs, $total)";
        mysqli_query($conn, $sqlPesanan);
    }

    echo json_encode(['success' => true, 'message' => 'Pesanan berhasil disimpan.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data pelanggan.']);
}

mysqli_close($conn);
?>

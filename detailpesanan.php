<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Detail Pesanan</h1>

    <?php
    $host = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $dbname = getenv('DB_NAME');

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Mendapatkan pesanan terakhir
    $sql_last_order = "SELECT id_pelanggan FROM pesanan ORDER BY id DESC LIMIT 1";
    $result_last_order = $conn->query($sql_last_order);
    $last_pelanggan = $result_last_order->fetch_assoc()['id_pelanggan'];

    // Mendapatkan detail pesanan pelanggan terakhir
    $sql = "SELECT p.nama AS nama_pelanggan, p.email, p.alamat, p.nomor_hp, 
                   ps.nama_produk, ps.jumlah, ps.harga_per_pcs, (ps.jumlah * ps.harga_per_pcs) AS total
            FROM pelanggan p
            JOIN pesanan ps ON p.id = ps.id_pelanggan
            WHERE p.id = $last_pelanggan
            ORDER BY p.nama ASC";

    $result = $conn->query($sql);

    $totalHargaPelanggan = 0;

    if ($result->num_rows === 0) {
        echo '<p>Belum ada pesanan yang tersedia.</p>';
    } else {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Nama Pelanggan</th>';
        echo '<th>Email</th>';
        echo '<th>Alamat</th>';
        echo '<th>Nomor HP</th>';
        echo '<th>Nama Produk</th>';
        echo '<th>Jumlah</th>';
        echo '<th>Harga per pcs</th>';
        echo '<th>Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['nama_pelanggan']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['alamat']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nomor_hp']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nama_produk']) . '</td>';
            echo '<td>' . $row['jumlah'] . '</td>';
            echo '<td>Rp. ' . number_format($row['harga_per_pcs'], 0, ',', '.') . '</td>';
            echo '<td>Rp. ' . number_format($row['total'], 0, ',', '.') . '</td>';
            echo '</tr>';

            $totalHargaPelanggan += $row['total'];
        }

        echo '<tr>';
        echo '<td colspan="7">Total Harga Keseluruhan:</td>';
        echo '<td>Rp. ' . number_format($totalHargaPelanggan, 0, ',', '.') . '</td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
    }

    $conn->close();
    ?>

    <a href="index.html">Kembali ke Beranda</a>
</body>
</html>

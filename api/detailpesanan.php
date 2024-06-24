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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dodol";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $sql = "SELECT pp.nama_produk, pp.jumlah, pp.harga_per_pcs, (pp.jumlah * pp.harga_per_pcs) AS total, 
                    p.nama_pelanggan, p.email, p.alamat, p.nomor_hp
            FROM pesanan_produk pp
            LEFT JOIN pelanggan p ON pp.id_pelanggan = p.id_pelanggan
            WHERE pp.nama_produk IS NOT NULL
            ORDER BY p.nama_pelanggan ASC";

    $result = $conn->query($sql);

    $currentPelanggan = null;
    $totalHargaPelanggan = 0;
    $totalHargaKeseluruhan = 0;

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
            if ($row['nama_pelanggan'] !== $currentPelanggan) {
                if ($currentPelanggan !== null) {
                    echo '<tr>';
                    echo '<td colspan="7">Total Harga untuk ' . htmlspecialchars($currentPelanggan) . ':</td>';
                    echo '<td>Rp. ' . number_format($totalHargaPelanggan, 0, ',', '.') . '</td>';
                    echo '</tr>';
                }

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

                $totalHargaPelanggan = 0;
                $currentPelanggan = $row['nama_pelanggan'];
            } else {
                echo '<tr>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td>' . htmlspecialchars($row['nama_produk']) . '</td>';
                echo '<td>' . $row['jumlah'] . '</td>';
                echo '<td>Rp. ' . number_format($row['harga_per_pcs'], 0, ',', '.') . '</td>';
                echo '<td>Rp. ' . number_format($row['total'], 0, ',', '.') . '</td>';
                echo '</tr>';
            }

            $totalHargaKeseluruhan += $row['total'];
            $totalHargaPelanggan += $row['total'];
        }

        echo '<tr>';
        echo '<td colspan="7">Total Harga untuk ' . htmlspecialchars($currentPelanggan) . ':</td>';
        echo '<td>Rp. ' . number_format($totalHargaPelanggan, 0, ',', '.') . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td colspan="7">Total Harga Keseluruhan:</td>';
        echo '<td>Rp. ' . number_format($totalHargaKeseluruhan, 0, ',', '.') . '</td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
    }

    $conn->close();
    ?>

    <a href="index.php">Kembali ke Beranda</a>
</body>
</html>

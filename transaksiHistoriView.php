<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Transaksi Histori</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1><center>Transaksi Histori</center></h1>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="search">Search: </label>
        <input type="text" id="search" name="q" placeholder="Enter keywords...">
        <button type="submit">Search</button>
    </form><br><br>
    <button class="btn btn-danger" style="margin-bottom:10px; margin-left:10px;">
                <a href="stokBarangView.php" onclick="return confirm('Are you sure want to back stuff stock?')" style="color:white;" >    
                    Back
                </a>
            </button>
    <table>
        <tr>
            <th>No</th>
            <th>Bukti</th>
            <th>Tgl</th>
            <th>Jam</th>
            <th>Lokasi</th>
            <th>Kode Barang</th>
            <th>Tgl Masuk</th>
            <th>Qty Trn</th>
            <th>Prog</th>
        </tr>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "php_akhir";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $search = isset($_GET['q']) ? $_GET['q'] : '';

            // Konversi format tanggal pencarian
            $searchDate = '';
            if (!empty($search)) {
                $searchDate = date("Y-m-d", strtotime(str_replace('/', '-', $search)));
            }


            $query = "SELECT
                        th.tgl_transaksi_histori,
                        th.jam_masuk,
                        th.bukti_transaksi,
                        th.jenis_transaksi,
                        sb.lokasi,
                        sb.kode_barang,
                        sb.nama_barang,
                        sb.tgl_masuk,
                        sb.saldo_barang
                    FROM
                        stok_barang AS sb
                    LEFT JOIN
                        transaksi_histori AS th ON th.id_stok = sb.id_stok";
            
            if (!empty($search)) {
                $query .= " WHERE 
                            bukti_transaksi LIKE '%$search%' OR
                            DATE_FORMAT(tgl_transaksi_histori, '%d/%m/%Y') LIKE '%$search%' OR
                            lokasi LIKE '%$search%' OR
                            kode_barang LIKE '%$search%'";
            }
            $result = mysqli_query($conn, $query);
           
                // Display data in HTML table
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo '<td style="text-align: right;">' . $no . '</td>';
                    echo "<td>" . $row['bukti_transaksi'] . "</td>";
                    //CONVERT DD-MM-YYYY 
                    $originalDate = $row['tgl_transaksi_histori'];
                    $newDate = date("d/m/Y", strtotime($originalDate));
                    echo "<td>" . $newDate . "</td>"; 
                    echo "<td>" . $row['jam_masuk'] . "</td>";
                    echo "<td>" . $row['lokasi'] . "</td>";
                    echo "<td>" . $row['kode_barang'] . "</td>";
                    //CONVERT DD-MM-YYYY 
                    $originalDate = $row['tgl_masuk'];
                    $newDateS = date("d/m/Y", strtotime($originalDate));
                    echo "<td>" . $newDateS . "</td>";
                    echo '<td style="text-align: right;">' . $row['saldo_barang']. '</td>';
                    echo "<td>" . strtoupper($row['jenis_transaksi']) . "</td>";
                    echo "</tr>";
                    $no++;
            }

            mysqli_close($conn);
         ?>
    </table>
</body>
</html>

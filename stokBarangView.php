<!DOCTYPE html>
<html>
<head>
	<title>TEST AKHIR</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
	<center><h1>DATA STOK BARANG</h1></center>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="search">Search: </label>
        <input type="text" id="search" name="q" placeholder="Enter keywords...">
        <button type="submit">Search</button>
    </form><br><br>
</div>
    <a href="addStokBarangPage.php" class="button-secondary" onclick="return confirm('Are you sure you want to add stock?')">Tambah Stok</a>
    <a href="transaksiHistoriView.php" class="button-secondary" onclick="return confirm('Are you sure you want to go transaction history?')">Histori Stok</a>
    <a href="masterBarangView.php" class="button-secondary" onclick="return confirm('Are you sure want to add master stuff?')">Master Barang</a>
    <a href="masterLokasiView.php" class="button-secondary" onclick="return confirm('Are you sure want to add master location?')">Master Lokasi</a>
		
	<table cellspacing='0'>
		<thead>
			<tr>
				<th>No</th>
				<th>Lokasi</th>
                <th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Saldo</th>
                <th>Tgl Masuk</th>
			</tr>
		</thead>
		<tbody>
		<?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "php_akhir";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // $query = "SELECT lokasi, kode_barang, nama_barang, saldo_barang, tgl_masuk FROM stok_barang";
            // $result = mysqli_query($conn, $query);
            $search = isset($_GET['q']) ? $_GET['q'] : '';

            $query = "SELECT lokasi, kode_barang, nama_barang, saldo_barang, tgl_masuk FROM stok_barang";
            if (!empty($search)) {
                $query .= " WHERE kode_barang LIKE '%$search%' OR lokasi LIKE '%$search%'";
            }
            $result = mysqli_query($conn, $query);
           
                // Display data in HTML table
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo '<td style="text-align: right;">' . $no . '</td>';
                echo "<td>" . strtoupper(trim($row['lokasi'])) . "</td>";
                echo "<td>" . strtoupper(trim($row['kode_barang'])) . "</td>";
                echo "<td>" . strtoupper(trim($row['nama_barang'])) . "</td>";
                echo '<td style="text-align: right;">' . trim($row['saldo_barang']) . '</td>';
                //CONVERT DD-MM-YYYY 
                $originalDate = $row['tgl_masuk'];
                $newDate = date("d/m/Y", strtotime($originalDate));
                echo "<td>" . trim($newDate) . "</td>"; 
                echo "</tr>";
                $no++;
            }

            mysqli_close($conn);
         ?>
		</tbody>
	</table>
        </body>
        </html>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Master-Barang</title>
    <style>
        table {
            border-collapse: collapse;
            width: 40%;
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
    <h1><center>Master Barang</center></h1>
    <button class="btn btn-primary" style="margin-bottom:10px; margin-left:10px;">
        <a href="addMasterBarangPage.php" style="color:white;" onclick="return confirm('Are you sure you want to add master Stuff?')">Tambah Master</a>
    </button>
    
    <button class="btn btn-danger" style="margin-bottom:10px; margin-left:10px;">
        <a href="stokBarangView.php" style="color:white;" onclick="return confirm('Are you sure want to back stuff stock?')">Back</a>
    </button>

    <table>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
        </tr>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test_akhir";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = "SELECT kode_barang, nama_barang FROM master_barang";
            $result = mysqli_query($conn, $query);
           
                // Display data in HTML table
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo '<td style="text-align: right;">' . $no . '</td>';
                    echo "<td>" . strtoupper(trim($row['kode_barang'])) . "</td>";
                    echo "<td>" . strtoupper(trim($row['nama_barang'])) . "</td>";
                    echo "</tr>";
                    $no++;
            }

            mysqli_close($conn);
         ?>
    </table>
</body>
</html>

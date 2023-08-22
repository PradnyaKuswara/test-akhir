<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "php_akhir";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    $kode_barang =  $nama_barang ="";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET['id_barang'])) {
            $id_barang = $_GET['id_barang'];
    
            $sql = "SELECT * FROM master_barang WHERE id_barang = $id_barang";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $kode_barang = $row['kode_barang'];
                $nama_barang = $row['nama_barang'];
            } else {
                echo "Data tidak ada";
            }
        } else {
            echo "Parameter id_barang tidak ada";
        }
    
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <title>Form Stok - Edit Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            border: 1px solid #ccc;
            padding: 20px;
            width: 400px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        input[type="submit"],
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>
<h1>Update Master Barang</h1>
    <div class="form-container">
    <form action="updateMasterBarangProcess.php" method="POST">   
    <input type="hidden" name="id_barang" value="<?php echo $id_barang; ?>">  
        <label for="kode_barang">Kode Barang:</label><br>
        <input type="text" name="kode_barang" id="kode_barang" value="<?php echo $kode_barang; ?>"><br>
 
        
        <label for="nama_barang">Nama Barang:</label><br>
        <input type="text" name="nama_barang" value="<?php echo $nama_barang; ?>"><br>

        
        <input type="submit" name="submit" class="btn btn-primary" value="Edit Transaksi" onclick="return confirm('Are you sure want to edit master stuff?')>
        
        <button class="btn btn-danger" >
            <a href="stokBarangView.php" class="btn btn-danger" onclick="return confirm('Are you sure want to back stuff stock?')"> Back</a>        
        </button>   
    </form>
    </div>
</body>
</html>
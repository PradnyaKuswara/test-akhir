<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "php_akhir";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    $lokasi = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET['id_lokasi'])) {
            $id_lokasi = $_GET['id_lokasi'];
    
            $sql = "SELECT * FROM master_lokasi WHERE id_lokasi = $id_lokasi";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $lokasi = $row['lokasi'];
            } else {
                echo "Lokasi tidak ada";
            }
        } else {
            echo "Parameter id_lokasi tidak ada";
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
    <title>Form Stok - Tambah Transaksi</title>

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
            
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-danger {
            background-color: #dc3545;
        }
    </style>


</head>
<body>
<h1>Update Master Lokasi</h1>
    <div class="form-container">
    <form action="updateMasterLokasiProcess.php" method="POST">
        <input type="hidden" name="id_lokasi" value="<?php echo $id_lokasi; ?>">    
        <label for="lokasi">Lokasi :</label><br>
        <input type="text" name="lokasi" id="lokasi" value= "<?php echo $lokasi ?>"><br><br>
        <input type="submit" name="submit" class="btn btn-primary" value="Edit Transaksi" onclick="return confirm('Are you sure want to edit master location?')">
        <button class="btn btn-danger">
            <a href="stokBarangView.php" class="btn btn-danger" onclick="return confirm('Are you sure want to back stuff stock?')"> Back</a>        
        </button>

    </form>
    </div>
</body>
</html>
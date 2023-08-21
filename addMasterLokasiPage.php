<!DOCTYPE html>
<html>
<head>
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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lokasi = trim($_POST["lokasi"]);

    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "test_akhir";

    // Create connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to insert data into database
    $sql = "INSERT INTO master_lokasi (lokasi)
            VALUES ( '$lokasi')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                    alert("Tambah Master Lokasi Sukses");
                    window.location = "masterLokasiPage.php";
                    </script>';
    } else {
        echo '<script>
        alert("Tambah Master Lokasi Gagal");
        </script>';
    }

    $conn->close();
}
?>

    <h1>Tambah Master Lokasi</h1>
    <div class="form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">    
        <label for="lokasi">Lokasi :</label><br>
        <input type="text" name="lokasi" id="lokasi"><br><br>
        <input type="submit" name="submit" class="btn btn-primary" value="Tambah Transaksi" onclick="return confirm('Are you sure want to add master location?')>
        <button class="btn btn-danger" >
            <a href="stokBarangView.php" class="btn btn-danger" onclick="return confirm('Are you sure want to back stuff stock?')"> Back</a>        
        </button>
    </form>
    </div>

</body>
</html>

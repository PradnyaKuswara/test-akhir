<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="path/to/bootstrap.min.css">
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

<?php
$host = "localhost";
$user = "root";
$pass = "";
$name = "php_akhir";

$con = mysqli_connect($host,$user,$pass,$name);

//error
$kodeErr = array(null);
$namaErr = array(null);
$status = true;


    if(isset($_POST['submit'])){
        $kode_barang = trim($_POST["kode_barang"]);
        $nama_barang = trim($_POST["nama_barang"]);

    //validasi kode_barang unik
    $query = "SELECT * FROM master_barang WHERE kode_barang = '$kode_barang'";
    $result = $con->query($query);
    if(empty($_POST['kode_barang'])){
        array_push($kodeErr, 'Kode barang must fill');
        $status = false;
    }

    if ($result->num_rows > 0){
        array_push($kodeErr, 'Kode barang must be unique');
        $status = false;
    }

    //validasi nama_barang harus terisi
    if(empty($_POST['nama_barang'])){
        array_push($namaErr, 'Nama barang must fill');
        $status = false;
    }

    // Masuk ke Database
    if ($status === true) {
        ($query = mysqli_query(
            $con,
            "INSERT INTO master_barang (kode_barang, nama_barang)
            VALUES ( '$kode_barang', '$nama_barang')",
        )) or die(mysqli_error($con));

        if ($query) {
            echo '<script>
                    alert("Tambah Master Barang Sukses");
                    window.location = "masterBarangView.php";
                    </script>';
        } else {
            echo '<script>
                alert("Tambah Master Barang Gagal"");
                </script>';
        }
    }

    }
?>

    <h1>Tambah Master Barang</h1>
    <div class="form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">    
        <label for="kode_barang">Kode Barang:</label><br>
        <input type="text" name="kode_barang" id="kode_barang"><br>
        <?php 
            if($kodeErr){
                foreach($kodeErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>
        
        <label for="nama_barang">Nama Barang:</label><br>
        <input type="text" name="nama_barang"><br>
        <?php 
            if($namaErr){
                foreach($namaErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>
        
        <input type="submit" name="submit" class="btn btn-primary" value="Tambah Transaksi" onclick="return confirm('Are you sure want to add master stuff?')>
        
        <button class="btn btn-danger" >
            <a href="stokBarangView.php" class="btn btn-danger" onclick="return confirm('Are you sure want to back stuff stock?')"> Back</a>        
        </button>   
    </form>
    </div>

</body>
</html>

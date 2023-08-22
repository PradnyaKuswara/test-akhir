<?php
    include('db.php');


    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "php_akhir";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $id_barang =$_GET['id_barang'];

    $sql = "DELETE FROM master_barang WHERE id_barang = '$id_barang'";
    $result = $conn->query($sql);

    if ($result) {
        if ($conn->affected_rows > 0) {
            echo
            '<script>
            alert("Data Barang Sukses Dihapus");
            window.location = "masterBarangView.php";
            </script>';
        } else {
            echo
            '<script>
            alert("Gagal Menghapus Data Barang"); 
            window.location = "masterBarangView.php";
            </script>';
        }
    }
?>
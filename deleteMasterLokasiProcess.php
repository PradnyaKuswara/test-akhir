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

    $id_lokasi =$_GET['id_lokasi'];

    $sql = "DELETE FROM master_lokasi WHERE id_lokasi = '$id_lokasi'";
    $result = $conn->query($sql);

    if ($result) {
        if ($conn->affected_rows > 0) {
            echo
            '<script>
            alert("Data Lokasi Sukses Dihapus");
            window.location = "masterLokasiView.php";
            </script>';
        } else {
            echo
            '<script>
            alert("Gagal Menghapus Data Lokasi"); 
            window.location = "masterLokasiView.php";
            </script>';
        }
    }
?>
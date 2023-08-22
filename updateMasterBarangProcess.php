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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $kode_barang = $_POST['kode_barang'];
        $nama_barang = $_POST['nama_barang'];
        $id_barang = $_POST['id_barang'];
        
        $sql = "UPDATE master_barang SET nama_barang = '$nama_barang', kode_barang = '$kode_barang' WHERE id_barang = $id_barang";
        $result = $conn->query($sql);

        if($result){
            if($conn->affected_rows > 0){
                echo
            '<script>
            alert("Data Lokasi Sukses Diupdate");
            window.location = "masterBarangView.php";
            </script>';
        } else {
            echo
            '<script>
            alert("Gagal Mengupdate Data Lokasi"); 
            window.location = "updateMasterBarangPage.php?id_lokasi='.$id_barang.'";
            </script>';
        }
        }
    }
?>
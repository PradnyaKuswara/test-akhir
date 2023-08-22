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
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newLokasi = $_POST['lokasi'];
    $id_lokasi = $_POST['id_lokasi'];

    // Lakukan operasi update data di sini
    $sql = "UPDATE master_lokasi SET lokasi='$newLokasi' WHERE id_lokasi = $id_lokasi";  

    $result = $conn->query($sql);

    // Setelah melakukan update, bisa redirect ke halaman lain
    if ($result) {
        if ($conn->affected_rows > 0) {
            echo
            '<script>
            alert("Data Lokasi Sukses Diupdate");
            window.location = "masterLokasiView.php";
            </script>';
        } else {
            echo
            '<script>
            alert("Gagal Mengupdate Data Lokasi"); 
            window.location = "updateMasterLokasiPage.php?id_lokasi='.$id_lokasi.'";
            </script>';
        }
    }
}
?>
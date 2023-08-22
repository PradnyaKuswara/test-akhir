<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_akhir";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['query'])){
    $query = $_POST['query'];
    
    $sql = "SELECT kode_barang FROM master_barang WHERE kode_barang LIKE '%$query%'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo '<li>' . $row['kode_barang'] . '</li>';
        }
    }
}



$conn->close();
?>
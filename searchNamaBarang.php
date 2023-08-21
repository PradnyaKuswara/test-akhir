<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_akhir";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['query'])){
    $query = $conn->real_escape_string($_POST['query']);
    
    $sql = "SELECT nama_barang FROM master_barang WHERE kode_barang = '$query'";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Query error: " . $conn->error;
    }

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo $row['nama_barang'];
    } else {
        echo "No results found.";
    }
}

$conn->close();
?>

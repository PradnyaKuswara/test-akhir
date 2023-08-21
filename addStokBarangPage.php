<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    #kode-list {
        max-height: 150px;
        /* Atur ketinggian maksimum daftar saran */
        overflow-y: auto;
        /* Aktifkan scroll jika konten melebihi ketinggian maksimum */
        border: 1px solid #ccc;
        /* Tambahkan garis pinggir untuk estetika */
        list-style: none;
        /* Hilangkan poin dari daftar */
        padding: 0;
        /* Hapus padding default dari daftar */
    }

    #nama-list {
        max-height: 150px;
        /* Atur ketinggian maksimum daftar saran */
        overflow-y: auto;
        /* Aktifkan scroll jika konten melebihi ketinggian maksimum */
        border: 1px solid #ccc;
        /* Tambahkan garis pinggir untuk estetika */
        list-style: none;
        /* Hilangkan poin dari daftar */
        padding: 0;
        /* Hapus padding default dari daftar */
    }

    #lokasi-list {
        max-height: 150px;
        /* Atur ketinggian maksimum daftar saran */
        overflow-y: auto;
        /* Aktifkan scroll jika konten melebihi ketinggian maksimum */
        border: 1px solid #ccc;
        /* Tambahkan garis pinggir untuk estetika */
        list-style: none;
        /* Hilangkan poin dari daftar */
        padding: 0;
        /* Hapus padding default dari daftar */
    }
    </style>
</head>

<body>


    <?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $name = "test_akhir";

    $con = mysqli_connect($host,$user,$pass,$name);

    if(mysqli_connect_errno()){ 
        echo "Failed to connect : " . mysqli_connect_error(); 
    }
    //error
    $jenisErr = array(null);
    $lokasiErr = array(null);
    $kodeErr = array(null);
    $tglErr = array(null);
    $saldoErr = array(null);
    $jtErr = array(null);
    $status = true;

    // inisialisasi data
    $lokasi = $kode_barang = $tgl_masuk = $saldo_barang ='';

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(isset($_POST['submit'])){
        $jenisTransaksi = trim(isset($_POST["jenis_transaksi"])?$_POST['jenis_transaksi']:null);
        $buktiTransaksi = trim($_POST["bukti_transaksi"]);
        $lokasi = trim($_POST["lokasi"]);
        $kodeBarang = trim($_POST["kode_barang"]);
        $namaBarang = trim($_POST["nama_barang"]);
        $tglMasuk = trim($_POST["tgl_masuk"]);
        $saldoBarang = trim($_POST["saldo_barang"]);

        //validasi jens transaksi empty
        if(!isset($_POST['jenis_transaksi'])){
            array_push($jtErr, 'Jenis transaksi must fill');
            $status = false;
        }

        //validasi lokasi empty
        if(empty($_POST['lokasi'])){
            array_push($lokasiErr, 'Location must fill');
            $status = false;
        }
        $lokasi = test_input($_POST['lokasi']);
        
        //validasi kode barang empty
        if(empty($_POST['kode_barang'])){
            array_push($kodeErr, 'Kode barang must fill');
            $status = false;
        }
        $kode_barang = test_input($_POST['kode_barang']);

        //validasi tgl masuk empty
        if(empty($_POST['tgl_masuk'])){
            array_push($tglErr, 'Tgl masuk must fill');
            $status = false;
        }
        $tgl_masuk = test_input($_POST['tgl_masuk']);

        //validasi saldo barang
        if(empty($_POST['saldo_barang'])){
            array_push($saldoErr, 'Quantity must fill');
            $status = false;
        }
        $saldo_barang = test_input($_POST['saldo_barang']);

        //validasi transaksi masuk jika tglmasuk < tglmasukterakhir
        // Ambil data saldo dan tanggal masuk terakhir dari database
        $status = false;
        $kodeBarang = $_POST['kode_barang'];
        $lokasi = $_POST['lokasi'];

        $query = "SELECT saldo_barang, tgl_masuk FROM stok_barang WHERE kode_barang='$kodeBarang' AND lokasi='$lokasi' ORDER BY tgl_masuk DESC LIMIT 1";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);

        if(mysqli_num_rows($result)){
            $saldoTerakhir = $row['saldo_barang'];
            $tglMasukTerakhir = strtotime($row['tgl_masuk']);
    
            // Validasi tgl transaksi < dari tgl masuk terakhir pada saldo 
            $tglTransaksiBaru = strtotime($_POST['tgl_masuk']);
            if ($tglTransaksiBaru < $tglMasukTerakhir) {
                echo 
                '<script>
                    alert("Tambah Stok Barang Gagal: Tanggal transaksi lebih kecil dari tanggal masuk terakhir.");
                    window.location = "addStokBarangPage.php";
                </script>';
            } else {
                $status = true;
            }
        }else{
            $status = true;
        }

        // //validasi transaksi keluar 
        // $status = false;
        // $kodeBarang = $_POST['kode_barang'];
        // $lokasi = $_POST['lokasi'];

        // $query = "SELECT saldo_barang, tgl_masuk FROM stok_barang WHERE kode_barang='$kodeBarang' AND lokasi='$lokasi' ORDER BY tgl_masuk DESC LIMIT 1";
        // $result = mysqli_query($con, $query);
        // $row = mysqli_fetch_assoc($result);

        // $saldoTerakhir = $row['saldo_barang'];
        // $tglMasukTerakhir = strtotime($row['tgl_masuk']);

        // // Validasi tanggal transaksi baru
        // $tglTransaksiBaru = strtotime($_POST['tgl_masuk']);
        // if ($tglTransaksiBaru < $tglMasukTerakhir) {
        //     echo '<script>
        //             alert("Tambah/Kurang Stok Barang Gagal: Tanggal transaksi lebih kecil dari tanggal masuk terakhir.");
        //             window.location = "addStokBarangPage.php";
        //         </script>';
        // } else {
        //     $status = true;
        // }

        if ($status === true) {
            // Validasi saldo barang
            $jenisTransaksi = $_POST['jenis_transaksi'];
            // $jumlahTransaksi = $_POST['jumlah_transaksi'];

            if ($jenisTransaksi === 'MASUK') {
                $buktiTransaksi = "TAMBAH";
                // Jika jenis transaksi adalah tambah, lanjutkan dengan proses
                $sql = mysqli_query($con,"INSERT INTO stok_barang (jenis_transaksi, bukti_transaksi, lokasi, kode_barang, nama_barang, tgl_masuk, saldo_barang)
                        VALUES ('$jenisTransaksi', '$buktiTransaksi', '$lokasi', '$kodeBarang', '$namaBarang', '$tglMasuk', '$saldoBarang')") or die (mysqli_error($con));

                if ($sql) {
                    $query_select = mysqli_query($con,"SELECT * from stok_barang ORDER BY id_stok DESC LIMIT 1");
                    $data = mysqli_fetch_assoc($query_select);
                    $lastInsertedId = $data['id_stok'];

                    $number = str_pad($lastInsertedId, 2, '0', STR_PAD_LEFT);;
                    $bukti_code = $data['bukti_transaksi'] . $number;
                    $query_update = mysqli_query($con,"UPDATE stok_barang SET bukti_transaksi='$bukti_code'WHERE id_stok='$lastInsertedId'") or die(mysqli_error($con));

                    date_default_timezone_set('Asia/Jakarta');
                    $currentDate = date('Y-m-d');
                    $currentTime = date('H:i:s');
                
                    $currentDate = $con->real_escape_string($currentDate);
                    $currentTime = $con->real_escape_string($currentTime);
                
                    $sql2 = mysqli_query($con,"INSERT INTO transaksi_histori (id_stok, tgl_transaksi_histori, jam_masuk) 
                            VALUES ('$lastInsertedId', '$currentDate', '$currentTime')") or die(mysqli_error($con));

                    echo '<script>
                        alert("Transaksi Histori Sukses");
                        window.location = "stokBarangView.php";
                    </script>';
                } else {
                    echo '<script>
                            alert("Tambah Stok Barang Gagal");
                            window.location = "addStokBarangPage.php";
                        </script>';
                }
                $stmt->close();
            } elseif ($jenisTransaksi === 'KURANG') {
                // Jika jenis transaksi adalah kurang, validasi saldo
                if ($saldoTerakhir >= $jumlahTransaksi) {
                    // Lanjutkan dengan proses kurang stok
                    $saldoBarangBaru = $saldoTerakhir - $jumlahTransaksi;
                    $sql = "INSERT INTO stok_barang (jenis_transaksi, bukti_transaksi, lokasi, kode_barang, nama_barang, tgl_masuk, saldo_barang)
                            VALUES ($jenisTransaksi, $buktiTransaksi, $lokasi, $kodeBarang, $namaBarang, $tglMasuk, $saldoBarangBaru)";
                    $stmt2 = $con->prepare($sql);
                    $stmt2->bind_param("sssssss", $jenisTransaksi, $buktiTransaksi, $lokasi, $kodeBarang, $namaBarang, $tglMasuk, $saldoBarangBaru);

                    if ($stmt2->execute()) {
                        $lastInsertedId = $stmt2->insert_id;
                        echo '<script>
                        alert("Kurang Stok Barang dan Transaksi Histori Sukses");
                        window.location = "stokBarangView.php";
                        </script>';
                    }
                } else {
                    echo '<script>
                            alert("Kurang Stok Barang Gagal: Saldo barang tidak mencukupi.");
                            window.location = "addStokBarangPage.php";
                        </script>';
                }
                $stmt2->close();
            }
        }


        // Masuk ke Database
        // if ($status === true) {
        //     // SQL query to insert data into stok_barang table using prepared statement
        //     $sql = "INSERT INTO stok_barang (jenis_transaksi, bukti_transaksi, lokasi, kode_barang, nama_barang, tgl_masuk, saldo_barang)
        //             VALUES ('$jenisTransaksi', '$buktiTransaksi', '$lokasi', '$kodeBarang', '$namaBarang', '$tglMasuk', '$saldoBarang')";
            
        //     $stmt = $con->prepare($sql);
        // //    $stmt->bind_param("sssssss", $jenisTransaksi, $buktiTransaksi, $lokasi, $kodeBarang, $namaBarang, $tglMasuk, $saldoBarang);
    
        //     if ($stmt->execute()) {
        //         $lastInsertedId = $stmt->insert_id;
    
        //         // ... (lanjutkan dengan operasi untuk transaksi_histori)
        //         date_default_timezone_set('Asia/Jakarta');
        //         $currentDate = date('Y-m-d');
        //         $currentTime = date('H:i:s');
            
        //         $currentDate = $con->real_escape_string($currentDate);
        //         $currentTime = $con->real_escape_string($currentTime);
            
        //         $sql2 = "INSERT INTO transaksi_histori (id_stok, tgl_transaksi_histori, jam_masuk) 
        //                 VALUES ('$lastInsertedId', '$currentDate', '$currentTime')";
    
        // if ($con->query($sql2) === TRUE) {
        //         echo '<script>
        //                 alert("Tambah Stok Barang dan Transaksi Histori Sukses");
        //                 window.location = "stokBarangView.php";
        //             </script>';
        //     } else {
        //         echo '<script>
        //                 alert("Tambah Stok Barang Gagal");
        //             </script>';
        //     }
        // }
        //     $stmt->close();
        // }
    }
?>

    <h1 style="margin-right:20px;">Tambah Transaksi Stok</h1>
    <div class="form-container">
        <h1>
            <center><span id="clock"></span></center>
        </h1><br>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="jenis_transaksi">Jenis Transaksi:</label><br>
            <input type="radio" name="jenis_transaksi" value="MASUK" onclick="fillBukti()">Masuk
            <input type="radio" name="jenis_transaksi" value="KELUAR" onclick="fillBukti()">Keluar<br><br>
            <input type="hidden" name="bukti_transaksi" id="bukti_transaksi" value="">
            <input type="hidden" name="counter" id="counter" value="01">
            <input type="hidden" name="counter_keluar" id="counter_keluar" value="01">
            <?php 
            if($jtErr){
                foreach($jtErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>

            <label for="lokasi">Lokasi:</label><br>
            <input type="text" id="search_lokasi" name="lokasi"><br>
            <?php 
            if($lokasiErr){
                foreach($lokasiErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>
            <script>
            $(document).ready(function() {
                $('#search_lokasi').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'searchLokasi.php', // Ubah ini menjadi path ke berkas PHP Anda
                            method: 'POST',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#lokasi-list').html(data);
                            }
                        });
                    } else {
                        $('#lokasi-list').empty(); // Hapus daftar saran jika input kosong
                    }
                });

                // Menangani klik pada saran
                $('#lokasi-list').on('click', 'li', function() {
                    var selectedlokasi = $(this).text();
                    $('#search_lokasi').val(selectedlokasi);
                    $('#lokasi-list').empty(); // Hapus daftar saran setelah dipilih
                });
            });
            </script>
            <ul id="lokasi-list"></ul>


            <label for="kode_barang">Kode Barang:</label><br>
            <input type="text" id="search_kode" name="kode_barang"><br>
            <?php 
            if($kodeErr){
                foreach($kodeErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>
            <script>
            $(document).ready(function() {
                $('#search_kode').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: '../searchKodeBarang.php', // Ubah ini menjadi path ke berkas PHP Anda
                            method: 'POST',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#kode-list').html(data);
                            }
                        });
                    } else {
                        $('#kode-list').empty(); // Hapus daftar saran jika input kosong
                    }
                });

                // Menangani klik pada saran
                $('#kode-list').on('click', 'li', function() {
                    var selectedKode = $(this).text();
                    $('#search_kode').val(selectedKode);
                    $('#kode-list').empty(); // Hapus daftar saran setelah dipilih
                });
            });
            </script>
            <ul id="kode-list"></ul>

            <label for="nama_barang">Nama Barang:</label><br>
            <input type="text" id="search_nama" name="nama_barang"><br><br>
            <script>
            $(document).ready(function() {
                $('#search_kode').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'searchKodeBarang.php',
                            method: 'POST',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#kode-list').html(data);
                            }
                        });
                    } else {
                        $('#kode-list').empty();
                    }
                });

                $('#kode-list').on('click', 'li', function() {
                    var selectedKode = $(this).text();
                    $('#search_kode').val(selectedKode);
                    $('#kode-list').empty();

                    // Ketika kode_barang dipilih, cari dan isi nama_barang yang sesuai
                    $.ajax({
                        url: 'searchNamaBarang.php',
                        method: 'POST',
                        data: {
                            query: selectedKode
                        },
                        success: function(data) {
                            $('#search_nama').val(data);
                        }
                    });
                });

                $('#search_nama').keyup(function() {
                    var query = $(this).val();

                    if (query !== '') {
                        $.ajax({
                            url: 'searchNamaBarang.php',
                            method: 'POST',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#nama-list').html(data);
                            }
                        });
                    } else {
                        $('#nama-list').empty();
                    }
                });

                $('#nama-list').on('click', 'li', function() {
                    var selectedNama = $(this).text();
                    $('#search_nama').val(selectedNama);
                    $('#nama-list').empty();
                });
            });
            </script>
            <ul id="nama-list"></ul>

            <label for="tgl_masuk">Tanggal Transaksi:</label><br>
            <input type="date" name="tgl_masuk"><br>
            <?php 
            if($tglErr){
                foreach($tglErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>

            <label for="saldo_barang">Quantity:</label><br>
            <input type="number" name="saldo_barang"><br>
            <?php 
            if($saldoErr){
                foreach($saldoErr as $value){
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>

            <input type="submit" name="submit" value="Tambah Transaksi"
                onclick="return confirm('Are you sure you want to add stock?')">
            <button class="btn btn-danger">
                <a href="stokBarangView.php" style="color:white;"
                    onclick="return confirm('Are you sure want to back stuff stock?')">Back</a>
            </button>
        </form>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const counter = getCookie("counter");
        document.getElementById("counter").value = counter ? counter : "01";
    });

    function fillBukti() {
        const jenisTransaksi = document.querySelector('input[name="jenis_transaksi"]:checked').value;

        let counterElement, buktiTransaksi;

        if (jenisTransaksi === "MASUK") {
            counterElement = document.getElementById("counter");
            buktiTransaksi = "TAMBAH";
        } else if (jenisTransaksi === "KELUAR") {
            counterElement = document.getElementById("counter_keluar");
            buktiTransaksi = "KURANG";
        }

        const counter = parseInt(counterElement.value);
        const formattedCounter = counter.toString().padStart(2, "0");

        document.getElementById("bukti_transaksi").value = buktiTransaksi + formattedCounter;

        // Increment the counter
        counterElement.value = (counter + 1).toString().padStart(2, "0");

        // Save the counter value in a cookie
        setCookie("counter", counterElement.value, 365);
    }

    // Function to set a cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Function to get a cookie
    function getCookie(name) {
        const cname = name + "=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === " ") {
                c = c.substring(1);
            }
            if (c.indexOf(cname) === 0) {
                return c.substring(cname.length, c.length);
            }
        }
        return "";
    }
    </script>
</body>

</html>
<script>
function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Formatting to add leading zeros
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');

    var timeString = hours + ':' + minutes + ':' + seconds;
    $('#clock').text(timeString);
}

$(document).ready(function() {
    // Update the clock every second
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
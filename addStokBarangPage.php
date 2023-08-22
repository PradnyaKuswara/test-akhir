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
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $name = 'php_akhir';
    
    $con = mysqli_connect($host, $user, $pass, $name);
    
    if (mysqli_connect_errno()) {
        echo 'Failed to connect : ' . mysqli_connect_error();
    }
    //error
    $jenisErr = [null];
    $lokasiErr = [null];
    $kodeErr = [null];
    $tglErr = [null];
    $saldoErr = [null];
    $jtErr = [null];
    $status = true;
    
    // inisialisasi data
    $lokasi = $kode_barang = $tgl_masuk = $saldo_barang = '';
    
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if (isset($_POST['submit'])) {
        $jenisTransaksi = trim(isset($_POST['jenis_transaksi']) ? $_POST['jenis_transaksi'] : null);
        $buktiTransaksi = trim($_POST['bukti_transaksi']);
        $lokasi = trim($_POST['lokasi']);
        $kodeBarang = trim($_POST['kode_barang']);
        $namaBarang = trim($_POST['nama_barang']);
        $tglMasuk = trim($_POST['tgl_masuk']);
        $saldoBarang = trim($_POST['saldo_barang']);
    
        //validasi jens transaksi empty
        if (!isset($_POST['jenis_transaksi'])) {
            array_push($jtErr, 'Jenis transaksi must fill');
            $status = false;
        }
    
        //validasi lokasi empty
        if (empty($_POST['lokasi'])) {
            array_push($lokasiErr, 'Location must fill');
            $status = false;
        }
        $lokasi = test_input($_POST['lokasi']);
    
        //validasi kode barang empty
        if (empty($_POST['kode_barang'])) {
            array_push($kodeErr, 'Kode barang must fill');
            $status = false;
        }
        $kode_barang = test_input($_POST['kode_barang']);
    
        //validasi tgl masuk empty
        if (empty($_POST['tgl_masuk'])) {
            array_push($tglErr, 'Tgl masuk must fill');
            $status = false;
        }
        $tgl_masuk = test_input($_POST['tgl_masuk']);
    
        //validasi saldo barang
        if (empty($_POST['saldo_barang'])) {
            array_push($saldoErr, 'Quantity must fill');
            $status = false;
        }
        $saldo_barang = test_input($_POST['saldo_barang']);
    
        //validasi transaksi masuk jika tglmasuk < tglmasukterakhir//
        // Ambil data saldo dan tanggal masuk terakhir dari database
        $status = false;
        $kodeBarang = $_POST['kode_barang'];
        $lokasi = $_POST['lokasi'];
    
        $query = "SELECT saldo_barang, tgl_masuk FROM stok_barang WHERE kode_barang='$kodeBarang' AND lokasi='$lokasi' ORDER BY tgl_masuk DESC LIMIT 1";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
    
        if (mysqli_num_rows($result)) {
            $saldoTerakhir = $row['saldo_barang'];
            $tglMasukTerakhir = strtotime($row['tgl_masuk']);
    
            // Validasi tgl transaksi < dari tgl masuk terakhir pada saldo
            $tglTransaksiBaru = strtotime($_POST['tgl_masuk']);
            if ($tglTransaksiBaru < $tglMasukTerakhir) {
                echo '<script>
                                alert("Tambah Stok Barang Gagal: Tanggal transaksi lebih kecil dari tanggal masuk terakhir.");
                                window.location = "addStokBarangPage.php";
                            </script>';
            } else {
                $status = true;
            }
        } else {
            $status = true;
        }
    
        if ($status === true) {
            // Validasi saldo barang
            $jenisTransaksi = $_POST['jenis_transaksi'];
            // $jumlahTransaksi = $_POST['jumlah_transaksi'];
    
            if ($jenisTransaksi === 'MASUK') {
                $buktiTransaksi = 'TAMBAH';
                // Jika jenis transaksi adalah tambah, lanjutkan dengan proses
                ($sql = mysqli_query(
                    $con,
                    "INSERT INTO stok_barang (lokasi, kode_barang, nama_barang, tgl_masuk, saldo_barang)
                                    VALUES ( '$lokasi', '$kodeBarang', '$namaBarang', '$tglMasuk', '$saldoBarang')",
                )) or die(mysqli_error($con));
    
                if ($sql) {
                    $query_select = mysqli_query($con, 'SELECT * from stok_barang ORDER BY id_stok DESC LIMIT 1');
                    $data = mysqli_fetch_assoc($query_select);
                    $lastInsertedId = $data['id_stok'];
    
                    $number = str_pad($lastInsertedId, 2, '0', STR_PAD_LEFT);
                    $bukti_code = $buktiTransaksi . $number;
                    // $query_update = mysqli_query($con,"UPDATE transaksi_histori SET bukti_transaksi='$bukti_code' WHERE id_stok='$lastInsertedId'ORDER BY id_stok ASC") or die(mysqli_error($con));
    
                    date_default_timezone_set('Asia/Jakarta');
                    $currentDate = date('Y-m-d');
                    $currentTime = date('H:i:s');
    
                    $currentDate = $con->real_escape_string($currentDate);
                    $currentTime = $con->real_escape_string($currentTime);
    
                    ($sql2 = mysqli_query(
                        $con,
                        "INSERT INTO transaksi_histori (id_stok, jenis_transaksi, bukti_transaksi, tgl_transaksi_histori, jam_masuk,saldo_barang) 
                                        VALUES ( '$lastInsertedId', '$jenisTransaksi','$bukti_code', '$currentDate', '$currentTime','$saldoBarang')",
                    )) or die(mysqli_error($con));
    
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
            } elseif ($jenisTransaksi === 'KELUAR') {
                //check database row empty
                ($query_select = mysqli_query($con, "SELECT * from stok_barang WHERE kode_barang='$kodeBarang' AND lokasi='$lokasi'")) or die(mysqli_error($con));
                if (!mysqli_num_rows($query_select)) {
                    echo '<script>
                                alert("Stok barang ' .
                        $kode_barang .
                        ' di lokasi ' .
                        $lokasi .
                        ' tidak ada, silahkan buat transkasi masuk terlebih dahulu");
                                window.location = "stokBarangView.php";
                                </script>
                                ';
                } else {
                    //check count row
                    ($query_count = mysqli_query($con, "SELECT COUNT('id_stok') as jumlah_data FROM stok_barang")) or die(mysqli_error($con));
                    $data_count = mysqli_fetch_assoc($query_count);
                    $count = 0;
                    $saldo = 0;
    
                    $data = [];
                    while ($row = mysqli_fetch_assoc($query_select)) {
                        //calculating check if all saldo is 0
                        if ($row['saldo_barang'] == 0) {
                            $count++;
                        } else {
                            $saldo += $row['saldo_barang'];
                        }
                        //insert result query to array
                        array_push($data, $row);
                    }
                    //check all saldo is 0 and  check sum saldo can use to substract
                    if ($count == $data_count['jumlah_data']) {
                        echo '<script>
                                    alert("Saldo barang ' .
                            $kode_barang .
                            ' di lokasi ' .
                            $lokasi .
                            ' semua kosong, silahkan buat transkasi masuk terlebih dahulu");
                                    window.location = "stokBarangView.php";
                                    </script>
                                    ';
                    } elseif ($saldo < $saldoBarang) {
                        echo '<script>
                                    alert("Saldo barang ' .
                            $kode_barang .
                            ' di lokasi ' .
                            $lokasi .
                            ' tidak mencukupi permintaan untuk transaksi keluar, silahkan buat transkasi masuk terlebih dahulu");
                                    window.location = "stokBarangView.php";
                                    </script>
                                    ';
                    } else {
                        $buktiTransaksi = 'KURANG';
    
                        ($query_select = mysqli_query($con, "SELECT * FROM transaksi_histori WHERE jenis_transaksi = 'KELUAR' ORDER BY id_histori DESC LIMIT 1")) or die(mysqli_error($con));
    
                        if (mysqli_num_rows($query_select)) {
                            $data_count = mysqli_fetch_assoc($query_select);
                            $string = substr($data_count['bukti_transaksi'], 7);
                            $count = (int) $string;
                        } else {
                            $count = 0;
                        }

                        $temp_saldo;
                        $count++;
                        $number = str_pad($count, 2, '0', STR_PAD_LEFT);
                        $bukti_code = $buktiTransaksi . $number;

                        date_default_timezone_set('Asia/Jakarta');
                        $tglTransaksiHistori = date('Y-m-d'); // Tanggal transaksi histori (sesuaikan dengan format yang dibutuhkan)
                        $jamHistori = date('H:i:s'); // Jam transaksi histori (sesuaikan dengan format yang dibutuhkan)
    
                        foreach ($data as $item) {
                            $id = $item['id_stok'];
                            if ($item['saldo_barang'] != 0) {
                                $temp_saldo = $item['saldo_barang'] - $saldoBarang;
                                if ($temp_saldo < 0) {
                                    ($query_update = mysqli_query($con, "UPDATE stok_barang SET saldo_barang='0' WHERE id_stok='$id'")) or die(mysqli_error($con));
                                    $saldoBarang = abs($temp_saldo);
    
                                    // Insert ke tabel transaksi_histori
                                    $temp = $item['saldo_barang'];
                                    $insertQuery = "INSERT INTO transaksi_histori (id_stok, jenis_transaksi, bukti_transaksi, tgl_transaksi_histori, jam_masuk,saldo_barang) 
                                        VALUES ('$id', '$jenisTransaksi', '$bukti_code', '$tglTransaksiHistori', '$jamHistori','-$temp')";
                                    $insertResult = mysqli_query($con, $insertQuery);
    
                                    if (!$insertResult) {
                                        throw new Exception(mysqli_error($con)); // Throw an exception with the MySQL error message
                                    }
                                } else {
                                    ($query_update = mysqli_query($con, "UPDATE stok_barang SET saldo_barang='$temp_saldo' WHERE id_stok='$id'")) or die(mysqli_error($con));
                                    // Insert ke tabel transaksi_histori
                                    $insertQuery = "INSERT INTO transaksi_histori (id_stok, jenis_transaksi, bukti_transaksi, tgl_transaksi_histori, jam_masuk,saldo_barang) 
                                    VALUES ('$id', '$jenisTransaksi', '$bukti_code', '$tglTransaksiHistori', '$jamHistori','-$saldoBarang')";
                                    $insertResult = mysqli_query($con, $insertQuery);
    
                                    if (!$insertResult) {
                                        throw new Exception(mysqli_error($con)); // Throw an exception with the MySQL error message
                                    }
                                    break;
                                }
                            }
                        }
                        echo '
                                    <script>
                                    alert("Berhasil");
                                    window.location = "stokBarangView.php";
                                </script>
                                    ';
                    }
                }
            }
        }
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
            if ($jtErr) {
                foreach ($jtErr as $value) {
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>

            <label for="lokasi">Lokasi:</label><br>
            <input type="text" id="search_lokasi" name="lokasi"><br>
            <?php
            if ($lokasiErr) {
                foreach ($lokasiErr as $value) {
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
            if ($kodeErr) {
                foreach ($kodeErr as $value) {
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
            if ($tglErr) {
                foreach ($tglErr as $value) {
                    echo "<p class='error mb-1'>" . $value . '</p>';
                }
            }
            ?>

            <label for="saldo_barang">Quantity:</label><br>
            <input type="number" name="saldo_barang"><br>
            <?php
            if ($saldoErr) {
                foreach ($saldoErr as $value) {
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

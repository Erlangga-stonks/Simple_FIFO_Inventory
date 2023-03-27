<?php require_once "config.php";

// session_start();

// if(isset($_SESSION['username'])){
// 	$userlog = $_SESSION['username'];
// }else{
//     header("location: login.php");
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="style/all.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <title>Stock barang polytron</title>
</head>
<body>
<div id="wrapper">
<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <!-- <li class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        waw
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="logout">logout</a></li>
            <li><a class="dropdown-item" href="logout">logout</a></li>
        </ul>
        </li> -->
        <li class="sidebar-brand">
            <a href="index.php">
                Stock Barang Polytron
            </a>
        </li>
        <li>
            <a href="index.php"><b>Dashboard</b></a>
        </li>
        <li>
            <a href="input.php">Input masuk</a>
        </li>
        <li>
            <a href="input_kel.php">Input keluar</a>
        </li>
        <li>
            <a href="stock_trans.php">transaksi stok</a>
        </li>
        <li>
            <a href="history.php">History</a>
        </li>
        <li>
            <a href="logout.php">logout</a>
        </li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- <nav class="navbar navbar-light bg-light"></nav> -->
    <div class="card-header">

        <h2 class="pull-center">Transaction</h2>
    </div>
    <div class="table-responsive">
        <div class="col-lg-12">
                <!-- Stok Barang -->
                <?php
                    $sql = "SELECT transaksi.bukti, transaksi.tanggal_transaksi, transaksi.waktu, lokasi.lokasi, barang.kode_barang , barang.nama_barang, transaksi.tanggal_transaksi as 'tanggal_masuk', stock.stock_input, transaksi.qty, transaksi.program, user.username
                    FROM transaksi
                    JOIN lokasi ON lokasi.lokasi_id = transaksi.lokasi_id
                    JOIN stock ON stock.stock_id = transaksi.stock_id
                    JOIN barang ON barang.barang_id = stock.barang_id
                    JOIN user ON user.user_id = transaksi.user_id
                   ";
                    
                    // WHERE transaksi.program = 'masuk'";
                if($hasil = mysqli_query($conn,$sql)){
                    if(mysqli_num_rows($hasil) > 0){
        echo '<table class="table table-hover table-bordered" style="margin-top: 10px;">';
            echo '<thead>';
                echo '<tr class="success">';
                    echo "<th><center>Bukti</center></th>";
                    echo "<th><center>Tanggal</center></th>";
                    echo "<th><center>Jam</center></th>";
                    echo "<th><center>lokasi</center></th>";
                    echo "<th><center>Kode Barang</center></th>";
                    echo "<th><center>Nama Barang</center></th>";
                    echo "<th><center>Tgl Masuk</center></th>";
                    // echo "<th><center>Qty Trn</center></th>";
                    echo "<th><center>qty trn</center></th>";
                    echo "<th><center>program</center></th>";
                    echo "<th><center>user</center></th>";
                    echo "</tr>";
                    echo "</thead> ";
            echo "<tbody>";
                while($row = mysqli_fetch_array($hasil)){
                    date_default_timezone_set('Asia/Jakarta');
                    $tanggal_masuka = $row['tanggal_transaksi'];
                    $tanggals = date('d/m/Y');
                    $row['tanggal'] = $tanggals;
                    // $waktu = date('H:i:s');
                    // $row['jam'] = $waktu;
                echo '<tr>';
                    echo "<td>". "<center>" . $row['bukti'] . "</center>" ."</td>";
                    echo "<td>". "<center>" . $row['tanggal'] . "</center>"  ."</td>";
                    echo "<td>". "<center>" . $row['waktu'] . "</center>"  ."</td>";
                    echo "<td>". "<center>" . $row['lokasi'] . "</center>"  ."</td>";
                    echo "<td>". "<center>" . $row['kode_barang'] . "</center>" ."</td>";
                    echo "<td>"."<center>" . $row['nama_barang'] . "</center>"."</td>";
                    echo "<td>". "<center>" . date("d/m/Y", strtotime($tanggal_masuka)) ."</td>";
                    // echo "<td>"."<center>" . $row["stock_input"] ."</center>"."</td>";
                    echo "<td>"."<center>" . $row["qty"] ."</center>"."</td>";
                    echo "<td>". "<center>" . $row['program'] . "</center>" ."</td>";
                    echo "<td>". "<center>" . $row['username'] . "</center>" ."</td>";
                echo '</tr>';
            }
            echo "</tbody>";
        echo '</table>';
        mysqli_free_result($hasil);
            }
            else{
                echo '<div class="alert alert-danger"><em>tidak ada stock barang yang ditemukan.</em></div>';
            }
        }else{
            echo "tolong refresh kembali.";
        }
        mysqli_close($conn);
        ?>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="style/all.js"></script>
    <title>Stock barang polytron</title>
</head>
<body>
<div id="wrapper">

<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="index.php">
                Stock Barang Polytron
            </a>
        </li>
        <li>
            <a href="index.php">Dashboard</a>
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
            <a href="history.php"><b>History</b></a>
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
                <nav class="navbar navbar-light bg-light"></nav>
    <?php require_once "config.php";?>
    <div class="card-header">
        <h2 class="pull-center">Transaction History</h2>
    </div>
    <div class="table-responsive">
        <div class="col-lg-12">
                <!-- Stok Barang -->
                <?php
                    $log_act = "SELECT * FROM transaksi_history";
                if($hasil = mysqli_query($conn,$log_act)){
                    if(mysqli_num_rows($hasil) > 0){
        echo '<table class="table table-hover table-bordered" style="margin-top: 10px;">';
            echo '<thead>';
                echo '<tr class="success">';
                    echo "<th>Bukti</th>";
                    echo "<th>Tanggal</th>";
                    echo "<th>Jam</th>";
                    echo "<th>lokasi</th>";
                    echo "<th>Kode Barang</th>";
                    echo "<th>Nama Barang</th>";
                    echo "<th>Tgl Masuk</th>";
                    echo "<th>Qty Trn</th>";
                    echo "<th>program</th>";
                    echo "<th>user</th>";
                echo "</tr>";
            echo "</thead> ";

            echo "<tbody>";
                while($row = mysqli_fetch_array($hasil)){
                    date_default_timezone_set('Asia/Jakarta');
                    $tanggal_masuka = $row['tanggal_masuk'];
                    $tanggals = date('d/m/Y');
                    $row['tanggal'] = $tanggals;
                    // $waktu = date('H:i:s');
                    // $row['jam'] = $waktu;
                echo '<tr>';
                    echo "<td>". $row['bukti'] ."</td>";
                    echo "<td>". $row['tanggal'] ."</td>";
                    echo "<td>". $row['jam'] ."</td>";
                    echo "<td>". $row['lokasi'] ."</td>";
                    echo "<td>". $row['kode_barang'] ."</td>";
                    echo "<td>". $row['nama_barang'] ."</td>";
                    echo "<td>". date("d/m/Y", strtotime($tanggal_masuka)) ."</td>";
                    echo "<td>". $row['stock_input'] ."</td>";
                    echo "<td>". $row['program'] ."</td>";
                    echo "<td>". $row['user'] ."</td>";            
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
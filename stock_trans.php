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
            <a href="stock_trans.php"><b>transaksi stok</b></a>
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
                <nav class="navbar navbar-light bg-light"></nav>
    <?php require_once "config.php";?>
    <div class="card-header">
        <h2 class="pull-center">Transaksi Stock</h2>
    </div>
    <div class="table-responsive">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                 Tanggal: <input type="date" name="tanggal">
                 <input type="submit" name="submit" value="Cari">
                </form>
            </div>
<?php
// Cek apakah form telah disubmit
if (isset($_POST['submit'])) {
   
    // Query untuk mencari data berdasarkan tanggal
    $tanggal = $_POST['tanggal'];

    $query = "SELECT lokasi.lokasi, barang.kode_barang , barang.nama_barang, stock.tanggal_masuk as 'tanggal_masuk', stock.stock_input as 'stock_input', transaksi.program, user.username
                    FROM transaksi
                    JOIN lokasi ON lokasi.lokasi_id = transaksi.lokasi_id
                    JOIN stock ON stock.stock_id = transaksi.stock_id
                    JOIN barang ON barang.barang_id = stock.barang_id
                    JOIN user ON user.user_id = transaksi.user_id
                WHERE
                 transaksi.program = 'masuk'
                GROUP BY
                barang.kode_barang , barang.nama_barang ,  stock.tanggal_masuk
                ORDER BY
                stock.tanggal_masuk asc";
    
    // Eksekusi query
    $hasil = mysqli_query($conn, $query);

    // Tampilkan hasil pencarian
    
    if(mysqli_num_rows($hasil) > 0){
        echo '<table class="table table-hover table-bordered" style="margin-top: 10px;">';
        echo '<thead>';
          echo '<tr>';
          // echo "<th><center>tanggal Transaksi</center></th>";
          // echo "<th><center>waktu</center></th>";
          echo "<th><center>lokasi</center></th>";
          echo "<th><center>kode barang</center></th>";
          echo "<th><center>nama barang</center></th>";
          echo "<th><center>Tanggal_transaksi</center></th>";
          echo "<th><center>qty trn</center></th>";
          echo "<th><center>user</center></th>";
          echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($row = mysqli_fetch_assoc($hasil)) {
            $date =  $row['tanggal_masuk'];
            
            if($tanggal >= $date){
                     echo '<tr>';
                   //   echo "<td>" . "<center>" . $data['tanggal_transaksi'] . "</center>" . "</td>";
                   //   echo "<td>" . "<center>" . $data['waktu'] . "</center>" . "</td>";
                     echo "<td>" . "<center>" . $row['lokasi'] . "</center>" . "</td>";    
                     echo "<td>" . "<center>" . $row['kode_barang'] . "</center>" . "</td>";
                     echo "<td>" . "<center>" . $row['nama_barang'] . "</center>" . "</td>";
                     echo "<td>" . "<center>" . date("d/m/Y", strtotime($date)) . "</center>" . "</td>";
                     echo "<td>" . "<center>" . $row['stock_input'] . "</center>" . "</td>";
                     echo "<td>" . "<center>" . $row['username'] . "</center>" . "</td>";
                     echo '</tr>';
            }
            
        }
            echo '</tbody>';
           echo '</table>';
        
    }else{
        echo '<div class="alert alert-danger"><em>tidak ada stock barang yang ditemukan.</em></div>';
    }
  }
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
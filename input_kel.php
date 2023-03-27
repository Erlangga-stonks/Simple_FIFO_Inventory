<?php
require_once "config.php";

// Mendefinisikan var buat input

 $bukti = $lokasi = $kode_barang = $nama_barang =$tanggal_transaksi  = $stock_input = $stock_id = $programs = " ";
 $bukti_err  = $lokasi_err = $kode_barang_err = $nama_barang_err = $tanggal_transaksi_err = $stock_input_err = $stock_id_err = $programs_err = "" ;

if($_SERVER["REQUEST_METHOD"] == "POST"){
     $barang_id = mysqli_insert_id($conn);
    $lokasi_id = mysqli_insert_id($conn);
 // kode barang   
    // $kode_barang =$_POST['kode_barang'];
    // $sqlu = "SELECT barang_id, nama_barang FROM barang WHERE kode_barang='$kode_barang'";
    // $queri= mysqli_query($conn, $sqlu);
    
    
    // // nama barang  
    // $nama_barang = $_POST['nama_barang'];
    // if(mysqli_num_rows($queri) > 0){
    //     $data = mysqli_fetch_array($queri);
    //     if($nama_barang != $data[1]){
    //         $nama_barang_err = 'Kode barang '.$kode_barang.' sudah ada dengan barang '.$data[1];
    //     }
    // }
    // // var_dump($nama_barang);
    // // exit;

    // $sqla ="SELECT barang_id, kode_barang FROM barang WHERE nama_barang= '$nama_barang'";
    // $quera = mysqli_query($conn,$sqla); 
    // if(mysqli_num_rows($quera) > 0){
    //     $datas = mysqli_fetch_array($quera);
    //     if($kode_barang != $datas[1]){
    //         $kode_barang_err = 'Nama Barang ' .$nama_barang. 'sudah ada dengan kode barang ' . $datas[1];
    //     }
    // }

    function input_lokasi($inpt){
        $inpt = trim($inpt);
        $inpt = stripslashes($inpt);
        $inpt = htmlspecialchars($inpt);
        return $inpt;
    }

    function saldo($sald){
        $sald = trim($sald);
        $sald = stripslashes($sald);
        $sald = htmlspecialchars($sald);
        return $sald;
    }

    //validation jenis transaksi
    if(isset($_POST['program'])){

        $input_programs = trim(($_POST["program"]));
    
        if(empty($_POST['program'])){
            $programs_err = "masukkan jenis transaksi dengan benar";
        }else
        {
            $programs = $input_programs;
        }
    }
    // var_dump($input_programs); //biar tau inputan
    // exit;

    // Validation Bukti
        $input_bukti = trim($_POST['bukti']);
        if(empty($input_bukti)){
            $bukti_err = "masukkan bukti dengan benar";
        }
        elseif(!preg_match("/^[A-Z0-9]*$/", $input_bukti)){
            $bukti_err = "masukkan dengan format 
                          TAMBAH jika jenis transaksi masuk
                          KURANG jika jenis transaksi keluar
                          dengan diikuti 2 angka ";
        }
        // elseif(!preg_match("/\b(TAMBAH0-9|KURANG0-9)\b/i", $input_bukti)){
        //     $bukti_err = "Harus menggunakan format 
        //                   TAMBAH jika masuk
        //                   KURANG jika keluar";
        //     }
        else{
            $bukti = $input_bukti;
        }
        // var_dump($input_bukti);
        // exit;
    // validasi lokasi
    $locat = input_lokasi($_POST["lokasi"]);
    if(empty($_POST["lokasi"])){
       $lokasi_err = "masukkan nama lokasi dengan benar";
   }
   elseif(!preg_match("/^[A-Z0-9]*$/", $locat)){
    $lokasi_err = "masukkan dengan huruf balok diikuti oleh 2 angka ";
    }  
    else{
        $lokasi = $locat; 
    }
    // validasi kode barang /^[A-Z]\d{2}.+$

    $input_kode_barang = trim($_POST["kode_barang"]);
    
    if(empty($input_kode_barang)){
        $kode_barang_err = "masukkan kode barang dengan benar";
    }
    elseif(!preg_match("/^[A-Z0-9\-]*$/", $input_kode_barang)){
        $kode_barang_err = "masukkan dengan huruf besar ";
        } 
    else{

        $kode_barang = $input_kode_barang;
    }

    // validasi nama barang
    $input_nama_barang = trim($_POST["nama_barang"]);
    if(empty($input_kode_barang)){
        $nama_barang_err = "masukkan nama barang dengan benar";
    }
    elseif(!preg_match("/^[a-zA-Z0-9\ ]*$/", $input_nama_barang)){
        $nama_barang_err = "masukkan dengan huruf besar ";
        } 
    else{
       $nama_barang = $input_nama_barang;
    }

    // validasi tanggal transaksi 
    if(isset($_POST["tanggal_transaksi"])){
        $input_tanggal_transaksi = mysqli_real_escape_string($conn,$_POST['tanggal_transaksi']);
        // $tgl_masuk = mysqli_real_escape_string($conn,$_POST['tanggal_masuk']);
        if(empty($input_tanggal_transaksi)){
            $tanggal_transaksi_err = "masukkan tanggal transaksi dengan benar";
        }
        else{
           $tanggal_transaksi = $input_tanggal_transaksi;
        }
    }
    
    // validasi Quantity 
        $jml_stoc = trim($_POST['stock_input']);
        if(empty($_POST["stock_input"])){
            $stock_input_err = "masukkan stock dengan benar";
        }
        else{
           $stock_input = $jml_stoc;
        }

    if(empty($programs_err) && empty($bukti_err) && empty($lokasi_err) && empty($kode_barang_err) && empty($nama_barang_err) && empty($tanggal_masuk_err) &&empty($stock_input_err) && empty($stock_id_err)){

                if(isset($_POST['submit'])){

                    $nama_barang = trim($_POST['nama_barang']);   
                    $kode_barang = trim($_POST['kode_barang']);
                    $stock_input = (int)$_POST['stock_input'];
                    $bukti = trim($_POST['bukti']);
                    $lokasi_nam = trim($_POST['lokasi']);
                    $transaksi_tgl = date('Y-m-d');
                    $waktu_trans = mysqli_query($conn, "SELECT  waktu FROM transaksi");
                    $waktu_fet = mysqli_fetch_assoc($waktu_trans);
                    $jam = $waktu_fet['waktu'];
                    
                    $checK_sto = mysqli_query($conn, "SELECT SUM(stock_input) AS stock_all FROM stock
                    
                    JOIN barang ON barang.barang_id = stock.barang_id

                    WHERE barang.nama_barang = '$nama_barang'");
                    $fetch_check = mysqli_fetch_assoc($checK_sto);
                    $stock_check = (int)$fetch_check['stock_all'];

                    if($stock_check < $stock_input){
                        echo "stock not enough";
                        exit;
                    }
                    
                    $lokat_quer = mysqli_query($conn, "SELECT lokasi_id , lokasi FROM lokasi WHERE lokasi = '$lokasi_nam'");
                    $fetch_lokat = mysqli_fetch_assoc($lokat_quer);
                    $lokasi_id = $fetch_lokat['lokasi_id'];
                    // var_dump($lokasi_id);
                    // exit;
                    $min_sto = mysqli_query($conn, "SELECT stock_id , stock_input, tanggal_masuk , barang.nama_barang  FROM stock
                    
                    JOIN barang ON barang.barang_id = stock.barang_id
                    
                    WHERE nama_barang LIKE '$nama_barang'

                    ORDER BY stock_id, tanggal_masuk ASC");
;
                    while( $fetchin_sto = mysqli_fetch_assoc($min_sto)){
                        $stock_id = $fetchin_sto['stock_id'];
                        $sto = $fetchin_sto['stock_input'];
                        $tgl_masuk = $fetchin_sto['tanggal_masuk'];

                        if($stock_input == 0){
                            break;
                        }
                        elseif($stock_input <= $sto){
                            $pengu = $sto - $stock_input;   
                            $sto -= $stock_input;
                            mysqli_query($conn, "UPDATE stock SET stock_input = '$pengu' WHERE stock.stock_id = '$stock_id'");
                            mysqli_query($conn, "INSERT INTO transaksi (tanggal_transaksi,bukti,qty, program, lokasi_id, stock_id, user_id) VALUES ('$transaksi_tgl','$bukti','$stock_input','keluar','$lokasi_id','$stock_id','1')");
                            mysqli_query($conn, "INSERT INTO transaksi_history(bukti,tanggal,jam,lokasi,kode_barang,nama_barang,tanggal_masuk,stock_input,program,user) VALUES ('$bukti','$transaksi_tgl','$jam','$lokasi_nam','$kode_barang','$nama_barang','$tgl_masuk','$stock_input','keluar','admin')");
                            
                        }
                        elseif($stock_input > $sto){
                            $stock_input -= $sto;
                            mysqli_query($conn, "UPDATE stock SET stock_input = 0 WHERE stock.stock_id = '$stock_id'");
                            mysqli_query($conn, "INSERT INTO transaksi (tanggal_transaksi,bukti,qty, program, lokasi_id, stock_id, user_id) VALUES ('$transaksi_tgl','$bukti','$sto','keluar','$lokasi_id','$stock_id','1')");
                            mysqli_query($conn, "INSERT INTO transaksi_history(bukti,tanggal,jam,lokasi,kode_barang,nama_barang,tanggal_masuk,stock_input,program,user) VALUES ('$bukti','$transaksi_tgl','$jam','$lokasi_nam','$kode_barang','$nama_barang','$tgl_masuk','$sto','keluar','admin')");
                        }
                    }
                    header("location: index.php");         
                }   
                else{
                    echo "tidak ada operasi pengurangan yang di jalankan";
                }       
            }
        }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesh  eet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="style/all.js"></script>
    <title>Input Data stock Barang</title>
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
            <a href="input_kel.php"><b>Input keluar</b></a>
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
 <div id="page-content-wrapper">
     <div class="container-fluid">
         <div class="row">
             <div class="col-md-12">
                 <div class="card">
                     <div class="card-header">
                         <h4 class="text-center">stock keluar</h4>
                     </div>
                     <div class="card-body">
                        <?php
                        
                        $sqle = "SELECT stock.stock_id, transaksi.tanggal_transaksi, transaksi.waktu, lokasi.lokasi, barang.kode_barang , barang.nama_barang,  stock.stock_input, transaksi.program, user.username
                        FROM transaksi
                        JOIN lokasi ON lokasi.lokasi_id = transaksi.lokasi_id
                        JOIN stock ON stock.stock_id = transaksi.stock_id
                        JOIN barang ON barang.barang_id = stock.barang_id
                        JOIN user ON user.user_id = transaksi.user_id
                        
                        WHERE transaksi.program = 'masuk'";

                            if($res = mysqli_query($conn,$sqle)){
                                if(mysqli_num_rows($res) > 0){
                                    echo '<table class="table table-hover table-bordered" style="margin-top: 10px;">';
                                    echo '<thead>';
                                         echo '<tr class="success">';
                                            echo "<th><center>No</center></th>";
                                            echo "<th><center>lokasi</center></th>";
                                            echo "<th><center>Kode Barang</center></th>";
                                            echo "<th><center>Nama Barang</center></th>";
                                            echo "<th><center>Tgl Masuk</center></th>";
                                            echo "<th><center>stock</center></th>";
                                            echo "</tr>";
                                            echo "</thead> ";
                                            echo "<tbody>";

                                            while($row = mysqli_fetch_array($res)){
                                                date_default_timezone_set('Asia/Jakarta');
                                                $tanggal_masuka = $row['tanggal_transaksi'];
                                                echo '<tr>';
                                                echo "<td>". "<center>" . $row['stock_id'] . "</center>"  ."</td>";
                                                echo "<td>". "<center>" . $row['lokasi'] . "</center>"  ."</td>";
                                                echo "<td>". "<center>" . $row['kode_barang'] . "</center>" ."</td>";
                                                echo "<td>". "<center>" . $row['nama_barang'] . "</center>"."</td>";
                                                echo "<td>". "<center>" . date("d/m/Y", strtotime($tanggal_masuka)) ."</td>";
                                                echo "<td>". "<center>" . $row["stock_input"] ."</center>"."</td>";
                                            echo '</tr>';
                                        }
                                        echo "</tbody>";
                                    echo '</table>';
                                }
                            }
                        ?>
                         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                            <div class="mb-3">
                                <label><b>Jenis transaksi</b></label>
                                <!-- <input type="radio" id="masuk" value="masuk" name="program">
                                <label for="masuk">masuk</label> -->
                                <input type="radio"  id="keluar" value="keluar" name="program">
                                <label for="keluar">keluar</label>
                            </div>
                            <!-- <div class="mb-3">
                                <label><b>no</b></label>
                                <input type="text" name="stock_id" class="form-control <?php echo (!empty($stock_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $stock_id ; ?>">
                                <span class="invalid-feedback"><?php echo $bukti_err;?></span>
                            </div> -->
                            <div class="mb-3">
                                <label><b>Bukti</b></label>
                                <input type="text" name="bukti" class="form-control <?php echo (!empty($bukti_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bukti; ?>">
                                <span class="invalid-feedback"><?php echo $bukti_err;?></span>
                            </div>
                            <div class="mb-3">
                                <label><b>Lokasi</b></label>
                                <input type="text" name="lokasi" class="form-control <?php echo (!empty($lokasi_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lokasi; ?>">
                                <span class="invalid-feedback"><?php echo $lokasi_err;?></span>
                            </div>
                            <div class="mb-3">
                                <label><b>Kode Barang</b></label>
                                <input type="text" name="kode_barang" class="form-control <?php echo (!empty($kode_barang_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kode_barang; ?>">
                                <span class="invalid-feedback"><?php echo $kode_barang_err;?></span>
                            </div>
       
                            <div class="mb-3">
                                <label><b>Nama Barang</b></label>
                                <input type="text" name="nama_barang" class="form-control <?php echo (!empty($nama_barang_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nama_barang; ?>">
                                <span class="invalid-feedback"><?php echo $nama_barang_err;?></span>
                            </div>
                            <div class="mb-3">
                                <label><b>Tanggal Transaksi</b></label>
                                <input type="date" name="tanggal_transaksi" class="form-control" value="<?php echo $tanggal_transaksi; ?>">
                            </div>
                            <div class="mb-3">
                                <label><b>Quantity</b></label>
                                <input type="text" name="stock_input" class="form-control <?php echo (!empty($stock_input_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $stock_input; ?>">
                            </div>
                            <div class="mb-3">                               
                                <input type="submit" name="submit" class="btn btn-primary" value="submit">
                                <a href="index.php" class="btn btn-danger">exit</a>
                                </div>
                        </form>
                     </div>
                             
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
</body>
</html>
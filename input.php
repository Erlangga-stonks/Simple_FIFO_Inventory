<?php
require_once "config.php";

// Mendefinisikan var buat input

 $bukti = $lokasi = $kode_barang = $nama_barang = $tanggal_transaksi = $stock_input = $programs = " ";
 $bukti_err  = $lokasi_err = $kode_barang_err = $nama_barang_err = $tanggal_transaksi_err = $stock_input_err = $programs_err = "" ;

if($_SERVER["REQUEST_METHOD"] == "POST"){
     $barang_id = mysqli_insert_id($conn);
    
    // kode barang   
    $kode_barang =$_POST['kode_barang'];
    $sqlu = "SELECT barang_id, nama_barang FROM barang WHERE kode_barang='$kode_barang'";
    $queri= mysqli_query($conn, $sqlu);
    
    
    // nama barang  
    $nama_barang = $_POST['nama_barang'];
    if(mysqli_num_rows($queri) > 0){
        $data = mysqli_fetch_array($queri);
        if($nama_barang != $data[1]){
            $nama_barang_err = 'Kode barang '.$kode_barang.' sudah ada dengan barang '.$data[1];
        }
    }
    // var_dump($nama_barang);
    // exit;

    $sqla ="SELECT barang_id, kode_barang FROM barang WHERE nama_barang= '$nama_barang'";
    $quera = mysqli_query($conn,$sqla); 
    if(mysqli_num_rows($quera) > 0){
        $datas = mysqli_fetch_array($quera);
        if($kode_barang != $datas[1]){
            $kode_barang_err = 'Nama Barang ' .$nama_barang. 'sudah ada dengan kode barang ' . $datas[1];
        }
    }

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

    if(empty($programs_err) && empty($bukti_err) && empty($lokasi_err) && empty($kode_barang_err) && empty($nama_barang_err) && empty($tanggal_transaksi_err) &&empty($stock_input_err)){
        $inpute_stock = "INSERT INTO stock(stock_input,tanggal_masuk, barang_id) values (?,?,?)";
        $inputter = "INSERT INTO transaksi (tanggal_transaksi, bukti, program, qty, lokasi_id, stock_id, user_id) VALUES (?,?,?,'$stock_input',?,?,?)";
        $inputter_barang = "INSERT INTO barang(kode_barang,nama_barang) VALUES (?,?)";
        $inputa_lokasi = "INSERT INTO lokasi(lokasi) values (?)";
        if($input_programs == 'masuk'){
            if($stater= mysqli_prepare($conn,$inputter_barang)){    
                mysqli_stmt_bind_param($stater, "ss" , $param_kode_barang,$param_nama_barang);
                // setting parameter
                $param_kode_barang = $kode_barang;  
                $param_nama_barang = $nama_barang;  
                
                $sql_barang = "SELECT barang_id FROM barang WHERE barang.kode_barang = '$param_kode_barang' OR barang.nama_barang = '$param_nama_barang'";
                $querbar = mysqli_query($conn, $sql_barang);
                if(mysqli_num_rows($querbar)){
                    $datas = mysqli_fetch_array($querbar);
                    $barang_id = $datas[0];
                    $insert_barang = TRUE;
                }else{
                    $insert_barang = mysqli_stmt_execute($stater);
                    $barang_id = mysqli_insert_id($conn);
                }
                if($insert_barang){
                    if($stato = mysqli_prepare($conn,$inpute_stock)){
                        // var_dump($stato);
                        // exit;
                        mysqli_stmt_bind_param($stato, "sss" , $param_stock_input, $tanggal_transaksi , $barang_id);
                        // setting parameter
                        $param_stock_input = $stock_input;
                        
                        if(mysqli_stmt_execute($stato)){
                            $stock_id =  mysqli_insert_id($conn);
                            if($stako= mysqli_prepare($conn,$inputa_lokasi)){
                                mysqli_stmt_bind_param($stako, "s" ,$param_nama_lokasi);
                                // setting parameter
                                $param_nama_lokasi = $lokasi;
                                $sql_lokasi = "SELECT lokasi_id FROM lokasi WHERE lokasi='$param_nama_lokasi'";
                                $query= mysqli_query($conn, $sql_lokasi);
                                if(mysqli_num_rows($query) > 0){   
                                    $data = mysqli_fetch_array($query);
                                    $lokasi_id = $data[0]; 
                                    $insert_lokasi = true;
                                }else{
                                    $insert_lokasi = mysqli_stmt_execute($stako);
                                    $lokasi_id = mysqli_insert_id($conn);
                                }
                                if($insert_lokasi){
                                    $user_id = 1 ;
                                    if($state= mysqli_prepare($conn,$inputter)){
                                        mysqli_stmt_bind_param($state,"ssssss", $param_tanggal_transaksi, $param_bukti, $param_program, $lokasi_id, $stock_id , $user_id);
                                        // setting parameterv
                                        $param_tanggal_transaksi = $tanggal_transaksi;
                                        $param_bukti = $bukti;
                                        $param_program = $programs;
                                      
                                        if(mysqli_stmt_execute($state)){        
                                        $transaksi_id = mysqli_insert_id($conn);
                                        $transaksi_hist = "INSERT INTO transaksi_history (bukti, tanggal , jam , lokasi, kode_barang, nama_barang , tanggal_masuk , stock_input, program, user) 
                                SELECT transaksi.bukti, transaksi.tanggal_transaksi, transaksi.waktu, lokasi.lokasi, barang.kode_barang , barang.nama_barang, stock.tanggal_masuk , stock.stock_input, transaksi.program, user.username
                                    FROM transaksi 
                                         JOIN lokasi ON lokasi.lokasi_id = transaksi.lokasi_id
                                         JOIN stock ON stock.stock_id = transaksi.stock_id
                                         JOIN barang ON barang.barang_id = stock.barang_id
                                         JOIN user ON user.user_id = transaksi.user_id
                                      WHERE transaksi.transaksi_id = '$transaksi_id'";
                                    //   var_dump($transaksi_hist);
                                    //   exit;
                                                if(mysqli_query($conn, $transaksi_hist)){
                                                    header("location: index.php");
                                                }
                                        }else{
                                            echo "maaf, tolong refresh kembali";
                                        }
                                    }
                        }else{
                            echo "maaf, tolong refresh kembali";
                        }
                    }

                        }else{
                            echo "maaf, tolong refresh kembali";    
                        }
                    }
                }
                }else{
                    echo "maaf, tolong refresh kembali";
                }
                mysqli_stmt_close($stato);
                mysqli_stmt_close($state);
                mysqli_stmt_close($stater);
                mysqli_stmt_close($stako);
                mysqli_close($conn);
            }
        }else{
            echo "Try Again";
        }
    }
?>

<!DOCTYPE html>
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
            <a href="input.php"><b>Input masuk</b></a>
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
 <div id="page-content-wrapper">
     <div class="container-fluid">
         <div class="row">
             <div class="col-md-12">
                 <div class="card">
                     <div class="card-header">
                         <h4 class="text-center">stock masuk </h4>
                     </div>
                     <div class="card-body">
                         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                            <div class="mb-3">
                                <label><b>Jenis transaksi</b></label>
                                <input type="radio" id="masuk" value="masuk" name="program">
                                <label for="masuk">masuk</label>
                                <!-- <input type="radio"  id="keluar" value="keluar" name="program">
                                <label for="keluar">keluar</label> -->
                            </div>
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
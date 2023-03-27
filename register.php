<?php
require_once 'config.php'; // memanggil file koneksi ke database
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);// md5 untuk hash code agar password tidak terbaca oleh siapapun
    // lakukan validasi data
    if(!empty($username) && !empty($password)) {
        // cek apakah username sudah digunakan atau belum
        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) == 0) {
            // simpan data user ke database
            $query = "INSERT INTO user (username, password) VALUES ( '$username', '$password')";
            $result = mysqli_query($conn, $query);
            if($result) {
                header("Location: login.php");
            } else {
                echo "Gagal menyimpan data!";
            }
        } else {
            echo "Username sudah digunakan!";
        }
    } else {
        echo "Silakan isi semua data!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Halaman Register</title>
    <style>
.wrapper {
	margin-top: 80px;
	margin-bottom: 80px;
}

.form-register {
	max-width: 380px;
	padding: 15px 35px 45px;
	margin: 0 auto;
	background-color: #fff;
	border: 1px solid rgba(0, 0, 0, 0.1);
}

.form-register .form-register-heading,
.form-register .checkbox {
	margin-bottom: 30px;
}

.form-register .checkbox {
	font-weight: normal;
}

.form-register .form-control {
	position: relative;
	font-size: 16px;
	height: auto;
	padding: 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.form-register .form-control:focus {
	z-index: 2;
}

.form-register input[type="text"] {
	margin-bottom: -1px;
	border-bottom-left-radius: 0;
	border-bottom-right-radius: 0;
}

.form-register input[type="password"] {
	margin-bottom: 20px;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
}
    </style>
</head>
<body>
    <div class="wrapper">
        <form method="POST" class="form-register">
            <h2 class="form-register-heading">Halaman Register</h2>
            <label>Username:</label><br>
            <input type="text" name="username" class="form-control"><br>
            <label>Password:</label><br>
            <input type="password" name="password" class="form-control"><br>
            <br>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>
</body>
</html>
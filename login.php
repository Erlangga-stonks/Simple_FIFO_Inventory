<?php

require_once "config.php";

error_reporting(0);

if(isset($_SESSION['username'])){
	header("location : index.php");
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = ($_POST['password']);

    if(empty($username) && empty($password)){
        header("location : index.php?error = username dan password kosong tolong input kembali");
    }elseif(empty($username)){
        header("location : index.php?error = username kosong, tolong input kembali dengan benar");
    }elseif(empty($password)){
        header("location : index.php?error = Password kosong, tolong input kembali dengan benar");
    }
 
    $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        header("Location: index.php");
    } else {
        echo "<script>alert('Email atau password Anda salah. Silahkan coba lagi!')</script>";
    }
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Login Page</title>
    <style>
.wrapper {
	margin-top: 80px;
	margin-bottom: 80px;
}

.form-signin {
	max-width: 380px;
	padding: 15px 35px 45px;
	margin: 0 auto;
	background-color: #fff;
	border: 1px solid rgba(0, 0, 0, 0.1);
}

.form-signin .form-signin-heading,
.form-signin .checkbox {
	margin-bottom: 30px;
}

.form-signin .checkbox {
	font-weight: normal;
}

.form-signin .form-control {
	position: relative;
	font-size: 16px;
	height: auto;
	padding: 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.form-signin .form-control:focus {
	z-index: 2;
}

.form-signin input[type="text"] {
	margin-bottom: -1px;
	border-bottom-left-radius: 0;
	border-bottom-right-radius: 0;
}

.form-signin input[type="password"] {
	margin-bottom: 20px;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
}
    </style>
</head>
<body>
<div class="alert alert-warning" role="alert">
        <?php echo $_SESSION['error']?>
    </div>
<div class="wrapper">
   <form class="form-signin" action="POST">
      <h2 class="form-signin-heading"><center>Please login</center></h2>
      <input type="text" class="form-control" name="username" placeholder="username" value="<?php echo $_POST['username']; ?>" required/>
      <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $_POST['password']; ?>" required/>      
      <label class="checkbox">
      <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Remember me
      </label>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
            <p class="login-register-text">Anda belum punya akun? <a href="register.php">Register</a></p>
        </form>
</div>
</body>
</html>
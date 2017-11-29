<?php

include './config/conn.php';

// Start Session
session_start();

if(!empty($_SESSION['name'])) { 

    if(isset($_SESSION['email'])) {

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $status = $_SESSION['status'];

        if($email != "" && $status == "logged in")
        {
            header("Location: feed.php");
            exit();
        }
    }
}

// check Login request
if (!empty($_POST['btnLogin'])) {
    
	$email = trim($_POST['email']);
    $password = trim($_POST['password']);
 
    if ($email == "") {
        $login_error_message = 'Email is required!';
        echo $login_error_message . "<br>";
    } else if ($password == "") {
        $login_error_message = 'Password is required!';
        echo $login_error_message . "<br>";
    } else {
    	try {
            $enc_password = hash('sha256', $password);
            $status = "activated";
            // prepare sql and bind parameters
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
            $stmt2->bindParam(':email', $email);
            $stmt2->bindParam(':password', $enc_password);
            $stmt2->execute();
            if ($stmt2->rowCount() > 0) {
                $stmt3 = $conn->prepare("SELECT name FROM users WHERE email=:email AND status=:status");
                $stmt3->bindParam(':email', $email);
                $stmt3->bindParam(':status', $status);
                $stmt3->execute();
                if ($stmt3->rowCount() > 0) {
                    $row = $stmt3->fetch();
                    $name = $row['name'];
                    $loginstatus = "loggedin";
                    
                    $stmt_status = $conn->prepare("UPDATE users SET loginstatus=:loginstatus
                    WHERE email=:email");
                    $stmt_status->bindParam(':loginstatus', $loginstatus);
                    $stmt_status->bindParam(':email', $email);
                    $stmt_status->execute();

                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['status'] = "logged in";
                    header("Location: updateprofile.php");
                } else {
                    echo "Your account is not Activated! Check your email for activation link." . "<br>";
                }
            } else {
                // prepare sql and bind parameters
                $name = $email;
                $stmt4 = $conn->prepare("SELECT * FROM users WHERE name=:name AND password=:password");
                $stmt4->bindParam(':name', $name);
                $stmt4->bindParam(':password', $enc_password);
                $stmt4->execute();
                if ($stmt4->rowCount() > 0) {
                    $stmt5 = $conn->prepare("SELECT email FROM users WHERE name=:name AND status=:status");
                    $stmt5->bindParam(':name', $name);
                    $stmt5->bindParam(':status', $status);
                    $stmt5->execute();
                    if ($stmt5->rowCount() > 0) {
                        $row = $stmt5->fetch();
                        $email = $row['email'];
                        $loginstatus = "loggedin";
                        
                        $stmt_status = $conn->prepare("UPDATE users SET loginstatus=:loginstatus
                        WHERE email=:email");
                        $stmt_status->bindParam(':loginstatus', $loginstatus);
                        $stmt_status->bindParam(':email', $email);
                        $stmt_status->execute();

                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $email;
                        $_SESSION['status'] = "logged in";
                        header("Location: updateprofile.php");
                    } else {
                        echo "Your account is not Activated! Check your email for activation link." . "<br>";
                    }
                } else {
                    echo "Incorrect user credentials, please try again!" . "<br>";
                }
            }
		} catch (PDOException $e) {
			echo "error: " . $e->getMessage();
		}
		$conn = null;
    }

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Matcha</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/animate.css">
<link rel="stylesheet" type="text/css" href="./assets/css/font.css">
<link rel="stylesheet" type="text/css" href="./assets/css/li-scroller.css">
<link rel="stylesheet" type="text/css" href="./assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="./assets/css/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="./assets/css/theme.css">
<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
<!--[if lt IE 9]>
<script src="../assets/js/html5shiv.min.js"></script>
<script src="../assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
  <header id="header">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="header_top">
          <div class="header_top_left">
            <ul class="top_nav">
              <li><a href="./index.php">Home</a></li>
              <li><a href="./signup.php">Sign Up</a></li>
            </ul>
          </div>
          <div class="header_top_right">
            <a href="./inc/logout.php"><p>Logout</p></a>
          </div>
        </div>
      </div>
    </div>
  </header>
  <section id="contentSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          <div class="contact_area">
            <h2>Login</h2>
            <p>Login to your account. Or create a new account by clicking sign up.</p>
                <form action="index.php" method="post">
                    <div class="form-group">
                        <label for="">Username/Email</label>
                        <input type="text" name="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <a href='./inc/initreset.php'>Forgotten Password</a>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnLogin" class="btn btn-primary" value="Login"/>
                    </div>
                </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer id="footer">
    <div class="footer_bottom">
      <p class="copyright">Copyright &copy; 2017 <a href="index.php">Matcha</a></p>
      <p class="developer">Developed By kmuvezwa</p>
    </div>
  </footer>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script src="./assets/js/jquery.min.js"></script> 
<script src="./assets/js/wow.min.js"></script> 
<script src="./assets/js/bootstrap.min.js"></script> 
<script src="./assets/js/slick.min.js"></script> 
<script src="./assets/js/jquery.li-scroller.1.0.js"></script> 
<script src="./assets/js/jquery.newsTicker.min.js"></script> 
<script src="./assets/js/jquery.fancybox.pack.js"></script> 
<script src="./assets/js/custom.js"></script>
</body>
</html>
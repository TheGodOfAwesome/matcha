<?php
// Start Session
session_start();

if(isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];
}

include './config/conn.php';
include './inc/uploadphoto.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ./index.php");
    exit();
}

$image=$_GET['image'];
$_SESSION['image'] = $image;

// check Delete request
if (!empty($_POST['btnDelete'])) {
  echo $_SESSION['image'] . "</br>" . "Test";
  $image_name = $_SESSION['image'];

  try {
    $stmt_deletephoto = $conn->prepare("DELETE FROM images WHERE image_name=:image_name AND image_creator=:image_creator");
    $stmt_deletephoto->bindValue(":image_name", $image_name);
    $stmt_deletephoto->bindValue(":image_creator", $name);
    $stmt_deletephoto->execute();
    header("Location: feed.php");
  } catch (PDOException $e) {
    echo "error: " . $e->getMessage();
  }


}

?>

<!DOCTYPE html>
<html>
<head>
<title>Matcha - Home</title>
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
              <li><a href="./feed.php">Home</a></li>
              <li><a href="./inc/logout.php">Logout</a></li>
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
                <div class="single_page">
                    <div class="single_page_content"> 
                        <?php
                            $image_url = $_SESSION['image'];
                            echo $image_url . "</br>";

                            echo '
                            <figure class="bsbig_fig  wow fadeInDown">
                                <img src="./images/user_images/' . $image_url . '" alt="">
                            </figure>';
                            
                            echo '</br>';

                            echo '
                            <form action="" method="POST" enctype="multipart/form-data">
                             <input type="submit" class="btn btn-primary" value="Delete Photo" name="btnDelete">
                            </form>';
                        ?>
                    </div>
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
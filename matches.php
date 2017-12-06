<?php

// Start Session
session_start();

if(isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];

}

include './config/conn.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ./index.php");
    exit();
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
              <li><a href="./message.php">Messages</a></li>
              <li><a href="./updateprofile.php">Edit Profile</a></li>
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
    <h2>Matches</h2>
    <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          <div style="height:350px; width:100%; overflow:hidden; border:10px solid;">
          </div>
          <div class="contact_area">
            <h2>Quick Match Search</h2>
                <form action="#" method="post" class="contact_form">
                    <div class="form-group">
                      <label for="">Sort By:</label>
                        <select name="sort">
                            <option value="">Sort By?</option>
                            <option value="age">Age</option>
                            <option value="location">Location</option>
                            <option value="popularity">Popularity</option>
                            <option value="interests">Interests</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="">Filter By Popularity:</label>
                        <select name="sort">
                            <option value="">Filter Popularity?</option>
                            <option value="newbie">Newbies</option>
                            <option value="wildcard">Wildcards</option>
                            <option value="coolkid">Cool Kids</option>
                            <option value="prom">Prom Kings/Queens</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="">Filter By:</label>
                        <select name="filter">
                            <option value="">Filter By?</option>
                            <option value="age">Age</option>
                            <option value="location">Location</option>
                            <option value="interests">Interests</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Range</label>
                        <input type="text" name="range" class="form-control" placeholder="Give range for above filter e.g. 5 for age range of ±5 yrs, Pretoria for Location & #hashtags for Interests."/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnSearch" class="btn btn-primary" value="Search"/>
                    </div>
                </form>
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
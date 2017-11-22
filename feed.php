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
<title>Matcha - Feed</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/animate.css">
<link rel="stylesheet" type="text/css" href="assets/css/font.css">
<link rel="stylesheet" type="text/css" href="assets/css/li-scroller.css">
<link rel="stylesheet" type="text/css" href="assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="assets/css/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.min.js"></script>
<script src="assets/js/respond.min.js"></script>
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
              <li><a href="#">Home</a></li>
              <li><a href="#">Messages</a></li>
              <li><a href="./updateprofile.php">Edit Profile</a></li>
              <li><a href="./inc/logout.php">Logout</a></li>
            </ul>
          </div>
          <div class="header_top_right">
            <p><a class="slider_tittle" href="./inc/logout.php" alt="logout">LOGOUT</a></p>
          </div>
        </div>
      </div>
    </div>
  </header>
  <section id="sliderSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="slick_slider">
          <div class="single_iteam"> <a href="pages/single_page.html"> <img src="images/slider_img4.jpg" alt=""></a>
            <div class="slider_article">
              <h2><a class="slider_tittle" href="pages/single_page.html">Fusce eu nulla semper porttitor felis sit amet</a></h2>
              <p>Nunc tincidunt, elit noon cursus euismod, lacus augue ornare metus, egestas imperdiet nulla nisl quis mauris. Suspendisse a pharetra urna. Morbi dui...</p>
            </div>
          </div>
          <div class="single_iteam"> <a href="pages/single_page.html"> <img src="images/slider_img2.jpg" alt=""></a>
            <div class="slider_article">
              <h2><a class="slider_tittle" href="pages/single_page.html">Fusce eu nulla semper porttitor felis sit amet</a></h2>
              <p>Nunc tincidunt, elit non cursus euismod, lacus augue ornare metus, egestas imperdiet nulla nisl quis mauris. Suspendisse a pharetra urna. Morbi dui...</p>
            </div>
          </div>
          <div class="single_iteam"> <a href="pages/single_page.html"> <img src="images/slider_img3.jpg" alt=""></a>
            <div class="slider_article">
              <h2><a class="slider_tittle" href="pages/single_page.html">Fusce eu nulla semper porttitor felis sit amet</a></h2>
              <p>Nunc tincidunt, elit non cursus euismod, lacus augue ornare metus, egestas imperdiet nulla nisl quis mauris. Suspendisse a pharetra urna. Morbi dui...</p>
            </div>
          </div>
          <div class="single_iteam"> <a href="pages/single_page.html"> <img src="images/slider_img1.jpg" alt=""></a>
            <div class="slider_article">
              <h2><a class="slider_tittle" href="pages/single_page.html">Fusce eu nulla semper porttitor felis sit amet</a></h2>
              <p>Nunc tincidunt, elit non cursus euismod, lacus augue ornare metus, egestas imperdiet nulla nisl quis mauris. Suspendisse a pharetra urna. Morbi dui...</p>
            </div>
          </div>
        </div>
            <div class="single_post_content_left">
              <ul class="business_catgnav">
                <?php
                  $path = "./images/user_images/";
                  $type = "profile";
                  $stmt_rating = $conn->prepare("SELECT * FROM users WHERE name=:name");
                  $stmt_rating->bindValue(":name", $name);
                  // initialise an array for the results 
                  $user = array();
                  if ($stmt_rating->execute()) {
                    while ($row = $stmt_rating->fetch(PDO::FETCH_ASSOC)) {
                      $user[] = $row;
                      $image_num = $row['rating'];
                      if ($image_num < 25) {
                        $image_rat = 1;
                        $rating_desc = "Newbie";
                      } else if ($image_num < 100) {
                        $image_rat = 2;
                        $rating_desc = "Wildcard";
                      } else if ($image_num < 1000) {
                        $image_rat = 3;
                        $rating_desc = "Cool Kid";
                      } else if ($image_num < 10000) {
                        $image_rat = 4;
                        $rating_desc = "Prom King/Queen";
                      }

                      echo
                      '<li>
                      <figure class="bsbig_fig  wow fadeInDown"> <a class="featured_img" href="./pages/ratingdesc' . $image_rat . '.php"> <img src="./images/rating/heart' . $image_rat . '.png" alt=""> <span class="overlay"></span> </a>
                        <figcaption> <a href="./pages/ratingdesc' . $image_rat . '.php"><h2> ' . $name . '\'s Rating </h2></a> </figcaption>
                        <p> ' . $rating_desc . ' </p>
                      </figure>
                      </li>';
                    }
                  }
                ?>
              </ul>
            </div>
            <div class="single_post_content_right">
              <ul class="spost_nav">
                <li>
                  <div class="media wow fadeInDown"> <a href="pages/ratingdesc1.php" class="media-left"> <img alt="" src="images/rating/heart1.png"> </a>
                    <div class="media-body"> <a href="pages/ratingdesc1.php" class="catg_title"> The Newbies</a> </div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> <a href="pages/ratingdesc2.php" class="media-left"> <img alt="" src="images/rating/heart2.png"> </a>
                    <div class="media-body"> <a href="pages/ratingdesc2.php" class="catg_title"> The Wildcard</a> </div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> <a href="pages/ratingdesc3.php" class="media-left"> <img alt="" src="images/rating/heart3.png"> </a>
                    <div class="media-body"> <a href="pages/ratingdesc3.php" class="catg_title"> The Cool Kids</a> </div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> <a href="pages/ratingdesc4.php" class="media-left"> <img alt="" src="images/rating/heart4.png"> </a>
                    <div class="media-body"> <a href="pages/ratingdesc4.php" class="catg_title"> The Prom King/Queen</a> </div>
                  </div>
                </li>
              </ul>
            </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="single_sidebar wow fadeInDown">
            <h2><span>Me</span></h2>
            <?php
              $path = "./images/user_images/";
              $type = "profile";
              $stmt_profilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
              $stmt_profilephoto->bindValue(":image_creator", $name);
              $stmt_profilephoto->bindValue(":image_type", $type);
              // initialise an array for the results 
              $user_image = array();
              if ($stmt_profilephoto->execute()) {
                while ($row = $stmt_profilephoto->fetch(PDO::FETCH_ASSOC)) {
                  $user_image[] = $row;
                  $image_url = $row['image_name'];
                  $url = $path . $image_url;
                  //echo '<li><img src="./images/' . $image_url . '" width="150" height="150"></li>';
                  echo '<a class="sideAdd" href="#"><img src=" ' . $url . '" alt=""></a>';
                }
              }
            ?>

        </div>
        <div class="latest_post">
          <h2><span>Latest Matches</span></h2>
          <div class="latest_post_container">
            <div id="prev-button"><i class="fa fa-chevron-up"></i></div>
            <ul class="latest_postnav">
              <li>
                <div class="media"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 1</a> </div>
                </div>
              </li>
              <li>
                <div class="media"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 2</a> </div>
                </div>
              </li>
              <li>
                <div class="media"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 3</a> </div>
                </div>
              </li>
              <li>
                <div class="media"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 4</a> </div>
                </div>
              </li>
              <li>
                <div class="media"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 2</a> </div>
                </div>
              </li>
            </ul>
            <div id="next-button"><i class="fa  fa-chevron-down"></i></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="contentSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
            
          <div class="single_post_content">
            <h2><span>My Photos</span></h2>
            <ul class="photograph_nav  wow fadeInDown">
            <?php
              $path = "./images/user_images/";
              $type = "profile";
              $stmt_profilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator ORDER BY image_timestamp DESC");
              $stmt_profilephoto->bindValue(":image_creator", $name);
              // initialise an array for the results 
              $user_image = array();
              if ($stmt_profilephoto->execute()) {
                while ($row = $stmt_profilephoto->fetch(PDO::FETCH_ASSOC)) {
                  $user_image[] = $row;
                  $image = $row['image_name'];
                  $image_url = $row['image_name'] . "?image=" . $image;
                  $url = $path . $image_url;
                  echo '<li><div class="photo_grid">
                  <figure class="effect-layla"> <a class="fancybox-buttons" data-fancybox-group="button" href=" ' . $url . ' " title=" ' . $image . ' "> <img src=" ' . $url . ' " alt=""/></a> </figure>
                  </div></li>';
                }
              }
            ?>
            </ul>
          </div>
        </div>
      </div>
       
      <div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2><span>Messages</span></h2>
            <ul class="spost_nav">
              <li>
                <div class="media wow fadeInDown"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 1</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img2.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 2</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 3</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="pages/single_page.html" class="media-left"> <img alt="" src="images/post_img2.jpg"> </a>
                  <div class="media-body"> <a href="pages/single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 4</a> </div>
                </div>
              </li>
            </ul>
          </div>
        </aside>
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
<script src="assets/js/jquery.min.js"></script> 
<script src="assets/js/wow.min.js"></script> 
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/jquery.li-scroller.1.0.js"></script> 
<script src="assets/js/jquery.newsTicker.min.js"></script> 
<script src="assets/js/jquery.fancybox.pack.js"></script> 
<script src="assets/js/custom.js"></script>
</body>
</html>
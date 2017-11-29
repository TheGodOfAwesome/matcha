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

  $profile=$_GET['profile'];
  if($profile == "")
  {
    header("Location: ./feed.php");
    exit();
  } else {
    $_SESSION['profile'] = $profile;
  }

  $path = "./images/user_images/";
  $type = "profile";
  $stmt_profile = $conn->prepare("SELECT * FROM users WHERE name=:name");
  $stmt_profile->bindValue(":name", $profile);
  // initialise an array for the results 
  $user = array();
  if ($stmt_profile->execute()) {
    while ($row = $stmt_profile->fetch(PDO::FETCH_ASSOC)) {
      $user[] = $row;
      $full_name = $row['fullname'];
      $user_name = $row['name'];
      $user_dob = $row['dateofbirth'];
      $user_bio = $row['bio'];
      $user_gender = $row['gender'];
      $user_preference = $row['preference'];
      $user_city = $row['city'];
      $user_country = $row['country'];
      $user_loginstatus = $row['loginstatus'];
      $user_lastseen = $row['lastseen'];

      if ($user_bio == ""){
        $user_bio = 'They haven\'t updated their bio yet!';
      }
      if ($user_city == ""){
        $user_city = 'They haven\'t updated their city yet!';
      }
      if ($user_country == ""){
        $user_country = 'They haven\'t updated their country yet!';
      }
      if ($user_dob == ""){
        $user_age = 'Please update your profile and add a date of birth by clicking this <a href="./updateprofile.php">link</a>!';
      } else {
        //date is in yyyy/mm/dd format;
        //explode the date to get month, day and year
        $birthDate = explode("-", $user_dob);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));
        $user_age = $age;
      }

    }
  }

?>


<!DOCTYPE html>
<html>
<head>
<title>Matcha - Profiles</title>
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
              <li><a href="./feed.php">Home</a></li>
              <li><a href="#">Matches</a></li>
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
  <section id="contentSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="single_post_content">
          <h2><span>Profile</span></h2>
            <div class="single_post_content_left">
              <ul class="business_catgnav  wow fadeInDown">
              <li>   
              <figure class="#"> <a class="sideAdd" href="#">

                <?php
                  $path = "./images/user_images/";
                  $type = "profile";
                  $stmt_profilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                  $stmt_profilephoto->bindValue(":image_creator", $profile);
                  $stmt_profilephoto->bindValue(":image_type", $type);
                  // initialise an array for the results 
                  $user_image = array();
                  if ($stmt_profilephoto->execute()) {
                    while ($row = $stmt_profilephoto->fetch(PDO::FETCH_ASSOC)) {
                      $user_image[] = $row;
                      $image_url = $row['image_name'];
                      $url = $path . $image_url;
                      echo '<img src=" ' . $url . '" alt="">';
                    }
                  }
                ?>

                <span class="overlay"></span> </a>

                <?php
                
                      echo'<figcaption><h3>' . $full_name . ' : ' . $user_name . ' </h3></figcaption>
                      <p><h4>Bio : ' . $user_bio .' </h4></p>
                      </figure>';
                   
                ?>

                </li>
              </ul>
            </div>
            <div class="single_post_content_right">
              <ul class="spost_nav">
                <li>
                  <div class="media wow fadeInDown"> 
                    <div class="media-body"><h2> Gender: <?php echo $user_gender; ?> </h2></div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> 
                    <div class="media-body"><h2> Preference: <?php echo $user_preference; ?> </h2></div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> 
                    <div class="media-body"><h2> City: <?php echo $user_city; ?> </h2></div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> 
                    <div class="media-body"><h2> Country: <?php echo $user_country; ?> </h2></div>
                  </div>
                </li>
                <li>
                  <div class="media wow fadeInDown"> 
                    <div class="media-body"><h2> Age: <?php echo $user_age; ?> </h2></div>
                  </div>
                </li>
              </ul>
            </div>
        </div>
            <div class="single_post_content_left">
              <ul class="business_catgnav">
                <?php
                  $path = "./images/user_images/";
                  $type = "profile";
                  $stmt_rating = $conn->prepare("SELECT * FROM users WHERE name=:name");
                  $stmt_rating->bindValue(":name", $profile);
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
                        <figcaption> <a href="./pages/ratingdesc' . $image_rat . '.php"><h2> ' . $profile . '\'s Rating </h2></a> </figcaption>
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
            <?php
              echo "<h2><span>" .  $user_name . "</span></h2>";
              $path = "./images/user_images/";
              $type = "profile";
              $stmt_profilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
              $stmt_profilephoto->bindValue(":image_creator", $profile);
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
          <h2><span>Profiles Similar To <?php echo $user_name; ?> </span></h2>
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
              $stmt_photo = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator ORDER BY image_timestamp DESC");
              $stmt_photo->bindValue(":image_creator", $profile);
              // initialise an array for the results 
              $user_image = array();
              if ($stmt_photo->execute()) {
                while ($row = $stmt_photo->fetch(PDO::FETCH_ASSOC)) {
                  $user_image[] = $row;
                  $image = $row['image_name'];
                  $image_url = $row['image_name'] . "?image=" . $image;
                  $url = $path . $image_url;
                  $link = "./imageviews.php?image=" . $image;
                  echo '<li><div class="photo_grid">
                  <figure class="effect-layla"> <a href=" ' . $link . ' " title=" ' . $image . ' "> <img src=" ' . $url . ' " alt=""/></a> </figure>
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
            <h2><span>Interactions</span></h2>
            <ul class="spost_nav">
              <li>
                <div class="media wow fadeInDown"> 
                  <?php echo '<div class="media-body"><a href="./inc/like.php?profile='.$profile.'" class="catg_title"><h2> Like '.$profile .'\'s user profile!</h2></a></div>'; ?>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> 
                  <?php
                    if ($user_loginstatus == "loggedout"){
                      echo '<div class="media-body"><h2> Login Status: Logged Out </BR> </BR> Last Seen: '. $user_lastseen .'.</h2></div>';    
                    }else{
                      echo '<div class="media-body"><h2> Login Status: Logged In </h2></div>'; 
                    }
                   ?>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> 
                  <?php echo '<div class="media-body"><a href="./inc/block.php?profile='.$profile.'" class="catg_title"><h2> Block '.$profile .'\'s user profile!</h2></a></div>'; ?> 
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> 
                  <?php echo '<div class="media-body"><a href="./inc/report.php?profile='.$profile.'" class="catg_title"><h2> Report '.$profile .'\'s as a fake user profile!</h2></a></div>'; ?> 
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
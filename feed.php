<?php

  // Start Session
  session_start();
  
  if(isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];
  }

  include './config/conn.php';
  include './inc/uploadphotos.php';

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
              <li><a href="./matches.php">Matches</a></li>
              <li><a href="./message.php">Messages</a></li>
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
                  $stmt_profilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp ASC");
                  $stmt_profilephoto->bindValue(":image_creator", $name);
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
                  $path = "./images/user_images/";
                  $type = "profile";
                  $stmt_rating = $conn->prepare("SELECT * FROM users WHERE name=:name");
                  $stmt_rating->bindValue(":name", $name);
                  // initialise an array for the results 
                  $user = array();
                  if ($stmt_rating->execute()) {
                    while ($row = $stmt_rating->fetch(PDO::FETCH_ASSOC)) {
                      $user[] = $row;
                      $full_name = $row['fullname'];
                      $user_name = $row['name'];
                      $user_dob = $row['dateofbirth'];
                      $user_bio = $row['bio'];
                      $user_gender = $row['gender'];
                      $user_preference = $row['preference'];
                      $user_city = $row['city'];
                      $user_country = $row['country'];

                      if ($user_bio == ""){
                        $user_bio = 'Please update your profile and add a <a href="./updateprofile.php">bio</a>!';
                      }
                      if ($user_city == ""){
                        $user_city = 'Please update your profile and add a <a href="./updateprofile.php">city</a>!';
                      }
                      if ($user_country == ""){
                        $user_country = 'Please update your profile and add a <a href="./updateprofile.php">country</a>!';
                      }
                      if ($user_dob == ""){
                        $user_age = 'Please update your profile and add a <a href="./updateprofile.php">date of birth</a>!';
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

                      

                      echo'<figcaption><h3>' . $full_name . ' : ' . $user_name . ' </h3></figcaption>
                      <p><h4>Bio : ' . $user_bio .' </h4></p>
                      </figure>';
                    }
                  }
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
          <h2><span>Notifications</span></h2>
          <div class="latest_post_container">
            <div id="prev-button"><i class="fa fa-chevron-up"></i></div>
            <ul class="latest_postnav">
              <?php
                $log_action_result = 0;
                $stmt_log = $conn->prepare("SELECT * FROM log WHERE log_action_recipient_name=:log_action_recipient_name AND log_action_result=:log_action_result ORDER BY log_timestamp DESC");
                $stmt_log->bindValue(":log_action_recipient_name", $name);
                $stmt_log->bindValue(":log_action_result", $log_action_result);
                if ($stmt_log->execute()) {
                  while ($row = $stmt_log->fetch(PDO::FETCH_ASSOC)) {
                    $log_id = $row['log_id'];
                    $log_action = $row['log_action'];
                    $log_creator = $row['log_user_name'];

                    /******************************************************************************** */

                    $path = "./images/user_images/";
                    $type = "profile";
                    $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                    $stmt_notificationprofilephoto->bindValue(":image_creator", $log_creator);
                    $stmt_notificationprofilephoto->bindValue(":image_type", $type);
                    // initialise an array for the results 
                    $user_image = array();
                    if ($stmt_notificationprofilephoto->execute()) {
                      while ($row = $stmt_notificationprofilephoto->fetch(PDO::FETCH_ASSOC)) {
                        $user_image[] = $row;
                        $image_url = $row['image_name'];
                        $url = $path . $image_url;
                      }
                    }

                    /******************************************************************************** */

                    If ($log_action == "like") {
                      $counter_action = "like back";
                      echo '
                      <li>
                        <div class="media"> <a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="./profile.php?profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked your profile!</a></h6><a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" ><h2>Like ' . $log_creator . ' Back?</h2></a></div>
                        </div>
                      </li>';
                    } else if ($log_action == "likeback") {
                      $counter_action = "message";
                      echo '
                      <li>
                        <div class="media"> <a href="inc/message.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="inc/message.php?profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked you back!</h6><h2>Message ' . $log_creator . '</h2></a> </div>
                        </div>
                      </li>';
                    } else if ($log_action == "message") {
                      $counter_action = "message";
                      echo '
                      <li>
                        <div class="media"> <a href="inc/message.php?profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="inc/message.php?profile=' . $log_creator . '" class="catg_title">Message ' . $log_creator . ' Back</a> </div>
                        </div>
                      </li>';
                    }
                  }
                }
              ?>
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
              $stmt_photo->bindValue(":image_creator", $name);
              // initialise an array for the results 
              $user_image = array();
              if ($stmt_photo->execute()) {
                while ($row = $stmt_photo->fetch(PDO::FETCH_ASSOC)) {
                  $user_image[] = $row;
                  $image = $row['image_name'];
                  $image_type = $row['image_type'];
                  $image_url = $row['image_name'] . "?image=" . $image;
                  $url = $path . $image_url;
                  $link = "./imageview.php?image=" . $image;
                  $links = "./imageviews.php?image=" . $image;

                  if ($image_type == "profile"){
                    echo '<li><div class="photo_grid">
                    <figure class="effect-layla"> <a href=" ' . $links . ' " title=" ' . $image . ' "> <img src=" ' . $url . ' " alt=""/></a> </figure>
                    </div></li>';
                  } else {
                    echo '<li><div class="photo_grid">
                    <figure class="effect-layla"> <a href=" ' . $link . ' " title=" ' . $image . ' "> <img src=" ' . $url . ' " alt=""/></a> </figure>
                    </div></li>';
                  }
                }
              }
            ?>
            <li>
                <form action="" method="POST" enctype="multipart/form-data">
                     <b>Select new gallery picture:</b>
                    <input type="file" class="btn btn-primary" name="fileToUpload" id="fileToUpload">
                    <input type="submit" class="btn btn-primary" value="Upload Profile Photo" name="submit">
                </form>
            </li>
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
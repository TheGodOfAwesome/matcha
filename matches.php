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
                        <div class="media"> <a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="./profile.php?profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked your profile!</a></h6><a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" ><h2>Like ' . $log_creator . ' Back?</h2></a></div>
                        </div>
                        ';
                    } else if ($log_action == "likeback") {
                      $counter_action = "message";
                      echo '
                        <div class="media"> <a href="inc/message.php?profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="inc/message.php?profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked you back!</h6><h2>Message ' . $log_creator . '</h2></a> </div>
                        </div>
                          ';
                    } else if ($log_action == "messaged") {
                      $counter_action = "message";
                      echo '
                        <div class="media"> <a href="inc/message.php?profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="inc/message.php?profile=' . $log_creator . '" class="catg_title">Message ' . $log_creator . ' Back</a> </div>
                        </div>
                          ';
                    }
                  }
                }
              ?>

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
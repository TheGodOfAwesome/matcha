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

$path = "./images/user_images/";
$type = "profile";
$stmt_profile = $conn->prepare("SELECT * FROM users WHERE name=:name");
$stmt_profile->bindValue(":name", $name);
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
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Matcha - Matches</title>
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
              <li><div id="msg"><?php include './inc/newmsgs.php';?></div></li>
              <li><a href="./updateprofile.php">Edit Profile</a></li>
              <li><a href="./notifications.php"><div id="note"><?php include './inc/note.php';?></div></a></li>
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
          <div style="height:350px; width:100%; overflow: auto; border:10px solid;">

          <?php
            $sortby = "";
            $filtpop = "";
            $city = "";
            $interests = "";
            $age = "";
            $sortby = $_GET['sortby'];
            $filtpop = $_GET['filtpop'];
            $city = $_GET['city'];
            $interests = $_GET['interests'];
            $age = $_GET['age'];
            //if(!($_GET['sortby'] == "" && $_GET['filtpop'] == "" && $_GET['city'] == "" && $_GET['interests'] == "" && $_GET['age'] == ""))
            if(!($sortby == "" && $filtpop == "" && $city == "" && $interests == "" && $age == ""))
            {
              $sortby = $_GET['sortby'];
              $filtpop = $_GET['filtpop'];
              $city = $_GET['city'];
              $interests = $_GET['interests'];
              $age = $_GET['age'];
              if ($sortby == ""){
                //echo "No sort </br>";
                //echo "sort:" . $sortby . "</br>";
                //echo "filtpop:" . $filtpop . "</br>";
                //echo "city:" . $city . "</br>";
                //echo "interests:" . $interests . "</br>";
                //echo "age:" . $age . "</br>";
                $stmt_matches = $conn->prepare("SELECT * FROM users WHERE country=:country");
                $stmt_matches->bindValue(":country", $user_country);
              } elseif($sortby !== ""){
                //echo "Sort </br>";
                //echo "sort:" . $sortby . "</br>";
                //echo "filtpop:" . $filtpop . "</br>";
                //echo "city:" . $city . "</br>";
                //echo "interests:" . $interests . "</br>";
                //echo "age:" . $age . "</br>";
                if($sortby == "dateofbirth"){
                  $stmt_matches = $conn->prepare("SELECT * FROM users WHERE country=:country ORDER BY dateofbirth");
                }else if($sortby == "city"){
                  $stmt_matches = $conn->prepare("SELECT * FROM users WHERE country=:country ORDER BY city");
                }else{
                  $stmt_matches = $conn->prepare("SELECT * FROM users WHERE country=:country ORDER BY rating");
                }
                $stmt_matches->bindValue(":country", $user_country);
              } else {
                $stmt_matches = $conn->prepare("SELECT * FROM users WHERE country=:country");
                $stmt_matches->bindValue(":country", $user_country);
              }

              if ($stmt_matches->execute()) {
              while ($row = $stmt_matches->fetch(PDO::FETCH_ASSOC)) {
                $match_id = $row['id'];
                $matchfull_name = $row['fullname'];
                $match_name = $row['name'];
                $match_dob = $row['dateofbirth'];
                $match_bio = $row['bio'];
                $match_rating = $row['rating'];
                $match_gender = $row['gender'];
                $match_interests = $row['interests'];
                $match_preference = $row['preference'];
                $match_city = $row['city'];
                $match_country = $row['country'];
                $match_loginstatus = $row['loginstatus'];
                $match_lastseen = $row['lastseen'];
  
                      $path = "./images/user_images/";
                      $type = "profile";
                      $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                      $stmt_notificationprofilephoto->bindValue(":image_creator", $match_name);
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
                      if ($match_dob != ""){
                        $birthDate = explode("-", $match_dob);
                        $user_age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                        ? ((date("Y") - $birthDate[0]) - 1)
                        : (date("Y") - $birthDate[0]));
                      }
                      if ($filtpop != ""){
                        if ($filtpop == "newbie") {
                          $user_rating = 0;
                          $user_rating1 = 25;
                        } else if ($filtpop = "wildcard") {
                          $user_rating = 25;
                          $user_rating1 = 100;
                        } else if ($filtpop = "coolkid") {
                          $user_rating = 100;
                          $user_rating1 = 1000;
                        } else if ($filtpop = "prom") {
                          $user_rating = 1000;
                          $user_rating1 = 10000;
                        }
                      }

                      //strstr($the_string, $the_word)
                      //strstr($match_interests, $interests)
                      //strpos($interests, $match_interests)
                      $pos = strpos($match_interests, $interests);

                      if ($filtpop != "" && (($user_rating > $match_rating) && ($match_rating < $user_rating1))){
                        $search_pop = '%' . $filtpop . '%';
                      } else if ($city != "" && $match_city != $city){
                        $search_city = '%' . $city . '%';
                      } else if ($interests != "" && !($pos !== FALSE)){
                        $search_interests = '%' . $interests . '%';
                      } else if ($age != "" && $user_age != $age){
                        $search_age = '%' . $age . '%';
                      } else {
                        if ($user_preference == "undefined"){
                          echo '
                          <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                            <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                          </div>
                          ';
                        }else{
                          if ($user_preference == $match_gender && ($match_preference == "undefined" || $match_preference == $user_gender)){
                            echo '
                            <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                              <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                            </div>
                            ';
                          }
                        }
                      }
                    }
                }
              
            }else{

              $stmt_matches = $conn->prepare("SELECT * FROM users WHERE city=:city AND country=:country");
              $stmt_matches->bindValue(":city", $user_city);
              $stmt_matches->bindValue(":country", $user_country);
              if ($stmt_matches->execute()) {
              while ($row = $stmt_matches->fetch(PDO::FETCH_ASSOC)) {
                $match_id = $row['id'];
                $matchfull_name = $row['fullname'];
                $match_name = $row['name'];
                $match_dob = $row['dateofbirth'];
                $match_bio = $row['bio'];
                $match_gender = $row['gender'];
                $match_preference = $row['preference'];
                $match_city = $row['city'];
                $match_country = $row['country'];
                $match_loginstatus = $row['loginstatus'];
                $match_lastseen = $row['lastseen'];
  
                      $path = "./images/user_images/";
                      $type = "profile";
                      $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                      $stmt_notificationprofilephoto->bindValue(":image_creator", $match_name);
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
  
                      if ($match_dob != ""){
                        //date is in yyyy/mm/dd format;
                        //explode the date to get month, day and year
                        $birthDate = explode("-", $user_dob);
                        //get age from date or birthdate
                        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                        ? ((date("Y") - $birthDate[0]) - 1)
                        : (date("Y") - $birthDate[0]));
                        $user_age = $age;
                      }
                      
                      if ($match_name == $name){
  
                      } else if ($match_city != $user_city){
  
                      } else if ($match_country != $user_country){
  
                      }

                      else {
                        if ($user_preference == "undefined"){
                          echo '
                          <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                            <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                          </div>
                          ';
                        }else{
                          if ($user_preference == $match_gender && ($match_preference == "undefined" || $match_preference == $user_gender)){
                            echo '
                            <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                              <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                            </div>
                            ';
                          }
                        }
                    }
                  }
                }

            }
            ?>

          </div>
          <div class="contact_area">
            <h2>Quick Match Search</h2>
                <form action="search.php" method="post" class="contact_form">
                    <div class="form-group">
                      <label for="">Sort By:</label>
                        <select name="sort">
                            <option value="">Sort By?</option>
                            <option value="dateofbirth">Age</option>
                            <option value="city">Location</option>
                            <option value="rating">Popularity</option>
                        </select>
                    </div>
                    <p><b>Filter Search:</b></p>
                    <div class="form-group">
                      <label for="">Filter By Popularity:</label>
                        <select name="filtpop">
                            <option value="">Filter By Popularity?</option>
                            <option value="newbie">Newbies</option>
                            <option value="wildcard">Wildcards</option>
                            <option value="coolkid">Cool Kids</option>
                            <option value="prom">Prom Kings/Queens</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Enter prefered city."/>
                    </div>
                    <div class="form-group">
                        <label for="">Interests</label>
                        <input type="text" name="interests" class="form-control" placeholder="Enter prefered interests."/>
                    </div>
                    <div class="form-group">
                        <label for="">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="Enter prefered age."/>
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
<script>
  setInterval(function(){
    $('#msg').load("./inc/newmsgs.php").fadeIn("slow");
    $('#note').load("./inc/notify.php").fadeIn("slow");
  }, 8000);
</script>
</body>
</html>
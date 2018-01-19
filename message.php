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
<title>Matcha - Message</title>
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
              <li><a href="./matches.php">Matches</a></li>
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
    <h2>Chats</h2>
    <div style="height:1000px; width:100%; overflow:auto; border:10px solid;">

      <?php  

          $log_action_result = 0;
          $stmt_log = $conn->prepare("SELECT * FROM log WHERE log_action_recipient_name=:log_action_recipient_name OR  
          log_user_name=:log_user_name
          ORDER BY log_timestamp DESC");
          $stmt_log->bindValue(":log_action_recipient_name", $name);
          $stmt_log->bindValue(":log_user_name", $name);
          if ($stmt_log->execute()) {
            while ($row = $stmt_log->fetch(PDO::FETCH_ASSOC)) {
              $log_id = $row['log_id'];
              $log_action = $row['log_action'];
              $log_creator = $row['log_user_name'];
              $log_action_recipient_name = $row['log_action_recipient_name'];

                  //echo $log_id . '</br>';
                  //echo $log_action . '</br>';
                  //echo $log_creator . '</br>';
                  //echo $log_action_recipient_name . '</br>';

                    /******************************************************************************** */

                    

                    /******************************************************************************** */

                    if ($log_action == "likeback" && $log_creator != $name ) {
                      
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
                      
                      echo '
                        <div class="media"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . '</h6><h2>Message ' . $log_creator . '</h2></a></div>
                        </div>
                          ';
                    }else if ($log_action == "likeback") {
                      
                      $path = "./images/user_images/";
                      $type = "profile";
                      $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                      $stmt_notificationprofilephoto->bindValue(":image_creator", $log_action_recipient_name);
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

                      echo '
                        <div class="media"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_action_recipient_name. '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                          <div class="media-body"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_action_recipient_name . '" class="catg_title"><h6>' . $log_action_recipient_name . '</h6><h2>Message ' . $log_action_recipient_name . '</h2></a></div>
                        </div>
                          ';
                    }

                  }
                }
              ?>

    </div>
  </section>
  <p>&nbsp</p>

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
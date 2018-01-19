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
<title>Matcha - New Messages</title>
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
              <li><a href="./matches.php">Matches</a></li>
              <li><a href="./updateprofile.php">Edit Profile</a></li>
              <li><a href="./notifications.php">Notifications</a></li>
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
    <h2>New Messages</h2>
    <div id="message_view" style="height:600px; width:100%; overflow:auto; border:10px solid;">
        <?php  
          
          $stmt_messages = $conn->prepare("SELECT * FROM messages WHERE message_sender_name=:message_sender_name OR  
          message_recepient_name=:message_recepient_name
          ORDER BY message_id DESC");
          $stmt_messages->bindValue(":message_sender_name", $name);
          $stmt_messages->bindValue(":message_recepient_name", $name);
          if ($stmt_messages->execute()) {
            while ($row = $stmt_messages->fetch(PDO::FETCH_ASSOC)) {
              $message_id = $row['message_id'];
              $message_read = $row['chat_id'];
              $message_text = $row['message_text'];
              $message_timestamp = $row['message_timestamp'];
              $message_sender_name = $row['message_sender_name'];
              $message_recepient_name = $row['message_recepient_name'];

              if ($message_sender_name != $name && $message_read == 0) {
                
                $path = "./images/user_images/";
                $type = "profile";
                $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                $stmt_notificationprofilephoto->bindValue(":image_creator", $message_sender_name);
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
                  <div class="media wow fadeInDown"> <a href="./messages.php?id=' . $message_id . '&profile=' . $message_sender_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                    <div class="media-body"> <a href="./messages.php?id=' . $message_id . '&profile=' . $message_sender_name . '" class="catg_title"><h6>' . $message_sender_name . ' Messaged you!</h6><h2>Message ' . $message_sender_name . '</h2></a> </div>
                  </div>
                ';
              }
            }
          }

        ?>
    </div>
  <p>&nbsp</p>
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
    $('#message_view').load("./inc/fetchnewmsgs.php").fadeIn("slow");
  }, 8000);
</script>
</body>
</html>
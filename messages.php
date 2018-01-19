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

/*if ($_GET['profile'] == "" &&  $_GET['id'] == "") {
  header("Location: ./feed.php");
  exit();
} else {*/
  $profile=$_GET['profile'];
  $id=$_GET['id'];
//}
$_SESSION['message_profile'] = $profile;

// check Send Message request
if (!empty($_POST['btnMessage'])) {
  $message = $_POST['message'];
  $profile = $_POST['profile'];
  $id = $_POST['id'];
  if ($message == "") {
    $message_error_message = 'Message field is required!';
    echo $register_error_message . "<br>";
  } else {
    try {
      $stmt = $conn->prepare("INSERT INTO messages(message_sender_name, message_recepient_name, message_text) 
      VALUES(:message_sender_name, :message_recepient_name, :message_text)");
      $stmt->bindParam(':message_sender_name', $name);
      $stmt->bindParam(':message_recepient_name', $profile);
      $stmt->bindParam(':message_text', $message);
      $stmt->execute();
    } catch (PDOException $e) {
      echo "error: " . $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    header("Location: ./messages.php?id=" . $id . "&profile=" . $profile);
    exit();
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Matcha - Messages</title>
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
    <h2>Messages</h2>
    <div id="message_view" style="height:500px; width:100%; overflow:auto; border:10px solid;">
        <?php  
          $stmt_messages = $conn->prepare("SELECT * FROM messages WHERE message_sender_name=:message_sender_name OR  
          message_recepient_name=:message_recepient_name
          ORDER BY message_id DESC");
          $stmt_messages->bindValue(":message_sender_name", $name);
          $stmt_messages->bindValue(":message_recepient_name", $name);
          if ($stmt_messages->execute()) {
            while ($row = $stmt_messages->fetch(PDO::FETCH_ASSOC)) {
              $message_id = $row['message_id'];
              $message_text = $row['message_text'];
              $message_timestamp = $row['message_timestamp'];
              $message_sender_name = $row['message_sender_name'];
              $message_recepient_name = $row['message_recepient_name'];

              if ($message_sender_name == $name && $message_recepient_name == $profile) {
                      
                $path = "./images/user_images/";
                $type = "profile";
                $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                $stmt_notificationprofilephoto->bindValue(":image_creator", $name);
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
                  <div class="media"> <a href="" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                    <div class="media-body"></h6>' . $message_text . '</h2></div>
                  </div>
                ';
              }else if ($message_sender_name == $profile) {
                
                $path = "./images/user_images/";
                $type = "profile";
                $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                $stmt_notificationprofilephoto->bindValue(":image_creator", $profile);
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
                
                $read = 1;
                $stmt_read = $conn->prepare("UPDATE messages SET chat_id=:chat_id
                WHERE message_id=:message_id");
                $stmt_read->bindParam(':chat_id', $read);
                $stmt_read->bindParam(':message_id', $message_id);
                $stmt_read->execute();

                echo '
                  <div class="media"> <a href="" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                    <div class="media-body"></h6>' . $message_text . '</h6></div>
                  </div>
                ';
              }
            }
          }
        ?>
    </div>
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          <div class="contact_area">
            <?php  
              echo '  
                <form action="./messages.php?id=' . $id . '&profile=' . $profile . '" method="post">
                    <div class="form-group">
                        <label for="">Message</label>
                        <textarea class="form-control" name="message" cols="30" rows="5" placeholder="Write your mesage here..."></textarea>
                    </div>
                    <input type="hidden" name="id" value="' . $id . '" />
                    <input type="hidden" name="profile" value="' . $profile . '" />
                    <div class="form-group">
                        <input type="submit" name="btnMessage" class="btn btn-primary" value="Send"/>
                    </div>
                </form>
              ';
            ?>
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
<script>
  setInterval(function(){
    $('#message_view').load("./inc/fetchconvo.php").fadeIn("slow");
  }, 8000);
</script>
</body>
</html>
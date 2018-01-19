<?php

// Start Session
session_start();
  
if(isset($_SESSION['email'])) {
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $status = $_SESSION['status'];
}

include '../config/conn.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ../index.php");
    exit();
}

$read = 0;
$stmt_messages = $conn->prepare("SELECT * FROM messages WHERE message_sender_name=:message_sender_name OR  
message_recepient_name=:message_recepient_name
ORDER BY message_timestamp DESC LIMIT 4");
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
                <li>
                  <div class="media wow fadeInDown"> <a href="./messages.php?id=' . $message_id . '&profile=' . $message_sender_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                    <div class="media-body"> <a href="./messages.php?id=' . $message_id . '&profile=' . $message_sender_name . '" class="catg_title"><h6>' . $message_sender_name . ' Messaged you!</h6><h2>Message ' . $message_sender_name . '</h2></a> </div>
                  </div>
                </li>
            ';
        }
    }
}

?>
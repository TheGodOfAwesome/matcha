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

$count = 0;
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
            $count = $count + 1;           
        }
    }
    if ($count != 0){
        echo '<a href="./newmessages.php"><font color="blue">New MESSAGES</font></a>';
    }else{
        echo '<a href="./message.php">MESSAGES</a>';
    }
}

?>
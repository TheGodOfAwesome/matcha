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

if ($_GET['profile'] == "") {
    header("Location: ../feed.php");
    exit();
} else {
    $profile=$_GET['profile'];
    $log_description = "block";
    $log_action = "block";
    $stmt_block = $conn->prepare("INSERT INTO log(log_user_name, log_description, log_action, log_action_recipient_name) 
    VALUES(:log_user_name, :log_description, :log_action, :log_action_recipient_name)");
    $stmt_block->bindParam(':log_user_name', $name);
    $stmt_block->bindParam(':log_description', $log_description);
    $stmt_block->bindParam(':log_action', $log_action);
    $stmt_block->bindParam(':log_action_recipient_name', $profile);
    $stmt_block->execute();

    $stmt_removeactions = $conn->prepare("DELETE FROM log WHERE log_user_name=:log_user_name
    AND log_action_recipient_name=:log_action_recipient_name;");
    $stmt_removeactions->bindParam(':log_user_name', $name);
    $stmt_removeactions->bindParam(':log_action_recipient_name', $profile);
    $stmt_removeactions->execute();

    $stmt_removeactions = $conn->prepare("DELETE FROM log WHERE log_user_name=:log_user_name
    AND log_action_recipient_name=:log_action_recipient_name;");
    $stmt_removeactions->bindParam(':log_user_name', $profile);
    $stmt_removeactions->bindParam(':log_action_recipient_name', $name);
    $stmt_removeactions->execute();

    $stmt_removemessages = $conn->prepare("DELETE FROM messages WHERE message_sender_name=:message_sender_name
    AND message_recepient_name=:message_recepient_name;");
    $stmt_removemessages->bindParam(':message_sender_name', $profile);
    $stmt_removemessages->bindParam(':message_recepient_name', $name);
    $stmt_removemessages->execute();

    $stmt_removemessages = $conn->prepare("DELETE FROM messages WHERE message_sender_name=:message_sender_name
    AND message_recepient_name=:message_recepient_name;");
    $stmt_removemessages->bindParam(':message_sender_name', $name);
    $stmt_removemessages->bindParam(':message_recepient_name', $profile);
    $stmt_removemessages->execute();

    header("Location: ../feed.php");
    exit();
}

?>
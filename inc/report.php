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
    $log_description = "report";
    $log_action = "report";
    $stmt_like = $conn->prepare("INSERT INTO log(log_user_name, log_description, log_action, log_action_recipient_name) 
    VALUES(:log_user_name, :log_description, :log_action, :log_action_recipient_name)");
    $stmt_like->bindParam(':log_user_name', $name);
    $stmt_like->bindParam(':log_description', $log_description);
    $stmt_like->bindParam(':log_action', $log_action);
    $stmt_like->bindParam(':log_action_recipient_name', $profile);
    $stmt_like->execute();

    header("Location: ../feed.php");
    exit();
}

?>
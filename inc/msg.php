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

if ($_GET['profile'] == "" &&  $_GET['id'] == "") {
    header("Location: ../feed.php");
    exit();
} else {
    $profile=$_GET['profile'];
    $log_id=$_GET['id'];
    $log_description = "message";
    $log_action = "message";
    $log_action_result = 1;
    $stmt_msg = $conn->prepare("UPDATE log SET log_action_result=:log_action_result
    WHERE log_id=:log_id;");
    $stmt_msg->bindParam(':log_id', $log_id);
    $stmt_msg->bindParam(':log_action_result', $log_action_result);
    $stmt_msg->execute();
    header("Location: ../messages.php?profile=" . $profile);
    exit();
}

?>
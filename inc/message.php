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
    /*$log_description = "message";
    $log_action = "message";
    $stmt_message = $conn->prepare("INSERT INTO log(log_user_name, log_description, log_action, log_action_recipient_name) 
    VALUES(:log_user_name, :log_description, :log_action, :log_action_recipient_name)");
    $stmt_message->bindParam(':log_user_name', $name);
    $stmt_message->bindParam(':log_description', $log_description);
    $stmt_message->bindParam(':log_action', $log_action);
    $stmt_message->bindParam(':log_action_recipient_name', $profile);
    $stmt_message->execute();*/

    $log_description = "likeback";
    $log_action = "likeback";
    $log_action_result = 1;
    $stmt_updatelikeback = $conn->prepare("UPDATE log SET log_action_result=:log_action_result
    WHERE log_id=:log_id AND log_user_name=:log_user_name AND log_action_recipient_name=:log_action_recipient_name;");
    $stmt_updatelikeback->bindParam(':log_id', $log_id);
    $stmt_updatelikeback->bindParam(':log_action_result', $log_action_result);
    $stmt_updatelikeback->bindParam(':log_user_name', $profile);
    $stmt_updatelikeback->bindParam(':log_action_recipient_name', $name);
    $stmt_updatelikeback->execute();

    header("Location: ../message.php");
    exit();
}

?>
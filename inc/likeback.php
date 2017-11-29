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
    $log_description = "likeback";
    $log_action = "likeback";
    $stmt_likeback = $conn->prepare("INSERT INTO log(log_user_name, log_description, log_action, log_action_recipient_name) 
    VALUES(:log_user_name, :log_description, :log_action, :log_action_recipient_name)");
    $stmt_likeback->bindParam(':log_user_name', $name);
    $stmt_likeback->bindParam(':log_description', $log_description);
    $stmt_likeback->bindParam(':log_action', $log_action);
    $stmt_likeback->bindParam(':log_action_recipient_name', $profile);
    $stmt_likeback->execute();

    $log_description = "like";
    $log_action = "like";
    $log_action_result = 1;
    $stmt_updatelike = $conn->prepare("UPDATE log SET log_action_result=:log_action_result
    WHERE log_id=:log_id;");
    $stmt_updatelike->bindParam(':log_id', $log_id);
    $stmt_updatelike->bindParam(':log_action_result', $log_action_result);
    $stmt_updatelike->execute();
    header("Location: ../feed.php");
    exit();
}

?>
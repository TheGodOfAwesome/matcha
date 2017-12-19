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
    $stmt_message = $conn->prepare("INSERT INTO log(log_user_name, log_description, log_action, log_action_recipient_name) 
    VALUES(:log_user_name, :log_description, :log_action, :log_action_recipient_name)");
    $stmt_message->bindParam(':log_user_name', $name);
    $stmt_message->bindParam(':log_description', $log_description);
    $stmt_message->bindParam(':log_action', $log_action);
    $stmt_message->bindParam(':log_action_recipient_name', $profile);
    $stmt_message->execute();

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

    $stmt_user = $conn->prepare("SELECT * FROM users WHERE name=:name");
    $stmt_user->bindValue(":name", $name); 
    $user = array();
    if ($stmt_user->execute()) {
      while ($row = $stmt_user->fetch(PDO::FETCH_ASSOC)) {
        $user[] = $row;
        $user_id = $row['id'];
        $full_name = $row['fullname'];
        $user_name = $row['name'];
        $user_dob = $row['dateofbirth'];
        $user_bio = $row['bio'];
        $user_gender = $row['gender'];
        $user_preference = $row['preference'];
        $user_city = $row['city'];
        $user_country = $row['country'];
      }
    }

    /*
    $matchestable = $name . "§matches";
    $stmt_match = $conn->prepare("INSERT INTO ' . $matchestable . '(match_message_id, match_name, match_creator, match_creator_email) 
    VALUES(:match_message_id, :match_name, :match_creator, :match_creator_email)");
    $stmt_match->bindParam(':match_message_id', $log_id);
    $stmt_match->bindParam(':match_name', $profile);
    $stmt_match->bindParam(':match_creator', $name);
    $stmt_match->bindParam(':match_creator_email', $email);
    $stmt_match->execute();

    $matchestable = $profile . "§matches";
    $stmt_match1 = $conn->prepare("INSERT INTO ' . $matchestable . '(match_message_id, match_name, match_creator, match_creator_email) 
    VALUES(:match_message_id, :match_name, :match_creator, :match_creator_email)");
    $stmt_match1->bindParam(':match_message_id', $user_id);
    $stmt_match1->bindParam(':match_name', $name);
    $stmt_match1->bindParam(':match_creator', $name);
    $stmt_match1->bindParam(':match_creator_email', $email);
    $stmt_match1->execute();
    */

    header("Location: ../feed.php");
    exit();
}

?>
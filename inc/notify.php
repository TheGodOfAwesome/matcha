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
$log_action_result = 0;
$stmt_log = $conn->prepare("SELECT * FROM log WHERE log_action_recipient_name=:log_action_recipient_name AND log_action_result=:log_action_result ORDER BY log_timestamp DESC");
$stmt_log->bindValue(":log_action_recipient_name", $name);
$stmt_log->bindValue(":log_action_result", $log_action_result);
if ($stmt_log->execute()) {
    while ($row = $stmt_log->fetch(PDO::FETCH_ASSOC)) {
       $count = $count + 1;
    }
}
if ($count != 0){
    echo '<font color="red">New NOTIFICATIONS</font>';
    //echo 'NOTIFICATIONS';
}else{
    //echo '<font color="red">New NOTIFICATIONS</font>';
    echo "NOTIFICATIONS";
}

?>
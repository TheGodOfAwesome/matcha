<?php

include '../config/conn.php';

session_start();
if(isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];
}

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ../index.php");
    exit();
}

$lastseen = mktime();
$loginstatus = "loggedout";

$stmt_lastseen = $conn->prepare("UPDATE users SET loginstatus=:loginstatus
WHERE email=:email");
$stmt_lastseen->bindParam(':loginstatus', $loginstatus);
$stmt_lastseen->bindParam(':email', $email);
$stmt_lastseen->execute();

unset($_SESSION['name']);
unset($_SESSION['status']);
unset($_SESSION['email']);

header("Location: ../index.php");
exit();

?>
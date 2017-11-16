<?php

$passkey=$_GET['passkey'];

// Start Session
session_start();
$_SESSION['passkey'] = $passkey;
header("Location: ./reset.php");

?>
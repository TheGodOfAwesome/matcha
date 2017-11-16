<?php

include '../config/conn.php';

// Passkey that got from link
$link_address = '../index.php';
$passkey=$_GET['passkey'];

// prepare sql and bind parameters
$stmt = $conn->prepare("SELECT * FROM users WHERE confirmation_code=:confirmation_code");
$stmt->bindParam(':confirmation_code', $passkey);
$stmt->execute();
if ($stmt->rowCount() == 1) {
    $status = "activated";
    // prepare sql and bind parameters
    $stmt1 = $conn->prepare("UPDATE users SET status=:status
    WHERE confirmation_code=:confirmation_code");
    $stmt1->bindParam(':status', $status);
    $stmt1->bindParam(':confirmation_code', $passkey);
    $stmt1->execute();
    echo "Your account has been activated." . "<br>" . "Sign in to continue!" . "<br>";
    echo "<a href='$link_address'>Sign In</a>";
} else{
    echo "Wrong Confirmation code" . "<br>";
}

?>
<?php

session_start();
unset($_SESSION['name']);
unset($_SESSION['status']);
unset($_SESSION['email']);
header("Location: ../index.php");
exit();

?>
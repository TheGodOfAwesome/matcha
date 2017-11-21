<?php

// Start Session
    session_start();
    define ('SITE_ROOT', realpath(dirname(__FILE__)));
    echo "Matcha" . "</br>";
    echo $_SESSION['name'] . "</br>";
    echo $_SESSION['email'];

?>
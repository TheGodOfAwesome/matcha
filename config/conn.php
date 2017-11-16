<?php
     
    include 'database.php';

    try{
        $conn = new PDO("mysql:host=$DB_DSN;dbname=" . $DB_NAME . "", $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException  $e ){
        echo "Error: ".$e;
    }

?>
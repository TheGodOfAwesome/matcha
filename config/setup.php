<?php

include './database.php';

echo "user: " . $DB_USER . "<br>";
echo "host: " . $DB_DSN . "<br>";

try {
	$init = new PDO("mysql:host=$DB_DSN;", $DB_USER, $DB_PASSWORD);
	$init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE DATABASE IF NOT EXISTS matcha;";
	$init->exec($sql);
	echo "Database created successfully<br>";
} catch (PDOException $e) {
	echo "error: " . $sql . "<br>" . $e->getMessage();
}

$init = null;

$sql2 = "CREATE TABLE IF NOT EXISTS users ("
. "id int NOT NULL AUTO_INCREMENT,"
. "fullname varchar(150),"
. "name varchar(50),"
. "email varchar(50),"
. "gender varchar(7),"
. "bio varchar(280),"
. "interests varchar(280),"
. "preference varchar(7),"
. "password varchar(1000),"
. "status varchar(50),"
. "confirmation_code varchar(1000),"
. "PRIMARY KEY (id));";

try {
	$conn = new PDO("mysql:host=$DB_DSN;dbname=matcha", $DB_USER, $DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->exec($sql2);
	echo "Users created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql2 . "<br>" . $e->getMessage();
}

$sql3 = "CREATE TABLE IF NOT EXISTS images ("
. "image_id int NOT NULL AUTO_INCREMENT,"
. "image_name varchar(100),"
. "image_creator varchar(50),"
. "image_creator_email varchar(50),"
. "image_likes int,"
. "image_url varchar(100),"
. "image_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
. "PRIMARY KEY (image_id));";

try {
	$conn->exec($sql3);
	echo "Images created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql3 . "<br>" . $e->getMessage();
}

$sql4 = "CREATE TABLE IF NOT EXISTS comments ("
. "comment_id int NOT NULL AUTO_INCREMENT,"
. "comment_creator varchar(50),"
. "image_name varchar(100),"
. "image_id int,"
. "image_creator varchar(50),"
. "image_creator_email varchar(50),"
. "image_url varchar(100),"
. "comment_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
. "PRIMARY KEY (comment_id));";

try {
	$conn->exec($sql4);
	echo "Comments created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql4 . "<br>" . $e->getMessage();
}

$conn = null;

?>
<?php

// Start Session
session_start();

if(isset($_SESSION['email'])) {
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $status = $_SESSION['status'];
}

include './config/conn.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ./index.php");
    exit();
}

$target_dir = "./images/user_images/";
$image_name = $name . "-profile.png";
$image_type = "profile";
$target_file = $target_dir . $image_name;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    /* Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }*/

    // Check file size

    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</br>";
            try {
                $stmt = $conn->prepare("UPDATE images SET image_name=:image_name, image_type=:image_type, 
                image_creator_email=:image_creator_email, image_url=:image_url
                WHERE image_creator=:image_creator");
                $stmt->bindParam(':image_name', $image_name);
                $stmt->bindParam(':image_type', $image_type);
                $stmt->bindParam(':image_creator', $name);
                $stmt->bindParam(':image_creator_email', $email);
                $stmt->bindParam(':image_url', $target_file);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "error: " . $sql . "<br>" . $e->getMessage();
            }
            $conn = null;
            echo "Profile photo updated.<br>";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
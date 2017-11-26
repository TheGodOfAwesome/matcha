<?php

$target_dir = "./images/user_images/";
$image_name = $name . "-" . mktime() . ".png";
$image_type = "gallery";
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
    
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

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

                $stmt_user = $conn->prepare("SELECT * FROM users WHERE name=:name");
                $stmt_user->bindValue(":name", $name);
                if ($stmt_user->execute()) {
                  while ($row = $stmt_user->fetch(PDO::FETCH_ASSOC)) {
                    $image_count = $row['imagecount'];
                  }
                }

                $image_count = $image_count + 1;
                if ($image_count < 5){                
                    $stmt_uploadpic = $conn->prepare("INSERT INTO images(image_name, image_type, image_creator, image_creator_email, image_url) 
                    VALUES(:image_name, :image_type, :image_creator, :image_creator_email, :image_url)");
                    $stmt_uploadpic->bindParam(':image_name', $image_name);
                    $stmt_uploadpic->bindParam(':image_type', $image_type);
                    $stmt_uploadpic->bindParam(':image_creator', $name);
                    $stmt_uploadpic->bindParam(':image_creator_email', $email);
                    $stmt_uploadpic->bindParam(':image_url', $target_file);
                    $stmt_uploadpic->execute();

                    $stmt_imagecount = $conn->prepare("UPDATE users SET imagecount=:imagecount
                    WHERE email=:email");
                    $stmt_imagecount->bindParam(':imagecount', $image_count);
                    $stmt_imagecount->bindParam(':email', $email);
                    $stmt_imagecount->execute();
                } else {
                    echo "You are about to exceed your image limit!</BR>Please delete an image from your profile first!";
                }
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
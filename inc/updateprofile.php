<?php

include './config/conn.php';

// Start Session
session_start();

$name = $_SESSION['name'];
$email = $_SESSION['email'];
$status = $_SESSION['status'];

echo $name . "</br>";
echo $email . "</br>";
echo $status . "</br>";

if(!empty($_SESSION['name'])) { 

    if(isset($_SESSION['email'])) {

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $status = $_SESSION['status'];

        if($email == "" && $status != "logged in")
        {
            header("Location: ../matcha.php");
            exit();
        }
    }
}

// check Update request
if (!empty($_POST['btnUpdate'])) {
    if ($_POST['fname'] == "") {
        $register_error_message = 'Name field is required!';
        echo $register_error_message . "<br>";
    }else if ($_POST['lname'] == "") {
        $register_error_message = 'Surname field is required!';
        echo $register_error_message . "<br>";
    }else if ($_POST['interests'] == "") {
        $register_error_message = 'Interests are required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['email'] == "") {
        $register_error_message = 'Email field is required!';
        echo $register_error_message . "<br>";
    } else {
        try {
            $user_ip = getenv('REMOTE_ADDR');
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $country = $geo["geoplugin_countryName"];
            $city = $geo["geoplugin_city"];

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $interests = $_POST['interests'];
            
            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO users(name, email, gender, password, confirmation_code) 
            VALUES(:name, :email, :gender, :password, :confirmation_code)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':password', $enc_password);
            $stmt->bindParam(':confirmation_code', $confirm_code);

            $stmt1 = $conn->prepare("SELECT id FROM users WHERE email=:email");
            $stmt1->bindParam(':email', $email);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                echo "Email Is Already In Use! <br>";
            } else{
                $stmt->execute();
                echo $gender . "</br>";
                echo "Account Created Login To Continue! <br>";
                header("Location: matcha.php");
                
            }
		} catch (PDOException $e) {
			echo "error: " . $sql . "<br>" . $e->getMessage();
		}
        $conn = null;
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Matcha - Update</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Index CSS -->
    <link rel="stylesheet" href="../css/main.css">
  
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
  <body align="center">

    <div class="container" >
        <div class="row">
            <div class="register-group">
                <h4>Update - Profile</h4>
                <form action="index.php" method="post">
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" name="fname" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" name="lname" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Bio</label>
                        <input type="text" name="lname" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <input type="text" name="add" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Neighbourhood</label>
                        <input type="text" name="hood" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Interests</label>
                        <input type="text" name="interests" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Sexual Preference</label>
                        <select name="preference">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="undefined">Undefined</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="update" name="btnUpdate" class="btn btn-primary" value="Update"/>
                    </div>
                </form>
            </div>
        </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <FOOTER align="center">
		<p1>© kmuvezwa 2017</p1>
	</FOOTER>
  </body>
</html>
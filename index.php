<?php

    include './config/conn.php';

// Start Session
session_start();

if(!empty($_SESSION['name'])) { 

    if(isset($_SESSION['email'])) {

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $status = $_SESSION['status'];

        if($email != "" && $status == "logged in")
        {
            header("Location: matcha.php");
            exit();
        }
    }
}

// check Register request
if (!empty($_POST['btnRegister'])) {
    if ($_POST['name'] == "") {
        $register_error_message = 'Name field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['email'] == "") {
        $register_error_message = 'Email field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['password'] == "") {
        $register_error_message = 'Password field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['repeat_password'] == "") {
        $register_error_message = 'Repeat Password field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['repeat_password'] != $_POST['password']) {
        $register_error_message = 'Passwords don\'t match!';
        echo $register_error_message . "<br>";
    } else if (strlen($_POST['repeat_password']) < 6) {
        $register_error_message = 'Password must be at least 6 characters!';
        echo $register_error_message . "<br>";
    } else if (!preg_match('/[^a-zA-Z]+/',($_POST['repeat_password']))) {
        $register_error_message = 'Passwords must have at least one special character!';
        echo $register_error_message . "<br>";
    } else {
        try {
        	$name = $_POST['name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
        	$password = $_POST['password'];
            $enc_password = hash('sha256', $password);
            $confirm_code=md5(uniqid(rand()));
            
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

                // ---------------- SEND MAIL FORM ----------------
                    
                    // send e-mail to ...
                    $to=$email;
                    // Your subject
                    $subject="Your Matcha signup confirmation link here";
                    // From
                    $header="from: Matcha";
                    // Your message
                    $message="Your Confirmation link \r\n";
                    $message.="Click on this link to activate your account \r\n";
                    $message.="http://127.0.0.1:8080/matcha/inc/confirmation.php?passkey=$confirm_code";
    
                    // send email
                    $sentmail = mail($to,$subject,$message,$header);

                    // if your email succesfully sent
                    if($sentmail){
                        echo "Your Confirmation link Has Been Sent To Your Email Address.";
                    } else {
                        echo "Cannot send Confirmation link to your e-mail address";
                    }
                
            }
		} catch (PDOException $e) {
			echo "error: " . $sql . "<br>" . $e->getMessage();
		}
        $conn = null;
    }
}

// check Login request
if (!empty($_POST['btnLogin'])) {
    
	$email = trim($_POST['email']);
    $password = trim($_POST['password']);
 
    if ($email == "") {
        $login_error_message = 'Email is required!';
        echo $login_error_message . "<br>";
    } else if ($password == "") {
        $login_error_message = 'Password is required!';
        echo $login_error_message . "<br>";
    } else {
    	try {
            $enc_password = hash('sha256', $password);
            $status = "activated";
            // prepare sql and bind parameters
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
            $stmt2->bindParam(':email', $email);
            $stmt2->bindParam(':password', $enc_password);
            $stmt2->execute();
            if ($stmt2->rowCount() > 0) {
                $stmt3 = $conn->prepare("SELECT name FROM users WHERE email=:email AND status=:status");
                $stmt3->bindParam(':email', $email);
                $stmt3->bindParam(':status', $status);
                $stmt3->execute();
                if ($stmt3->rowCount() > 0) {
                    $row = $stmt3->fetch();
                    $name = $row['name'];

                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['status'] = "logged in";
                    header("Location: matcha.php");
                } else {
                    echo "Your account is not Activated! Check your email for activation link." . "<br>";
                }
            } else {
                echo "Incorrect user credentials, please try again!" . "<br>";
            }

		} catch (PDOException $e) {
			echo "error: " . $e->getMessage();
		}
		$conn = null;
    }

}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Matcha</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Index CSS -->
    <link rel="stylesheet" href="./css/main.css">
  
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
  <body align="center">

    <div class="container" >
        <div class="row">
            <div class="register-group">
                <h4>Register</h4>
                <form action="index.php" method="post">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" name="name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Gender</label>
                        <select name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="undefined">Undefined</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Repeat Password</label>
                        <input type="password" name="repeat_password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnRegister" class="btn btn-primary" value="Register"/>
                    </div>
                </form>
            </div>
            <div>
                    &nbsp;
                    &nbsp;
                    &nbsp;
            </div>
            <div class="login-group">
                <h4>Login</h4>
                <form action="index.php" method="post">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <a href='./inc/initreset.php'>Forgotten Password</a>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnLogin" class="btn btn-primary" value="Login"/>
                    </div>
                </form>
            </div>
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
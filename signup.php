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
            header("Location: feed.php");
            exit();
        }
    }
}

// check Register request
if (!empty($_POST['btnRegister'])) {
    if ($_POST['name'] == "") {
        $register_error_message = 'Name field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['fname'] == "") {
        $register_error_message = 'first name field is required!';
        echo $register_error_message . "<br>";
    } else if ($_POST['lname'] == "") {
        $register_error_message = 'last name field is required!';
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
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $fullname = $fname . " " . $lname;
            $name = $_POST['name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $rating = 0;
            $imagecount = 0;
        	$password = $_POST['password'];
            $enc_password = hash('sha256', $password);
            $confirm_code=md5(uniqid(rand()));
            
            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO users(fullname, name, email, gender, rating, imagecount, password, confirmation_code) 
            VALUES(:fullname, :name, :email, :gender, :rating, :imagecount, :password, :confirmation_code)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':imagecount', $imagecount);
            $stmt->bindParam(':password', $enc_password);
            $stmt->bindParam(':confirmation_code', $confirm_code);

            $target_dir = "./images/user_images/";
            $image_name = "temp-profile.png";
            $image_type = "profile";
            $target_file = $target_dir . $image_name;

            $stmt_uploadprofilepic = $conn->prepare("INSERT INTO images(image_name, image_type, image_creator, image_creator_email, image_url) 
            VALUES(:image_name, :image_type, :image_creator, :image_creator_email, :image_url)");
            $stmt_uploadprofilepic->bindParam(':image_name', $image_name);
            $stmt_uploadprofilepic->bindParam(':image_type', $image_type);
            $stmt_uploadprofilepic->bindParam(':image_creator', $name);
            $stmt_uploadprofilepic->bindParam(':image_creator_email', $email);
            $stmt_uploadprofilepic->bindParam(':image_url', $target_file);

            $stmt0 = $conn->prepare("SELECT id FROM users WHERE name=:name");
            $stmt0->bindParam(':name', $name);
            $stmt0->execute();

            $stmt1 = $conn->prepare("SELECT id FROM users WHERE email=:email");
            $stmt1->bindParam(':email', $email);
            $stmt1->execute();
            if (($stmt0->rowCount() > 0) || ($stmt1->rowCount() > 0)) {
                if ($stmt0->rowCount() > 0) {
                    echo "Email Is Already In Use! <br>";
                }else{
                    echo "Username Is Already In Use! <br>";
                }
            } else{
                //echo $gender . "</br>";
                //echo "Account Created Login To Continue! <br>";

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
                        $stmt->execute();
                        $stmt_uploadprofilepic->execute();

                        /*$matchestable = $name . "§matches";

                        echo $matchestable . "<br>";

                        $sql_creatematches = "CREATE TABLE IF NOT EXISTS " 
                        . $matchestable
                        . " ("
                        . "match_id int NOT NULL AUTO_INCREMENT,"
                        . "match_message_id varchar(100),"
                        . "match_name varchar(100),"
                        . "match_creator varchar(50),"
                        . "match_creator_email varchar(50),"
                        . "match_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
                        . "PRIMARY KEY (match_id));";
            
                        try {
                            $conn->exec($sql_creatematches);
                        } catch (PDOException $e) {
                            echo "error: " . $sql_creatematches . "<br>" . $e->getMessage();
                        }*/

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

?>

<!DOCTYPE html>
<html>
<head>
<title>Matcha</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/animate.css">
<link rel="stylesheet" type="text/css" href="./assets/css/font.css">
<link rel="stylesheet" type="text/css" href="./assets/css/li-scroller.css">
<link rel="stylesheet" type="text/css" href="./assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="./assets/css/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="./assets/css/theme.css">
<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
<!--[if lt IE 9]>
<script src="../assets/js/html5shiv.min.js"></script>
<script src="../assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
  <header id="header">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="header_top">
          <div class="header_top_left">
            <ul class="top_nav">
              <li><a href="./index.php">Home</a></li>
            </ul>
          </div>
          <div class="header_top_right">
            <a href="./inc/logout.php"><p>Logout</p></a>
          </div>
        </div>
      </div>
    </div>
  </header>
  <section id="contentSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          <div class="contact_area">
            <h2>Sign Up</h2>
            <p>Fill in the form below and sign up!</p>
            <form action="signup.php" method="post">
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" name="name" class="form-control" placeholder="JohnDoer"/>
            </div>
            <div class="form-group">
                <label for="">First Name</label>
                <input type="text" name="fname" class="form-control" placeholder="John"/>
            </div>
            <div class="form-group">
                <label for="">Last Name</label>
                <input type="text" name="lname" class="form-control" placeholder="Doe"/>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="johndoe@example.com"/>
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
        </div>
      </div>
    </div>
  </section>
  <footer id="footer">
    <div class="footer_bottom">
      <p class="copyright">Copyright &copy; 2017 <a href="index.php">Matcha</a></p>
      <p class="developer">Developed By kmuvezwa</p>
    </div>
  </footer>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script src="./assets/js/jquery.min.js"></script> 
<script src="./assets/js/wow.min.js"></script> 
<script src="./assets/js/bootstrap.min.js"></script> 
<script src="./assets/js/slick.min.js"></script> 
<script src="./assets/js/jquery.li-scroller.1.0.js"></script> 
<script src="./assets/js/jquery.newsTicker.min.js"></script> 
<script src="./assets/js/jquery.fancybox.pack.js"></script> 
<script src="./assets/js/custom.js"></script>
</body>
</html>
<?php

include './config/conn.php';

// Start Session
session_start();

if(isset($_SESSION['email'])) {
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $status = $_SESSION['status'];

  echo $name . "</br>";
  echo $email . "</br>";
  echo $status . "</br>";
}

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ./index.php");
    exit();
}

// check Update request
if (!empty($_POST['btnUpdate'])) {
    if ($_POST['name'] == "" && $_POST['fname'] == "" && $_POST['lname'] == "" && $_POST['interests'] == "" && $_POST['email'] == "") {
        $update_profile_error_message = 'You have not updated any of the information on your profile, please fill in at least one field!';
        echo $update_profile_error_message . "<br>";
    } else {
        try {
            $name = $_POST['name'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $bio = $_POST['bio'];
            $address = $_POST['add'];
            $interests = $_POST['interests'];
            $neighbourhood = $_POST['hood'];
            $preference = $_POST['preference'];
            
            if ($_POST['name'] != "") {
                $stmt_name = $conn->prepare("UPDATE users SET name=:name
                WHERE email=:email");
                $stmt_name->bindParam(':name', $name);
                $stmt_name->bindParam(':email', $email);
                $stmt_name->execute();
            }

            $fullname = "";
            $stmt_fullnamesearch = $conn->prepare("SELECT id FROM users WHERE fullname=:fullname");
            $stmt_fullnamesearch->bindParam(':fullname', $fullname);
            $stmt_fullnamesearch->execute();
            if (($stmt_fullnamesearch->rowCount() > 0) || ($_POST['fname'] == "" && $_POST['lname'] == "")) {
                if (($stmt_fullnamesearch->rowCount() > 0) && ($_POST['fname'] == "" && $_POST['lname'] == "")) {
                    echo "";
                } elseif ($_POST['fname'] == "" && $_POST['lname'] == ""){
                    echo "Name and Surname need to be both filled in! <br>";
                } elseif ($stmt_fullnamesearch->rowCount() > 0) {
                    echo "Name and Surname are already saved! <br>";
                }
            } else{
                $fullname = $fname . " " . $lname;
                $stmt_fullname = $conn->prepare("UPDATE users SET fullname=:fullname
                WHERE email=:email");
                $stmt_fullname->bindParam(':fullname', $fullname);
                $stmt_fullname->bindParam(':email', $email);
                $stmt_fullname->execute();
            }

            if ($_POST['bio'] != "") {
                $stmt_bio = $conn->prepare("UPDATE users SET bio=:bio
                WHERE email=:email");
                $stmt_bio->bindParam(':bio', $bio);
                $stmt_bio->bindParam(':email', $email);
                $stmt_bio->execute();
            }

            if ($_POST['add'] != "") {
                $stmt_add = $conn->prepare("UPDATE users SET address=:address
                WHERE email=:email");
                $stmt_add->bindParam(':address', $address);
                $stmt_add->bindParam(':email', $email);
                $stmt_add->execute();
            }

            if ($_POST['interests'] != "") {
                $stmt_hood = $conn->prepare("UPDATE users SET interests=:interests
                WHERE email=:email");
                $stmt_hood->bindParam(':interests', $interests);
                $stmt_hood->bindParam(':email', $email);
                $stmt_hood->execute();
            }

            if ($_POST['hood'] != "") {
                $stmt_hood = $conn->prepare("UPDATE users SET neighbourhood=:neighbourhood
                WHERE email=:email");
                $stmt_hood->bindParam(':neighbourhood', $neighbourhood);
                $stmt_hood->bindParam(':email', $email);
                $stmt_hood->execute();
            }

            
            $user_ip = getenv('REMOTE_ADDR');
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $country = $geo["geoplugin_countryName"];
            $city = $geo["geoplugin_city"];
            $stmt_location = $conn->prepare("UPDATE users SET neighbourhood=:neighbourhood
            WHERE email=:email");
            $stmt_location->bindParam(':neighbourhood', $neighbourhood);
            $stmt_location->bindParam(':email', $email);
            $stmt_location->execute();
            
            $stmt_pref = $conn->prepare("SELECT id FROM users WHERE preference=:preference");
            $stmt_pref->bindParam(':preference', $preference);
            $stmt_pref->execute();
            if ($stmt_pref->rowCount() > 0) {
                $stmt_preference = $conn->prepare("UPDATE users SET preference=:preference
                WHERE email=:email");
                $stmt_preference->bindParam(':preference', $preference);
                $stmt_preference->bindParam(':email', $email);
                $stmt_preference->execute();
            }

            // prepare sql and bind parameters
            /*$stmt = $conn->prepare("UPDATE users SET password=:password
            WHERE confirmation_code=:confirmation_code");
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
            }*/
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
<title>Matcha - Home</title>
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
              <li><a href="./feed.php">Home</a></li>
              <li><a href="./inc/logout.php">Logout</a></li>
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
            <h2>Update Profile</h2>
            <p>Update your Profile to help you find your perfect match!!!</p>
            <form action="contact.php" method="post" class="contact_form">
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
                        <label for="">Bio</label>
                        <textarea class="form-control" name="bio" cols="30" rows="5" placeholder="Bio"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <textarea class="form-control" name="add" cols="30" rows="10" placeholder="Address"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Neighbourhood</label>
                        <input type="text" name="hood" class="form-control" placeholder="JohannesburgCBD"/>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="johndoe@example.com"/>
                    </div>
                    <div class="form-group">
                        <label for="">Interests</label>
                        <input type="text" name="interests" class="form-control" placeholder="e.g. #sports #technology #fashion #shopping #games #dancing #travel"/>
                    </div>
                    <div class="form-group">
                        <label for="">Sexual Preference</label>
                        <select name="preference">
                            <option value="undefined">Undefined</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnUpdate" class="btn btn-primary" value="Update"/>
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
<?php

// Start Session
session_start();

if(isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $status = $_SESSION['status'];
  
    //echo $name . "</br>";
    //echo $email . "</br>";
    //echo $status . "</br>";
}

include './config/conn.php';
include './inc/uploadphoto.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ./index.php");
    exit();
}

$ip = $_REQUEST['REMOTE_ADDR']; // the IP address to query
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
if($query && $query['status'] == 'success') {
    $country = $query['country'];
    $city = $query['city'];
    $geo = $query['lat'] . "," . $query['lon'];
}

$stmt_location = $conn->prepare("UPDATE users SET city=:city, country=:country, geolocation=:geolocation 
WHERE email=:email");
$stmt_location->bindParam(':city', $city);
$stmt_location->bindParam(':country', $country);
$stmt_location->bindParam(':geolocation', $geo);
$stmt_location->bindParam(':email', $email);
$stmt_location->execute();

$stmt_user = $conn->prepare("SELECT * FROM users WHERE name=:name");
$stmt_user->bindValue(":name", $name);
// initialise an array for the results 
$user = array();
if ($stmt_user->execute()) {
    while ($row = $stmt_user->fetch(PDO::FETCH_ASSOC)) {
        $user[] = $row;
        $full_name = $row['fullname'];
        $user_name = $row['name'];
        $user_dob = $row['dateofbirth'];
        $user_bio = $row['bio'];
        $user_add = $row['address'];
        $user_gender = $row['gender'];
        $user_preference = $row['preference'];
        $user_city = $row['city'];
        $user_country = $row['country'];
        $user_geo = $row['geolocation'];
        $user_interests = $row['interests'];

        //date is in yyyy/mm/dd format;
        //explode the date to get month, day and year
        $birthDate = explode("-", $user_dob);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
            ? ((date("Y") - $birthDate[0]) - 1)
            : (date("Y") - $birthDate[0]));
        $user_age = $age;

        if ($user_bio == ""){
            $user_bio = 'Please update your profile and add a <a href="./updateprofile.php">bio</a>!';
        }
        if ($user_city == ""){
            $user_city = 'Please update your profile and add a <a href="./updateprofile.php">city</a>!';
        }
        if ($user_country == ""){
            $user_country = 'Please update your profile and add a <a href="./updateprofile.php">country</a>!';
        }
        if ($user_dob == ""){
            $user_age = 'Please update your profile and add a <a href="./updateprofile.php">date of birth</a>!';
        } else {
            
        }
    }
}

// check Update request
if (!empty($_POST['btnUpdate'])) {
    if ($_POST['name'] == "" && $_POST['fname'] == "" && $_POST['lname'] == "" && $_POST['add'] == "" && $_POST['bio'] == "" && $_POST['interests'] == "" && $_POST['email'] == "" && ($_POST['date'] == "Date" && $_POST['month'] == "Month" && $_POST['year'] == "Year")) {
        $update_profile_error_message = 'You have not updated any of the information on your profile, please fill in at least one field!';
        echo $update_profile_error_message . "<br>";
    } else if (($_POST['fname'] == "" && $_POST['lname'] != "") || ($_POST['fname'] != "" && $_POST['lname'] == "")){
        $update_profile_error_message = 'You cannot updated your first name without completing your last name and vice versa!';
        echo $update_profile_error_message . "<br>";
    } else {
        try {
            $name = $_POST['name'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $emails = $_POST['email'];
            $bio = $_POST['bio'];
            $address = $_POST['add'];
            $interests = $_POST['interests'];
            $neighbourhood = $_POST['hood'];
            $date = $_POST['date'];
            $month = $_POST['month'];
            $year = $_POST['year'];
            
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
                } elseif (($_POST['fname'] == "" && $_POST['lname'] != "") || ($_POST['fname'] != "" && $_POST['lname'] == "")){
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

            if ($_POST['email'] != "") {
                //$emails = $_POST['email'];
                $stmt_email = $conn->prepare("UPDATE users SET email=:email
                WHERE name=:name");
                $stmt_email->bindParam(':email', $emails);
                $stmt_email->bindParam(':name', $name);
                $stmt_email->execute();
            }

            if ($_POST['bio'] != "") {
                $stmt_bio = $conn->prepare("UPDATE users SET bio=:bio
                WHERE email=:email");
                $stmt_bio->bindParam(':bio', $bio);
                $stmt_bio->bindParam(':email', $email);
                $stmt_bio->execute();
            }

            if ($_POST['date'] != "Date" && $_POST['month'] != "Month" && $_POST['year'] != "Year") {
                $dateofbirth = $_POST['year']."-". $_POST['month']."-".$_POST['date'];
                $stmt_dob = $conn->prepare("UPDATE users SET dateofbirth=:dateofbirth
                WHERE email=:email");
                $stmt_dob->bindParam(':dateofbirth', $dateofbirth);
                $stmt_dob->bindParam(':email', $email);
                $stmt_dob->execute();
            }

            if ($_POST['add'] != "") {
                $stmt_add = $conn->prepare("UPDATE users SET address=:address
                WHERE email=:email");
                $stmt_add->bindParam(':address', $address);
                $stmt_add->bindParam(':email', $email);
                $stmt_add->execute();
            }

            if ($_POST['interests'] != "") {
                //$stmt_hood = $conn->prepare("UPDATE users SET interests=:interests  CONCAT(preference, 
                //WHERE email=:email");
                $stmt_hood = $conn->prepare("UPDATE users SET interests=:interests 
                WHERE email=:email");
                $stmt_hood->bindParam(':interests', $interests);
                $stmt_hood->bindParam(':email', $email);
                $stmt_hood->execute();
            }
            
            if ($_POST['preference'] != "pref") {
                $preference = $_POST['preference'];
                $stmt_preference = $conn->prepare("UPDATE users SET preference=:preference
                WHERE email=:email");
                $stmt_preference->bindParam(':preference', $preference);
                $stmt_preference->bindParam(':email', $email);
                $stmt_preference->execute();
            }

            if ($_POST['hood'] != "") {
                $stmt_hood = $conn->prepare("UPDATE users SET neighbourhood=:neighbourhood
                WHERE email=:email");
                $stmt_hood->bindParam(':neighbourhood', $neighbourhood);
                $stmt_hood->bindParam(':email', $email);
                $stmt_hood->execute();
            }

            $externalContent = file_get_contents('http://checkip.dyndns.com/');
            preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
            $externalIp = $m[1];
            $PublicIP = $externalIp;

            //da9ddd4c7bba633cc4de71f115b283283f5528c861f80d7f963bfcf1b8b5300e

            //http://api.ipinfodb.com/v3/ip-city/?key=da9ddd4c7bba633cc4de71f115b283283f5528c861f80d7f963bfcf1b8b5300e&ip=74.125.45.100

            //$geolocation = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$PublicIP"));
            //http://ip-api.com/json/41.71.121.36


            $ip = $_REQUEST['REMOTE_ADDR']; // the IP address to query
            $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
            if($query && $query['status'] == 'success') {
                //$geolocation = unserialize(file_get_contents("http://ip-api.com/json/" . $PublicIP));
                $country = $query['country'];
                $city = $query['city'];
                $geo = $query['lat'] . "," . $query['lon'];
            }


            /*echo "Ext IP " . $PublicIP . "</br>";
            echo "Country " . $country . "</br>";
            echo "City " . $city . "</br>";
            echo "Geo " . $geo . "</br>";*/

            $stmt_location = $conn->prepare("UPDATE users SET city=:city, country=:country, geolocation=:geolocation 
            WHERE email=:email");
            $stmt_location->bindParam(':city', $city);
            $stmt_location->bindParam(':country', $country);
            $stmt_location->bindParam(':geolocation', $geo);
            $stmt_location->bindParam(':email', $email);
            $stmt_location->execute();

            echo "Profile updated!";

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
                <li><a href="./index.php">Home</a></li>
                <li><a href="./matches.php">Matches</a></li>
                <li><div id="msg"><?php include './inc/newmsgs.php';?></div></li>
                <li><a href="./notifications.php"><div id="note"><?php include './inc/note.php';?></div></a></li>
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
                <form action="updateprofile.php" method="post" class="contact_form">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" name="name" class="form-control" placeholder="<?php echo $user_name;?>"/>
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
                        <label for="">Date of Birth</label>
                        <select name="date">
                            <option value="Date">Date</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                        <select name="month">
                            <option value="Month">Month</option>
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                        <select name="year">
                            <option value="Year">Year</option>
                            <option value="1981">1980</option>
                            <option value="1981">1981</option>
                            <option value="1982">1982</option>
                            <option value="1983">1983</option>
                            <option value="1984">1984</option>
                            <option value="1985">1985</option>
                            <option value="1986">1986</option>
                            <option value="1987">1987</option>
                            <option value="1988">1988</option>
                            <option value="1989">1989</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Bio</label>
                        <textarea class="form-control" name="bio" cols="30" rows="5" placeholder="Write something yourself that others users will see."></textarea>
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
                        <label for="">Interests</label>
                        <input type="text" name="interests" class="form-control" placeholder="e.g. #sports #technology #fashion #shopping #games #dancing #travel"/>
                    </div>
                    <div class="form-group">
                        <label for="">Sexual Preference</label>
                        <select name="preference">
                            <option value="pref">Choose Preference</option>
                            <option value="undefined">Undefined</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btnUpdate" class="btn btn-primary" value="Update"/>
                    </div>
                </form>
                <form action="" method="POST" enctype="multipart/form-data">
                     <b>Select new profile picture:</b>
                    <input type="file" class="btn btn-primary" name="fileToUpload" id="fileToUpload">
                    <input type="submit" class="btn btn-primary" value="Upload Profile Photo" name="submit">
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
<script>
  setInterval(function(){
    $('#msg').load("./inc/newmsgs.php").fadeIn("slow");
    $('#note').load("./inc/notify.php").fadeIn("slow");
  }, 8000);
</script>
</body>
</html>
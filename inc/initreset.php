<?php

include '../config/conn.php';

// check Login request
if (!empty($_POST['btnReset'])) {
    
	$email = trim($_POST['email']);
 
    if ($email == "") {
        $login_error_message = 'Email is required!';
        echo $login_error_message . "<br>";
    } else {
    	try {
            // prepare sql and bind parameters
                $stmt3 = $conn->prepare("SELECT confirmation_code FROM users WHERE email=:email");
                $stmt3->bindParam(':email', $email);
                $stmt3->execute();
                if ($stmt3->rowCount() > 0) {
                    $row = $stmt3->fetch();
                    $confirm_code = $row['confirmation_code'];

                    // ---------------- SEND MAIL FORM ----------------
                    
                    // send e-mail to ...
                    $to=$email;
                    // Your subject
                    $subject="Your Matcha password link is here";
                    // From
                    $header="from: Matcha";
                    // Your message
                    $message="Your password reset link \r\n";
                    $message.="Click on this link to reset your password \r\n";
                    $message.="http://localhost:8080/matcha/inc/resetlink.php?passkey=$confirm_code";
    
                    // send email
                    $sentmail = mail($to,$subject,$message,$header);

                    // if your email succesfully sent
                    if($sentmail){
                        echo "Your Reset Password Link Has Been Sent To Your Email Address.";
                    } else {
                        echo "Cannot send Reset Password Link to your e-mail address";
                    }
                } else {
                    echo "Incorrect Email!" . "<br>";
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
    <meta charset="UTF-8">
    <title>Matcha - Reset</title>
</head>
<body>
 
<div class="container">
    <div class="row">
            <h4>Reset Password</h4>
            <form action="initreset.php" method="post">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="submit" name="btnReset" class="btn btn-primary" value="Reset"/>
                </div>
            </form>
    </div>
</div>
 
</body>
</html>
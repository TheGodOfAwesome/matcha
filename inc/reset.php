<?php

// Start Session
session_start();

include '../config/conn.php';

// Passkey that got from link
$link_address = '../index.php';

// check Login request
if (!empty($_POST['btnReset'])) {
    
    if ($_POST['email'] == "") {
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
        	$email = $_POST['email'];
        	$password = $_POST['password'];
            $enc_password = hash('sha256', $password);
            $confirm_code = $_SESSION['passkey'];

            // prepare sql and bind parameters
            $stmt = $conn->prepare("UPDATE users SET password=:password
            WHERE confirmation_code=:confirmation_code");
            $stmt->bindParam(':password', $enc_password);
            $stmt->bindParam(':confirmation_code', $confirm_code);

            $stmt1 = $conn->prepare("SELECT id FROM users WHERE email=:email");
            $stmt1->bindParam(':email', $email);
            $stmt1->execute();
            if ($stmt1->rowCount() != 1) {
                echo "Email Is Incorrect! <br>";
            } else{
                $stmt->execute();
                echo "Password Reset! Login To Continue. <br>";
                echo "<a href='$link_address'>Sign In</a>";              
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
    <title>Matcha- Reset Password</title>
</head>
<body>
 
<div class="container">
    <div class="row">
            <h4>Register</h4>
            <form action="reset.php" method="post">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control"/>
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
                    <input type="submit" name="btnReset" class="btn btn-primary" value="Reset"/>
                </div>
            </form>
    </div>
</div>
 
</body>
</html>
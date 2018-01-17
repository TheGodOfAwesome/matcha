<?php

// Start Session
session_start();
  
if(isset($_SESSION['email'])) {
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $status = $_SESSION['status'];
}

include '../config/conn.php';

if($name == "" || $email == "" || $status != "logged in")
{
    header("Location: ../index.php");
    exit();
}

$log_action_result = 0;
$stmt_log = $conn->prepare("SELECT * FROM log WHERE log_action_recipient_name=:log_action_recipient_name AND log_action_result=:log_action_result ORDER BY log_timestamp DESC");
$stmt_log->bindValue(":log_action_recipient_name", $name);
$stmt_log->bindValue(":log_action_result", $log_action_result);
if ($stmt_log->execute()) {
    while ($row = $stmt_log->fetch(PDO::FETCH_ASSOC)) {
        $log_id = $row['log_id'];
        $log_action = $row['log_action'];
        $log_creator = $row['log_user_name'];

        /******************************************************************************** */

        $path = "./images/user_images/";
        $type = "profile";
        $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
        $stmt_notificationprofilephoto->bindValue(":image_creator", $log_creator);
        $stmt_notificationprofilephoto->bindValue(":image_type", $type);
        // initialise an array for the results 
        $user_image = array();
        if ($stmt_notificationprofilephoto->execute()) {
            while ($row = $stmt_notificationprofilephoto->fetch(PDO::FETCH_ASSOC)) {
                $user_image[] = $row;
                $image_url = $row['image_name'];
                $url = $path . $image_url;
            }
        }

        /******************************************************************************** */

        If ($log_action == "like") {
            $counter_action = "like back";
            echo '
                <li>
                    <div class="media"> 
                    <a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                        <div class="media-body"> 
                            <a href="./profile.php?profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked your profile!</h6></a>
                            <a href="inc/ignorelike.php?id=' . $log_id . '&profile=' . $log_creator . '" class="catg_title"><h6>Ignore like!</h6></a>
                            <a href="inc/likeback.php?id=' . $log_id . '&profile=' . $log_creator . '" ><h4>Like ' . $log_creator . ' Back?</h4></a>
                        </div>
                    </div>
                </li>';
        } else if ($log_action == "likeback") {
            $counter_action = "message";
            echo '
                <li>
                    <div class="media"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                        <div class="media-body"> <a href="./messages.php?id=' . $log_id . '&profile=' . $log_creator . '" class="catg_title"><h6>' . $log_creator . ' liked you back!</h6><h2>Message ' . $log_creator . '</h2></a> </div>
                    </div>
                </li>';
        } else if ($log_action == "message") {
            $counter_action = "message";
            echo '
                <li>
                    <div class="media"> <a href="inc/msg.php?id=' . $log_id . '&profile=' . $log_creator . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                        <div class="media-body"> <a href="inc/msg.php?id=' . $log_id . '&profile=' . $log_creator . '" class="catg_title"><h2>Message ' . $log_creator . ' Back</h2></a> </div>
                    </div>
                </li>';
        }
    }
}

?>
<?php

if ($user_loginstatus == "loggedout"){
    echo '<div class="media-body"><h2> Login Status: Logged Out </BR> </BR> Last Seen: '. $user_lastseen .'.</h2></div>';    
}else{
    echo '<div class="media-body"><h2> Login Status: Logged In </h2></div>'; 
}

?>
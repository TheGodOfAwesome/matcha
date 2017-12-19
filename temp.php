<?php 
              /*$result = mysql_query("SELECT * FROM $table
              WHERE (part LIKE '%$search%') OR (serial LIKE '%$search%') OR (mac LIKE '%$search%') OR(ip LIKE '%$search%')OR(status LIKE '%$search%')
              ORDER BY part ASC");
              $stmt_matches = $conn->prepare("SELECT * FROM users WHERE city=:city AND country=:country");
              $query_art_title = $this->db_connection->prepare('SELECT * FROM articles WHERE art_title LIKE :$artt');
              $query_art_title->bindValue(':artt', '%$art_title_search%', PDO::PARAM_STR);
              $query_art_title->execute();*/
              //echo $_GET["city"] . '</br>';
              //echo $_GET["interests"] . '</br>';
              $search_city = '%' . $_GET["city"] . '%';
              $search_interests = '%' . $_GET["interests"] . '%';
              $stmt_matches = $conn->prepare("SELECT * FROM users WHERE city LIKE :city AND interests LIKE :interests");
              $stmt_matches->bindValue(":city", $search_city);
              $stmt_matches->bindValue(":interests", $search_interests);
              //$stmt_matches->bindValue(":age", '%' . $_GET['age'] . '%');
              if ($stmt_matches->execute()) {
              while ($row = $stmt_matches->fetch(PDO::FETCH_ASSOC)) {
                $match_id = $row['id'];
                $matchfull_name = $row['fullname'];
                $match_name = $row['name'];
                $match_dob = $row['dateofbirth'];
                $match_bio = $row['bio'];
                $match_gender = $row['gender'];
                $match_preference = $row['preference'];
                $match_city = $row['city'];
                $match_country = $row['country'];
                $match_loginstatus = $row['loginstatus'];
                $match_lastseen = $row['lastseen'];
  
                      /* ******************************************************************************* */
  
                      $path = "./images/user_images/";
                      $type = "profile";
                      $stmt_notificationprofilephoto = $conn->prepare("SELECT * FROM images WHERE image_creator=:image_creator AND image_type=:image_type ORDER BY image_timestamp DESC");
                      $stmt_notificationprofilephoto->bindValue(":image_creator", $match_name);
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
  
                      /* ******************************************************************************* */
  
                      if ($match_dob != ""){
                        //date is in yyyy/mm/dd format;
                        //explode the date to get month, day and year
                        $birthDate = explode("-", $user_dob);
                        //get age from date or birthdate
                        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                        ? ((date("Y") - $birthDate[0]) - 1)
                        : (date("Y") - $birthDate[0]));
                        $user_age = $age;
                      }
                      
                      if ($match_name == $name){
  
                      } else if ($match_city != $user_city){
  
                      } else if ($match_country != $user_country){
  
                      }/* else if ($match_dob == ""){
  
                      }*/ 
                      else {
                        if ($user_preference == "" && $user_preference == "undefined"){
                          echo '
                          <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                            <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                          </div>
                          ';
                        }else{
                          if ($user_preference == $match_gender && ($match_preference == "undefined" || $match_preference == $user_gender)){
                            echo '
                            <div class="media"> <a href="./profile.php?profile=' . $match_name . '" class="media-left"> <img src=" ' . $url . '" alt=""> </a>
                              <div class="media-body"> <a href="./profile.php?profile=' . $match_name . '" class="catg_title"><h6>' . $match_name . ' is a potential connection for you!</a></h6><a href="./profile.php?profile=' . $match_name . '" ><h2>View ' . $match_name . '</h2></a></div>
                            </div>
                            ';
                          }
                        }
                    }
                  }
                }
?>

<?php

          if(!($_GET['sort'] == "" && $_GET['filtpop'] == "" && $_GET['city'] == "" && $_GET['interests'] == "" && $_GET['age'] == ""))
            {
              $sortby = $_GET['sortby'];
              $filtpop = $_GET['filtpop'];
              $city = $_GET['city'];
              $interests = $_GET['interests'];
              $age = $_GET['age'];
              if ($sortby == ""){
                echo "No sort </br>";
                echo "sort:" . $sortby . "</br>";
                $matches_query = "SELECT * FROM users WHERE preference LIKE :preference";
                //$example = "SELECT * FROM `users` WHERE `interests` LIKE '%batman%' AND `preference` LIKE 'female'";
                
                //$stmt_matches->bindValue(":interests", $search_interests);
                /*if ($filtpop != ""){
                  $search_pop = '%' . $filtpop . '%';
                  $matches_query = $matches_query . " "
                }*/
                if ($city != ""){
                  $search_city = '%' . $city . '%';
                  $matches_query = $matches_query . " AND city LIKE :city";
                }
                if ($interests != ""){
                  $search_interests = '%' . $interests . '%';
                  $matches_query = $matches_query . " AND interests LIKE :interests";
                }
                /*if ($age != ""){
                  $search_age = '%' . $age . '%';
                }*/
              }elseif($sortby !== ""){
                echo "Sort </br>";
                echo "sort:" . $sortby . "</br>";
              }

?>
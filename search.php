<?php

// check search request
if (!empty($_POST['btnSearch'])) {
 $sort = $_POST['sort'];
 $filttpop = $_POST['filtpop'];
 $city = $_POST['city'];
 $interests = $_POST['interests'];
 $age = $_POST['age'];
 echo $sort . '</br>';
 echo $filtpop . '</br>';
 echo $city . '</br>';
 echo $interests . '</br>';
 echo $age . '</br>';
 header("Location: ./matches.php?sortby=" . $sort . "&filtpop=" . $filtpop . "&city=" . $city . "&interests=" . $interests . "&age=" . $age . "");
 exit();
}

?>
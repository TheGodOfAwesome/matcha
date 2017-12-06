<?php

include './database.php';

echo "user: " . $DB_USER . "<br>";
echo "host: " . $DB_DSN . "<br>";

try {
	$init = new PDO("mysql:host=$DB_DSN;", $DB_USER, $DB_PASSWORD);
	$init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE DATABASE IF NOT EXISTS matcha;";
	$init->exec($sql);
	echo "Database created successfully<br>";
} catch (PDOException $e) {
	echo "error: " . $sql . "<br>" . $e->getMessage();
}

$init = null;

$sql2 = "CREATE TABLE IF NOT EXISTS users ("
	. "id int NOT NULL AUTO_INCREMENT,"
	. "fullname varchar(150),"
	. "name varchar(50),"
	. "email varchar(50),"
	. "gender varchar(7),"
	. "dateofbirth date,"
	. "bio varchar(255),"
	. "msg varchar(255),"
	. "rating int,"
	. "imagecount int,"
	. "address varchar(255),"
	. "neighbourhood varchar(255),"
	. "city varchar(255),"
	. "country varchar(255),"
	. "geolocation varchar(255),"
	. "interests varchar(255),"
	. "preference varchar(9) DEFAULT 'undefined',"
	. "password varchar(255),"
	. "status varchar(50),"
	. "loginstatus varchar(10),"
	. "confirmation_code varchar(255),"
	. "lastseen timestamp,"
	. "last_login_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
	. "PRIMARY KEY (id));";

try {
	$conn = new PDO("mysql:host=$DB_DSN;dbname=matcha", $DB_USER, $DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->exec($sql2);
	echo "Users created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql2 . "<br>" . $e->getMessage();
}


$sql_insertusers = "INSERT INTO users (fullname, name, email, gender, dateofbirth, msg, bio, rating, imagecount, address, neighbourhood, city, country, preference, password, status, confirmation_code) VALUES"
			."('Naruto Uzumaki', 'Hokage', 'naruto@konoha.com', 'male', '1980-03-02', 'hokage§matches', 'Naruto Uzumaki (うずまきナルト, Uzumaki Naruto) is a shinobi of Konohagakure and a descendant of the Uzumaki clan. He became the jinchūriki of the Nine-Tails on the day of his birth.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Hinata Hyuuga', 'Hinata', 'hinata@konoha.com', 'female', '1980-03-02', 'hinata§matches', 'Hinata Uzumaki (うずまきヒナタ, Uzumaki Hinata, née Hyūga (日向)) is a kunoichi and the former heiress of the Hyūga clan.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Sasuke Uchiha', 'Sasuke', 'suchiha@konoha.com', 'male', '1980-03-02', 'sasuke§matches', 'Former ronnin one of Hagoromos descendents.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Sakura Haruno', 'Sakura', 'sharuno@konoha.com', 'female', '1980-03-02', 'sakura§matches', 'Kunoichi and student and protege to Lady Tsunade.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Shikamaru Nara', 'Shikamaru', 'snara@konoha.com', 'male', '1980-03-02', 'shikamaru§matches', 'From the Nara clan, superb strategist and can use shadow control jutsu.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Temari Nara', 'Temari', 'tnara@sand.com', 'female', '1980-03-02', 'temari§matches', 'Temari exceptional wind jutsu ability, daughter to the former Kazekage.', '0', '0', 'The Hidden Sand', 'Suna', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Kakashi Hatake', 'Hatake', 'khatake@konoha.com', 'male', '1970-03-02', 'hatake§matches', 'Former anbu son of the white fang.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Rock Lee', 'Rock-Lee', 'rlee@konoha.com', 'male', '1980-05-02', 'rlee§matches', 'Excellent taijutsu users student to Gai sensei.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Tenten Tamura', 'Tenten', 'tenten@konoha.com', 'female', '1980-03-02', 'tenten§matches', 'Tenten (テンテン, Tenten) is a kunoichi from Konohagakure and a member of Team Guy. While initially wanting to be like the legendary Sannin Tsunade, she realised that she did not have the necessary chakra control to be a medical-nin.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Ino Yamanaka', 'Ino', 'iyamanaka@konoha.com', 'female', '1980-03-02', 'ino§matches', 'Ino Yamanaka (山中いの, Yamanaka Ino) is a kunoichi of Konohagakures Yamanaka clan.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Itachi Uchiha', 'Itachi', 'iuchiha@konoha.com', 'male', '1978-03-02', 'itachi§matches', 'A prodigy of Konohagakures Uchiha clan and also served as an Anbu Captain. He later became an international criminal after murdering his entire clan, sparing only his younger brother, Sasuke.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Killer Bee', 'KillerBee', 'killerbee@kumo.com', 'male', '1970-03-02', 'killerBee§matches', 'Killer B (キラービー, Kirābī, Viz: Killer Bee) is a shinobi from Kumogakure. He is the most recent jinchūriki of the Eight-Tails, though, unlike his predecessors, he is able to befriend it.', '0', '0', 'The Hidden Lighting', 'Kumo', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Jiraiya Sannin', 'Jiraiya', 'jiraiya@konoha.com', 'male', '1980-03-02', 'jiraiya§matches', 'Jiraiya (自来也) was one of Konohagakures Sannin. Famed as a hermit and pervert of stupendous ninja skill.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Gaara Subaku', 'Gaara', 'gaara@sand.com', 'male', '1984-03-02', 'gaara§matches', 'Gaara (我愛羅) is a shinobi of Sunagakure. He was made the jinchūriki of the One-Tailed Shukaku before he was born.', '0', '0', 'The Hidden Sand', 'Suna', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Kurenai Yuhi', 'Kurenai', 'kyuhi@konoha.com', 'female', '1970-03-02', 'kurenai§matches', 'A kunoichi, she served as the jōnin-leader of Team 8.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Sai Yamanaka', 'Sai', 'sai@konoha.com', 'male', '1982-05-02', 'sai§matches', 'Sai Yamanaka (山中サイ, Yamanaka Sai) is a Chūbu of Konohagakures Yamanaka clan. Prior to this, he was an Anbu from Root.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Rin Nohara', 'Rin', 'rnohara@konoha.com', 'female', '1972-05-02', 'rin§matches', 'Rin Nohara (のはらリン, Nohara Rin) was a chūnin of Konohagakure and a member of Team Minato.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'female', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Tsunade Senju', 'Tsunade', 'tsenju@konoha.com', 'female', '1960-03-02', 'tsunade§matches', 'Tsunade (綱手) is a descendant of the Senju clan and one of Konohagakures Sannin, famed as the worlds strongest kunoichi and its greatest medical nin.', '0', '0', 'The Hidden Leaf', 'Konoha', 'Johannesburg', 'South Africa', 'undefined', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50'),"
			."('Karin Uzumaki', 'Karin', 'karin@kusa.com', 'female', '1982-03-02', 'karin§matches', 'Karin (香燐, Karin) is a subordinate of Orochimaru, a former kunoichi of Kusagakure, and a member of the Uzumaki clan.', '0', '0', 'The Hidden Grass', 'Kusa', 'Johannesburg', 'South Africa', 'male', 'f82a7d02e8f0a728b7c3e958c278745cb224d3d7b2e3b84c0ecafc5511fdbdb7', 'activated', 'aa57b005e032da3b2f9f0b5e9ad40e50');";

try {
	$conn->exec($sql_insertusers);
	echo "Users added successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql_insertusers . "<br>" . $e->getMessage();
}


$arr = array("Hokage", "Hinata", "Sasuke", "Sakura", "Shikamaru", "Temari", "Hatake", "rlee", "Tenten", "Ino", "Itachi", "KillerBee", "Jiraiya", "Gaara", "Kurenai", "Sai", "Rin", "Tsunade", "Karin");
reset($arr);
while (list(, $name) = each($arr)) {
	//echo "Value: $name<br />\n";
	
	$matchestable = $name . "§matches";
	//echo $matchestable . "<br>";
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
	}

}
echo "Matches created successfully <br>";

$sql3 = "CREATE TABLE IF NOT EXISTS images ("
	. "image_id int NOT NULL AUTO_INCREMENT,"
	. "image_name varchar(100),"
	. "image_type varchar(100),"
	. "image_creator varchar(50),"
	. "image_creator_email varchar(50),"
	. "image_likes int,"
	. "image_url varchar(100),"
	. "image_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
	. "PRIMARY KEY (image_id));";

try {
	$conn->exec($sql3);
	echo "Images created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql3 . "<br>" . $e->getMessage();
}

$target_dir = "./images/user_images/";
$image_name = $name . "-profile.png";
$image_type = "profile";
$target_file = $target_dir . $image_name;

$sql_insertimages = "INSERT INTO images (image_name, image_type, image_creator, image_creator_email, image_likes, image_url) VALUES"
			."('Hokage-profile.png', 'profile', 'Hokage', 'naruto@konoha.com', '0', './images/user_images/Hokage-profile.png'),"
			."('Hinata-profile.png', 'profile', 'Hinata', 'hinata@konoha.com', '0', './images/user_images/Hinata-profile.png'),"
			."('Sasuke-profile.png', 'profile', 'Sasuke', 'suchiha@konoha.com', '0', './images/user_images/Sasuke-profile.png'),"
			."('Sakura-profile.png', 'profile', 'Sakura', 'sharuno@konoha.com', '0', './images/user_images/Sakura-profile.png'),"
			."('Shikamaru-profile.png', 'profile', 'Shikamaru', 'snara@konoha.com', '0', './images/user_images/Shikamaru-profile.png'),"
			."('Temari-profile.png', 'profile', 'Temari', 'tnara@konoha.com', '0', './images/user_images/Temari-profile.png'),"
			."('Kakashi-profile.png', 'profile', 'Kakashi', 'khatake@konoha.com', '0', './images/user_images/Kakashi-profile.png'),"
			."('Rock-Lee-profile.png', 'profile', 'Rock-Lee', 'rlee@konoha.com', '0', './images/user_images/Rock-Lee-profile.png'),"
			."('Tenten-profile.png', 'profile', 'Tenten', 'tenten@konoha.com', '0', './images/user_images/Tenten-profile.png'),"
			."('Ino-profile.png', 'profile', 'Ino', 'iyamanaka@konoha.com', '0', './images/user_images/Ino-profile.png'),"
			."('Itachi-profile.png', 'profile', 'Itachi', 'iuchiha@konoha.com', '0', './images/user_images/Itachi-profile.png'),"
			."('KillerBee-profile.png', 'profile', 'KillerBee', 'killerbee@kumo.com', '0', './images/user_images/KillerBee-profile.png'),"
			."('Jiraiya-profile.png', 'profile', 'Jiraiya', 'jiraiya@konoha.com', '0', './images/user_images/Jiraiya-profile.png'),"
			."('Gaara-profile.png', 'profile', 'Gaara', 'gaara@sand.com', '0', './images/user_images/Gaara-profile.png'),"
			."('Kurenai-profile.png', 'profile', 'Kurenai', 'kyuhi@konoha.com', '0', './images/user_images/Kurenai-profile.png'),"
			."('Sai-profile.png', 'profile', 'Sai', 'sai@konoha.com', '0', './images/user_images/Sai-profile.png'),"
			."('Rin-profile.png', 'profile', 'Rin', 'rnohara@konoha.com', '0', './images/user_images/Rin-profile.png'),"
			."('Tsunade-profile.png', 'profile', 'Tsunade', 'tsenju@konoha.com', '0', './images/user_images/Tsunade-profile.png'),"
			."('Karin-profile.png', 'profile', 'Karin', 'karin@kusa.com', '0', './images/user_images/Karin-profile.png');";

try {
	$conn->exec($sql_insertimages);
	echo "Users added successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql_insertimages . "<br>" . $e->getMessage();
}

$sql4 = "CREATE TABLE IF NOT EXISTS comments ("
	. "comment_id int NOT NULL AUTO_INCREMENT,"
	. "comment_creator varchar(50),"
	. "image_name varchar(100),"
	. "image_id int,"
	. "image_creator varchar(50),"
	. "image_creator_email varchar(50),"
	. "image_url varchar(100),"
	. "comment_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
	. "PRIMARY KEY (comment_id));";

try {
	$conn->exec($sql4);
	echo "Comments created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql4 . "<br>" . $e->getMessage();
}

$sql5 = "CREATE TABLE IF NOT EXISTS log ("
	. "log_id int NOT NULL AUTO_INCREMENT,"
	. "log_user_id int,"
	. "log_user_name varchar(100),"
	. "log_user_image varchar(100),"
	. "log_description varchar(50),"
	. "log_action varchar(50),"
	. "log_action_recipient_id int,"
	. "log_action_recipient_name varchar(100),"
	. "log_action_result int NOT NULL DEFAULT '0',"
	. "log_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
	. "PRIMARY KEY (log_id));";

try {
	$conn->exec($sql5);
	echo "Log created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql5 . "<br>" . $e->getMessage();
}

$sql6 = "CREATE TABLE IF NOT EXISTS messages ("
. "message_id int NOT NULL AUTO_INCREMENT,"
. "chat_id varchar(100),"
. "message_sender_id int,"
. "message_sender_name varchar(100),"
. "message_recepient_id int,"
. "message_recepient_name varchar(100),"
. "message_text text,"
. "message_timestamp timestamp NOT NULL DEFAULT current_timestamp on update current_timestamp,"
. "PRIMARY KEY (message_id));";

try {
	$conn->exec($sql6);
	echo "Messages created successfully <br>";
} catch (PDOException $e) {
	echo "error: " . $sql6 . "<br>" . $e->getMessage();
}

$conn = null;

?>
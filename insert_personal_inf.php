<?php
	//Start session
	session_start();
	ini_set( 'default_charset', 'UTF-8' );
	
	//Include database connection details
	require_once('config.php');


	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
		$member_id = $_SESSION['SESS_MEMBER_ID'];
		$gender = $_POST['gender'];
		$age = $_POST['age'];
		$education = $_POST['education'];
		$origin = $_POST['country'];
		$genre = implode(",", $_POST['genre']);
		$format = implode(",", $_POST['format']);
		$morning = $_POST['morning'];
		$afternoon = $_POST['afternoon'];
		$evening = $_POST['evening'];
		$night = $_POST['night'];
		$morning_weekend = $_POST['morning_weekend'];
		$afternoon_weekend = $_POST['afternoon_weekend'];
		$evening_weekend = $_POST['evening_weekend'];
		$night_weekend = $_POST['night_weekend'];
		$qry="update members set education='$education',age='$age',gender='$gender',origin='$origin',fav_format='$format',fav_genre='$genre',
		pref_morning='$morning',pref_afternoon='$afternoon',pref_evening='$evening',pref_night='$night',
		pref_morning_weekend='$morning_weekend',pref_afternoon_weekend='$afternoon_weekend',pref_evening_weekend='$evening_weekend',pref_night_weekend='$night_weekend'
		where member_id = $member_id";
		echo $qry;
		$result = mysql_query($qry);
		echo $result;
		
	
	
?>
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
	$id = $_POST['id'];
	$startTime = $_POST['starttime'];
	$endTime = $_POST['endtime'];
	$rating = $_POST['rating'];
	$member_id = $_SESSION['SESS_MEMBER_ID'];
	$countWhole = intval($_POST["count_whole_value"]);
	$countTags = intval($_POST["count_tags_value"]);
	$already_tagged = array();
	$qry="INSERT INTO watch_whole(member_id,program_id,with_subs,whole,start_scene,end_scene) value ($member_id , $id , 1, '$rating',$startTime, $endTime  )";
	$result=mysql_query($qry);
	for ($whole = 0; $whole < $countWhole; $whole++) {
		$tag = $_POST[$whole];
		$rating = intval($_POST[$whole.'-rating']);
		$already_tagged[$tag] = $rating;
		$qry="INSERT INTO rated_whole(member_id,program_id,tag,rating) value ($member_id , $id , '$tag', $rating )";
		$result=mysql_query($qry);
	}
	for ($tags = 0; $tags < $countTags; $tags++) {
		$tag = $_POST[$tags.'-tags'];
		if (array_key_exists($tag,$already_tagged)){
			$rating = $already_tagged[$tag];
		}else{
			$rating = intval($_POST[$tags.'-rating']);}
		$qry="INSERT INTO rated_scenes(member_id,program_id,tag,rating,start_scene,end_scene) value ($member_id , $id , '$tag', $rating,$startTime , $endTime )";
		$result=mysql_query($qry);
	} 
	
	echo $rating;
	
?>
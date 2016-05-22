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
	echo implode(",",$_POST);
	$id = intval($_POST['id']);
	$startTime = intval($_POST['start']);
	$duration = intval($_POST['duration']);
	$section = $_POST['section'];
	$rating = intval($_POST['rating']);
	$long = intval($_POST['too-long']);
	$short = intval($_POST['too-short']);
	$enough = intval($_POST['long-enough']);
	$spoilers = intval($_POST['spoilers']);
	$member_id = intval($_SESSION['SESS_MEMBER_ID']);
	$qry="INSERT INTO rating(programme_id,start_point,duration,section,member_id,interesting,long_enough,too_short,too_long,spoilers) values ($id, $startTime, $duration, '$section', $member_id , $rating, $enough, $short, $long, $spoilers  )";
	mysql_query($qry);
	
?>
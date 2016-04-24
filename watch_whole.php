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

	$id = $_POST['id'];
	$startTime = $_POST['time_started'];
	$endTime = $_POST['time_ended'];
	$watchWhole = $_POST['watchWhole'];
	$qry="INSERT INTO watch_whole(member_id,program_id,with_subs,whole,start_scene,end_scene) value ($member_id , $id , 1, '$watchWhole','$startTime' , '$endTime'  )";
		
	$result=mysql_query($qry);
	echo $result;
	exit();
?>	
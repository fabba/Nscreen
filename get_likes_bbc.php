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
	//Create query
	$qry="SELECT * FROM bbc_programs WHERE id in (SELECT talk_id as id from likes WHERE member_id='$member_id' and bbc_or_ted = 1)";
	
	$result=mysql_query($qry);  
	//Check whether the query was successful or not
	$arr = array();
	if($result) {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($arr,$row);
			}
			echo json_encode($arr);
			
	}
	exit();	
	
?>
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

	$programme_id = $_POST['programme_id'];
	$share_with_member = intval(explode("member_",$_POST['member_id'])[1]);
	$qry="INSERT IGNORE INTO shared_with_friend(shared_by,shared_with,programme_id) values ($member_id , $share_with_member , $programme_id  )";
	echo $qry;
	$result=mysql_query($qry);
	echo $result;
	exit();
?>	
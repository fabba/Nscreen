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
	$bbcorted = $_POST['bbcorted'];
	$dislike = $_POST['liked'];
	if(intval($dislike) == 1){
		$qry="INSERT INTO likes(member_id,talk_id,bbc_or_ted) values ($member_id , $id , $bbcorted  )";
	}else{
		$qry="DELETE FROM likes where member_id=$member_id and talk_id=$id and bbc_or_ted =$bbcorted ";
	}
	$result=mysql_query($qry);
	echo $result;
	exit();
?>	
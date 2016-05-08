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
	$dislike = $_POST['dislike'];
	$qry="SELECT * FROM keywords WHERE bbc_id in (SELECT bbc_id from bbc_programs WHERE id = $oid)";
	$keywords =mysql_query($qry); 
	$oid = intval($id);
	if(intval($dislike) == 1){
		$qry="INSERT INTO dislikes(member_id,talk_id,bbc_or_ted) values ($member_id , $id , $bbcorted  )";
		if($result) {
					while ($row = mysql_fetch_assoc($keywords)) {
						$keyword = $row['keywords'];
						$relevance = $row['relevance']*3;
						$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
						if(mysql_num_rows($check_tags) == 0) {
							$qry1="insert into interest_area (member_id,tag,interest_value) values ($member_id,'$keyword',$relevance)";
							mysql_query($qry); 
				
						} else {
							$qry1="update interest_area set interest_value = interest_value - $relevance WHERE member_id=$member_id and tag='$keyword'";
							mysql_query($qry); 
						
						}
					}
					
				}
	}else{
		$qry="DELETE FROM dislikes where member_id=$member_id and talk_id=$id and bbc_or_ted =$bbcorted ";
		if($result) {
					while ($row = mysql_fetch_assoc($keywords)) {
						$keyword = $row['keywords'];
						$relevance = $row['relevance']*3;
						$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
						if(mysql_num_rows($check_tags) == 0) {
							$qry1="insert into interest_area (member_id,tag,interest_value) values ($member_id,'$keyword',$relevance)";
							mysql_query($qry); 
				
						} else {
							$qry1="update interest_area set interest_value = interest_value + $relevance WHERE member_id=$member_id and tag='$keyword'";
							mysql_query($qry); 
						
						}
					}
					
				}
	}
	$result=mysql_query($qry);
	echo $result;
	exit();
?>	
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
	
	//Selecting database for the user
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$fname = utf8_encode($_POST['firstname']);
	$lname = '';
	$login = null;
	$password = null;
	$cpassword = null;

	$facebook_id = $_POST['facebook_id'];
	//$recommendations= mysql_real_escape_string($_POST['recommendations']);
	echo $facebook_id;
	// //Check for duplicate facebook ID and login directly
	if($facebook_id != '') {
		$qry = "SELECT * FROM members WHERE facebook_id=$facebook_id";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$_SESSION['FACEBOOKID'] = $facebook_id;
				$qry="SELECT * FROM members WHERE facebook_id=$facebook_id";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $member['member_id'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			session_write_close();
			echo $facebook_id;
			exit();
		}else {
			//Login failed
			header('Location: /'); 
			exit();
		}
	}else {
		die(mysql_error());
	}
			}
			//@mysql_free_result($result);
		}
		else {
			die("Query failed");
		}
	}
	
	//Create INSERT query
	$qry = "INSERT INTO members(firstname, lastname, login, passwd, facebook_id) VALUES('$fname','$lname','$login','$password',$facebook_id)";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {

		//Insert user based content to be tracked
		//$recommendations = file_get_contents("data/recommendations.js");
		//$recently_viewed = file_get_contents("data/recently_viewed.js");
		//$watch_later = file_get_contents("data/watch_later.js");
		//$like_dislike = file_get_contents("data/like_dislike.js");
		//$shared_by_friends = file_get_contents("data/shared_by_friends.js");
		//$sql = "INSERT INTO content(recommendations, recently_viewed, watch_later, like_dislike, shared_by_friends) VALUES ('$recommendations', '$recently_viewed', '$watch_later', '$like_dislike', '$shared_by_friends')"; //Insert every read line from txt to mysql database
		//mysql_query($sql);

		$qry="SELECT * FROM members WHERE facebook_id=$facebook_id";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $member['member_id'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			session_write_close();
			echo $facebook_id;
			exit();
		}else {
			//Login failed
			header('Location: /'); 
			exit();
		}
	}else {
		die(mysql_error());
	}

	}
	else {
		die("Query failed");
	}
?>
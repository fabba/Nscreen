<?php
	//Start session
	
	$limit_keyword_video = 20;
	$limit_keyword_segments = 10;
	session_start();
	ini_set( 'default_charset', 'UTF-8' );
	
	//Include database connection details
	require_once('config.php');

	$id = $_POST['id'];
	$bbcorted = $_POST['bbcorted'];
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
	
	if($bbcorted == 0){
		//Create query
		$qry="SELECT * FROM ted_talks WHERE id='$id'";
		
		$result=mysql_query($qry);   
		//Check whether the query was successful or not
		if($result) {
			if(mysql_num_rows($result) == 1) {
				$member = mysql_fetch_array($result);
				echo json_encode($member);
			}else {
				exit();
			}
		}else {
			die("Query failed");
		}
	}else{
		$qry="SELECT * FROM bbc_programs WHERE id='$id'";
		$keywords = [];
		$keys = [];
		$result=mysql_query($qry); 
		$subs = false;
		if($result) {
			if(mysql_num_rows($result) == 1) {
				$bbc = mysql_fetch_array($result);
				$bbc_id = $bbc[11];
				$keys_whole = array();
				$qry="SELECT keywords FROM keywords WHERE bbc_id='$bbc_id' order by relevance desc limit $limit_keyword_video";
				$result_keywords=mysql_query($qry); 
				
				if($result_keywords) {
					while ($row = mysql_fetch_assoc($result_keywords)) {
						array_push($keys_whole,$row["keywords"]);
					}
				}
				$start_scene = 0;
				$old_start_scene = 0;
				$count = 0;
				$times = array();
				$scene_keyword = array();
				$keywords = array();
				$scenes = array();
				$qry="SELECT keyword,start_scene,end_scene FROM keyword_scenes WHERE bbc_id='$bbc_id' order by start_scene,relevance desc";
				$result_keyword=mysql_query($qry);
							
				if($result_keyword) {
					while ($row = mysql_fetch_assoc($result_keyword)) {
					
						$start_scene = intval($row['start_scene']);
						if( $start_scene == $old_start_scene){
							if(count($keywords) <= $limit_keyword_segments){
								array_push($keywords,$row['keyword']);
							}
						}else{
							$times['scene'.$count] = array(intval($row['start_scene']),intval($row['end_scene']));
							$scene_keyword['scene'.$count] = $keywords;
		
							array_push($scenes,'scene'.$count);
							$count += 1;
							$keywords = array();
						}
						$old_start_scene = $start_scene;
					}
						
				}
				$duration = intval($bbc[14]);
				$section = array('beginning','middle','end');
				$sec = $section[array_rand($section)];
				$duration_scene = array(5,10,15,20,25,30);
				$dur_scene = $duration_scene[array_rand($duration_scene)];
				if($sec=='beginning'){
					$start = rand( 0, $duration/3 );
				}
				elseif($sec=='middle'){
					$start = rand(  $duration/3, $duration/3*2 );
				}
				else{
					$start = rand( $duration/3*2, $duration/3*3 );
				}
				$end = $start + $dur_scene;
				$keys = [];
				echo json_encode(array($bbc[0],$bbc[11],$bbc[1],$bbc[2],$bbc[10],$bbc[4],0,$start,$end,$sec,$keys_whole,$keys));
			}else {
				exit();
			}
		}else {
			die("Query failed");
		}
	}
	exit();
?>
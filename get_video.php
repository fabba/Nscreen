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
				/*
				$result=mysql_query($qry);
				if (file_exists("BBCPrograms/".$bbc_id."_keywords.srt")){
					$scen = array();
					$scen_keys = array();
					$keywords =file_get_contents("BBCPrograms/".$bbc_id."_keywords.srt");
					$keywords= explode(",", $keywords);
				
					foreach ( $keywords as $keyword ){
						if( preg_match('/[A-Za-z]/',$keyword )){
							array_push($keys_whole,$keyword);
						}
					}
					$myfile = fopen("BBCPrograms/".$bbc_id."_scenes_subs.srt", "r") or die("Unable to open file!");
					while(!feof($myfile)) {
						$scene = explode("-->", fgets($myfile));
						if( isset($scene[1])){
							$keys = explode(",",$scene[1]);
							$keys_stripped = array();
							foreach ( $keys as $key ){
								if( preg_match('/[A-Za-z]/',$key )){
									array_push($keys_stripped,$key);
								}
							}
							foreach ($keys_stripped as $key){
								if(in_array($key, $keywords)) {
									$subs = true;
									array_push($scen,preg_replace("/\s+/", "",$scene[0]));
									
								    $scen_keys[preg_replace("/\s+/", "",$scene[0])] = $keys_stripped;
								}
							}
						}
					}
					fclose($myfile);
				}
				$times = array();
				$scenes_playable = array();
				$scenes = explode("/", $bbc[6]);
				$array = explode("\n", file_get_contents($scenes[1]."/".$scenes[2]));
				foreach($array as $key=>$value) {
					if (strpos($value, '-->') !== false) {
						$value = explode("-->", $value);
						$scene = preg_replace("/\s+/", "",$array[$key+1]);
						if($subs){
							if(in_array($scene, $scen)) {
								$time_start_shot = explode(":",$value[0]);
								$time_start_shot = intval($time_start_shot[0])*3600 + intval($time_start_shot[1])*60 + intval($time_start_shot[2]);
								$time_end_shot = explode(":",$value[1]);
								$time_end_shot = intval($time_end_shot[0])*3600 + intval($time_end_shot[1])*60 + intval($time_end_shot[2]);
								array_push($scenes_playable,$scene);
								$times[$scene] = array($time_start_shot,$time_end_shot);
							}
						}else{
							$time_start_shot = explode(":",$value[0]);
							$time_start_shot = intval($time_start_shot[0])*3600 + intval($time_start_shot[1])*60 + intval($time_start_shot[2]);
							$time_end_shot = explode(":",$value[1]);
							$time_end_shot = intval($time_end_shot[0])*3600 + intval($time_end_shot[1])*60 + intval($time_end_shot[2]);
							array_push($scenes_playable,$scene);
							$times[$scene] = array($time_start_shot,$time_end_shot);
						}
					}
				}
				*/

				$scene = $scenes[array_rand($scenes)];
				$time = $times[$scene];
				$keys = $scene_keyword[$scene];
				echo json_encode(array($bbc[0],$bbc[11],$bbc[1],$bbc[2],$bbc[10],$bbc[4],0,$time[0],$time[1],$keys_whole,$keys));
			}else {
				exit();
			}
		}else {
			die("Query failed");
		}
	}
	exit();
?>
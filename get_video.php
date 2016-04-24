<?php
	//Start session
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
				if (file_exists("BBCPrograms/".$bbc_id."_keywords.srt")){
					$scen = array();
					$keywords =file_get_contents("BBCPrograms/".$bbc_id."_keywords.srt");
					$keywords= explode(",", $keywords);
					$myfile = fopen("BBCPrograms/".$bbc_id."_scenes_subs.srt", "r") or die("Unable to open file!");
					while(!feof($myfile)) {
						$scene = explode("-->", fgets($myfile));
						if( isset($scene[1])){
							$keys = explode(",",$scene[1]);
							foreach ($keys as $key){
								if(in_array($key, $keywords)) {
									$subs = true;
									array_push($scen,preg_replace("/\s+/", "",$scene[0]));
								}
							}
						}
					}
					fclose($myfile);
				}
				$times = array();
				$scenes = explode("/", $bbc[6]);
				$array = explode("\n", file_get_contents($scenes[1]."/".$scenes[2]));
				foreach($array as $key=>$value) {
					if (strpos($value, '-->') !== false) {
						$value = explode("-->", $value);
						if($subs){
							if(in_array(preg_replace("/\s+/", "",$array[$key+1]), $scen)) {
								$time_start_shot = explode(":",$value[0]);
								$time_start_shot = intval($time_start_shot[0])*3600 + intval($time_start_shot[1])*60 + intval($time_start_shot[2]);
								$time_end_shot = explode(":",$value[1]);
								$time_end_shot = intval($time_end_shot[0])*3600 + intval($time_end_shot[1])*60 + intval($time_end_shot[2]);
								array_push($times,array($time_start_shot,$time_end_shot));
							}
						}else{
							$time_start_shot = explode(":",$value[0]);
							$time_start_shot = intval($time_start_shot[0])*3600 + intval($time_start_shot[1])*60 + intval($time_start_shot[2]);
							$time_end_shot = explode(":",$value[1]);
							$time_end_shot = intval($time_end_shot[0])*3600 + intval($time_end_shot[1])*60 + intval($time_end_shot[2]);
							array_push($times,array($time_start_shot,$time_end_shot));
						}
					}
				}
				$time = $times[array_rand($times)];
				echo json_encode(array($bbc[0],$bbc[11],$bbc[1],$bbc[2],$bbc[10],$bbc[4],0,$time[0],$time[1],$keywords,$keys));
			}else {
				exit();
			}
		}else {
			die("Query failed");
		}
	}
	exit();
?>
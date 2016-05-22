<?php
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

$tableName = "traceuser";




if($_POST["visitID"]>0){
    $visit_name = "`visitID`,";
    $visit_val = "".$_POST["visitID"].",";
}else{
    $visit_name = "";
    $visit_val = "";
}

//create SQL code
$sql = "
insert into `".$tableName."`
     (".$visit_name." `userID`,`objectID`,`sessionID`,`pageID`,`pageType`,`imagesCount`,
         `textSizeCount`,`linksCount`,`windowSizeX`,`windowSizeY`,`pageSizeX`,`pageSizeY`,`objectsListed`,
      `startDatetime`,`endDatetime`,`timeOnPage`,`mouseClicksCount`,`pageViewsCount`,`mouseMovingTime`,
      `mouseMovingDistance`,`scrollingCount`,`scrollingTime`,`scrollingDistance`,
      `printPageCount`,`selectCount`,`selectedText`,`copyCount`,`copyText`,`clickOnPurchaseCount`,
      `purchaseCount`,`forwardingToLinkCount`,`forwardedToLink`,`logFile`) 
     VALUES ( ".$visit_val." ".$_POST["userID"].",  ".$_POST["objectID"].",\"".$_POST["sessionID"]."\",\"".$_POST["pageID"]."\", \"".$_POST["pageType"]."\",".$_POST["imagesCount"].",
              ".$_POST["textSizeCount"].",".$_POST["linksCount"].", ".$_POST["windowSizeX"].", ".$_POST["windowSizeY"].", ".$_POST["pageSizeX"].", ".$_POST["pageSizeY"].", \"".$_POST["objectsListed"]."\",
            \"".$_POST["startDatetime"]."\", \"".$_POST["endDatetime"]."\", ".$_POST["timeOnPageMiliseconds"].", ".$_POST["mouseClicksCount"].", ".$_POST["pageViewsCount"].",".$_POST["mouseMovingTime"].",
              ".$_POST["mouseMovingDistance"].",".$_POST["scrollingCount"].", ".$_POST["scrollingTime"].", ".$_POST["scrollingDistance"].",
              ".$_POST["printPageCount"].",".$_POST["selectCount"].", \"".$_POST["selectedText"]."\", ".$_POST["copyCount"].", \"".$_POST["copyText"]."\", ".$_POST["clickOnPurchaseCount"].", 
              ".$_POST["purchaseCount"].", ".$_POST["forwardingToLinkCount"].",\"".$_POST["forwardedToLink"]."\",\"".$_POST["logFile"]."\"                                                                                                                                     
         )
ON DUPLICATE KEY
UPDATE  `endDatetime`= \"".$_POST["endDatetime"]."\",
        `timeOnPage`= `timeOnPage` + VALUES(`timeOnPage`),
        `mouseClicksCount`= `mouseClicksCount` + VALUES(`mouseClicksCount`),
        `pageViewsCount`= `pageViewsCount` + VALUES(`pageViewsCount`),
        `mouseMovingTime`= `mouseMovingTime` + VALUES(`mouseMovingTime`),
        `mouseMovingDistance`= `mouseMovingDistance` + VALUES(`mouseMovingDistance`),
        `scrollingCount`= `scrollingCount` + VALUES(`scrollingCount`),
        `scrollingTime`= `scrollingTime` + VALUES(`scrollingTime`),
        `scrollingDistance`= `scrollingDistance` + VALUES(`scrollingDistance`),
        `printPageCount`= `printPageCount` + VALUES(`printPageCount`),
        `selectCount`= `selectCount` + VALUES(`selectCount`),
        `selectedText`= concat(`selectedText` , VALUES(`selectedText`)),
        `searchedText`= concat(`searchedText` , VALUES(`searchedText`)),
        `copyCount`= `copyCount` + VALUES(`copyCount`),
        `copyText`= concat(`copyText` , VALUES(`copyText`)),
        `clickOnPurchaseCount`= `clickOnPurchaseCount` + VALUES(`clickOnPurchaseCount`),
        `purchaseCount`= `purchaseCount` + VALUES(`purchaseCount`),
        `forwardingToLinkCount`= `forwardingToLinkCount` + VALUES(`forwardingToLinkCount`),
        `forwardedToLink`= concat( `forwardedToLink` , VALUES(`forwardedToLink`) ),
        `logFile`= concat(`logFile`, VALUES(`logFile`) )
";

$id = $_POST["userID"];
$qry="update members set member_online=now() where member_id=$id";
$resp = mysql_query($qry);
mysql_query("SET character_set_client=UTF8");
mysql_query($sql) ;
$logfile = $_POST["logFile"];
$member_id = $_POST["userID"];
$logfile_lines = explode("\n",$logfile);
foreach ($logfile_lines as $logfile_line) {
	if (strpos($logfile_line, 'MouseClick') !== false) {
		$oid = explode("oid=",$logfile_line)[1];
		$oid = explode(";",$oid)[0];
		if($oid != "null"){
			if (is_numeric($oid)) {
				$oid = intval($oid);
				$qry="SELECT * FROM keywords WHERE bbc_id in (SELECT bbc_id from bbc_programs WHERE id = $oid)";
				$result=mysql_query($qry); 
				if($result) {
					while ($row = mysql_fetch_assoc($result)) {
						$keyword = $row['keywords'];
						$relevance = $row['relevance'];
						$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
						if(mysql_num_rows($check_tags) == 0) {
							$qry="insert into interest_area (member_id,tag,interest_value) values ($member_id,'$keyword',$relevance)";
							mysql_query($qry); 
				
						} else {
							$qry="update interest_area set interest_value = interest_value + $relevance WHERE member_id=$member_id and tag='$keyword'";
							mysql_query($qry); 
						
						}
					}
					
				}
			}
			elseif ( strpos($oid, 'tags') !== false ){
				$oid = explode("tags_",$oid)[1];
				$keyword = explode(";",$oid)[0];
				$relevance = 7;
				$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
				if(mysql_num_rows($check_tags) == 0) {
					$qry="insert into interest_area (member_id,tag,interest_value,watched) values ($member_id,'$keyword',$relevance,1)";
					$result=mysql_query($qry); 
		
				} else {
					$qry="update interest_area set interest_value = interest_value + $relevance and watched = 1 WHERE member_id=$member_id and tag='$keyword'";
					$result=mysql_query($qry); 
				}	
			}
			elseif ( strpos($oid, 'play') !== false ){
				$oid = explode("play",$oid)[1];
				$oid_start_end = explode("_",$oid);
				$oid = $oid_start_end[1];
				$start = $oid_start_end[2];
				$end = explode(";",$oid_start_end[3])[0];
				$oid = intval($oid);
				$qry="SELECT keywords,relevance FROM keywords WHERE bbc_id in (SELECT bbc_id from bbc_programs WHERE id = $oid)";
				$result=mysql_query($qry); 
				if($result) {
					while ($row = mysql_fetch_assoc($result)) {
						$keyword = $row['keywords'];
						$relevance = $row['relevance']*3;
						$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
						if(mysql_num_rows($check_tags) == 0) {
							$qry="insert into interest_area (member_id,tag,interest_value) values ($member_id,'$keyword',$relevance)";
							$result=mysql_query($qry); 
						} else {
							$qry="update interest_area set interest_value = interest_value + $relevance WHERE member_id=$member_id and tag='$keyword'";
							$result=mysql_query($qry); 
						}
					}
				}
				$qry="SELECT keyword FROM keyword_scenes WHERE bbc_id in (SELECT bbc_id from bbc_programs WHERE id = $oid) and start_scene = $start and end_scene = $end";
				$result=mysql_query($qry); 
				if($result) {
					while ($row = mysql_fetch_assoc($result)) {
						$keyword = $row['keyword'];
						$check_tags = mysql_query("SELECT * FROM interest_area WHERE member_id=$member_id and tag='$keyword'");
						if(mysql_num_rows($check_tags) != 0) {
							$qry="update interest_area set watched = 1 WHERE member_id=$member_id and tag='$keyword'";
							$result=mysql_query($qry); 
						} 
					}
				}
				
			}
		}
	}
}
print_r($logfile_lines);
            print_r($_POST);
            echo $sql;
            echo mysql_error();
            if(!$_POST["visitID"]>0){
                echo mysql_insert_id();
            }else{
                echo $_POST["visitID"];
            }



?>

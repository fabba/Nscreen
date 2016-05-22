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
	$required = array('gender', 'age', 'education', 'genre', 'format', 'morning', 'afternoon', 'evening', 'night', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	$error = false;
	foreach($required as $field) {
		if (empty($_POST[$field])) {
			$error = true;
		}
	}

	if ($error) {?>
		<script language="javascript" type="text/javascript">
        alert('Please fill in all the personal questions!');
        window.location = 'member-index.php';
		</script>
		<?php
	} else {
		$member_id = $_SESSION['SESS_MEMBER_ID'];
		$gender = $_POST['gender'];
		$age = $_POST['age'];
		$education = $_POST['education'];
		$origin = $_POST['country'];
		$genre = implode(",", $_POST['genre']);
		$format = implode(",", $_POST['format']);
		$morning = $_POST['morning'];
		$afternoon = $_POST['afternoon'];
		$evening = $_POST['evening'];
		$night = $_POST['night'];
		$monday = $_POST['Monday'];
		$tuesday = $_POST['Tuesday'];
		$wednesday = $_POST['Wednesday'];
		$thursday = $_POST['Thursday'];
		$friday = $_POST['Friday'];
		$saturday = $_POST['Saturday'];
		$sunday = $_POST['Sunday'];
		$qry="update members set age='$age',gender='$gender',origin='$origin',fav_format='$format',fav_genre='$genre',
		pref_morning='$morning',pref_afternoon='$afternoon',pref_evening='$evening',pref_night='$night',
		pref_monday='$monday',pref_tuesday='$tuesday',pref_wednesday='$wednesday',pref_thursday='$thursday',pref_friday='$friday',pref_saturday='$saturday',pref_sunday='$sunday'
		where member_id = $member_id";
		$result = mysql_query($qry);
		?>
		<script language="javascript" type="text/javascript">
        window.location = 'member-index.php';
		</script>
		<?php
		
	}
	
?>
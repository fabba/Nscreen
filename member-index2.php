<?php
  require_once('auth.php');
?>
<!DOCTYPE html>
<!--
 --------------------------------------------------------------------------
 Copyright 2012 British Broadcasting Corporation and Vrije Universiteit 
 Amsterdam
 
    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at
 
        http://www.apache.org/licenses/LICENSE-2.0
 
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
 --------------------------------------------------------------------------
-->
<html>
 <head>
  <title>N-Screen</title>
    
    <script type="text/javascript" src="lib/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="lib/jquery-ui-1.8.10.custom.min.js"></script>
    <script type="text/javascript" src="lib/jquery.ui.touch-punch.min.js"></script> 

    <script type="text/javascript" src="lib/strophe.js"></script>
    <script type="text/javascript" src="lib/buttons.js"></script>
    <script type="text/javascript" src="lib/spin.min.js"></script>
    <script type="text/javascript" src="lib/play_video.js"></script>

  	<link type="text/css" rel="stylesheet" href="css/new.css" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	 <link href="css/agency.css" rel="stylesheet">
 	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<!-- link IPIget component -->    
<script language="JavaScript" type="text/javascript" src="/IPIget-master/component/client-side/TraceUser.js"></script>  
<script type="text/javascript" language="javascript" src="/IPIget-master/component/client-side/AjaxRequest.js"></script>  

 </head>


<body onload="javascript:init()">

<!-- workaround for audio problems on ipad - http://stackoverflow.com/questions/2894230/fake-user-initiated-audio-tag-on-ipad -->

<div id="junk" style="display:none">
  <audio src="sounds/x4.wav" id="a1" preload="auto"  autobuffer="autobuffer" controls="" ></audio>
  <audio src="sounds/x1.wav" id="a2" preload="auto"  autobuffer="autobuffer" controls="" ></audio>
</div>

<script type="text/javascript">

        
var userID = <?php echo $_SESSION['SESS_MEMBER_ID']; ?>;
var objectID = 0;
var pageID = "index";    
var sessionID = "<?php echo session_id() ?>";    
var pageType = "index";

//the main buttons object
var buttons = null;

//if there's more than one TV you'll get more than one message so this is to limit the duplication
var lastmsg="";
//this is so we can link to the TV and perhaps the api easily
var clean_loc = String(window.location).replace(/\#.*/,"");

//group name is based on the part after # in the url, handled in init()
var my_group=null;

//for api calls
//could be a web service
var api_root = "data/";

var recommendations_url=api_root+"recommendations.js"
var channels_url=api_root+"channels.js";
var search_url = api_root+"search.js";
var start_url = "get_channel.php";
var random_url = "get_random.php"

//jabber server
var server = "localhost";
//var server = "jabber.notu.be";

//polling interval (for changes to channels)
var interval = null;

//handle back and forward navigating
var overlay_navigation = [];
var overlaycounter = null;

//var list = new Array(); //initialazing array

// Set of local variables that help us to store later (if so)

var tags = []
var scenes_tags = []
var list_watch_later = [];
var watch_later_json = {};
var list_watch_later_TED = [];
var list_watch_later_BBC = [];
var list_likes_TED = [];
var list_likes_BBC = [];
var list_dislikes_TED = [];
var list_dislikes_BBC = [];
var list_recently_viewed = [];
var recently_viewed_json = {};

var list_shared_by_friends = [];
var shared_by_friends_json = {};

var list_likes = [];
var list_dislikes = [];

var likes_json = {};
var real_likesdislikes_json = {}; //to update later
var dislikes_json = {};

var random_json = {};
var recommendations_json = {};

var related_json = {};

var ted_key = "xbsdfg4uhxf6prsp8c7adrty";
var ted_api_request = "https://api.ted.com/v1/talks.json?api-key=xbsdfg4uhxf6prsp8c7adrty"
var ted_api_filter_id= "filter=id:>"; //Remember to make "&" between them and provide int!!
var ted_api_filter_limit = "limit=";
var ted_api_filter_offset = "offset=";
var local_search = false;
var suggestions = null;   

$.fn.hasOverflow = function() {
    var $this = $(this);
    var $children = $this.find('*');
    var len = $children.length;

    if (len) {
        var maxWidth = 0;
        var maxHeight = 0
        $children.map(function(){
            maxWidth = Math.max(maxWidth, $(this).outerWidth(true));
            maxHeight = Math.max(maxHeight, $(this).outerHeight(true));
        });

        return maxWidth > $this.width() || maxHeight > $this.height();
    }

    return false;
};

window.onresize = function(event) {

    if ($("#programmes")[0].scrollHeight > $("#programmes").innerHeight()) {
     $('#moreblue').show();
   }
   else{
    $('#moreblue').hide();
   }
   if ($("#side-b")[0].scrollHeight > $("#side-b").innerHeight()) {
     $('#moreblue1').show();
   }
   else{
    $('#moreblue1').hide();
   }
   if ($("#content")[0].scrollHeight > $("#content").innerHeight()) {
     $('#moreblue2').show();
   }
   else{
    $('#moreblue2').hide();
   }
   if ($("#content2")[0].scrollHeight > $("#content2").innerHeight()) {    
     $('#moreblue3').show();
   }
   else{
    $('#moreblue3').hide();
   }
   if ($("#content3")[0].scrollHeight > $("#content").innerHeight()) {
      $('#moreblue4').show();
   }
   else{
    $('#moreblue4').hide();
   }
   if ($("#content4")[0].scrollHeight > $("#content2").innerHeight()) {    
      $('#moreblue5').show();
   }
   else{
    $('#moreblue5').hide();
   }
  if ($("#content5")[0].scrollHeight > $("#content2").innerHeight()) {    
    $('#moreblue6').show();
  }
  else{
    $('#moreblue6').hide();
  }
    
};

function check_overflow(){
 if ($("#programmes")[0].scrollHeight > $("#programmes").innerHeight()) {
     $('#moreblue').show();
   }
   else{
    $('#moreblue').hide();
   }
   if ($("#side-b")[0].scrollHeight > $("#side-b").innerHeight()) {
     $('#moreblue1').show();
   }
   else{
    $('#moreblue1').hide();
   }
   if ($("#content")[0].scrollHeight > $("#content").innerHeight()) {
     $('#moreblue2').show();
   }
   else{
    $('#moreblue2').hide();
   }
   if ($("#content2")[0].scrollHeight > $("#content2").innerHeight()) {    
     $('#moreblue3').show();
   }
   else{
    $('#moreblue3').hide();
   }
   if ($("#content3")[0].scrollHeight > $("#content").innerHeight()) {
      $('#moreblue4').show();
   }
   else{
    $('#moreblue4').hide();
   }
   if ($("#content4")[0].scrollHeight > $("#content2").innerHeight()) {    
      $('#moreblue5').show();
   }
   else{
    $('#moreblue5').hide();
   }
  if ($("#content5")[0].scrollHeight > $("#content2").innerHeight()) {    
    $('#moreblue6').show();
  }
  else{
    $('#moreblue6').hide();
  }
}

function init(){
//detect ipads etc
   //console.log("platform "+navigator.platform);
   if(navigator.platform.indexOf("iPad") != -1 || navigator.platform.indexOf("Linux armv7l") != -1){
       $("#inner").addClass("inner_noscroll");
       $(".slidey").addClass("slidey_noscroll");
       $("#search_results").css("width","80%");
   }else{
     $("#inner").addClass("inner_scroll");
   }

   create_buttons();

   // $("#main_title").html("N-SCREEN");

   $sr=$("#search_results");
   $sr.css("display","none");
   // $container=$("#browser");
   // $container.css("display","block");

   // $browse=$("#browse");
   // $browse.addClass("blue").removeClass("grey");

   // $random=$("#random");
   // $random.addClass("grey").removeClass("blue");
  
   //clean_loc = String(window.location);
   //window.location.hash=my_group;
   //$("#group_name").html(my_group);
   // $("#grp_link").html(clean_loc+"#"+my_group);
   // $("#grp_link").attr("href",clean_loc+"#"+my_group);
  // var state = {"canBeAnything": true};
   $(".more_blue").hide();
   add_name();


   //load the start url or random if no start_url (see conf.js)
   //get a random set of starting points


   // ??????????????????????????????????????????????????
   //do_start("progs","get_suggestions.php");

}

function add_name(){
  
  var name = get_name();
  if(name){

    var me = new Person(name,name);
    buttons.me = me;
    $(document).trigger('send_name');
	update_online_members();
	setInterval(update_online_members, 60*1000);
	html_likes = [];
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
		$member_id = $_SESSION['SESS_MEMBER_ID'];
		$qry="SELECT gender,age,education,origin,fav_genre,fav_format,pref_morning,pref_afternoon,pref_evening,pref_night,pref_morning_weekend,pref_afternoon_weekend,pref_evening_weekend,pref_night_weekend FROM members where member_id=$member_id";
		$result=mysql_query($qry);   
		while ($row = mysql_fetch_array($result)){ 
			$gender = $row[0];
			$age = $row[1];
			$education = $row[2];
			$origin = $row[3];
			$genre = $row[4];
			$format = $row[5];
			$mor = $row[6];
			$aft = $row[7];
			$even = $row[8];
			$night = $row[9];
			$morw = $row[10];
			$aftw = $row[11];
			$evenw = $row[12];
			$nightw = $row[13];
			?>
			var age = '<?php echo $row[1]; ?>';
		<?php
		}
		?>
		if( age == ''){
			$("#questions").show();
			$("#welcome").show();
			$("#inner").hide();
			$('#personalForm').submit(function (e) {
			e.preventDefault();
			var error_gender = "Please fill in a gender";
			var error_age = "Please fill in a age";
			var error_education = "Please fill in your study";
			var error_origin = "Please fill in your origin";
			var error_format = "Please select your favourite format";
			var error_genre = "Please select your favourite genre";
			var error_weekdays = "Please fill in all you tv preferences";
			var error_weekend = "Please fill in all you tv preferences";
			var data = $(this).serializeArray();
			var weekdays = 0;
			var weekend = 0;
			var count = 0;
			jQuery.each( data, function( i, field ) {
				if ( field.name == 'gender'){
					error_gender = "";
					count += 1;
				}
				if ( field.name == 'age'){
					error_age = "";
					count += 1;
				}
				if ( field.name == 'genre[]'){
					error_genre = "";
				
				}
				if ( field.name == 'education'){
					error_education = "";
					count += 1;
				}
				if ( field.name == 'country'){
					error_origin = "";
					count += 1;
				}
				if ( field.name == 'format[]'){
					error_format = "";
				}
				if (( field.name == 'morning') || (field.name == 'afternoon') || (field.name == 'evening') || (field.name == 'night')){
					weekdays += 1;
				}
				if (( field.name == 'morning_weekend') || (field.name == 'afternoon_weekend') || (field.name == 'evening_weekend') || (field.name == 'night_weekend')){
					weekend += 1;
				}
				
			});
			if ( error_genre == ""){
				count += 1;
				}
				if ( error_format == ""){
				count += 1;
				}
			if ( weekdays == 4){
				error_weekdays = "";
				count += 1;
			}
			if ( weekend == 4 ){
				error_weekend = "";
				count += 1;
			}
			if ( count == 8 ){
			$.ajax({
				type: 'post',
				url: 'insert_personal_inf.php',
				data: data,
				success: function (data) {
						$("#inner").show();
						$("#questions").hide();
					}
				});}else{
					document.getElementById("gender-error").innerHTML = error_gender;
					document.getElementById("age-error").innerHTML = error_age;
					document.getElementById("education-error").innerHTML = error_education;
					document.getElementById("origin-error").innerHTML = error_origin;
					document.getElementById("genre-error").innerHTML = error_genre;
					document.getElementById("format-error").innerHTML = error_format;
					document.getElementById("weekdays-error").innerHTML = error_weekdays;
					document.getElementById("weekend-error").innerHTML = error_weekend;

					show_next_questions(0)
				}
			}) 
		}
		else{
			$("#inner").show();
			$("#questions").hide();
			}
    $.ajax({
      type: "POST",
      url: "get_likes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_BBC.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}
		
	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_likes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_TED.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html_dislikes =[]
	 $.ajax({
      type: "POST",
      url: "get_dislikes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_BBC.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_dislikes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_TED.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html =[]
	 $.ajax({
      type: "POST",
      url: "get_later_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_BBC.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_later_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_TED.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	html_likes = [];
    $.ajax({
      type: "POST",
      url: "get_likes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_BBC.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}
		
	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_likes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_TED.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html_dislikes =[]
	 $.ajax({
      type: "POST",
      url: "get_dislikes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_BBC.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_dislikes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_TED.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html =[]
	 $.ajax({
      type: "POST",
      url: "get_later_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_BBC.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_later_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_TED.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	if( html != ""){
			$("#list_later").html(html.join(''));}
		else{
			$("#list_later").html("<div class='dotted_box'><br><br><br>No programs in this section yet </div>");
		}

	$("#list_dislikes").html(html_dislikes.join(''));
	$("#list_likes").html(html_likes.join(''));
	var html = [];
	$.ajax({
      type: "POST",
      url: "get_random.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]

		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	$("#programs").html(html.join(''));
	var html = [];
	$.ajax({
      type: "POST",
      url: "get_random.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	$("#results_format").html(html.join(''));

  }
  var state = {"canBeAnything": true};
  //history.pushState(state, "N-Screen", "/N-Screen/");
  //window.location.hash=my_group;
  $("#logoutspan").show();
}

//creates and initialises the buttons object                              

function get_symbols(){
	list_watch_later_TED = [];
	list_watch_later_BBC = [];
	list_likes_TED = [];
	list_likes_BBC = [];
	list_dislikes_TED = [];
	list_dislikes_BBC = [];
   $.ajax({
      type: "POST",
      url: "get_likes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_BBC.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}
		
	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_likes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_TED.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html_dislikes =[]
	 $.ajax({
      type: "POST",
      url: "get_dislikes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_BBC.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_dislikes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_TED.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html =[]
	 $.ajax({
      type: "POST",
      url: "get_later_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_BBC.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_later_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_TED.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	html_likes = [];
    $.ajax({
      type: "POST",
      url: "get_likes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_BBC.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}
		
	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_likes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		list_likes_TED.push(dat['id']);
		html_likes = create_html(html_likes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html_dislikes =[]
	 $.ajax({
      type: "POST",
      url: "get_dislikes_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_BBC.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_dislikes_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_dislikes_TED.push(dat['id']);
		html_dislikes = create_html(html_dislikes,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	html =[]
	 $.ajax({
      type: "POST",
      url: "get_later_bbc.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_BBC.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 $.ajax({
      type: "POST",
      url: "get_later_ted.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		list_watch_later_TED.push(dat['id']);
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	
	
	
}
function create_buttons(){
   //$("#inner").addClass("inner_noscroll");
   // $(".slidey").addClass("slidey_noscroll");
   //$(".about").hide();
   // $("#header").show();
    $("#roster_wrapper").show();
    
   //set up notifications area
   $("#notify").toggle(
     function (){
       //console.log("SHOW");
       $("#notify_large").show();
     },
     function (){
       //console.log("HIDE");
       $("#notify_large").hide();
       $("#notify").html("");
       $("#notify_large").html("");
       $("#notify").hide();
     }
   );

   //initialise buttons object and start the link
   buttons = new ButtonsLink({"server":server});
}

// called when buttons link is created

function blink_callback(blink){
  // console.log("INSIDE BLINK CALLBACK --> GOING TO CALL GET CHANNEL")
  //var delay = 60000;

  //interval = setInterval(get_channels, delay);

  get_roster(blink);

  // ???????????????????????????????????????????

  // $(document).trigger('refresh');
  // $(document).trigger('refresh_buttons');
  // $(document).trigger('refresh_group');
  // $(document).trigger('refresh_history');
  // $(document).trigger('refresh_recs');
  // $(document).trigger('refresh_search');

  // //my new channels
  // $(document).trigger('refresh_later');
  // $(document).trigger('refresh_ld');
}

//http://fgnass.github.com/spin.js/
var opts = {
  lines: 12, // The number of lines to draw
  length: 5, // The length of each line
  width: 3, // The line thickness
  radius: 5, // The radius of the inner circle
  color: '#fff', // #rbg or #rrggbb
  speed: 1, // Rounds per second
  trail: 100, // Afterglow percentage
  shadow: true // Whether to render a shadow
};

//UPDATE REGARDING COLUMN IN CONTENT DATABASE 
// watch_later, recently_viewed, shared_by_friends....
function update_channel(channel, data){

  if(channel == "like_dislike"){
    console.log(JSON.stringify(real_likesdislikes_json));
    real_likesdislikes_json.likes = likes_json.suggestions;
    real_likesdislikes_json.dislikes = dislikes_json.suggestions;
    console.log(JSON.stringify(real_likesdislikes_json));
    $.ajax({
      url: "set_channel.php",
      type: "POST",
      data: {data : JSON.stringify(real_likesdislikes_json), channel : channel},
      dataType: "json",
      success: function (response) {
          console.log("Correct " + channel + " updated");
      }
    });
  }
  else{
    $.ajax({
      url: "set_channel.php",
      type: "POST",
      data: {data : JSON.stringify(data), channel : channel},
      dataType: "json",
      success: function (response) {
          console.log("Correct " + channel + " updated");
      }
    });
  }
}


//display suggestions based on id 
// RECENTLY VIEWED !!

function insert_suggest2(id) {

  // console.log("list shared by friends");
  // console.log(list_shared_by_friends);
      if (overlaycounter == null){
        overlaycounter = 0;
      }
      else{
        overlaycounter ++;
      }
      overlay_navigation.splice(overlaycounter, 0, id);
      for (var i= overlay_navigation.length - 1; i > overlaycounter ; i--){
        overlay_navigation.splice(i, 1);
      }
      var item = {};

      var flag = false //to set recently viewed icon or not

      //code to check how to place the element in recently viewed 
      if(update_list(id,list_recently_viewed) == false){ //this means that the element IS already in the list
        flag = true;
        $('#history').find("#"+id).remove();
        for(var i in recently_viewed_json.suggestions){
          if(recently_viewed_json.suggestions[i].pid == ('' + id)){
            // console.log("inside if");
            item = recently_viewed_json.suggestions[i]; 
            recently_viewed_json.suggestions.splice(i,1); //we remove the item from the local json
          }
        }recently_viewed_json.suggestions.splice(0,0,item);
      }
      else{
        $.ajax({
          url: "get_tedtalks_by_id.php",
          type: "POST",
          async: false,
          data: {id: id},
          dataType: "json",
          success: function (data) {
              item =  changeData(data); //JSON with suggestions format
              recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
          }
        });
      }//recently_viewed_json.suggestions[0] is the current video now displayed   
      update_channel("recently_viewed", recently_viewed_json);
      var div = $("#"+id);

      var speaker = (/(.*):.*?/.exec(recently_viewed_json.suggestions[0].title))[1];
      var title = (/.*?:(.*)/.exec(recently_viewed_json.suggestions[0].title))[1];
      var description = recently_viewed_json.suggestions[0].description;
      // var tags = lalala **********************TO DO*********************
      var video = recently_viewed_json.suggestions[0].video;
      var pid = recently_viewed_json.suggestions[0].pid;
      var img = recently_viewed_json.suggestions[0].image;
      var speaker_id = recently_viewed_json.suggestions[0].speaker[0].speaker.id;
      console.log("THIS IS SPEKAER ID");
      console.log(speaker_id);

      var tags = Object.keys(recently_viewed_json.suggestions[0]["tags"]);
      // var tags = (/[^,]*/.exec(tags));
      // tags = (/[^, ]*/.exec(tags)); //array of tags
      html = [];
      html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
      
      html.push("<img class=\"img img_small\" src=\""+img+"\" />");
      html.push("<div style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");

      html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
      html.push("<p class=\"description large\">"+description+"</p>");
      html.push("</div>");
      $('#history').prepend(html.join(''));


      html2 = [];

      html2.push("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div class='navigation_buttons'><img onclick='javascript:navigation(-1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='back' src='images/icons/backward.png' width='30'/><img onclick='javascript:navigation(+1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='forward' src='images/icons/forward.png' width='30'/></div>");
      html2.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\"  class=\"large_prog\" style=\"position: relative;\">");
      html2.push("<div class=\"gradient_div\" style=\"text-align: center;  margin-left: 45%; position: absolute; \"> <img class=\"img\" src=\""+img+"\" />");
      html2.push("<div class=\"play_button\"><img style='width: 120px;' src=\"images/icons/play.png\" /></a></div></div>");
      html2.push("<div style='padding-left: 20px; padding-right: 20px; width: 50%; left: 0px; position: absolute;'>");
      html2.push("<div style ='cursor: pointer;'class=\"p_title_large_speaker\" onclick=\"javascript:insert_speaker("+speaker_id+");return true\">"+speaker+':'+"</div>");
      html2.push("<div class=\"p_title_large\">"+title+"</div>");
      html2.push("<p class=\"description\">"+description+"</p>");
      html2.push("<div class=\"list_tags\" style='display:inline;'>");
      for(var i=0; i<tags.length; i++){
        if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){ }
        else{
          html2.push("<span class=\"item_tag\" onclick=\"javascript:insert_suggest_by_tag('"+tags[i]+"');return true\">#"+tags[i]+"</span>");
        }
        
      }
      html2.push("</div>");
      // html2.push("<p class=\"explain\">"+explanation+"</p>");
//      html2.push("<p class=\"keywords\">"+keywords+"</p>");
      html2.push("<p class=\"link\"><a href=\"http://www.ted.com/talks/view/id/"+pid+"\" target=\"_blank\">Sharable Link</a></p></div>");

      html2.push("<div class='vertical_buttons' style='display:table-cell; vertical-align: middle; margin-right: 7%; position: absolute; text-align: center; right:0; top: 10px'>");

      if(not_in_list(id,list_watch_later)){
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='addtowatchlater' style='width: 40px;' src=\"images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span></div>");      
      }
      else{
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='deletewatchlater' style='width: 40px;' src=\"images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span></div>");      
      }

      if(not_in_list(id,list_likes)){
        html2.push("<div id='like' class=\"interactive_icon\"><img id='addtolike' style='width: 40px;' src=\"images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'>Like</span></div>");
      }
      else{
        html2.push("<div id='like' class=\"interactive_icon\"><img id='deletelike' style='width: 40px;' src=\"images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'>Like</span></div>");
      }
      if(not_in_list(id,list_dislikes)){
      html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'addtodislike' style='width: 40px;' src=\"images/icons/dislike.png\" /><span style='display: block'; class ='inter_span'>Dislike</span></div>");
      }
      else{
      html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'deletedislike' style='width: 40px;' src=\"images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'>Dislike</span></div>");
      }
      if(not_in_list(id,list_shared_by_friends)){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/shared.png\" /><span style='display: block'; class ='inter_span'>Shared by friends</span></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_shared.png\" /><span style='display: block'; class ='on_inter_span'>Shared by friends</span></div>");
      }
      if(flag == false){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/recently_viewed.png\" /><span style='display: block'; class ='inter_span'>Recenlty Viewed</span></div></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_recently_viewed.png\" /><span style='display: block'; class ='on_inter_span'>Recenlty Viewed</span></div></div>");
      }
      html2.push("</div>");
      html2.push("</div>");

      if(recently_viewed_json.suggestions[0].manifest){
      var manifest = recently_viewed_json.suggestions[0].manifest;
      var data =  recently_viewed_json.suggestions[0].manifest; 
      set_playable(data);

      // $.ajax({
      //  url: recently_viewed_json.suggestions[0].manifest,
      //  dataType: "json",
      //    success: function(data){
      //      set_playable(data);
      //    },
      //    error: function(jqXHR, textStatus, errorThrown){
      //      alert("oh dear "+textStatus);
      //    }
      // });
    }

      

      $('#new_overlay').html(html2.join(''));
    
      $('#new_overlay').show();
      show_grey_bg();

      // $(".play_button").live( "click", function() {

      // console.log("PLAY PRESSED!!!");
      //   var res = {};
      //   // res["id"]=id;
      //   res["pid"]=pid;
      //   res["title"]=title;
      //   res["video"]=video;
      //   res["description"]=description;
      //   // res["explanation"]=explanation;
      //   res["img"]=id;
      //   sendProgrammeTVs(res,my_tv); 
      //   return false;

      // });


      $('#new_overlay').append("<div id=\"more_like_this\" class=\"more_like_this\" style=\"margin-top: 400px;\"><span class=\"sub_title\">MORE LIKE THIS</span><span class=\"more_blue\"><a id ='more_related' onclick='show_related();''>View All &triangledown;</a></span></div>");
      // $('#new_overlay').append("<br clear=\"both\"/>");
      $('#new_overlay').append("<div id='spinner' style=\"float: left;\"></div>");
      $('#new_overlay').append("<div class='clear'></div>");
      check_overflow();
      
      // var target = document.getElementById('spinner');//??
      // var spinner = new Spinner(opts).spin(target);

      for(var i=0; i<tags.length; i++){
      //console.log("THIS IS TAG " + tags[i]);
      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
      else {
        tag = tags[i];
        break;
        }
      }

      $.ajax({
       url: "get_tedtalks_related.php",
       data: {tag : tag},
       type: 'POST',
       dataType: "json",
         success: function(data){
          var related = changeData(data);
          related_json = related;
           recommendations(related,"spinner",false,title);
//           recommendations(data,"new_overlay",false,title);
         },
         error: function(jqXHR, textStatus, errorThrown){
         //alert("oh dear "+textStatus);
         }
      });

}

function insert_speaker(id) {

  // console.log("list shared by friends");
  // console.log(list_shared_by_friends);
  // var item;
      // if (overlaycounter == null){
      //   overlaycounter = 0;
      // }
      // else{
      //   overlaycounter ++;
      // }
  //     overlay_navigation.splice(overlaycounter, 0, id);
  //     for (var i= overlay_navigation.length - 1; i > overlaycounter ; i--){
  //       overlay_navigation.splice(i, 1);
  //     }
      var item = {};

      var flag = false //to set recently viewed icon or not
        $.ajax({
          url: "get_speaker.php",
          type: "POST",
          async: false,
          data: {id: id},
          dataType: "json",
          success: function (data) {
              item =  data; //JSON with suggestions format
          }
        });

      var div = $("#"+id);
      var speaker = item["speakers"][0].speaker.firstname + " " + item.speakers[0].speaker.lastname;
      var title = item.speakers[0].speaker.description;
      var description = item.speakers[0].speaker.whylisten;
      // var tags = lalala **********************TO DO*********************
      var video = item.speakers[0].speaker.photo_url;
      var pid = item.speakers[0].speaker.id;
      var img = item.speakers[0].speaker.photo_url;

      html = [];
      html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_speaker("+id+");return true\">");
      html.push("<img class=\"img img_small\" src=\""+img+"\" />");
            html.push("<div style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");
      html.push("<span class=\"p_title p_title_small\"><a>"+speaker+"</a></span>");
      html.push("<p class=\"description large\">"+title+"</p>");
      html.push("</div>");
      $('#history').prepend(html.join(''));


      html2 = [];

      html2.push("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div class='navigation_buttons'><img onclick='javascript:navigation(-1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='back' src='images/icons/backward.png' width='30'/><img onclick='javascript:navigation(+1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='forward' src='images/icons/forward.png' width='30'/></div>");
      html2.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\"  class=\"large_prog\" style=\"position: relative;\">");
      html2.push("<div class=\"gradient_div\" style=\"text-align: center;  margin-left: 59%; position: absolute; \"> <img class=\"img\" src=\""+img+"\" />");
      html2.push("</div>");
      html2.push("<div style='font-size: 0.9em; left: 0; padding-left: 19px; padding-right: 20px; position: absolute; width: 71%;'>");
      html2.push("<div class=\"p_title_large_speaker\">"+speaker+':'+"</div>");
      html2.push("<div>"+title+"</div>");
      html2.push("<p >"+description+"</p>");
      html2.push("<div class=\"list_tags\" style='display:inline;'>");
      html2.push("</div>");
      // html2.push("<p class=\"explain\">"+explanation+"</p>");
//      html2.push("<p class=\"keywords\">"+keywords+"</p>");
      html2.push("<p class=\"link\"><a href=\"http://www.ted.com/talks/view/id/"+pid+"\" target=\"_blank\">Sharable Link</a></p></div>");

      html2.push("<div class='vertical_buttons' style='display:table-cell; vertical-align: middle; margin-right: 7%; position: absolute; text-align: center; right:0; top: 10px'>");

    if(not_in_list(id,list_watch_later)){
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span></div>");      
      }
      else{
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span></div>");      
      }

      if(not_in_list(id,list_likes)){
        html2.push("<div id='like' class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'>Like</span></div>");
      }
      else{
        html2.push("<div id='like' class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'>Like</span></div>");
      }
      if(not_in_list(id,list_dislikes)){
      html2.push("<div id='dislike' class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/dislike.png\" /><span style='display: block'; class ='inter_span'>Dislike</span></div>");
      }
      else{
      html2.push("<div id='dislike' class=\"interactive_icon\"><img  style='width: 40px;' src=\"images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'>Dislike</span></div>");
      }
      if(not_in_list(id,list_shared_by_friends)){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/shared.png\" /><span style='display: block'; class ='inter_span'>Shared by friends</span></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_shared.png\" /><span style='display: block'; class ='on_inter_span'>Shared by friends</span></div>");
      }
      if(flag == false){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/recently_viewed.png\" /><span style='display: block'; class ='inter_span'>Recenlty Viewed</span></div></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_recently_viewed.png\" /><span style='display: block'; class ='on_inter_span'>Recenlty Viewed</span></div></div>");
      }
      html2.push("</div>");
      html2.push("</div>");
      

      $('#new_overlay').html(html2.join(''));
    
      $('#new_overlay').show();
      show_grey_bg();

      // $(".play_button").live( "click", function() {

      // console.log("PLAY PRESSED!!!");
      //   var res = {};
      //   // res["id"]=id;
      //   res["pid"]=pid;
      //   res["title"]=title;
      //   res["video"]=video;
      //   res["description"]=description;
      //   // res["explanation"]=explanation;
      //   res["img"]=id;
      //   sendProgrammeTVs(res,my_tv); 
      //   return false;

      // });


      $('#new_overlay').append("<div id=\"more_like_this\" class=\"more_like_this\" style=\"margin-top: 400px;\"><span class=\"sub_title\">" + speaker + " talks: </span><span class=\"more_blue\"><a id ='more_related' onclick='show_related();''>View All &triangledown;</a></span></div>");
      // $('#new_overlay').append("<br clear=\"both\"/>");
      $('#new_overlay').append("<div id='spinner' style=\"float: left;\"></div>");
      $('#new_overlay').append("<div class='clear'></div>");
      check_overflow();

      var speaker_talks = {
          suggestions: []
        };

  for(var i = 0; i < item.speakers[0].speaker.talks.length; i++) {  
    var something = item.speakers[0].speaker.talks[i];
    var talk_id = something.talk.id;
    var talk;

    $.ajax({
        url: "get_tedtalks_by_id.php",
        type: "POST",
        async: false,
        data: {id: talk_id},
        dataType: "json",
        success: function (data) {
            talk =  changeData(data); //JSON with suggestions format
        }
      });
    talk = talk.suggestions[0];
      speaker_talks.suggestions.push({ 
          "pid"   : talk.pid,
          "title" : talk.title,          
          "description" : talk.description,
          "date_time" : talk.date_time,
          // "media_profile_uris" : item.talk.media_profile_uris,
          "url" : talk.url, //TODO CHANGE THIS
          "video" : talk.video,
          "speaker" : talk.speaker,
          "image" : talk.image,
          "manifest" : talk.manifest,
          "tags" : talk.tags,
      });

      recommendations(speaker_talks,"spinner",false,title);

}
}

function navigate_by_id(id) {
      var item = {};

      var flag = false //to set recently viewed icon or not
      $.ajax({
        url: "get_tedtalks_by_id.php",
        type: "POST",
        async: false,
        data: {id: id},
        dataType: "json",
        success: function (data) {
            item =  changeData(data); //JSON with suggestions format
        }
      });  
      var div = $("#"+id);

      var speaker = (/(.*):.*?/.exec(item.suggestions[0].title))[1];
      var title = (/.*?:(.*)/.exec(item.suggestions[0].title))[1];
      var description = item.suggestions[0].description;
      // var tags = lalala **********************TO DO*********************
      var video = item.suggestions[0].video;
      var pid = item.suggestions[0].pid;
      var img = item.suggestions[0].image;
    var speaker_id = item.suggestions[0].speaker[0].speaker.id;



      var tags = Object.keys(item.suggestions[0]["tags"]);
      // var tags = (/[^,]*/.exec(tags));
      // tags = (/[^, ]*/.exec(tags)); //array of tags
      html = [];
      html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
      html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
      html.push("<p class=\"description large\">"+description+"</p>");
      html.push("</div>");
      $('#history').prepend(html.join(''));


      html2 = [];

      html2.push("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div class='navigation_buttons'><img onclick='javascript:navigation(-1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='back' src='images/icons/backward.png' width='30'/><img onclick='javascript:navigation(+1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='forward' src='images/icons/forward.png' width='30'/></div>");
      html2.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\"  class=\"large_prog\" style=\"position: relative;\">");
      html2.push("<div class=\"gradient_div\" style=\"text-align: center;  margin-left: 45%; position: absolute; \"> <img class=\"img\" src=\""+img+"\" />");
      html2.push("<div class=\"play_button\"><img style='width: 120px;' src=\"images/icons/play.png\" /></a></div></div>");
      html2.push("<div style='padding-left: 20px; padding-right: 20px; width: 50%; left: 0px; position: absolute;'>");
      html2.push("<div style ='cursor: pointer;'class=\"p_title_large_speaker\" onclick=\"javascript:insert_speaker("+speaker_id+");return true\">"+speaker+':'+"</div>");
      html2.push("<div class=\"p_title_large\">"+title+"</div>");
      html2.push("<p class=\"description\">"+description+"</p>");
      html2.push("<div class=\"list_tags\" style='display:inline;'>");
      for(var i=0; i<tags.length; i++){
        if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){ }
        else{
          html2.push("<span class=\"item_tag\" onclick=\"javascript:insert_suggest_by_tag('"+tags[i]+"');return true\">#"+tags[i]+"</span>");
        }
        
      }
      html2.push("</div>");
      // html2.push("<p class=\"explain\">"+explanation+"</p>");
//      html2.push("<p class=\"keywords\">"+keywords+"</p>");
      html2.push("<p class=\"link\"><a href=\"http://www.ted.com/talks/view/id/"+pid+"\" target=\"_blank\">Sharable Link</a></p></div>");

      html2.push("<div class='vertical_buttons' style='display:table-cell; vertical-align: middle; margin-right: 7%; position: absolute; text-align: center; right:0; top: 10px'>");

      if(not_in_list(id,list_watch_later)){
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='addtowatchlater' style='width: 40px;' src=\"images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span></div>");      
      }
      else{
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='deletewatchlater' style='width: 40px;' src=\"images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span></div>");      
      }

      if(not_in_list(id,list_likes)){
        html2.push("<div id='like' class=\"interactive_icon\"><img id='addtolike' style='width: 40px;' src=\"images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'>Like</span></div>");
      }
      else{
        html2.push("<div id='like' class=\"interactive_icon\"><img id='deletelike' style='width: 40px;' src=\"images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'>Like</span></div>");
      }
      if(not_in_list(id,list_dislikes)){
      html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'addtodislike' style='width: 40px;' src=\"images/icons/dislike.png\" /><span style='display: block'; class ='inter_span'>Dislike</span></div>");
      }
      else{
      html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'deletedislike' style='width: 40px;' src=\"images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'>Dislike</span></div>");
      }
      if(not_in_list(id,list_shared_by_friends)){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/shared.png\" /><span style='display: block'; class ='inter_span'>Shared by friends</span></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_shared.png\" /><span style='display: block'; class ='on_inter_span'>Shared by friends</span></div>");
      }
      if(flag == false){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/recently_viewed.png\" /><span style='display: block'; class ='inter_span'>Recenlty Viewed</span></div></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"images/icons/on_recently_viewed.png\" /><span style='display: block'; class ='on_inter_span'>Recenlty Viewed</span></div></div>");
      }
      html2.push("</div>");
      html2.push("</div>");

      if(item.suggestions[0].manifest){
      var manifest = item.suggestions[0].manifest;
      var data =  item.suggestions[0].manifest; 
      set_playable(data);

      // $.ajax({
      //  url: recently_viewed_json.suggestions[0].manifest,
      //  dataType: "json",
      //    success: function(data){
      //      set_playable(data);
      //    },
      //    error: function(jqXHR, textStatus, errorThrown){
      //      alert("oh dear "+textStatus);
      //    }
      // });
    }

      

      $('#new_overlay').html(html2.join(''));
    
      $('#new_overlay').show();
      show_grey_bg();

      // $(".play_button").live( "click", function() {

      // console.log("PLAY PRESSED!!!");
      //   var res = {};
      //   // res["id"]=id;
      //   res["pid"]=pid;
      //   res["title"]=title;
      //   res["video"]=video;
      //   res["description"]=description;
      //   // res["explanation"]=explanation;
      //   res["img"]=id;
      //   sendProgrammeTVs(res,my_tv); 
      //   return false;

      // });


      $('#new_overlay').append("<div id=\"more_like_this\" class=\"more_like_this\" style=\"margin-top: 400px;\"><span class=\"sub_title\">MORE LIKE THIS</span><span class=\"more_blue\"><a id ='more_related' onclick='show_related();''>View All &triangledown;</a></span></div>");
      // $('#new_overlay').append("<br clear=\"both\"/>");
      $('#new_overlay').append("<div id='spinner' style=\"float: left;\"></div>");
      $('#new_overlay').append("<div class='clear'></div>");
      
      // var target = document.getElementById('spinner');//??
      // var spinner = new Spinner(opts).spin(target);

      for(var i=0; i<tags.length; i++){
      //console.log("THIS IS TAG " + tags[i]);
      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
      else {
        tag = tags[i];
        break;
        }
      }

      $.ajax({
       url: "get_tedtalks_related.php",
       data: {tag : tag},
       type: 'POST',
       dataType: "json",
         success: function(data){
          var related = changeData(data);
          related_json = related;
           recommendations(related,"spinner",false,title);
//           recommendations(data,"new_overlay",false,title);
         },
         error: function(jqXHR, textStatus, errorThrown){
         //alert("oh dear "+textStatus);
         }
      });

}

function insert_suggest_by_tag(tag) {

      // console.log("list shared by friends");
      // console.log(list_shared_by_friends);
      if (overlaycounter == null){
        overlaycounter = 0;
      }
      else{
        overlaycounter ++;
      }
      overlay_navigation.splice(overlaycounter, 0, tag);
      for (var i= overlay_navigation.length - 1; i > overlaycounter; i--){
        overlay_navigation.splice(i, 1);
      }
      html2 = [];

      html2.push("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div class='navigation_buttons'><img onclick='javascript:navigation(-1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='back' src='images/icons/backward.png' width='30'/><img onclick='javascript:navigation(+1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='forward' src='images/icons/forward.png' width='30'/></div>");

      html2.push("<div class=\"p_title_large\" style='text-align:center;'>Programmes by keyword: "+tag+"</div>");
	  $.ajax({
      type: "POST",
      url: "get_talks_by_tag.php",
      async: false,
      data: {id: 0,tag: tag},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		html2 = create_html(html2,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
		}
	}
	});
      // $.ajax({
      //  url: recently_viewed_json.suggestions[0].manifest,
      //  dataType: "json",
      //    success: function(data){
      //      set_playable(data);
      //    },
      //    error: function(jqXHR, textStatus, errorThrown){
      //      alert("oh dear "+textStatus);
      //    }
      // });
      $('#new_overlay').html(html2.join(''));
   
      $('#new_overlay').show();  
      show_grey_bg();

      // $(".play_button").live( "click", function() {

      // console.log("PLAY PRESSED!!!");
      //   var res = {};
      //   // res["id"]=id;
      //   res["pid"]=pid;
      //   res["title"]=title;
      //   res["video"]=video;
      //   res["description"]=description;
      //   // res["explanation"]=explanation;
      //   res["img"]=id;
      //   sendProgrammeTVs(res,my_tv); 
      //   return false;

      // });

      $('#new_overlay').append("<div class=\"more_like_this_tag\" style=\"margin-top: 40px;\"></div>");
      // $('#new_overlay').append("<br clear=\"both\"/>");
      $('#new_overlay').append("<div id='spinner_tag' style=\"float: left; height:'100%';\"></div>");
      // var target = document.getElementById('spinner');//??
      // var spinner = new Spinner(opts).spin(target);
      check_overflow();

      $.ajax({
       url: "get_tedtalks_related.php",
       data: {tag : tag},
       type: 'POST',
       dataType: "json",
         success: function(data){
          var related = changeData(data);
          related_json = related;
           recommendations(related,"spinner_tag",false,title);
//           recommendations(data,"new_overlay",false,title);
         },
         error: function(jqXHR, textStatus, errorThrown){
         //alert("oh dear "+textStatus);
         }
      });
}

function navigate_by_tag(tag) {

      html2 = [];

      html2.push("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div class='navigation_buttons'><img onclick='javascript:navigation(-1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='back' src='images/icons/backward.png' width='30'/><img onclick='javascript:navigation(+1);' style='display: inline; margin: 0 5px; cursor:pointer;' title='forward' src='images/icons/forward.png' width='30'/></div>");

      html2.push("<div class=\"p_title_large\" style='text-align:center;'>"+tag+"</div>");
	  $.ajax({
		url: "get_tedtalks_by_id.php",
		type: "POST",
		async: false,
		data: {tag: tag},
		dataType: "json",
		success: function (data) {
			item =  changeData(data); //JSON with suggestions format
        // recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
		}
    });
      // $.ajax({
      //  url: recently_viewed_json.suggestions[0].manifest,
      //  dataType: "json",
      //    success: function(data){
      //      set_playable(data);
      //    },
      //    error: function(jqXHR, textStatus, errorThrown){
      //      alert("oh dear "+textStatus);
      //    }
      // });
      $('#new_overlay').html(html2.join(''));
   
      $('#new_overlay').show();  
      show_grey_bg();

      // $(".play_button").live( "click", function() {

      // console.log("PLAY PRESSED!!!");
      //   var res = {};
      //   // res["id"]=id;
      //   res["pid"]=pid;
      //   res["title"]=title;
      //   res["video"]=video;
      //   res["description"]=description;
      //   // res["explanation"]=explanation;
      //   res["img"]=id;
      //   sendProgrammeTVs(res,my_tv); 
      //   return false;

      // });

      $('#new_overlay').append("<div class=\"more_like_this_tag\" style=\"margin-top: 40px;\"></div>");
      // $('#new_overlay').append("<br clear=\"both\"/>");
      $('#new_overlay').append("<div id='spinner_tag' style=\"float: left; height:'100%';\"></div>");
      // var target = document.getElementById('spinner');//??
      // var spinner = new Spinner(opts).spin(target);

      $.ajax({
       url: "get_tedtalks_related.php",
       data: {tag : tag},
       type: 'POST',
       dataType: "json",
         success: function(data){
          var related = changeData(data);
          related_json = related;
           recommendations(related,"spinner_tag",false,title);
//           recommendations(data,"new_overlay",false,title);
         },
         error: function(jqXHR, textStatus, errorThrown){
         //alert("oh dear "+textStatus);
         }
      });
}

//Function to show navigation backward and forward 

function navigation(data){
  //backwards
  if(data == -1){
    
    //not the end
    if((overlaycounter -1) > -1){
      overlaycounter = overlaycounter -1; 
      if (overlay_navigation[overlaycounter].substring) { //if tag or metadata 
        navigate_by_tag(overlay_navigation[overlaycounter]);
      // do string thing
      } 
      else{ //if programme directly
        navigate_by_id(overlay_navigation[overlaycounter]);
      // do other thing
      }
    }
  }
  //forwards ---> +1
  else{
    //not the end
    if((overlaycounter+1) <= (overlay_navigation.length -1)){
      overlaycounter = overlaycounter +1; 
      if (overlay_navigation[overlaycounter].substring) { //if tag or metadata 
        navigate_by_tag(overlay_navigation[overlaycounter]);
      // do string thing
      } 
      else{ //if programme directly
        navigate_by_id(overlay_navigation[overlaycounter]);
      // do other thing
      }
    }

  }

}


function test_for_playability(formats, provider){
console.log("formats");
console.log(formats);
  //@@tmp - for ipads etc should say no for flash
  //might want also to exclude some providers
  if(navigator.platform.indexOf("iPad") != -1 || navigator.platform.indexOf("iPhone") !=-1){
    if(formats["mp4"]){
      return true;
    }else{
      return false;
    }
  }else{
    return true;
  }
}

function set_playable(manifest_data){

    if(manifest_data && manifest_data["limo"]){
       //two kinds of manifest - one with events and one not
       // this is the events one

       if(manifest_data["limo"]["event-resources"][0]["link"]){

         $.ajax({
           url: manifest_data["limo"]["event-resources"][0]["link"],
           dataType: "json",
           success: function(data){
           process_events(data);
           },
           error: function(jqXHR, textStatus, errorThrown){
           console.log("oh dear2 "+textStatus);
           }
         });

       }

       if(manifest_data["limo"]["media-resources"][0]["link"]){

        $.ajax({
         url: manifest_data["limo"]["media-resources"][0]["link"],
         dataType: "json",
           success: function(data){
             set_playable(data);
           },
           error: function(jqXHR, textStatus, errorThrown){
             alert("oh dear "+textStatus);
           }
        });
          
       }else{
         console.log("broken manifest limo file");
       }

    }else{

      var locally_playable = false;

      if(manifest_data && manifest_data["media"]){
         var swf = manifest_data["media"]["swf"];
         var mp4 = manifest_data["media"]["mp4"];

         var provider = manifest_data["provider"];
         var formats = {"swf":swf,"mp4":mp4};
         locally_playable = test_for_playability(formats, provider);
         if(locally_playable){
           // $(".play_button").show();
           // $(".play_button").unbind('click');
           $(".play_button").live( "click", function() {
                      //get the prpgramme
                      var el = $( this ).parent().parent();
                      var programme = get_data_from_programme_html(el);
                      $("#new_overlay").html("<div class='close_button'><img src='images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div><div id='player'></div>");
                      process_video(programme,formats,provider);
                      return false;

           });
         }
      }
   }
}

//print out who is in the group and what sort of thing they are

function get_roster(blink){

  var roster = blink.look();
  // console.log("THIS IS ROSTER === ");
  // console.log(roster);

   if(roster["me"]){
     $("#title").html(roster["me"].name);
     $("#small_title").html("<a href='player.html#"+my_group+"' target='_blank'>Open Virtual TV</a>");
   }
  $("#roster").empty();

  var html=[];

  if(roster){

     html.push("<h3 class=\"contrast\">SHARE WITH</h3>");

    html.push("<div class='snaptarget_group person' id='group'>");
    html.push("<img class='img_person'  src='images/icons/group.png'  />");
    html.push("<div class='friend_name' id='grp'>Group #"+my_group+"</div>");
    html.push("</div>");

    // html.push("<br clear=\"both\" />");

    for(r in roster){

      item = roster[r];
      var video;
      // console.log("printing roster[r]")
      // console.log(roster[r]);

       //i.e. not me
      if(item && item.name!=buttons.me.name){

        // if a person
        if(item.obj_type=="person"){
          html.push("<div class='snaptarget person ui-droppable' id='"+item.name+"'>");
          html.push("<img class='img_person'  src='images/icons/user.png'  />");
          html.push("<div class='friend_name'>"+item.name+"</div>");
          html.push("</div>");
          // html.push("<br clear=\"both\" />");
        }

        // if a bot
        if(item.obj_type=="bot"){
          html.push("<div class='snaptarget_bot person' id='"+item.name+"'>");
          html.push("<img class='img_person' src='images/bot.png'  />");
          html.push("<div class='friend_name'>"+item.name+"</div>");
          html.push("</div>");
          // html.push("<br clear=\"both\" />");
        }

        // if a TV

        if(item && item.obj_type=="tv"){
            var html_tv = [];
            html_tv.push("<div class='snaptarget_tv telly' id='tv_title'>");
            
            html_tv.push("<div id='tv_name' style='font-size:16px;padding-top:10px;padding-right:40px;'>My TV</div>");
            html_tv.push("<div style='float: left; margin-right: 15px; margin-top: 30px;'><img class='img_tv' src='images/tiny_tv.png' /></div>");
            
            // html_tv.push("<br clear=\"both\" />");
    
            html_tv.push("<div class='dotted_spacer'>");
            var nowp = item.nowp;
            if(nowp && nowp["title"]){
              html_tv.push(nowp["title"]);
              $("#tv").attr("pid",nowp["pid"]);
            }else{
              html_tv.push("Nothing currently playing");
              $("#tv").attr("pid","");
            }
            html_tv.push("</div>");
            html_tv.push("</div>");
            // html_tv.push("<br clear=\"both\"></br>");
            $('#tv').html(html_tv.join(''));
            $("#tv").unbind('click');
            $("#tv").click(function() {
               var pid = $("#tv").attr("pid");
               if(pid && pid!=""){
                 $.ajax({
                    url: "get_tedtalks_by_id.php",
                    type: "POST",
                    async: false,
                    data: {id: pid},
                    dataType: "json",
                    success: function (data) {
                        video =  changeData(data); //JSON with suggestions format
                        // recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
                    }
                    });
                    var tags = Object.keys(video.suggestions[0]["tags"]);
                    //var tag = (/[^, ]*/.exec(tags)[0]);
                    var tags = (/[^, ]*/.exec(tags)); //array of tags
                    var tag = ""; //initialiazing
                    //check for a tag without whitespace
                    for(var i=0; i<tags.length; i++){
                      //console.log("THIS IS TAG " + tags[i]);
                      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
                      else {
                        tag = tags[i];
                        break;
                      }
                    }
                 insert_suggest2(pid);
               }
            })

         }
        }
      }

    }
    $('#roster').html(html.join(''));
    $(document).trigger('refresh');
    check_overflow();

}


function show_browse_programmes(){
  // $("#main_title").html("N-SCREEN");
 /// $sr=$("#search_results");
  //$sr.css("display","none");
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
		$member_id = $_SESSION['SESS_MEMBER_ID'];
		$qry="SELECT gender,age FROM members where member_id=$member_id";
		$result=mysql_query($qry);   
		while ($row = mysql_fetch_array($result)){ ?>
			var age = '<?php echo $row[1]; ?>';
		<?php
		}
		?>
		if( age == ''){
			$("#questions").show();
			
		}else{
		$("#questions").hide();
  $("#inner").show();
  $("#genre_list").hide();
  $("#format_list").hide();
  	 var html = [];
	$.ajax({
      type: "POST",
      url: "get_random.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]

		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	$("#programs").html(html.join(''));
	var html = [];
	$.ajax({
      type: "POST",
      url: "get_random.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
for (var dat in data) {
		dat = data[dat]
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	$("#results_format").html(html.join(''));
	
  }
  //$browse=$("#browse");
  //$browse.removeClass("grey").addClass("blue");

 // $random=$("#randomBBC");
 // $random.removeClass("blue").addClass("grey");
  
 // $random=$("#randomTED");
 // $random.removeClass("blue").addClass("grey");
 // $container=$("#browser");
 // $container.css("display","block");
 // $(document).trigger('refresh');
 //  $(document).trigger('refresh_buttons');
 //  check_overflow();

}

function show_more_recommendations(){

  var content = $('#side-b');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
     //jquery bug animayion pertentage
     // content.animate(content.height()*.100,400);
      content.css('height','293px');
     $('a#moreprogs').html('View All &triangledown;');
     $('#lessprogs').remove();

  }
  else{
    // content.animate({height:'100%'},400);
    content.css('height','100%');
    $('a#moreprogs').html('View Less &utri;');
    content.append("<span id='lessprogs' class='more_blue'><a onclick='show_more_recommendations();'>View Less &utri;</a></span>");

  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}

function show_more_programmes(){

  var content = $('#programmes');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
     //jquery bug animayion pertentage
     // content.animate(content.height()*.100,400);
      content.css('height','293px');
     $('a#moreprogrammes').html('View All &triangledown;');
     $('#lessprogrammes').remove();

  }
  else{
    // content.animate({height:'100%'},400);
    content.css('height','100%');
    $('a#moreprogrammes').html('View Less &utri;');
    content.append("<span id='lessprogrammes' class='more_blue'><a onclick='show_more_programmes();'>View Less &utri;</a></span>");

  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}


function show_shared(){

  var content = $('#content');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#moreshared').html('View All &triangledown;');
    $('#lessshared').remove();
  }
  else{
    content.css('height','100%');
    $('a#moreshared').html('View Less &triangle;');
    content.append("<span id='lessshared' class='more_blue'><a onclick='show_shared();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}

function show_history(){
  var content = $('#content2');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#morerecently').html('View All &triangledown;');
    $('#lessrecently').remove();
  }
  else{
    content.css('height','100%');
    $('a#morerecently').html('View Less &utri;');
    content.append("<span id='lessrecently' class='more_blue'><a onclick='show_history();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();

}
function show_later(){
  var content = $('#content3');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#morelater').html('View All &triangledown;');
    $('#lesslater').remove();
  }
  else{
    content.css('height','100%');
    $('a#morelater').html('View Less &utri;');
    content.append("<span id='lesslater' class='more_blue'><a onclick='show_later();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}
function show_likes(){
  var content = $('#content4');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#morelikes').html('View All &triangledown;');
    $('#lesslikes').remove();
  }
  else{
    content.css('height','100%');
    $('a#morelikes').html('View Less &utri;');
    content.append("<span id='lesslikes' class='more_blue'><a onclick='show_likes();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();

}
function show_dislikes(){
  var content = $('#content5');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#moredislikes').html('View All &triangledown;');
    $('#lessdislikes').remove();
  }
  else{
    content.css('height','100%');
    $('a#moredislikes').html('View Less ' + '&utri;');
    content.append("<span id='lessdislikes' class='more_blue'><a onclick='show_dislikes();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}

function show_related(){
  var content = $('#spinner');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','268px');
    $('a#more_related').html('View All &triangledown;');
    $('#lessrelated').remove();
  }
  else{
    content.css('height','100%');
    $('a#more_related').html('View Less ' + '&utri;');
    $('#new_overlay').append("<span id='lessrelated' class='more_blue'><a onclick='show_related();'>View Less &utri;</a></span>");
    // content.append("<span id='lessrelated' class='more_blue'><a onclick='show_related();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}

function show_genre(){
  var content = $('#spinner');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','268px');
    $('a#moregenre').html('View All &triangledown;');
    $('#lessgenre').remove();
  }
  else{
    content.css('height','100%');
    $('a#moregenre').html('View Less ' + '&utri;');
    $('#new_overlay').append("<span id='lessgenre' class='more_blue'><a onclick='show_genre();'>View Less &utri;</a></span>");
    // content.append("<span id='lessrelated' class='more_blue'><a onclick='show_related();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}

function show_format(){
  var content = $('#spinner');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','268px');
    $('a#moreformat').html('View All &triangledown;');
    $('#lessformat').remove();
  }
  else{
    content.css('height','100%');
    $('a#moreformat').html('View Less ' + '&utri;');
    $('#new_overlay').append("<span id='lessformat' class='more_blue'><a onclick='show_genre();'>View Less &utri;</a></span>");
    // content.append("<span id='lessrelated' class='more_blue'><a onclick='show_related();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}
//ON CLICK LISTENER TO ADD TO WATCH LATER

$("#addtowatchlater").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  $("#watchlater").html("<img id='deletewatchlater' style='width: 40px;' src=\"images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span>");

  // console.log('THE DIV ID OF THE PROGRAM IS   ' + the_program );
  $.ajax({
    url: "get_tedtalks_by_id.php",
    type: "POST",
    async: false,
    data: {id: id},
    dataType: "json",
    success: function (data) {
        item =  changeData(data); //JSON with suggestions format
        // recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
    }
    });
    //recently_viewed_json.suggestions[0] is the current video now displayed   
    // update_channel("recently_viewed", recently_viewed_json);
    var div = $("#"+id);

    var speaker = (/(.*):.*?/.exec(item.suggestions[0].title))[1];
    var title = (/.*?:(.*)/.exec(item.suggestions[0].title))[1];
    var description = item.suggestions[0].description;
    // var tags = lalala **********************TO DO*********************
    var video = item.suggestions[0].video;
    var pid = item.suggestions[0].pid;
    var img = item.suggestions[0].image;

    var tags = Object.keys(item.suggestions[0]["tags"]);
    //var tag = (/[^, ]*/.exec(tags)[0]);
    var tags = (/[^, ]*/.exec(tags)); //array of tags
    var tag = ""; //initialiazing
    //check for a tag without whitespace
    for(var i=0; i<tags.length; i++){
      //console.log("THIS IS TAG " + tags[i]);
      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
      else {
        tag = tags[i];
        break;
      }
    }
    watch_later_json.suggestions.splice(0,0,item.suggestions[0]);
    list_watch_later.push(id);
    update_channel("watch_later", watch_later_json);
    html = [];
    html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
     html.push("<img class=\"img img_small\" src=\""+img+"\" />");
    html.push("<div style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");
    html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
    html.push("<p class=\"description large\">"+description+"</p>");
    html.push("</div>");
    $('#list_later').prepend(html.join(''));

  // insert_watchlater_from_div(the_program);
  // console.log("clicked watch later");

  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
  return false;
});

//ON CLICK LISTENER TO ADD TO LIKES

$("#addtolike").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  $("#like").html("<img id='deletelike' style='width: 40px;' src=\"images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'>Like</span>");

  // console.log('THE DIV ID OF THE PROGRAM IS   ' + the_program );
  $.ajax({
    url: "get_tedtalks_by_id.php",
    type: "POST",
    async: false,
    data: {id: id},
    dataType: "json",
    success: function (data) {
        item =  changeData(data); //JSON with suggestions format
        // recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
    }
    });
    //recently_viewed_json.suggestions[0] is the current video now displayed   
    // update_channel("recently_viewed", recently_viewed_json);
    var div = $("#"+id);

    var speaker = (/(.*):.*?/.exec(item.suggestions[0].title))[1];
    var title = (/.*?:(.*)/.exec(item.suggestions[0].title))[1];
    var description = item.suggestions[0].description;
    // var tags = lalala **********************TO DO*********************
    var video = item.suggestions[0].video;
    var pid = item.suggestions[0].pid;
    var img = item.suggestions[0].image;

    var tags = Object.keys(item.suggestions[0]["tags"]);
    //var tag = (/[^, ]*/.exec(tags)[0]);
    var tags = (/[^, ]*/.exec(tags)); //array of tags
    var tag = ""; //initialiazing
    //check for a tag without whitespace
    for(var i=0; i<tags.length; i++){
      //console.log("THIS IS TAG " + tags[i]);
      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
      else {
        tag = tags[i];
        break;
      }
    }
    likes_json.suggestions.splice(0,0,item.suggestions[0]);
    list_likes.push(id);
    update_channel("like_dislike", likes_json);
    html = [];
    html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
    html.push("<img class=\"img img_small\" src=\""+img+"\" />");
    html.push("<div style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");
    html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
    html.push("<p class=\"description large\">"+description+"</p>");
    html.push("</div>");
    $('#list_likes').prepend(html.join(''));
    check_overflow();

  // insert_watchlater_from_div(the_program);
  // console.log("clicked watch later");

  // $(document).trigger('refresh');
  // $(document).trigger('refresh_buttons');
  return false;
});

//ON CLICK LISTENER TO ADD TO DISLIKES
$("#addtodislike").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  $("#dislike").html("<img id='deletedislike' style='width: 40px;' src=\"images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'>Dislike</span>");

  // console.log('THE DIV ID OF THE PROGRAM IS   ' + the_program );
  $.ajax({
    url: "get_tedtalks_by_id.php",
    type: "POST",
    async: false,
    data: {id: id},
    dataType: "json",
    success: function (data) {
        item =  changeData(data); //JSON with suggestions format
        // recently_viewed_json.suggestions.splice(0,0,item.suggestions[0]);
    }
    });
    //recently_viewed_json.suggestions[0] is the current video now displayed   
    // update_channel("recently_viewed", recently_viewed_json);
    var div = $("#"+id);

    var speaker = (/(.*):.*?/.exec(item.suggestions[0].title))[1];
    var title = (/.*?:(.*)/.exec(item.suggestions[0].title))[1];
    var description = item.suggestions[0].description;
    // var tags = lalala **********************TO DO*********************
    var video = item.suggestions[0].video;
    var pid = item.suggestions[0].pid;
    var img = item.suggestions[0].image;

    var tags = Object.keys(item.suggestions[0]["tags"]);
    //var tag = (/[^, ]*/.exec(tags)[0]);
    var tags = (/[^, ]*/.exec(tags)); //array of tags
    var tag = ""; //initialiazing
    //check for a tag without whitespace
    for(var i=0; i<tags.length; i++){
      //console.log("THIS IS TAG " + tags[i]);
      if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
      else {
        tag = tags[i];
        break;
      }
    }
    dislikes_json.suggestions.splice(0,0,item.suggestions[0]);
    list_dislikes.push(id);
    update_channel("like_dislike", dislikes_json);
    html = [];
    html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
    html.push("<img class=\"img img_small\" src=\""+img+"\" />");
    html.push("<div style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");
    html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
    html.push("<p class=\"description large\">"+description+"</p>");
    html.push("</div>");
    $('#list_dislikes').prepend(html.join(''));

  // insert_watchlater_from_div(the_program);
  // console.log("clicked watch later");

  // $(document).trigger('refresh');
  // $(document).trigger('refresh_buttons');
  check_overflow();
  return false;
});


//ON CLICK LISTENER TO DELETE FROM WATCH LATER IST

$("#deletewatchlater").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  //remove from json
  for (var i = 0; i < watch_later_json.suggestions.length; i++) {
    if (watch_later_json.suggestions[i].pid == id) {
        watch_later_json.suggestions.splice(i, 1);
        break;
    }
  }
  //remove from list
  for (var i = 0; i < list_watch_later.length; i++) {
    if (list_watch_later[i] == id) {
        list_watch_later.splice(i, 1);
        break;
    }
  }

  $("#watchlater").html("<img id='addtowatchlater' style='width: 40px;' src=\"images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span>");
  $('#list_later').children('#'+ id).remove();
  update_channel("watch_later", watch_later_json);

  // insert_watchlater_from_div(the_program);
  // console.log("clicked watch later");

  // $(document).trigger('refresh');
  // $(document).trigger('refresh_buttons');
  check_overflow();
  return false;
});

//ON CLICK LISTENER TO DELETE FROM LIKES LIST

$("#deletelike").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  //remove from json
  for (var i = 0; i < likes_json.suggestions.length; i++) {
    if (likes_json.suggestions[i].pid == id) {
        likes_json.suggestions.splice(i, 1);
        break;
    }
  }
  //remove from list
  for (var i = 0; i < list_likes.length; i++) {
    if (list_likes[i] == id) {
        list_likes.splice(i, 1);
        break;
    }
  }

  $("#like").html("<img id='addtolike' style='width: 40px;' src=\"images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'>Like</span>");
  $('#list_likes').children('#'+ id).remove();
  update_channel("like_dislike", likes_json);
  check_overflow();
  return false;
});

//FUnction to sheck whether a programme in on a personal list or not
function not_in_list(pid, list){
  var not_in_the_list = true;
  for (var i = 0; i < list.length; i++){
    if(list[i] == pid){
      not_in_the_list = false;
    }
  }
  return not_in_the_list; //returns true if element not in the list
}

//ON CLICK LISTENER TO DELETE FROM LIKES LIST

$("#deletedislike").live( "click", function() {
  var father = $(this).parents().eq(2);
  var this_div = $(this).attr('id');
  var id= $(this).parents().eq(2).attr('id');

  //remove from json
  for (var i = 0; i < dislikes_json.suggestions.length; i++) {
    if (dislikes_json.suggestions[i].pid == id) {
        dislikes_json.suggestions.splice(i, 1);
        break;
    }
  }
  //remove from list
  for (var i = 0; i < list_dislikes.length; i++) {
    if (list_dislikes[i] == id) {
        list_dislikes.splice(i, 1);
        break;
    }
  }

  $("#dislike").html("<img id='addtodislike' style='width: 40px;' src=\"images/icons/dislike.png\" /><span style='display: block'; class ='inter_span'>Dislike</span>");
  $('#list_dislikes').children('#'+ id).remove();
  update_channel("like_dislike", dislikes_json);
  check_overflow();
  return false;
});


//FUnctin to update internal list of programmes within each section and to add properly html
//in order to prevent duplicate items
function update_list(pid, list){
  var not_in_the_list = true;
  for (var i = 0; i < list.length; i++){
    if(list[i] == pid){
      not_in_the_list = false;
    }
  }
  if(not_in_the_list){
    list.push(pid);
  }
  return not_in_the_list; //returns true if element not in the list
}

// // list of movies ---- > HAVE TO CHANGE IT TO MAKE IT BETTER

// function insert_watchlater_from_div(id){
//   var div = $("#"+id);
//   var j = get_data_from_programme_html(div);
//   var prog_id = j["pid"];
//   // console.log(j);
//   var not_in_the_list = true;

//   //checking wheter is already in the list or not
//   for (var i = 0; i < list_watch_later.length; i++){
//     if(list_watch_later[i] == prog_id) not_in_the_list = false;
//   }
//   if(not_in_the_list){ 
//     list_watch_later.push(prog_id);
//     insert_watchlater(j);
//     watch_later_json.suggestions.push(j);

//     jsObject_json = JSON.stringify(watch_later_json);

//     $.ajax({
//         url: "set_channel.php",
//         type: "POST",
//         data: {data : jsObject_json, channel : "watch_later"},
//         dataType: "json",
//         success: function (response) {
//             console.log("Correct watch_later updated");
//         }
//       });
//   }
// }

// // Call as
// //setUsername(3, "Thomas");

// function insert_watchlater(j){
//   var id = j["pid"];
//   // console.log("passing to addlater");
//   // console.log(j);
//   // console.log("passing to addlater");
//   var html3 = generate_html_for_programme(j,null,id);
//   $('#list_later').append(html3.join(''));
// }


//get a random selection

function do_random(el){

  $('#search_results').html(''); //clear previous display

  // $("#main_title").html("Random Selection");  
  $sr=$("#search_results");
  $sr.css("display","block");
  
  $container=$("#browser");
  $container.css("display","none");

  $browse=$("#browse");
  $browse.removeClass("blue").addClass("grey");

  $random=$("#random");
  $random.removeClass("grey").addClass("blue");

  //id for element to add it to
  if(!el){
    el = "search_results";
  }

  $.ajax({
    url: "get_random_tedtalks.php",
    dataType: "json",
    // async: false,
    success: function(data){
      var result = changeData(data);
      random_json = result; //set global variable in order to store
      // console.log(JSON.stringify(result));
      random(result,el);
    },
    error: function(jqXHR, textStatus, errorThrown){
    // console.log("nok "+textStatus);
    }

  });

}


// start url if different from do_random

function do_start(el,start_url){

  //do_start("search_results",start_url);

  //id for element to add it to
  if(!el){
    el = "progs";
  }

  if(start_url){

    $.ajax({
      type: "POST",
      url: "get_channel.php",
      //async: false,
      data: {channel: "recommendations"},
      dataType: "json",
      success: function(data){
        random(data,el);
        check_overflow();
      },
      error: function(jqXHR, textStatus, errorThrown){
        console.log("!!nokkkk "+textStatus);
      }
    });

  }else{
     //do_random(el);
  }

}

//search for txt
       
function do_search(txt){

  txt = txt.toLowerCase();
  // $('#main_title').html("Search for '"+txt+"'");

  $sr=$("#search_results");
  $sr.css("display","block");

  $container=$("#browser");
  $container.css("display","none");

  $browse=$("#browse");
  $browse.addClass("grey").removeClass("blue");

  $random=$("#random");
  $random.addClass("grey").removeClass("blue");


  $.ajax({
    url: get_search_url(txt),
    dataType: "json",
    success: function(data){
      search_results(data,txt,"search_results");
    },
    error: function(jqXHR, textStatus, errorThrown){
    //console.log("nok "+textStatus);
    }

  });

}

//called when random results are returned

function random(result,el){
//pass to the common bit of processing

  var suggestions = [];

  if(local_search){
  //if it's local search then random just returns everything
  //so do some processing
    for (var i =0;i<11;i++){
      var rand = Math.floor(Math.random()*result.length)
      suggestions.push(result[rand]);
    }
  }else{
    if(result && result["suggestions"]){
      suggestions = result["suggestions"];//??
    }else{
      suggestions = result;
      //console.log("no results");
    }
  }

//randomise what we have
  if(suggestions){
      suggestions.sort(function() {return 0.5 - Math.random()});
  }

  process_json_results(suggestions,el,null,true);
  check_overflow();
}


//called when recommendations are returned

function recommendations(result,el,add_stream,stream_title){

   if(!el){
     el = "progs";
   }
   if(result){

          var suggestions = result["suggestions"];
          var pid_title = result["title"];
          if(suggestions.length==0){
            if(pid_title){
               $("#pane2").html("<h3>Sorry, nothing found related to "+pid_title+"</h3>");
            }else{
               $("#pane2").html("<h3>Sorry, nothing found</h3>");
            }
            $("#"+el).append("");
          }else{
            if(pid_title){
               $("#pane2").html("<h3>Related to "+pid_title+"</h3>");
            }else{
               $("#pane2").html("<h3>Related</h3>");
            }
//            process_json_results(suggestions,el,pid_title,null,add_stream,stream_title);
            process_json_results(suggestions,el,pid_title,true,add_stream,stream_title);
            check_overflow();
          }
    }else{
//tmp@@ for when offline
/*
       var s = {    "pid": "b0074fpm",
    "core_title": "Doctor Who - Series 2 - The Satan Pit",
    "channel": "bbcthree",
    "description": "As Rose battles the murderous Ood, the Doctor finds his beliefs challenged.",
    "image": "http://dev.notu.be/2011/04/danbri/crawler/images/b0074fpm_512_288.jpg",
    "series_title": "Doctor Who",
    "date_time": "2010-10-03T18:00:00+00:00"};
       var suggestions = [];
       suggestions.push(s);
       process_json_results(suggestions,el,pid_title,null,add_stream,title);
*/
//console.log("OOPS!");

    }
}


//handle inserted search results
function search_results(result,current_query,el){
   if(!el){
     el = "progs";
   }

   suggestions = [];

   if(local_search){
     //if it's local search then search just returns everything
     //so do some processing

       for (r in result){
         var title = result[r]["title"];
         var desc = result[r]["description"];
         if(title.toLowerCase().match(current_query)||(desc.toLowerCase().match(current_query))){
            suggestions.push(result[r]);
         }
       }
   }else{
      suggestions = result;
   }

   if(!suggestions || suggestions.length==0){
      $("#"+el).html("<div class='sub_title' style='padding-top:26px;padding-left:8px'>Sorry, nothing found for '"+current_query+"'</div>   <div class='bluebutton'><a href='javascript:do_random()'>Give me a random selection</a></div>");
   }else{
      $("#rec_pane").html("<h3>Search results for "+current_query+"</h3>");
      var replace_content=true;
      process_json_results(suggestions,el,null,true);
   }

}



//process the results for displaying (small display) welcome page

function process_json_results(result,ele,pid_title,replace_content,add_stream,stream_title){
          var max = 100;
          var s ="";
          var html = [];
          suggestions = result;
          // console.log(result);

          if (suggestions && suggestions.length>0){
            //console.log("---------WE ARE NOW IN DIV " +  ele + "----------and json size is " + suggestions.length);
            var count = 0;
            var num = suggestions.length/2;
            for (var r in suggestions){
              if(count<max){
                count = count + 1;
                var title = suggestions[r]["core_title"];//@@
                if(!title){
                  title = suggestions[r]["title"];
                }
                var shared = suggestions[r]["shared"];
                var tags = Object.keys(suggestions[r]["tags"]);
                //var tag = (/[^, ]*/.exec(tags)[0]);
                var tags = (/[^, ]*/.exec(tags)); //array of tags
                var tag = ""; //initialiazing
                //check for a tag without whitespace
                for(var i=0; i<tags.length; i++){
                  //console.log("THIS IS TAG " + tags[i]);
                  if(tags[i].indexOf(' ') >= 0 || /^[A-Z]/.test(tags[i])){}
                  else {
                    tag = tags[i];
                    break;
                  }
                }
                // console.log("THIS IS TAG!!!!!!! " + tag);
                var desc="";
                var desc = suggestions[r]["description"];
//                desc = desc.replace(/\"/g,"'");
                var id = suggestions[r]["pid"];
                if(!id){
                  id = suggestions[r]["pid"];
                }
                var img = suggestions[r]["image"];

                var channel = suggestions[r]["channel"];
                var date_time = suggestions[r]["date_time"];

                var time_offset = suggestions[r]["time_offset"];
                var explanation=suggestions[r]["explanation"];
                //var vid = suggestions[r]["video"];
                // var vid = "http://video.ted.com/talks/dynamic/IsabelleAllende_2007-high.flv";  //*************************TODO

                var program_id = id.toString();

                var string = "<div id=\""+id+"\" pid=\""+id+"\" class=\"ui-widget-content button programme ui-draggable\" " ;
                string += " onclick= \"javascript:insert_suggest2(";
                string += program_id+");return true\">";
                //console.log(string);

/*
                var vid = suggestions[r]["media"]["swf"]["uri"];


//processing for local files option
                if(video_files){
                    vid = video_files+""+vid;
                }

//processing for a particular form of time offsets
//T00:18:31:15F25
                if(vid && time_offset){
                   var offs = time_offset.replace(/T/,"")
                   var aa = offs.split(":");
                   var secs = parseInt(aa[1])*60+parseInt(aa[2]);
                   video = video+"#"+secs
                }
*/
                if(id){
                  if(pid_title){
                    
                     // console.log(string);
                     html.push(string);
                  }else{
                     html.push(string);
                  }
      html.push("<img class=\"img img_small\" src=\""+img+"\" />");
      html.push("<div class = 'gradient_div_icons' style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_watch_later.png\"/>");
      }
            if(not_in_list(id,list_likes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/neutral.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_like.png\" />");      
      }
      if(not_in_list(id,list_dislikes)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/dislike.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_dislike.png\" />");      
      }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_recently_viewed.png\" />");      
      }
      if(not_in_list(id,list_shared_by_friends)){
        html.push("<img class=\"overlapicon\" src=\"images/icons/shared.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"images/icons/on_shared.png\" />");      
      }

    html.push("</div>");
                  //html.push("<span class=\"p_title p_title_small\"><a href=''>"+title+"</a></span>");
                  html.push("<span class=\"p_title p_title_small\"><a >"+title+"</a></span>");
                  if(shared){     
                    list_shared_by_friends.push(id);              
                    html.push("<span class=\"shared_by\">Shared by "+shared+"</span>");
                  }
                  // html.push("<div clear=\"both\"></div>");
                  if(desc && desc!=""){
                    html.push("<span class=\"large description\">"+desc+"</span>");
                  }
                  if(explanation && explanation!=""){
                    //see tidy_dbpedia.js
                    //idea is that the user doesn't need to see piles of junk
                    ///explanation = clean_up(explanation);
                    //i.s. if this is caled because it's related content, say why
                    if(explanation){
                        html.push("<span class=\"explain large\">"+explanation+"</span>");
                    }

                  }
//                  var cats = suggestions[r]["keywords"];
  //                if(cats && cats!=""){
    //                html.push("<span class=\"large keywords\"><i>"+cats+"</i></span>");
      //            }
                  html.push("</div>");
                }
              }//end if count < max
            }

//console.log("[1]");
           if(replace_content){
              $("#"+ele).html(html.join(''));
           }else{
              $("#"+ele).append("<div id=\"more\">"+html.join('')+"</div>");
           }
           if(add_stream){
              $("#side-c").prepend("<span class='sub_title'>Related to '"+stream_title+"'</span>\n<div class='slidey'>"+html.join('')+"</div>");
           }
          }else{
            $("#"+ele).append('');
          }
   
   $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');
   check_overflow();
}
        
//show disconnect overlay

function show_disconnect(){
   //console.log("disconnecting");

   $('#disconnected').show();
   show_grey_bg();
   $("#nick1").focus();

}

//when the user enters their name, tell buttons

function get_name() {
    var name = null;

    $.ajax({
        url: '../get_name.php',
        async: false,
        success: function(response) {
            name = response;
        }
    });
    console.log('The name retrieved from session variable is ' + name);
    return name;
}

function share_to_tvs(res){
////hm this should be an ajax call
                                var roster = buttons.blink.look();
                                if(roster){
                                  for(r in roster){
                                    var item = roster[r];
                                    if(item.obj_type =="tv"){
                                      var nm = item.name;
                                      buttons.share(res, new TV(nm,nm));//need to send this to a list of tvs

                                    }
                                  }
                                }

}

// various triggered things

// Connect to the service as me

$(document).bind('send_name', function () {
  console.log("sending name and connecting "+buttons.me.name);
  buttons.connect(buttons.me,my_group,false); // third arg is debugging
});


//when connection is confirmed
$(document).bind('connected', function (ev,blink) {
  //get the initial stuff
  blink_callback(blink);
});

//what to do when disconnected

$(document).bind('disconnected',function(){
   console.log("disconnecting");
   if(interval){
     clearInterval(interval);//stop polling
   }
   $('#disconnected').show();
   show_grey_bg();
   $("#nick1").focus();
   Logout();
});

///webpages - this is early stuff
function generate_html_for_webpage(j,n,id){

      var title=j["title"];
      var link=j["link"];
      var classes = null;
      var html = [];

      html.push("<div id=\""+id+"\" pid=\""+id+"\" href=\""+link+"\" ");
      if(classes){
        html.push("class=\""+classes+"\">");
      }else{
        html.push("class=\"ui-widget-content button programme open_win_web\">");
      }
      var img = "images/webpage.png";
//      html.push("<div><a href='"+link+"' target='_blank'><img class=\"img\" src=\""+img+"\" /></a></div>");
      html.push("<div><img class=\"img\" src=\""+img+"\" /></div>");
      html.push("<span class=\"p_title p_title_small\"><a href='"+link+"' target='_blank'>"+title+"</a></span>");
      html.push("<div clear=\"both\"></div>");
      if(n){                    
        html.push("<span class=\"shared_by\">Shared by "+n+"</span>");
      }
      html.push("</div>");
      return html
}    


//video on the client - even earlier stuff
function generate_html_for_video(j,n,id){

      var title=j["title"];
      var link=j["link"];
      var classes = null;
      var html = [];

      html.push("<div id=\""+id+"\" pid=\""+id+"\" href=\""+link+"\"");
      if(classes){
        html.push("class=\""+classes+"\">");
      }else{
        html.push("class=\"ui-widget-content button programme open_vid_win\">");
      }
      var img = "images/video.png";
      html.push("<div><img class=\"img\" src=\""+img+"\" /></div>");

      html.push("<span class=\"p_title p_title_small\">"+title+"</span>");
      // html.push("<div clear=\"both\"></div>");
      if(n){                    
        html.push("<span class=\"shared_by\">Shared by "+n+"</span>");
      }
      html.push("</div>");
      return html
}    


//from a programme html element, get the json

function get_data_from_programme_html(el){
     var item_type = "programme";
     var id = el.attr('id');
     var pid = el.attr('pid');
     var video = el.attr('href');
     var more = el.attr('more');
     var service = el.attr('service');
     var is_live = el.attr('is_live');
     var manifest = el.attr('manifest');
     var img = el.find("img").attr('src');
     var title=el.find(".p_title").text();
     if(!title){
        title=el.find(".p_title_large").text();
     }
     var desc=el.find(".description").text();
     var explain=el.find(".explain").text();
                                                
     var res = {};                 
     res["id"]=id;
     res["pid"]=pid;
     res["video"]=video;
     res["image"]=img;
     res["title"]=title; 
     res["more"]=more; 
     res["service"]=service; 
     res["item_type"]=item_type; 
     res["is_live"]=is_live; 

     res["manifest"]=manifest; 
     res["description"]=desc;
     res["explanation"]=explain;
     return res;
                                
}

//Adapt ted-talks http requesst to our ow data format

function changeData(data){

  var random_ted = {
    suggestions: []
  };

  if(data.talks == null){
    return random_ted;
  }  

  for(var i = 0; i < data.talks.length; i++) {  var item = data.talks[i];
      for(var j = 0; j < data.talks[i].talk.photo_urls.length; j++){
        if(data.talks[i].talk.photo_urls[j].size == "240x180"){
          var image = data.talks[i].talk.photo_urls[j].url;
        }
      } 

      if(item.talk.media_profile_uris["internal"]){

      random_ted.suggestions.push({ 
          "pid"   : item.talk.id,
          "title" : item.talk.name,          
          "description" : item.talk.description,
          "date_time" : item.talk.published_at,
          // "media_profile_uris" : item.talk.media_profile_uris,
          "url" : item.talk.media_profile_uris["internal"]["950k"].uri, //TODO CHANGE THIS
          "video" : item.talk.media_profile_uris["internal"]["950k"].uri,
          "speaker" : item.talk.speakers,
          "image" : image,
          "manifest" : {
              "pid"   : item.talk.id,
              "id" : item.talk.id,          
              "title" : item.talk.name,
              "image" : image,
              "provider" : "ted",
              "duration" : 1750,
              "media": {
                "mp4": {
                  // "type": "video/x-swf",
                  "uri": item.talk.media_profile_uris["internal"]["950k"].uri,
                  "is_live": "false"
                }
              },
              "type": "video/mp4"
          },
          "tags" : item.talk.tags
      });

      }
      else{

        random_ted.suggestions.push({ 
          "pid"   : item.talk.id,
          "title" : item.talk.name,          
          "description" : item.talk.description,
          "date_time" : item.talk.published_at,
          // "media_profile_uris" : item.talk.media_profile_uris,
          "url" : "", //TODO CHANGE THIS
          "video" : "",
          "speaker" : item.talk.speakers,
          "image" : image,
          "manifest" : {
              "pid"   : item.talk.id,
              "id" : item.talk.id,          
              "title" : item.talk.name,
              "image" : image,
              "provider" : "ted",
              "duration" : 1750,
              "media": {
                "mp4": {
                  // "type": "video/x-swf",
                  "uri": "",
                  "is_live": "false"
                }
              },
              "type": "video/mp4"
          },
          "tags" : item.talk.tags
      });

      }
      
  }return random_ted; 
}

//when the group changes, update the roster

$(document).bind('items_changed',function(ev,blink){
    get_roster(blink);
     $(document).trigger('refresh');
     check_overflow();
     // $(document).trigger('refresh_buttons');
     //$(document).trigger('refresh_group');
    // $(document).trigger('refresh_history');
    // $(document).trigger('refresh_recs');
    // $(document).trigger('refresh_search');
    // //my new channels
    // $(document).trigger('refresh_later');
    // $(document).trigger('refresh_ld');

});

//creates a new id from a programme and a person name string
function generate_new_id(j,n){
  var i = j["pid"];
  // var i = j["pid"]+"_"+n; //not really unique enough
  return i;
}

//when someone shares something, put a copy of it in the right place

$(document).bind('shared_changed', function (e,programme,name,msg_type) {
  var a = get_object("a2");
  a.play();

  var id = generate_new_id(programme,name);
  // var id = programme.pid;
  // console.log("THIS IS GENERATED ID");
  // console.log(id);

  console.log("THE ID OF THE PROGRAM SHARED IS " + id);
  var msg_text = "";
  var html = "";

  $.ajax({
    url: "get_tedtalks_by_id.php",
    type: "POST",
    async: false,
    data: {id: id},
    dataType: "json",
    success: function (data) {
      item =  changeData(data);
      item.suggestions[0].shared = name;
      //item.suggestions[item.suggestions.length].push = "shared" : name;
      shared_by_friends_json.suggestions.splice(0,0,item.suggestions[0]);
      update_channel("shared_by_friends", shared_by_friends_json);
      recommendations(shared_by_friends_json,"results");        
    }
  });

  if(programme.item_type=="webpage"){
    html = generate_html_for_webpage(programme,name,id);
    msg_text = name+" shared <a onclick='show_webpage(\""+programme["link"]+")'>"+programme["title"]+"</a> with you";
    if(msg_type=="groupchat"){
      msg_text = name+" shared <a onclick='show_webpage(\""+programme["link"]+")'>"+programme["title"]+"</a> with the group";
    }
  }else{
    if(programme.item_type=="video"){
      html = generate_html_for_video(programme,name,id);
      msg_text = name+" shared <a onclick='show_video(\""+programme["link"]+"\")'>"+programme["title"]+"</a> with you";
      if(msg_type=="groupchat"){
        msg_text = name+" shared <a onclick='show_video(\""+programme["link"]+"\")'>"+programme["title"]+"</a> with the group";
      }
    }else{
      //html = generate_html_for_programme(programme,name,id);
      msg_text = name+" shared "+programme["title"]+" with you";
      if(msg_type=="groupchat"){
        msg_text = name+" shared "+programme["title"]+" with the group";
      }
    }
  }

  //$('#results').prepend(html.join(''));

//notifications 
  build_notification(msg_text,programme,name);
  $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');

  // $(document).trigger('refresh_group');
  // $(document).trigger('refresh_buttons');
  // $(document).trigger('refresh_history');
  // $(document).trigger('refresh_recs');
  // $(document).trigger('refresh_search');

  // //my new channels
  // $(document).trigger('refresh_later');
  // $(document).trigger('refresh_ld');
  check_overflow();

});

function build_notification(msg_text,programme,name){

  console.log("Detected shared item");
  if(lastmsg!=msg_text){
    var p = $("#notify").text();
    var num = parseInt(p);

    if(!num){
      num=1;
    }else{
      num = num+1;
    }

    lastmsg = msg_text;
    var nid = generate_new_id(programme,name)+"_notification";
    $("#notify").html(num);
    $("#notify").show();
    $("#notify_large").prepend("<div id='"+nid+"' class='dotty_bottom'>"+msg_text+" </div>");//not sure if append / prepend makes most sense

  }            
}

//create html from a programme
function generate_html_for_programme(j,n,id){

      var pid=j["pid"];
      var video = j["video"];
      var title=j["title"];
      if(!title){
         title = j["core_title"];
      }
      var img=j["image"];
      if(!img){
        img=j["depiction"];
      }
      var manifest=j["manifest"];
      var more=j["more"];
      var explanation=j["explanation"];
      var desc=j["description"];
      var classes= j["classes"];
      var is_live = false;
      if(j["live"]==true || j["live"]=="true" || j["is_live"]==true || j["is_live"]=="true"){
        is_live = true;
      }
      var service = j["service"];
      var channel = j["channel"];
      if(channel && is_live){
        img = "channel_images/"+channel.replace(" ","_")+".png";
      }


      var html = [];
      html.push("<div id=\""+id+"\" pid=\""+pid+"\"");
      if(more){
        html.push(" more=\""+more+"\"");
      }
      html.push(" is_live=\""+is_live+"\"");

      if(video){
        html.push("  href=\""+video+"\"");
      }
      if(service){
        html.push("  service=\""+service+"\"");
      }
      if(manifest){
        html.push("  manifest=\""+manifest+"\"");
      }
      if(classes){
        html.push("class=\""+classes+"\">");
      }else{
        html.push("class=\"ui-widget-content button programme open_win\">");
      }
      html.push("<div class='img_container'><img class=\"img\" src=\""+img+"\" />");
      html.push("</div>");
      if(is_live){
       html.push("Live: ");

      }else{
      }
      html.push("<span class=\"p_title p_title_small\">"+title+"</span>");
      // html.push("<div clear=\"both\"></div>");
      if(n){                    
        html.push("<span class=\"shared_by\">Shared by "+n+"</span>");
      }
      if(desc){
        html.push("<span class=\"description large\">"+desc+"</span>");
      }
/*
      if(explanation){

        //string.charAt(0).toUpperCase() + string.slice(1);
        explanation = explanation.replace(/_/g," ");
        var exp = explanation.replace(/,/g," and ");

        html.push("<span class=\"explain_small\">Matches "+exp+" in your profile</span>");
      }
*/
      html.push("</div>");
      return html
}    

//when the TV changes, print out what's being watched

$(document).bind('tv_changed', function (ev,item) {
  var ct,cid;
  var ot = item.obj_type;
  var id = "tv";
  if(ot=="tv"){
      ct = item.nowp["title"];
      cid = item.nowp["id"];
  }
  var pid = item.nowp["pid"];

  $("#tv").find(".dotted_spacer").html(ct)
  $("#tv").attr("pid",pid);

//notifications

  $("#tv").find(".dotted_spacer").html(item.nowp["title"]);
  
  var msg_text = "TV started playing "+item.nowp["title"];
  if(item["nowp"]["state"]=="pause"){
    msg_text = "TV paused "+item.nowp["title"];
  }
  build_notification(msg_text,item.nowp, item.name);

});

//ensure the drag and drop is working

$(document).bind('refresh', function () {
                $( "#draggable" ).draggable();
				$( ".programme" ).draggable();
                $( ".programme" ).draggable(
                        {
                        appendTo: 'body',
                        containment:"#container",
                        opacity: 0.7,
                        helper: "clone",
                        zIndex: 2700,
      start: function() {
                          $(".snaptarget").addClass( "dd_highlight"); 
                          $(".snaptarget_tv").addClass( "dd_highlight"); 
                          $(".snaptarget_group").addClass( "dd_highlight"); 
                          $(".snaptarget_bot").addClass( "dd_highlight"); 
      },
      drag: function() {
                          $(".snaptarget").addClass( "dd_highlight"); 
                          $(".snaptarget_tv").addClass( "dd_highlight"); 
                          $(".snaptarget_group").addClass( "dd_highlight"); 
                          $(".snaptarget_bot").addClass( "dd_highlight"); 
      },
      stop: function() {
                          $(".snaptarget").removeClass( "dd_highlight"); 
                          $(".snaptarget_tv").removeClass( "dd_highlight"); 
                          $(".snaptarget_group").removeClass( "dd_highlight"); 
                          $(".snaptarget_bot").removeClass( "dd_highlight"); 
      }

                });
                $( ".snaptarget" ).droppable({
           
                        hoverClass: "dd_highlight_dark",
                        drop: function(event, ui) {
     
                                var el = $(this);
                                var jid = el.attr('id');
                                var el3 = ui.helper;
                                var el2 = el3.parent();
								alert(jid);
								alert(el3);
                                var a = get_object("a1");
                                a.play();

                                var res = get_data_from_programme_html(el3);//??
                                var url = el3.attr('href');
                                buttons.share(res,new Person(jid,jid));

                                $( this ).addClass( "dd_highlight",10,function() {
                                        setTimeout(function() {
                                                el.removeClass( "dd_highlight" ,100);
                                        }, 1500 );

                                });
                        }

                });
                $( ".snaptarget_group" ).droppable({
           
                        hoverClass: "dd_highlight_dark",
                        drop: function(event, ui) {
     
                                var el = $(this);
                                var jid = el.attr('id');
 
                                var el3 = ui.helper;
                                var el2 = el3.parent();

                                var a = get_object("a1");
                                a.play();

                                var res = get_data_from_programme_html(el3);//??
                                var url = el3.attr('href');
                                buttons.share(res);

                                $( this ).addClass( "dd_highlight",10,function() {
                                        setTimeout(function() {
                                                el.removeClass( "dd_highlight" ,100);
                                        }, 1500 );

                                });
                        }

                });


                $( ".snaptarget_bot" ).droppable({
           
                        hoverClass: "dd_highlight_dark",
                        drop: function(event, ui) {
     
                                var el = $(this);
                                var jid = el.attr('id');
 
                                var el3 = ui.helper;
                                var el2 = el3.parent();

                                var a = get_object("a1");
                                a.play();
                                var res = get_data_from_programme_html(el3);//??


                                html3 = [];
                                html3.push("<div id=\""+res["id"]+"_favs\" pid=\""+res["pid"]+"\" href=\""+recs["video"]+"\" class=\"ui-widget-content button programme ui-draggable open_win\">");
                                html3.push("<img class=\"img\" src=\""+res["image"]+"\" />");
                                html3.push("<span class=\"p_title\">"+res["title"]+"</a>");
                                html3.push("<p class=\"description large\">"+res["description"]+"</b></p>");
                                html3.push("</div>");
                                $('#favs').prepend(html3.join(''));
                                buttons.share(res,new Person(jid,jid))

                                $( this ).addClass( "dd_highlight",10,function() {
                                        setTimeout(function() {
                                                el.removeClass( "dd_highlight" ,100);
                                        }, 1500 );

                                });
                        }

                })

                $( ".snaptarget_tv" ).droppable({  //for tvs

                        hoverClass: "dd_highlight_dark",
                        drop: function(event, ui) {

                                var el = $(this);
                                var jid = el.attr('id');
                         
                                var el3 = ui.helper;
                                var el2 = el3.parent();

                                var a = get_object("a1");
                                a.play();

                                var res = get_data_from_programme_html(el3);//??
                                res["action"]="play";
                                res["shared_by"] = buttons.me.name;
                                var url = el3.attr('href');
                                var name = jid;
//go throgh the roster and send to all tvs
                                share_to_tvs(res);
                                $( this ).addClass( "dd_highlight",10,function() {
                                        setTimeout(function() {
                                                el.removeClass( "dd_highlight" ,100);
                                
                                        }, 1500 );
                                
                                });
                        }
                                
                });

});


//**************************TO DO*****************************

// $(document).bind('refresh_group', function () {

//                 $(".snaptarget_group").unbind('click');
//                 $( ".snaptarget_group" ).click(function() {

//                         $('.new_overlay').hide();
// //open a new overlay containing group shared
//                         $('#results').addClass("new_overlay");
//                         $('#results').show();
//                         show_grey_bg();
//                         return false;

//                 });

//                 $("#grp").unbind('click');
//                 $( "#grp" ).click(function() {

//                         $('.new_overlay').hide();
// //open a new overlay containing group shared
//                         $('#results').addClass("new_overlay");
//                         $('#results').show();
//                         show_grey_bg();
//                         return false;

//                 });

// });

function update_online_members() {
	var html_online = [];
	 $.ajax({
      type: "POST",
      url: "get_online_members.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		var id = dat['member_id'];
		var firstname = dat['firstname'];
		var lastname = dat['lastname'];
		var facebookId = dat['facebook_id'];
		if(facebookId == null){
			html_online.push('<span ondrop="drop(event)" ondragover="allowDrop(event)" id="member_'+id+'" ><img src="images/user-default.png" alt="test" height="30" width="30" class="snaptarget" >'+firstname+' '+lastname+'<br></span>');

		}
		else{
			html_online.push('<span ondrop="drop(event)" ondragover="allowDrop(event)" id="member_'+id+'" ><img src="https://graph.facebook.com/'+facebookId+'/picture?type=large" alt="test" height="30" width="30" >'+firstname+' '+lastname+'<br></span>');
		}
		}
	}	  }
    });
    $('#roster_online').empty().append(html_online.join(''));
	var html_offline = [];
	 $.ajax({
      type: "POST",
      url: "get_offline_members.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		var id = dat['member_id'];
		var firstname = dat['firstname'];
		var lastname = dat['lastname'];
		html_offline.push('<span ondrop="drop(event)" ondragover="allowDrop(event)" id="member_'+id+'" ><img src="images/user-default.png" alt="test" height="30" width="30">'+firstname+' '+lastname+'<br></span>');
		}
	}	  }
    });
	$('#roster_offline').empty().append(html_offline.join(''));
   var html = [];
    $.ajax({
      type: "POST",
      url: "get_shared_with_friend.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 
		if( html != ""){
			$("#progs").html(html.join(''));}
		else{
			$("#progs").html("<div class='dotted_box'><br><br><br>No programs in this section yet </div>");
		}
		var html = [];
    $.ajax({
      type: "POST",
      url: "get_recently_viewed.php",
      async: false,
      data: {},
      dataType: "json",
      success: function(data){
		if(data){
		for (var dat in data) {
		dat = data[dat]
		html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1)}
	}	  }
    });
	 
		if( html != ""){
			$("#history2").html(html.join(''));}
		else{
			$("#history2").html("<div class='dotted_box'><br><br><br>No programs in this section yet </div>");
		}
}
		
//annoying bloody audio stuff
//http://codingrecipes.com/documentgetelementbyid-on-all-browsers-cross-browser-getelementbyid
function get_object(id) {
   var object = null;
   if (document.layers) {       
    object = document.layers[id];
   } else if (document.all) {
    object = document.all[id];
   } else if (document.getElementById) {
    object = document.getElementById(id);
   }
   return object;
}


function remove_search_text(){
  $("#search_text").attr("value","");
}

function close_notifications(){
  $("#notify_large").hide();
}

function show_grey_bg(){
 $("#bg").show();
}


function hide_overlay(){
  overlaycounter = null;
  overlay_navigation = [];
$("#new_overlay").children().filter("video").each(function(){
    this.pause();
    this.remove();
});
$("#new_overlay").empty();
 // $("#new_overlay").get(0).pause();
 // $("#new_overlay")[0].pause();
 $("#bg").hide();
 $("#myvid").html("");
 $("#new_overlay").hide();

          
}

  // Logout Function
  function Logout() {
    $.ajax({
       url: 'logout.php',
       async : false,
         success: function(){
           window.location.href= "http://www.n-screen.crowdtruth.org";
         }
      });
    //console.log("RESPUESTA  "+ lalala):
    //FB.logout(function () { document.location.reload(); });
  }

function new_ted_random(){
	  var replace_content=true;
	  $sr=$("#search_results");
	  $sr.css("display","block");

	  $container=$("#browser");
	  $container.css("display","none");
	  var ele = "search_results";
	  $browse=$("#browse");
	  $browse.addClass("grey").removeClass("blue");
	  
	  $browse=$("#randomBBC");
	  $browse.addClass("grey").removeClass("blue");

	  $random=$("#randomTED");
	  $random.addClass("blue").removeClass("grey");
	   var html = [];
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
		$qry="SELECT id,image_url,title FROM ted_talks ORDER BY RAND() LIMIT 30";
		$result=mysql_query($qry);   
		while ($row = mysql_fetch_array($result)){ ?>
			var id = <?php echo $row[0]; ?>;
			var program_id = <?php echo $row[0]; ?>;
	        var imgUrl = "<?php echo $row[1]; ?>";
		    var title = "<?php echo str_replace('"',"",$row[2]); ?>";
	        html = create_html(html,id,program_id,imgUrl,title,0);
			<?php
		}
		?>
	
   	if(replace_content){
	  $("#"+ele).html(html.join(''));
	}else{
	  $("#"+ele).append("<div id=\"more\">"+html.join('')+"</div>");
	}
	
	$("#"+ele).append('');
   $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');

	
}

 function new_bbc_random(){
	  var replace_content=true;
	  $sr=$("#search_results");
	  $sr.css("display","block");

	  $container=$("#browser");
	  $container.css("display","none");
	  var ele = "search_results";
	  $browse=$("#browse");
	  $browse.addClass("grey").removeClass("blue");

	  $browse=$("#randomTED");
	  $browse.addClass("grey").removeClass("blue");
	  
	  $random=$("#randomBBC");
	  $random.addClass("blue").removeClass("grey");
	   var html = [];
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
		$qry="SELECT id,bbc_id,image_url,title FROM bbc_programs ORDER BY RAND() LIMIT 30";
		$result=mysql_query($qry);   
		while ($row = mysql_fetch_array($result)){ ?>
			var id = <?php echo $row[0]; ?>;
			var program_id = <?php echo $row[0]; ?>;
	        var imgUrl = "<?php echo $row[2]; ?>";
		    var title = "<?php echo str_replace('"',"",$row[3]); ?>";
	        html = create_html(html,id,program_id,imgUrl,title,1);
			<?php
		}
		?>
	 
   	if(replace_content){
	  $("#"+ele).html(html.join(''));
	}else{
	  $("#"+ele).append("<div id=\"more\">"+html.join('')+"</div>");
	}

	$("#"+ele).append('');
   $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');

	
}

function create_html(html,id,program_id,imgUrl,title,bbcorted){
	var string = "<div  id=\""+id+"\" pid=\""+id+"\" class=\"ui-widget-content button programme ui-draggable recomended object\" draggable=\"true\" ondragstart=\"drag(event)\" " ;
    string += " onclick= \"javascript:watch_video(";
    string += id+","+bbcorted+");return true\">";
	html.push(string);
	html.push("<img class=\"img img_small\" src=\""+imgUrl+"\" />");
	if (bbcorted == 0){
		var list_watch_later = list_watch_later_TED;
		var list_likes = list_likes_TED;
		var list_dislikes = list_dislikes_TED;
		}else{
		var list_watch_later = list_watch_later_BBC;
		var list_likes = list_likes_BBC;
		var list_dislikes = list_dislikes_BBC;
		}
      html.push("<div class = 'gradient_div_icons' style='margin-top:-54px;'>");
      if(not_in_list(id,list_watch_later)){
        html.push("<img id=\"watch_random"+id+"\" class=\"watch_random"+id+" overlapicon\" src=\"/images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img id='watch_random"+id+"' class=\"watch_random"+id+" overlapicon\" src=\"/images/icons/on_watch_later.png\"/>");
      }
      if(not_in_list(id,list_likes)){
		if(not_in_list(id,list_dislikes)){
			html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/neutral.png\" />");
		}
		else{
			html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/on_dislike.png\" />");
		}
      }
      else{
		
        html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/on_like.png\" />");      
      }
      //if(not_in_list(id,list_dislikes)){
      //  html.push("<img class=\"overlapicon\" src=\"/images/icons/dislike.png\" />");
      //}
      //else{
      //  html.push("<img class=\"overlapicon\" src=\"/images/icons/on_dislike.png\" />");      
      //}
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"/images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"/images/icons/on_recently_viewed.png\" />");      
      }

    html.push("</div>");
    html.push("<span class=\"p_title p_title_small\"><a >"+title+"</a></span>");
	html.push("</div>");
	return html;
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var dat = ev.dataTransfer.getData("text");
	var share_with = ev.target.id
	$.ajax({
	type:'POST',
	url: 'insert_share.php',
	data:{programme_id: dat, member_id: share_with},
	dataType: "json",
	success: function (data) {
	}
		
	});
}

function watch_video(id,bbcorted) {
      $.ajax({
		type:'POST',
		url: '../get_video.php',
		data:{id: id, bbcorted: bbcorted},
		dataType: "json",
		success: function (data) {
			
			if(bbcorted == 0){
				add_video (data['0'],data['1'],data['2'],data['4'],data['5'],data['6'],data['3'],0,0,[],[]);
			}else{
				add_video (data['0'],data['1'],data['2'],data['4'],data['5'],data['6'],data['3'],data['7'],data['8'],data['9'],data['10'],data['11']);
			}
		}
		
	  });
	 
}

function add_video(id,pid,titleRaw,video,img,speaker_id,description,start,end,section,tags_video,scene_tags_video){
	get_symbols();
	  tags = tags_video;
	  scene_tags = scene_tags_video;
      var div = $("#"+id);
	 var speaker = "";
		var title = titleRaw;
		bbcorted = 1;
	
     
	  var flag = false
      html = [];
	  if (bbcorted == 0){
		var list_watch_later = list_watch_later_TED;
		var list_likes = list_likes_TED;
		var list_dislikes = list_dislikes_TED;
		}else{
		var list_watch_later = list_watch_later_BBC;
		var list_likes = list_likes_BBC;
		var list_dislikes = list_dislikes_BBC;
		}
      html.push("<div id=\""+id+"\" pid=\""+pid+"\" href=\""+video+"\" class=\"ui-widget-content button programme ui-draggable recomended object\"" + "onclick=\"javascript:insert_suggest2("+pid+");return true\">");
      
      html.push("<img class=\"img img_small\" src=\""+img+"\" />");
      html.push("<div style='margin-top:-54px;'>");
	  
      if(not_in_list(id,list_watch_later)){
        html.push("<img id=\"watch_random"+id+"\" class=\"watch_random"+id+" overlapicon\"  src=\"/images/icons/watch_later.png\"/>");
      }
      else{
        html.push("<img id=\"watch_random"+id+"\" class=\"watch_random"+id+" overlapicon\" src=\"/images/icons/on_watch_later.png\"/>");
      }
           if(not_in_list(id,list_likes)){
		if(not_in_list(id,list_dislikes)){
			html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/neutral.png\" />");
		}
		else{
			html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/on_dislike.png\" />");
		}
      }
      else{
		
        html.push("<img name='like_name' class=\"like_name"+id+" overlapicon\" src=\"/images/icons/on_like.png\" />");      
      }
     // if(not_in_list(id,list_dislikes)){
      //  html.push("<img class=\"overlapicon\" src=\"/images/icons/dislike.png\" />");
     // }
     /// else{
     //   html.push("<img class=\"overlapicon\" src=\"/images/icons/on_dislike.png\" />");      
     // }
      if(not_in_list(id,list_recently_viewed)){
        html.push("<img class=\"overlapicon\" src=\"/images/icons/recently_viewed.png\" />");
      }
      else{
        html.push("<img class=\"overlapicon\" src=\"/images/icons/on_recently_viewed.png\" />");      
      }
 

    html.push("</div>");

      html.push("<span class=\"p_title p_title_small\"><a>"+title+"</a></span>");
      html.push("<p class=\"description large\">"+description+"</p>");
      html.push("</div>");
	 
      $('#history').prepend(html.join(''));


      html2 = [];

      html2.push("<div id='close' class='close_button recomended object'><img src='/images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div>");
      html2.push("<div id=\"video_"+id+"\" pid=\""+pid+"\" href=\""+video+"\"  class=\"large_prog recomended object\" style=\"position: relative;\">");
      html2.push("<div class=\"gradient_div\" style=\"text-align: center;  margin-left: 55%; position: absolute; \"> <img class=\"img\" src=\""+img+"\" />");
      html2.push("<div id=\"play_"+id+"_"+start+"_"+end+"_"+section+"\" class=\"play_button recomended object\" onclick=\"javascript:show_video('"+video+"',"+id+","+bbcorted+","+start+","+end+",'"+section+"');\"><img style='width: 120px;' src=\"/images/icons/play.png\" /></a></div></div>");
      html2.push("<div style='padding-left: 20px; padding-right: 20px; width: 50%; left: 0px; position: absolute;'>");
	   if (bbcorted == 0){
      html2.push("<div style ='cursor: pointer;'class=\"p_title_large_speaker\" onclick=\"javascript:insert_speaker("+speaker_id+");return true\">"+speaker+':'+"</div>");
      }
	  html2.push("<div class=\"p_title_large\">"+title+"</div>");
      html2.push("<p class=\"description\">"+description+"</p>");
      html2.push("<div class=\"list_tags\" style='display:inline;'>");
      for(var i=0; i<tags.length; i++){
      
          html2.push("<span id=\"tags_"+tags[i]+"\" class=\"item_tag recomended object\" onclick=\"javascript:insert_suggest_by_tag('"+tags[i]+"');return true\">#"+tags[i]+"</span>");
      
        
      }
	
      html2.push("</div>");
	  if (bbcorted == 0){
      html2.push("<p class=\"link\"><a href=\"http://www.ted.com/talks/view/id/"+pid+"\" target=\"_blank\">Sharable Link</a></p></div>");
		}else{
		  html2.push("<p class=\"link\"><a href=\"http://www.bbc.co.uk/programmes/"+pid+"\" target=\"_blank\">Sharable Link</a></p></div>");
		}
      html2.push("<div class='vertical_buttons' style='display:table-cell; vertical-align: middle; margin-right: 7%; position: absolute; text-align: center; right:0; top: 10px'>");
      if(not_in_list(id,list_watch_later)){
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='addtowatchlater' style='width: 40px;' onclick=\"javascript:watch_later("+id+","+bbcorted+",1);\" src=\"/images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span></div>");      
      }
      else{
              html2.push("<div id='watchlater'class=\"interactive_icon\"><img id='deletewatchlater' style='width: 40px;' onclick=\"javascript:watch_later("+id+","+bbcorted+",0);\"src=\"/images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span></div>");      
      }
	  if(not_in_list(id,list_likes)){
		if(not_in_list(id,list_dislikes)){
			html2.push("<div id='like' class=\"interactive_icon\"><img id='normal' style='width: 40px;' onclick=\"javascript:like("+id+","+bbcorted+",1);\" src=\"/images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'></span></div>");

		}
		else{
			html2.push("<div id='like' class=\"interactive_icon\"><img id = 'deletedislike' style='width: 40px;' onclick=\"javascript:like("+id+","+bbcorted+",0);\"src=\"/images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'></span></div>");

		}
      }
      else{
		
            html2.push("<div id='like' class=\"interactive_icon\"><img id='like' style='width: 40px;' onclick=\"javascript:dislike("+id+","+bbcorted+",1);\" src=\"/images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'></span></div>");
   
      }
   
      //if(not_in_list(id,list_dislikes)){
     // html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'addtodislike' style='width: 40px;' onclick=\"javascript:dislike("+id+","+bbcorted+",1);\"src=\"/images/icons/dislike.png\" /><span style='display: block'; class ='inter_span'>Dislike</span></div>");
     // }
      //else{
      //html2.push("<div id='dislike' class=\"interactive_icon\"><img id = 'deletedislike' style='width: 40px;' onclick=\"javascript:dislike("+id+","+bbcorted+",0);\"src=\"/images/icons/on_dislike.png\" /><span style='display: block'; class ='on_inter_span'>Dislike</span></div>");
     // }
      if(flag == false){
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"/images/icons/recently_viewed.png\" /><span style='display: block'; class ='inter_span'>Watched</span></div></div>");
      }
      else{
        html2.push("<div class=\"interactive_icon\"><img style='width: 40px;' src=\"/images/icons/on_recently_viewed.png\" /><span style='display: block'; class ='on_inter_span'>Watched</span></div></div>");
      }
      html2.push("</div>");
      html2.push("</div>");
      $('#new_overlay').html(html2.join(''));
    
      $('#new_overlay').show();
      show_grey_bg();
		   
      $('#new_overlay').append("<div id=\"more_like_this\" class=\"more_like_this\" style=\"margin-top: 400px;\"><span class=\"sub_title\">MORE LIKE THIS</span><span class=\"more_blue\"><a id ='more_related' onclick='show_related();''>View All &triangledown;</a></span></div>");
	  var html_related = [];
	  var already_added = [];
	  for(var i=0; i<tags.length; i++){
		 $.ajax({
			type: "POST",
			url: "get_talks_by_tag.php",
			async: false,
			data: {id: id, tag: tags[i]},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				
				dat = data[dat];
				if(already_added.indexOf(parseInt(dat['id'])) == -1){
				html_related = create_html(html_related,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				already_added.push(parseInt(dat['id']));}
				}
			}
		}
		});
	  }
	        $('#new_overlay').append("<div id='spinner' style=\"float: left;\"></div>");
	  //$("#spinner").html(html.join(''));
	  $("#spinner").append(html_related.join(''));
      $('#new_overlay').append("<div class='clear'></div>");
      check_overflow();
      
}

function show_video(videoUrl,id,bbcorted,startTime,end,section){
	if(bbcorted=="1"){
	$("#new_overlay").html("<div id='close' class='close_button recomended object'><img src='/images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();javascript:watch_whole("+id+","+startTime+","+end+",\""+section+"\");'/></div><div  id='player'><video id='myvid' width='100%' controls=''  autoplay onpause='javascript:hide_overlay();javascript:watch_whole("+id+","+startTime+","+end+",\""+section+"\");'><source src=\""+videoUrl+"#t="+startTime+","+end+"\" type=\"video/mp4\"></video></div>");
	}else{
	$("#new_overlay").html("<div id='close' class='close_button recomended object'><img src='/images/icons/exit.png' width='12px' onclick='javascript:hide_overlay();'/></div><div id='player'><video id='myvid' width='100%' controls=''><source src=\""+videoUrl+"\" type=\"video/mp4\"></video></div>");
	}
}

function show_next_questions(id){
	if( id == 0){
	$("#welcome").show();
	$("#preferences").hide();
		$("#demographics").hide();
	}else if(id==1){
		$("#welcome").hide();
		$("#preferences").hide();
		$("#demographics").show();
	}else{
		$("#welcome").hide();
		$("#preferences").show();
		$("#demographics").hide();
	}
}
function watch_whole(id,startTime,endTime,section){
	var duration = endTime - startTime;
	html = [];
	count_whole = 0;
	count_tags = 0;
	html.push('<br><div style="margin-left:2%"><form id="ratingsform" class="form-horizontal" action="insert_rates.php" method="post" > <div class="form-group"><h5> Please answer the following questions:</h5></div>');
	html.push("<div class='form-group' id='confirm'> 1. Would you like to watch the entire video?<br><span style='color:red;' id='fullprogram_error'></span>");
	html.push('<div class="radio"><label><input class="radio" type="radio" name="rating" value="1" onClick="interesting()"/>Yes </label></div>');
	html.push('<div class="radio"><label><input class="radio" type="radio" name="rating" value="0" onClick="not_interesting()"/>No </label></div>');
	html.push('</div>');
	html.push("<div class='form-group' id='surprise'> 2. Are you positively surprised by this recommendation?<br><span style='color:red;' id='surprise_error'></span>");
	html.push('<div class="radio"><label><input class="radio" type="radio" name="surprise_rec" value="1" onClick="interesting()"/>Yes </label></div>');
	html.push('<div class="radio"><label><input class="radio" type="radio" name="surprise_rec" value="0" onClick="not_interesting()"/>No </label></div>');
	html.push('</div>');
	html.push("<div class='form-group'> <div id='interesting' ><span id='interesting_title'> 3. Indicate how much do you agree with the following statements:</span><br><span style='color:red;' id='extract_error'></span>");
	html.push('<table style="width:80%">');
    html.push('<tr>');
    html.push('<td style="text-align:left; padding:0 15px 0 15px; width:50%;">       </td>');
    html.push('<td style="padding:0 15px 0 15px;">  Completely disagree </td> ');
    html.push('<td style="padding:0 15px 0 15px;"> Disagree </td>');
	html.push('<td style="padding:0 15px 0 15px;"> Neutral </td>');
    html.push('<td style="padding:0 15px 0 15px;"> Agree </td> ');
    html.push('<td style="padding:0 15px 0 15px;"> Completely agree </td>');
    html.push('</tr>');
    html.push('<tr>');
    html.push(' <td style="padding:0 15px 0 15px;"> The extract was long enough to decide whether to watch the whole video</td>');
    html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="long-enough" value="0"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="long-enough" value="1"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="long-enough" value="2"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="long-enough" value="3"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="long-enough" value="4"/></td> ');
    html.push('</tr>')
	 html.push('<tr>');
    html.push(' <td style="padding:0 15px 0 15px;"> The extract was too short to decide whether to watch the whole video</td>');
    html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-short" value="0"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-short" value="1"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-short" value="2"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-short" value="3"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-short" value="4"/></td> ');
    html.push('</tr>')
	 html.push('<tr>');
    html.push(' <td style="padding:0 15px 0 15px;"> The extract was too long </td>');
    html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-long" value="0"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-long" value="1"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-long" value="2"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-long" value="3"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="too-long" value="4"/></td> ');
    html.push('</tr>')
	 html.push('<tr>');
    html.push(' <td style="padding:0 15px 0 15px;"> The extract contained spoilers</td>');
    html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="spoilers" value="0"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="spoilers" value="1"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="spoilers" value="2"/></td> ');
    html.push(' <td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="spoilers" value="3"/></td>');
	html.push('<td style="padding:0 15px 0 15px;"><input class="genre-star" type="radio" name="spoilers" value="4"/></td> ');
    html.push('</tr>')
    html.push('</table></div>');
	html.push('<br>');
	html.push('<input type="hidden" name="id" value="'+id+'" />');
	html.push('<input type="hidden" name="start" value="'+startTime+'" />');
	html.push('<input type="hidden" name="duration" value="'+duration+'" />');
	html.push('<input type="hidden" name="section" value="'+section+'" />');
	html.push('<div style="text-align:center;"><button class="btn btn-primary" type="submit" value="Submit" >Continue</button></div>');
	//html.push('<div style="text-align:center;"><button type="button" value="Submit" class="btn btn-primary" ">Continue</button></div>');
	html.push('</div><br><br>');		
	html.push('</form></div>');

	$("#new_overlay").html(html.join(''));
	$('#new_overlay').show();
	$('#ratingsform').submit(function (e) {
			e.preventDefault();
			var error_rating = "Please answer the question.";
			var error_questions = "Please answer all the questions.";
			var data = $(this).serializeArray();
			var questions = 0
			var count = 0;
			jQuery.each( data, function( i, field ) {
				if ( field.name == 'rating'){
					error_rating = "";
					count += 1;
				}
				if ( field.name == 'surprise_rec'){
					error_rating = "";
					count += 1;
				}
				if (( field.name == 'long-enough') || (field.name == 'too-short') || (field.name == 'too-long') || (field.name == 'spoilers')){
					questions += 1;
				}
				
			});
			if ( questions == 4){
				error_questions = "";
				count += 1;
			}
			if ( count == 3 ){
			$.ajax({
				type: 'post',
				url: 'insert_rates.php',
				data: data,
				success: function (data) {
						$('#new_overlay').hide();
					}
				});}else{
					document.getElementById("fullprogram_error").innerHTML = error_rating;
					document.getElementById("surprise_error").innerHTML = error_rating;
					document.getElementById("extract_error").innerHTML = error_questions;

				}
			}) 
	
}


function watch_whole_confirm(id,startTime,endTime,watchWhole){

	$.ajax({
			type:'POST',
			url: '../watch_whole.php',
			data:{id: id,url: document.URL, time_started: startTime, time_ended: endTime,watchWhole: watchWhole},
			dataType: "json",
			success: function (data) {
			}
	    });
}

function watch_later(id,bbcorted,watched){
	$.ajax({
			type:'POST', 
			url:'insert_watched_later.php', //Defined in your routes file
			data:{id: id, bbcorted: bbcorted, watched: watched, url: document.URL},                                                                   
			dataType: "json",
			success: function (data) {	
				  if( watched == 0){
					$("#watchlater").html("<img id='addtowatchlater' style='width: 40px;' onclick=\"javascript:watch_later("+id+","+bbcorted+",1);\" src=\"/images/icons/watch_later.png\" /><span style='display: block'; class ='inter_span'>Watch Later</span>");
				    $(".watch_random"+id).attr("src","/images/icons/watch_later.png");
				  }else{
  					$("#watchlater").html("<img id='deletewatchlater' style='width: 40px;' onclick=\"javascript:watch_later("+id+","+bbcorted+",0);\" src=\"/images/icons/on_watch_later.png\" /><span style='display: block'; class ='on_inter_span'>Watch Later</span>");
					$(".watch_random"+id).attr("src","/images/icons/on_watch_later.png");
				  }					
			}
	  });
}

function like(id,bbcorted,liked){
	$.ajax({
			type:'POST', 
			url:'insert_like.php', //Defined in your routes file
			data:{id: id, bbcorted: bbcorted, liked: liked, url: document.URL},                                                                   
			dataType: "json",
			success: function (data) {	
				  if( liked == 0){
					$("#like").html("<img id = 'llike' style='width: 40px;' onclick=\"javascript:like("+id+","+bbcorted+",1);\"src=\"/images/icons/neutral.png\" /><span style='display: block'; class ='inter_span'></span>");
				    $(".like_name"+id).attr('src', '/images/icons/neutral.png');
				  }else{
  					$("#like").html("<img id='middle' style='width: 40px;' onclick=\"javascript:dislike("+id+","+bbcorted+",1);\" src=\"/images/icons/on_like.png\" /><span style='display: block'; class ='on_inter_span'></span>");
					$(".like_name"+id).attr('src', '/images/icons/on_like.png');
				  }					
			}
	  });
}

function dislike(id,bbcorted,dislike){
	$.ajax({
			type:'POST', 
			url:'insert_dislike.php', //Defined in your routes file
			data:{id: id, bbcorted: bbcorted, dislike: dislike, url: document.URL},                                                                   
			dataType: "json",
			success: function (data) {	
				  if( dislike == 1){
					$("#like").html("<img id = 'ldislike' style='width: 40px;' onclick=\"javascript:like("+id+","+bbcorted+",0);\"src=\"/images/icons/on_dislike.png\" /><span style='display: block'; class ='inter_span'></span>");
				    $(".like_name"+id).attr('src', '/images/icons/on_dislike.png');
				  }				
			}
	  });
}

function get_genre(genre){
	  var replace_content=true;
	  genre = genre.value;
	  var ele = "results_genre";
	   var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_genre.php",
			async: false,
			data: {genre: genre},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
				}
		}
		});
	
   	if(replace_content){
	  $("#"+ele).html(html.join(''));
	}else{
	  $("#"+ele).append("<div id=\"more\">"+html.join('')+"</div>");
	}
	
	$("#"+ele).append('');
   $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');

	
}

function get_format(format){
	  format = format.value;
	  var replace_content=true;
	  var ele = "results_format";
	   var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_format.php",
			async: false,
			data: {format: format},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				}
			}
		}
		});
	
   	if(replace_content){
	  $("#"+ele).html(html.join(''));
	}else{
	  $("#"+ele).append("<div id=\"more\">"+html.join('')+"</div>");
	}
	
	$("#"+ele).append('');
   $(document).trigger('refresh');
   $(document).trigger('refresh_buttons');

	
}
function show_format(){
		$.ajax({
			type: "POST",
			url: "get_questions_fill.php",
			async: false,
			data: {format: format},
			dataType: "json",
			success: function(data){
				if(data){
				 var age = data
			}
		}
		});

		if( age == ''){
			$("#questions").show();
			$("#inner").hide();
	}else{
$("#inner").hide();
$("#questions").hide();
$("#format_list").show();
$("#genre_list").hide();
  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_format.php",
			async: false,
			data: {format: "documentaries"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#docs").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_format.php",
			async: false,
			data: {format: "quizzes"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#quiz").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_format.php",
			async: false,
			data: {format: "events"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#talent").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_format.php",
			async: false,
			data: {format: "reality"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#real").html(html.join(''));}
}
function show_profile_page(){
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
		$member_id = $_SESSION['SESS_MEMBER_ID'];
		$qry="SELECT gender,age,education,origin,fav_genre,fav_format,pref_morning,pref_afternoon,pref_evening,pref_night,pref_morning_weekend,pref_afternoon_weekend,pref_evening_weekend,pref_night_weekend FROM members where member_id=$member_id";
		$result=mysql_query($qry);   
		while ($row = mysql_fetch_array($result)){ 
			$gender = $row[0];
			$age = $row[1];
			$education = $row[2];
			$origin = $row[3];
			$genre = $row[4];
			$format = $row[5];
			$morning = $row[6];
			$afternoon = $row[7];
			$evening = $row[8];
			$night = $row[9];
			$morning_weekend = $row[10];
			$afternoon_weekend = $row[11];
			$evening_weekend = $row[12];
			$night_weekend = $row[13];
			?>
			var age = '<?php echo $row[1]; ?>';
		<?php
		}
		?>
		if( age == ''){
			$("#questions").show();
			$("#inner").hide();
		}else{
			var error_gender = "";
			var error_age = "";
			var error_education = "";
			var error_origin = "";
			var error_format = "";
			var error_genre = "";
			var error_weekdays = "";
			var error_weekend = "";
			show_next_questions(0);
			document.getElementById("using_application").innerHTML = "<h3>The following answers are given by you: </h3>";
			$('#personalForm').submit(function (e) {
			e.preventDefault();
			var error_gender = "Please fill in a gender";
			var error_age = "Please fill in a age";
			var error_education = "Please fill in your study";
			var error_origin = "Please fill in your origin";
			var error_format = "Please select your favourite format";
			var error_genre = "Please select your favourite genre";
			var error_weekdays = "Please fill in all you tv preferences";
			var error_weekend = "Please fill in all you tv preferences";
			var data = $(this).serializeArray();
			var weekdays = 0;
			var weekend = 0;
			var count = 0;
			jQuery.each( data, function( i, field ) {
				if ( field.name == 'gender'){
					error_gender = "";
					count += 1;
				}
				if ( field.name == 'age'){
					error_age = "";
					count += 1;
				}
				if ( field.name == 'genre[]'){
					error_genre = "";
				
				}
				if ( field.name == 'education'){
					error_education = "";
					count += 1;
				}
				if ( field.name == 'country'){
					error_origin = "";
					count += 1;
				}
				if ( field.name == 'format[]'){
					error_format = "";
				}
				if (( field.name == 'morning') || (field.name == 'afternoon') || (field.name == 'evening') || (field.name == 'night')){
					weekdays += 1;
				}
				if (( field.name == 'morning_weekend') || (field.name == 'afternoon_weekend') || (field.name == 'evening_weekend') || (field.name == 'night_weekend')){
					weekend += 1;
				}
				
			});
			if ( error_genre == ""){
				count += 1;
				}
				if ( error_format == ""){
				count += 1;
				}
			if ( weekdays == 4){
				error_weekdays = "";
				count += 1;
			}
			if ( weekend == 4 ){
				error_weekend = "";
				count += 1;
			}
			if ( count == 8 ){
			$.ajax({
				type: 'post',
				url: 'insert_personal_inf.php',
				data: data,
				success: function (data) {
						$("#inner").show();
						$("#questions").hide();
					}
				});}else{
					document.getElementById("gender-error").innerHTML = error_gender;
					document.getElementById("age-error").innerHTML = error_age;
					document.getElementById("education-error").innerHTML = error_education;
					document.getElementById("origin-error").innerHTML = error_origin;
					document.getElementById("genre-error").innerHTML = error_genre;
					document.getElementById("format-error").innerHTML = error_format;
					document.getElementById("weekdays-error").innerHTML = error_weekdays;
					document.getElementById("weekend-error").innerHTML = error_weekend;

					show_next_questions(0)
				}
			}) 		
	$("#questions").show();
	$("#inner").hide();
	$("#format_list").hide();
	$("#genre_list").hide();
	}
}
function show_genre(){
 $.ajax({
			type: "POST",
			url: "get_questions_fill.php",
			async: false,
			data: {format: format},
			dataType: "json",
			success: function(data){
				if(data){
				 var age = data
			}
		}
		});

		if( age == ''){
			$("#questions").show();
			$("#inner").hide();
	}else{
	$("#questions").hide();
$("#inner").hide();
$("#format_list").hide();
$("#genre_list").show();
  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_genre.php",
			async: false,
			data: {genre: "comedy"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#comedies").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_genre.php",
			async: false,
			data: {genre: "factual"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#fac").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_genre.php",
			async: false,
			data: {genre: "entertainment"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#entertainment").html(html.join(''));
	  var html = [];
	   $.ajax({
			type: "POST",
			url: "get_programmes_by_genre.php",
			async: false,
			data: {genre: "drama"},
			dataType: "json",
			success: function(data){
				if(data){
				for (var dat in data) {
				dat = data[dat];
				html = create_html(html,dat['id'],dat['id'],dat['image_url'],dat['title'],1);
				
				}
			}
		}
		});
	
	$("#drama").html(html.join(''));}
}
function show_more_format(){
 var content = $('#content');
  //if expanded-->contract
  if (content[0].style.height == '100%'){
    content.css('height','293px');
    $('a#moreformat').html('View All &triangledown;');
    $('#lessformat').remove();
  }
  else{
    content.css('height','100%');
    $('a#moreformat').html('View Less &utri;');
    content.append("<span id='lessformat' class='more_blue'><a onclick='show_more_format();'>View Less &utri;</a></span>");
  }
  $(document).trigger('refresh');
  $(document).trigger('refresh_buttons');
  check_overflow();
}
</script>

<nav class="navbar">
 
  <div id="header" class="navbar-brand page-scroll">
  	<div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="javascript:show_browse_programmes()" style='font-family:"Kaushan Script","Helvetica Neue",Helvetica,Arial,cursive'>N-SCREEN</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                   
                    <li>
                        <a class="page-scroll" href="javascript:show_profile_page();" style='font-family:"Kaushan Script","Helvetica Neue",Helvetica,Arial,cursive'><?php echo $_SESSION['SESS_FIRST_NAME']; ?> </a>
                    </li>
                    <li>
                        <a class="page-scroll" href="javascript:Logout();"><i class="fa fa-sign-out fa-5" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    <!--<a href="javascript:show_browse_programmes()" style='font-family:"Kaushan Script","Helvetica Neue",Helvetica,Arial,cursive'>N-SCREEN</a>
    <a href="javascript:show_profile_page();" style='right:50px;position:absolute;text-align:right;margin-right:3%;font-family:"Kaushan Script","Helvetica Neue",Helvetica,Arial,cursive'><?php echo $_SESSION['SESS_FIRST_NAME']; ?> 
    <a href="javascript:Logout();"><i class="fa fa-sign-out fa-5" aria-hidden="true" style='right:50px;position:absolute;text-align:right;'></i></a>-->
	</div>
  
</nav>
       
<!-- <br clear="both"/> -->

  <div class="notifications_red" id="notify"></div>
  <div class="notifications_red_large" id="notify_large" onclick="javascript:close_notifications();"></div>


<div id="container">

	<div id="questions">
	<div id="welcome" style="position:absolute;left:10%; right :20%; display: none;">
	<br><br>
	<form class="form-horizontal">
	<div class="form-group">
    <h1 style="text-align:center;">Welcome!</h1>
    <p style="font-size:16px">This is a video recommender study. This study is performed at the VU University Amsterdam. You are a participant in an experiment to understand how long users need to view a video to decide whether they are interested in watching it further or not. The videos selected in this experiment are BBC videos from the period of April, 19th to May 19th, 2016 (excluding children's videos and news bulletins). 
</p>
		<p style="font-size:16px">As it is the first time you are using this application, we are going to ask you some questions about yourself and your video watching behaviour.</p>
		
		<p style="font-size:16px">All of your answers will be kept strictly confidential. The information provided will be used solely for the purpose of this academic research project.
		</p>
		<br>
		<p style="font-size:16px">
			Thank you for participating in this study!
		</p>
		<div style="text-align:center;">
					
			<button  type="button" class="btn btn-primary" onClick="show_next_questions(1)">Start</button>
		</div>
	
	</div>
	</div>
	</form>
	<div id="demographics" style="position:absolute;left:10%; right :20%;display: none;">
	<form id="personalForm" class="form-horizontal" role="form" method="post" action="insert_personal_inf.php">
		<br><br><br><div id="using_application">
		<div class="form-group">
			<h3>Demographics</h3>
		
			<h4 for="control">1. What is your gender?</h4><font size="3" color="red"><span id="gender-error"></span></font>
			<div class="radio" >
				<label class="radio"><input type="radio" value="Male"  <?php if (isset($gender) && $gender=="Male") echo "checked";?> name="gender">Male</label>
			</div>
			<div class="radio" >
				<label class="radio"><input type="radio" value="Female" <?php if (isset($gender) && $gender=="Female") echo "checked";?> name="gender">Female</label>
			</div>
			<div class="radio" >
				<label class="radio"><input type="radio" value="Other" <?php if (isset($gender) && $gender=="Other") echo "checked";?> name="gender">Other</label>
			</div>
		</div>
		<div class="form-group">
		<h4>2. What is your age?</h4><font size="3" color="red"><span id="age-error"></span></font>
		<div class="col-sm-2">
			<select id="age" name= "age" class="form-control">
				<option <?php if (isset($age) && $age=="Under 18") echo "selected";?>>Less than 18</option>
				<option <?php if (isset($age) && $age=="18") echo "selected";?>>18</option>
				<option <?php if (isset($age) && $age=="19") echo "selected";?>>19</option>
				<option <?php if (isset($age) && $age=="20") echo "selected";?>>20</option>
				<option <?php if (isset($age) && $age=="21") echo "selected";?>>21</option>
				<option <?php if (isset($age) && $age=="22") echo "selected";?>>22</option>
				<option <?php if (isset($age) && $age=="23") echo "selected";?>>23</option>
				<option <?php if (isset($age) && $age=="24") echo "selected";?>>24</option>
				<option <?php if (isset($age) && $age=="25") echo "selected";?>>25</option>
				<option <?php if (isset($age) && $age=="26") echo "selected";?>>26</option>
				<option <?php if (isset($age) && $age=="27") echo "selected";?>>27</option>
				<option <?php if (isset($age) && $age=="28") echo "selected";?>>28</option>
				<option <?php if (isset($age) && $age=="29") echo "selected";?>>29</option>
				<option <?php if (isset($age) && $age=="30") echo "selected";?>>30</option>
				<option <?php if (isset($age) && $age=="Above 30") echo "selected";?>>More than 30</option>
			</select>
		</div>
		</div>
		<div class="form-group">
		<h4>3. What is your study course?</h4><font size="3" color="red"><span id="education-error"></span></font>
		<div class="radio " >
			<label class="radio"><input type="radio" id="education" value="business_analytics" <?php if (isset($education) && $education=="business_analytics") echo "checked";?> name="education" />Business Analytics</label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="Computer_science" <?php if (isset($education) && $education=="Computer_science") echo "checked";?> name="education"/>Computer Science</label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="econometrie" <?php if (isset($education) && $education=="econometrie") echo "checked";?> name="education"/>Econometrie en Operationele Research </label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="informatie_multimedia_management" <?php if (isset($education) && $education=="informatie_multimedia_management") echo "checked";?> name="education"/>Informatie, Multimedia en Management</label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="liberal_arts_sciences" <?php if (isset($education) && $education=="liberal_arts_sciences") echo "checked";?> name="education"/>Liberal Arts en Sciences</label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="lifestyle_informatics" <?php if (isset($education) && $education=="lifestyle_informatics") echo "checked";?> name="education"/>Lifestyle Informatics</label>
		</div>
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="science_business_innovation" <?php if (isset($education) && $education=="science_business_innovation") echo "checked";?> name="education"/>Science, business en innovation</label>
		</div>
		
		<div class="radio" >
			<label class="radio"><input type="radio" id="education" value="other"  <?php if (isset($education) && $education=="other") echo "checked";?> name="education"/>Other: </label><div class="col-sm-2"><input class="form-control " type="textbox" name="other"></div>
		</div>
		</div>
		<div class="form-group">
			<h4>4. What is your nationality?</h4><font size="3" color="red"><span id="origin-error"></span></font>
			<div class="col-sm-2">
					<select id="country" name= "country" class="form-control" selected="Grenada">
						<option <?php if (isset($origin) && $origin=="Afghanistan") echo "selected";?>>Afghanistan</option>
						<option <?php if (isset($origin) && $origin=="Argentina") echo "selected";?>>Argentina</option>
						<option <?php if (isset($origin) && $origin=="Armenia") echo "selected";?>>Armenia</option>
						<option <?php if (isset($origin) && $origin=="Australia") echo "selected";?>>Australia</option>
						<option <?php if (isset($origin) && $origin=="Austria") echo "selected";?>>Austria</option>
						<option <?php if (isset($origin) && $origin=="Azerbaijan") echo "selected";?>>Azerbaijan</option>
						<option <?php if (isset($origin) && $origin=="Belgium") echo "selected";?>>Belgium</option>
						<option <?php if (isset($origin) && $origin=="Bosnia Herzegovina") echo "selected";?>>Bosnia Herzegovina</option>
						<option <?php if (isset($origin) && $origin=="Botswana") echo "selected";?>>Botswana</option>
						<option <?php if (isset($origin) && $origin=="Brazil") echo "selected";?>>Brazil</option>
				
						<option <?php if (isset($origin) && $origin=="Bulgaria") echo "selected";?>>Bulgaria</option>
				
					
						<option <?php if (isset($origin) && $origin=="China") echo "selected";?>>China</option>
						<option <?php if (isset($origin) && $origin=="Colombia") echo "selected";?>>Colombia</option>
						<option <?php if (isset($origin) && $origin=="Dominica") echo "selected";?>>Dominica</option>
		
						<option <?php if (isset($origin) && $origin=="Finland") echo "selected";?>>Finland</option>
						<option <?php if (isset($origin) && $origin=="France") echo "selected";?>>France</option>
				
						<option <?php if (isset($origin) && $origin=="Germany") echo "selected";?>>Germany</option>
					
						<option <?php if (isset($origin) && $origin=="Greece") echo "selected";?>>Greece</option>
					
						<option <?php if (isset($origin) && $origin=="Hungary") echo "selected";?>>Hungary</option>
						<option <?php if (isset($origin) && $origin=="Iceland") echo "selected";?>>Iceland</option>
						<option <?php if (isset($origin) && $origin=="India") echo "selected";?>>India</option>
						<option <?php if (isset($origin) && $origin=="Indonesia") echo "selected";?>>Indonesia</option>
						<option <?php if (isset($origin) && $origin=="Iran") echo "selected";?>>Iran</option>
						<option <?php if (isset($origin) && $origin=="Iraq") echo "selected";?>>Iraq</option>
						<option <?php if (isset($origin) && $origin=="Ireland {Republic}") echo "selected";?>>Ireland {Republic}</option>
						<option <?php if (isset($origin) && $origin=="Israel") echo "selected";?>>Israel</option>
						<option <?php if (isset($origin) && $origin=="Italy") echo "selected";?>>Italy</option>
					
						<option <?php if (isset($origin) && $origin=="Japan") echo "selected";?>>Japan</option>
					
						<option <?php if (isset($origin) && $origin=="Korea North") echo "selected";?>>Korea North</option>
						<option <?php if (isset($origin) && $origin=="Korea South") echo "selected";?>>Korea South</option>
					
						<option <?php if (isset($origin) && $origin=="Latvia") echo "selected";?>>Latvia</option>
					
						<option <?php if (isset($origin) && $origin=="Luxembourg") echo "selected";?>>Luxembourg</option>
						<option <?php if (isset($origin) && $origin=="Macedonia") echo "selected";?>>Macedonia</option>
					
						<option <?php if (isset($origin) && $origin=="Morocco") echo "selected";?>>Morocco</option>
					
						<option <?php if (isset($origin) && $origin=="Nepal") echo "selected";?>>Nepal</option>
						<option <?php if (isset($origin) && $origin=="Netherlands") echo "selected";?>>Netherlands</option>
						<option <?php if (isset($origin) && $origin=="New Zealand") echo "selected";?>>New Zealand</option>
					
						<option <?php if (isset($origin) && $origin=="Nigeria") echo "selected";?>>Nigeria</option>
						<option <?php if (isset($origin) && $origin=="Norway") echo "selected";?>>Norway</option>
						<option <?php if (isset($origin) && $origin=="Not in list") echo "selected";?>>Not in list</option>

						<option <?php if (isset($origin) && $origin=="Pakistan") echo "selected";?>>Pakistan</option>

						<option <?php if (isset($origin) && $origin=="South Africa") echo "selected";?>>South Africa</option>
					
						<option <?php if (isset($origin) && $origin=="Spain") echo "selected";?>>Spain</option>
					
						<option <?php if (isset($origin) && $origin=="Suriname") echo "selected";?>>Suriname</option>
				
						<option <?php if (isset($origin) && $origin=="Sweden") echo "selected";?>>Sweden</option>
						<option <?php if (isset($origin) && $origin=="Switzerland") echo "selected";?>>Switzerland</option>
						<option <?php if (isset($origin) && $origin=="Syria") echo "selected";?>>Syria</option>
						<option <?php if (isset($origin) && $origin=="Taiwan") echo "selected";?>>Taiwan</option>
					
						<option <?php if (isset($origin) && $origin=="Turkey") echo "selected";?>>Turkey</option>
				
					
						<option <?php if (isset($origin) && $origin=="Ukraine") echo "selected";?>>Ukraine</option>
						<option <?php if (isset($origin) && $origin=="United Kingdom") echo "selected";?>>United Kingdom</option>
						<option <?php if (isset($origin) && $origin=="United States") echo "selected";?>>United States</option>
						<option <?php if (isset($origin) && $origin=="Uruguay") echo "selected";?>>Uruguay</option>
						<option <?php if (isset($origin) && $origin=="Uzbekistan") echo "selected";?>>Uzbekistan</option>
					
						<option <?php if (isset($origin) && $origin=="Vietnam") echo "selected";?>>Vietnam</option>
				
					</select>
			</div>
			<br><br>
		</div>			
		<div style="text-align:center;">		
			<button  type="button" class="btn btn-primary" onClick="show_next_questions(2)">Continue</button>
			<br>
			<br>
			<br>
		</div>
		</div>
		</div>

		<div id="preferences"  style="position:absolute;left:10%; right :20%;display: none;"><br><br><br>
		<div id="using_application">
		<div class="form-group">
		<h3 >Your preferences</h3> 
		<h4>1. What are your favourite video genre(s)?</h4><font size="3" color="red"><span id="genre-error"></span></font>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Comedy"  <?php if (isset($genre) && (strpos($genre,"Comedy") !== false))  echo "checked";?> name="genre[]" id="genre">Comedy</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Drama" <?php if (isset($genre) && (strpos($genre, 'Drama') !== false))  echo "checked";?> name="genre[]" id="genre">Drama</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Entertainment" <?php if (isset($genre) && (strpos($genre, 'Entertainment')) !== false)  echo "checked";?> name="genre[]" id="genre">Entertainment</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Factual" <?php if (isset($genre) && (strpos($genre, 'Factual') !== false))  echo "checked";?> name="genre[]" id="genre">Factual</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Music" <?php if (isset($genre) && (strpos($genre, 'Music') !== false))  echo "checked";?> name="genre[]" id="genre">Music</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Religion" <?php if (isset($genre) && (strpos($genre, 'Religion') !== false))  echo "checked";?> name="genre[]" id="genre">Religion &amp; Ethics</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Sport" <?php if (isset($genre) && (strpos($genre, 'Sport') !== false))  echo "checked";?> name="genre[]" id="genre">Sport</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" value="Other" <?php if (isset($genre) && (strpos($genre, 'Other') !== false))  echo "checked";?> name="genre[]" onclick="var input = document.getElementById('genreOther'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}">Other:  </label><div class="col-sm-2"><input class="form-control" id="genreOther" name="genreOther" disabled="disabled"/></div></div>

		</div>
		<br><br>
		<div class="form-group">
		<h4>2. What are your favourite video format(s)?</h4><font size="3" color="red"><span id="format-error"></span></font>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]" value="Animation" <?php if (isset($format) && (strpos($format,"Animation") !== false))  echo "checked";?> id="format">Animation</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="Discussion_talk" <?php if (isset($format) && (strpos($format,"Discussion_talk") !== false))  echo "checked";?> id="format">Discussion &amp; Talk</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="Documentaries" <?php if (isset($format) && (strpos($format,"Documentaries") !== false))  echo "checked";?> id="format">Documentaries</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="Films" <?php if (isset($format) && (strpos($format,"Films") !== false))  echo "checked";?> id="format">Films</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="Games_Quizzes" <?php if (isset($format) && (strpos($format,"Games_Quizzes") !== false))  echo "checked";?> id="format">Games &amp; Quizzes</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="News" <?php if (isset($format) && (strpos($format,"News") !== false))  echo "checked";?> id="format">News &amp; Bullettins</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="Reality" <?php if (isset($format) && (strpos($format,"Reality") !== false))  echo "checked";?> id="format">Reality</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="PerformanceEvent" <?php if (isset($format) && (strpos($format,"PerformanceEvent") !== false))  echo "checked";?> id="format">Performance &amp; Event</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="magazinesandreviews" <?php if (isset($format) && (strpos($format,"magazinesandreviews") !== false))  echo "checked";?> id="format">Magazines &amp; Reviews</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]"  value="talentshows" <?php if (isset($format) && (strpos($format,"talentshows") !== false))  echo "checked";?> id="format">Talent shows</label></div>
		<div class="checkbox"><label class="checkbox"><input type="checkbox" name="format[]" value="Other" <?php if (isset($format) && (strpos($format,"Other") !== false))  echo "checked";?> onclick="var input = document.getElementById('formatOther'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}">Other:  </label><div class="col-sm-2"><input class="form-control" id="formatOther" name="formatOther" disabled="disabled"/></div></div>
		
		</div>
		
		<br><br>
		<div class="form-group">
		<h4>3. Tell us more about your attitude towards video recommendations.</h4>
			  	<table class="table-responsive" width="80%">
				  	<thead>
						<tr style="text-align:center">
						<th width="50%"><h5 style="text-align:center"></h5></th>
					  <th width="10%"><h5 style="text-align:center">Almost<br>never</h5></th>
					  <th width="10%"><h5 style="text-align:center">Rarely</h5></th>
					  <th width="10%"><h5 style="text-align:center">Sometimes</h5></th>
					  <th width="10%"><h5 style="text-align:center">Often</h5></th>
					  <th width="10%"><h5 style="text-align:center">Almost<br>always</h5></th>
						</tr>
					</thead>
				  	<tbody>
					<tr>
					  		<td  style="text-align:left">Automatic recommendations for TV programmes, videos or other products, typically influence what I eventually choose to watch, read or buy.</td>
					  		<td style="text-align:center">
								<div class="radio" >
									<label class="radio"><input type="radio" name="influenceDecision" value="AlmostNever"></label>
								</div>
					  		</td>
					  		<td  style="text-align:center">
								<div class="radio" >
									<label class="radio"><input type="radio" name="influenceDecision" value="Sometimes"></label>
								</div>
					  		</td>
					  		<td style="text-align:center">
								<div class="radio" >
									<label class="radio"><input type="radio" name="influenceDecision" value="Neutral"></label>
								</div>
					  		</td>
					 		<td  style="text-align:center">
								<div class="radio" >
									<label class="radio"><input type="radio" name="influenceDecision" value="Often"></label>
								</div>
					  		</td>
					  <td style="text-align:center">
						<div class="radio" >
									  <label class="radio"><input type="radio" name="influenceDecision" value="AlmostAlways"></label>
						</div>
					  </td>
					</tr>
					<tr>
					<td style="line-height:10px;" colspan=6>&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align:left">I would select an unexpected, but intriguing video recommendation, despite the fact that it seems to be out of scope of my interest. </td>
					 	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="outOfScope" value="AlmostNever"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="outOfScope" value="Sometimes"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="outOfScope" value="Neutral"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="outOfScope" value="Often"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="outOfScope" value="AlmostAlways"></label>
							</div>
					  	</td>
					</tr>
					<tr><td style="line-height:10px;" colspan=6>&nbsp;</td></tr>
					<tr>
					  	<td style="text-align:left">I usually enjoy when I get an unexpected recommendations from online streaming services.</td>
					  	<td style="text-align:center">
							<div class="radio" >
							 	<label class="radio"><input type="radio" name="unexpected" value="AlmostNever"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="unexpected" value="Sometimes"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="unexpected" value="Neutral"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							 	<label class="radio"><input type="radio" name="unexpected" value="Often"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="unexpected" value="AlmostAlways"></label>
							</div>
					  	</td>
					</tr>
					<tr><td><td  style="line-height:10px;" colspan=6>&nbsp;</td></td></tr>
					<tr>
					  	<td style="text-align:left">Unexpected recommendations could give me useful ideas and information that I was initially not looking for.</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="usefulIdeas" value="AlmostNever"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="usefulIdeas" value="Sometimes"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="usefulIdeas" value="Neutral"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="usefulIdeas" value="Often"></label>
							</div>
					  	</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="usefulIdeas" value="AlmostAlways"></label>
							</div>
					  	</td>
					</tr>
				  </tbody>
				</table>
			</div>
				<br><br>
		<div class="form-group">
				<h4>4. I would probably watch a video recommendation about something I haven't seen before, ...</h4>
			 	<table class="table-responsive" width="80%">
					<thead>
						<tr style="text-align:center">
						  <th width = "50%"><h5 style="text-align:center"></h5></th>
						  <th width = "10%"><h5 style="text-align:center">Completely<br>disagree</h5></th>
						  <th width = "10%"><h5 style="text-align:center">Disagree</h5></th>
						  <th width = "10%"><h5 style="text-align:center">Neutral</h5></th>
						  <th width = "10%"><h5 style="text-align:center">Agree</h5></th>
						  <th width = "10%"><h5 style="text-align:center">Completely<br>agree</h5></th>
						</tr>
				  	</thead>
				  	<tbody>
						<tr>
					  	<td style="text-align:left">... if it is of my favorite genre.</td>
					  	<td  style="text-align:center">
							<div class="radio" ><label class="radio"><input type="radio" name="familiarGenre" value="CompletelyDisagree"></label></div>
					  	</td>
					  	<td   style="text-align:center">
							<div class="radio" ><label class="radio"><input type="radio" name="familiarGenre" value="Disagree"></label></div>
						</td>
					  	<td   style="text-align:center">
							<div class="radio" ><label class="radio"><input type="radio" name="familiarGenre" value="Neutral"></label></div>
					 	</td>
					  	<td  style="text-align:center">
							<div class="radio" ><label class="radio"><input type="radio" name="familiarGenre" value="Agree"></label></div>
					  	</td>
					  	<td   style="text-align:center">
							<div class="radio" ><label class="radio"><input type="radio" name="familiarGenre" value="CompletelyAgree"></label></div>
					  	</td>
					</tr>
						<tr>
						<td style="text-align:left">... if it has my favorite actor.</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="favoriteActor" value="CompletelyDisagree"></label>
							</div>
					  	</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteActor" value="Disagree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteActor" value="Neutral"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteActor" value="Agree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteActor" value="CompletelyAgree"></label>
							</div>
						</td>
					</tr>
					<tr>
					<td style="text-align:left">... if it has my favorite director.</td>
					  	<td style="text-align:center">
							<div class="radio" >
							  	<label class="radio"><input type="radio" name="favoriteDirector" value="CompletelyDisagree"></label>
							</div>
					  	</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteDirector" value="Disagree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteDirector" value="Neutral"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteDirector" value="Agree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="favoriteDirector" value="CompletelyAgree"></label>
							</div>
						</td>
					</tr>
						<tr>
						<td style="text-align:left">... if the description or the trailer intrigues me.</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="intrigue" value="CompletelyDisagree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="intrigue" value="Disagree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="intrigue" value="Neutral"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="intrigue" value="Agree"></label>
							</div>
						</td>
						<td style="text-align:center">
							<div class="radio" >
								<label class="radio"><input type="radio" name="intrigue" value="CompletelyAgree"></label>
							</div>
						</td>
					</tr>
						
				  	</tbody>
				</table>
		</div>	
		<br><br>
		<div class='form-group'>
		<h4>5. How much time do you typically spend watching videos during the WEEKDAYS?</h4>
		<h5>(Including your regular TV, Phone, Tablet and online services.)</h5><font size="3" color="red"><span id="weekdays-error"></span></font>
		<table class="table" style="width:80%">
			<thead>
				<tr>
					<th width="10%"></th>
					<th width="15%" text-align="center"><h5>Never</h5></th>
					<th width="15%" text-align="center"><h5>Occasionally</h5></th>
					<th width="15%" text-align="center"><h5>Less than<br>1 hour</h5></th>
					<th width="15%" text-align="center"><h5>1 - 2<br>hours</h5></th>
					<th width="15%" text-align="center"><h5>2 - 4<br>hours</h5></th>
					<th width="15%" text-align="center"><h5>More than<br>4 hours</h5></th>
				</tr>
			</thead>
			<tbody>
				<tr>
			  <td ><h5>Morning</h5></td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-Never") echo "checked";?> value="Morning-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-Occasionally") echo "checked";?> value="Morning-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-1h") echo "checked";?> value="Morning-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-12h") echo "checked";?> value="Morning-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-24h") echo "checked";?> value="Morning-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="morning" <?php if (isset($morning) && $morning=="Morning-4h") echo "checked";?> value="Morning-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Afternoon</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-Never") echo "checked";?> value="Afternoon-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-Occasionally") echo "checked";?> value="Afternoon-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-1h") echo "checked";?> value="Afternoon-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-12h") echo "checked";?> value="Afternoon-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-24h") echo "checked";?> value="Afternoon-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="afternoon" <?php if (isset($afternoon) && $afternoon=="Afternoon-4h") echo "checked";?> value="Afternoon-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Evening</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-Never") echo "checked";?> value="Evening-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-Occasionally") echo "checked";?> value="Evening-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-1h") echo "checked";?> value="Evening-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-12h") echo "checked";?> value="Evening-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-24h") echo "checked";?> value="Evening-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="evening" <?php if (isset($evening) && $evening=="Evening-4h") echo "checked";?> value="Evening-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Night</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-Never") echo "checked";?> value="Night-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-Occasionally") echo "checked";?> value="Night-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-1h") echo "checked";?> value="Night-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-12h") echo "checked";?> value="Night-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-24h") echo "checked";?> value="Night-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="night" <?php if (isset($night) && $night=="Night-4h") echo "checked";?> value="Night-4h"></label>
				 </div>
			   </td>
			   
			</tr>
			</tbody>
		</table>
		</div>
		<br>
		<div class='form-group'>
		<h4>6. How much time do you typically spend watching videos in the WEEKEND?</h4>
		<h5>(Including your regular TV, Phone, Tablet and online services.)</h5><font size="3" color="red"><span id="weekend-error"></span></font>	
		<table class="table" style="width:80%">
			<thead>
				<tr>
					<th width="10%"></th>
					<th width="15%" text-align="center"><h5>Never</h5></th>
					<th width="15%" text-align="center"><h5>Occasionally</h5></th>
					<th width="15%" text-align="center"><h5>Less than<br>1 hour</h5></th>
					<th width="15%" text-align="center"><h5>1 - 2<br>hours</h5></th>
					<th width="15%" text-align="center"><h5>2 - 4<br>hours</h5></th>
					<th width="15%" text-align="center"><h5>More than<br>4 hours</h5></th>
				</tr>
			</thead>
			<tbody>
				<tr>
			  <td ><h5>Morning</h5></td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-Never") echo "checked";?> value="Morning-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-Occasionally") echo "checked";?> value="Morning-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-1h") echo "checked";?> value="Morning-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-12h") echo "checked";?> value="Morning-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-24h") echo "checked";?> value="Morning-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="morning_weekend" <?php if (isset($morning_weekend) && $morning_weekend =="Morning-4h") echo "checked";?> value="Morning-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Afternoon</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-Never") echo "checked";?> value="Afternoon-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-Occasionally") echo "checked";?> value="Afternoon-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-1h") echo "checked";?> value="Afternoon-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-12h") echo "checked";?> value="Afternoon-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-24h") echo "checked";?> value="Afternoon-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="afternoon_weekend" <?php if (isset($afternoon_weekend) && $afternoon_weekend =="Afternoon-4h") echo "checked";?> value="Afternoon-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Evening</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-Never") echo "checked";?> value="Evening-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-Occasionally") echo "checked";?> value="Evening-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-1h") echo "checked";?> value="Evening-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-12h") echo "checked";?> value="Evening-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-24h") echo "checked";?> value="Evening-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="evening_weekend" <?php if (isset($evening_weekend) && $evening_weekend =="Evening-4h") echo "checked";?> value="Evening-4h"></label>
				 </div>
			   </td>
			</tr>
				<tr>
			  <td ><h5>Night</h5></td>
			   <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-Never") echo "checked";?> value="Night-Never"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-Occasionally") echo "checked";?> value="Night-Occasionally"</label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-1h") echo "checked";?> value="Night-1h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-12h") echo "checked";?> value="Night-12h"></label>
				</div>
			  </td>
			  <td >
				<div class="radio" >
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-24h") echo "checked";?> value="Night-24h"></label>
				</div>
			   <td width = "10%">
				 <div class="radio">
					 <label class="radio"><input type="radio" name="night_weekend" <?php if (isset($night_weekend) && $night_weekend =="Night-4h") echo "checked";?> value="Night-4h"></label>
				 </div>
			   </td>
			   
			</tr>
			</tbody>
		</table>
		</div>
		<br>
		<br><br>
		<div style="text-align:center;">
					
			<button  type="submit" class="btn btn-primary" >Go to the application</button>
			<br><br><br>
		</div>
	    <!--<input type="image" src="/images/start_using.png" value="Start using the application" style="position:absolute;left:30%;width:150px; height:100px;"><br><br><br><br><br><br>-->
		</div>
		</div>
	</form>
	</div>
<!--
   <div id="format_list"style="display: none;">
	<div id="programmes" class="slidey recomended object">
        <span class="sub_title">Documentaries</span>
        <span class="more_blue" id="moreblue"><a id="moreprogrammes" onclick='show_more_programmes();'>View All &triangledown;</a></span>
        <div id="docs"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
      <div id="side-b" class="slidey recomended object">
        <span class="sub_title">Quizzes</span> 
        <span class="more_blue" id="moreblue1"><a id="moreprogs" onclick='show_more_recommendations();'>View All &triangledown;</a></span>
        <div id="quiz"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
	  <div id="content2" class="slidey recomended object">
        <span class="sub_title">Reality</span>
        <span  class="more_blue" id="moreblue3"><a id="morerecently" onclick='show_history();'>View All &triangledown;</a></span>
        <div id="real">
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
	  <div id="content" class="slidey recomended object">
        <span class="sub_title">Events</span>
        <span class="more_blue" id="moreblue2"><a id='moreformat' onclick='show_format();'>View All &triangledown;</a></span>
        <div id="talent">    
        </div>
         <div class="clear"></div>
      </div>
       <div class="clear"></div>
  </div>
     <div id="genre_list"style="display: none;">
	<div id="programmes" class="slidey recomended object">
        <span class="sub_title">Factual</span>
        <span class="more_blue" id="moreblue"><a id="moreprogrammes" onclick='show_more_programmes();'>View All &triangledown;</a></span>
        <div id="fac"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
      <div id="side-b" class="slidey recomended object">
        <span class="sub_title">Comedy</span> 
        <span class="more_blue" id="moreblue1"><a id="moreprogs" onclick='show_more_recommendations();'>View All &triangledown;</a></span>
        <div id="comedies"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
	  <div id="content2" class="slidey recomended object">
        <span class="sub_title">Drama</span>
        <span  class="more_blue" id="moreblue3"><a id="morerecently" onclick='show_history();'>View All &triangledown;</a></span>
        <div id="drama">
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
	  <div id="content" class="slidey recomended object">
        <span class="sub_title">Entertainment</span>
        <span class="more_blue" id="moreblue2"><a id='moreformat' onclick='show_format();'>View All &triangledown;</a></span>
        <div id="entertainment">    
        </div>
         <div class="clear"></div>
      </div>
       <div class="clear"></div>
  </div>-->
  <br><br>
  <div id="inner"style="display: none;">
   

    <div id="browser">
	  <div id="programmes" class="slidey recomended object">
        <span class="sub_title">Videos <!--<a href="javascript:show_genre();" style='color: #FFFFFF;'>&nbsp;&nbsp;&nbsp;Order by genre</a><a href="javascript:show_format();" style='color: #FFFFFF;'>&nbsp;&nbsp;&nbsp;Order by format</a> </span> -->
        <!--<span class="more_blue" id="moreblue">--><p><a id="moreprogrammes" onclick='javascript:show_more_programmes();' >View All &triangledown;</a></p></span>
        <div id="programs"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  
	    <div id="content" class="slidey recomended object">
        <span class="sub_title">Suggestions for you
		<p><a id='moreformat' onclick='show_more_format();'>View All &triangledown;</a></p></span>
        <div id="results_format">
         
        </div>
         <div class="clear"></div>
      </div>
       <div class="clear"></div>
	   
      <div id="side-b" class="slidey recomended object">
        <span class="sub_title">Shared by friends  <p><a id="moreprogs" onclick='show_more_recommendations();'>View All &triangledown;</a></p></span>
        <div id="progs"> </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
  

      <div id="content2" class="slidey recomended object">
        <span class="sub_title">Recently viewed
        <p><a id="morerecently" onclick='show_history();'>View All &triangledown;</a></p></span>
        <div id="history2">
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>

     
      <div id="content3" class="slidey recomended object">
        <span class="sub_title">Watch later</span>
        <span  class="more_blue" id="moreblue4"><a id="morelater" onclick='show_later();'>View All &triangledown;</a></span>
        <div id="list_later" >
          <div class='dotted_box'> </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
 <!--
      <div id="content4" class="slidey recomended object">
        <span class="sub_title">LIKES</span>
        <span  class="more_blue" id="moreblue5"><a id="morelikes" onclick='show_likes();'>View All &triangledown;</a></span>
        <div id="list_likes">
          <div class='dotted_box'> </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>

      <div id="content5" class="slidey recomended object">
        <span class="sub_title">DISLIKES</span>
        <span  class="more_blue" id="moreblue6"><a id="moredislikes" onclick='show_dislikes();'>View All &triangledown;</a></span>
        <div id="list_dislikes">
          <div class='dotted_box'> </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	  -->
      <div id="side-c">
      </div>
    </div>

      <div id="search_results" style='width: 80%;'></div><div class="clear"></div>
    <!-- <br clear="both" /> -->

  </div>

</div>

<div id="roster_wrapper">
  
  <div id="side-a">
	
    <div id="tv"></div>
      <!-- <br clear="both"/> -->
      <h3 class="contrast">Online</h3>
    <div id="roster_online"style="overflow-y: scroll; height:200px;">
	<br>
	</div>
	<h3 class="contrast">Offline</h3>
    <div id="roster_offline" style="overflow-y: scroll; height:200px;">
	<br>
	</div>
</div>
</div>
<!--<div id="footer">


   <div id="browse" class="blue menu " style="position:absolute;left:500px"><a href="javascript:show_browse_programmes()">HOME</a></div>
   <div id="randomBBC" class="grey menu" style="position:absolute;left:600px;"><a href="javascript:new_bbc_random()">RANDOM BBC SELECTION</a></div>
   <div id="randomTED" class="grey menu" style="position:absolute;left:800px"><a href="javascript:new_ted_random()">RANDOM TED SELECTION</a></div>

  <div id="logoutspan" onclick="Logout();" href="#" style="position:absolute;left:80%;"></div>
</div> -->




<p style="display: none;"><small>Status:
<span id="demo">
<span id="out"></span>
</span></small></p>

<!-- overlays -->
<div id='new_overlay' style='display:none;'>

  <div class='close_button'>
    <img width="12px" onclick="javascript:hide_overlay();" src="images/icons/exit.png"/>
  </div>
</div>
<div id='bg' style='display:none;' onclick='javascript:hide_overlay()'></div>


        
            <div id="ask_name" style="display: none;" class="alert">
            <h2 id="inline1_sub">Please enter your name:</h2>
              <form onsubmit="javascript:add_name();return false;" id="myname">
                 <input class="forminput" type="text" name="nick" id="login" spellcheck="false"  autocorrect="off"/>
                 <input class='bluesubmit' type="submit" name="go" value="Start" />               
              </form>
              </div>
        

                
            <div id="disconnected" style="overflow:auto;display: none;" class="alert">
              <h2>Sorry, you've been disconnected - please reload the page.</h2>
            </div>
</body>
</html>




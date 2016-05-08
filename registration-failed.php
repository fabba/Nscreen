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

    <link type="text/css" rel="stylesheet" href="css/ipad.css" />
    <script type="text/javascript" src="lib/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="lib/jquery-ui-1.8.10.custom.min.js"></script>
    <script type="text/javascript" src="lib/jquery.ui.touch.js"></script>

    <script type="text/javascript" src="lib/strophe.js"></script>
    <script type="text/javascript" src="lib/buttons.js"></script>
    <script type="text/javascript" src="lib/spin.min.js"></script>
    <script type="text/javascript" src="lib/play_video.js"></script>

    <script type="text/javascript">

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

var recommendations_url=api_root+"recommendations.js";
var channels_url=api_root+"channels.js";
var search_url = api_root+"search.js";

//jabber server
var server = "jabber.notu.be";

//polling interval (for changes to channels)
var interval = null;

var starting_data = {};

//handle group names
//if no group name, show the intro text

function init(){

  var grp = window.location.hash;
  var state = {"canBeAnything": true};
  history.pushState(state, "N-Screen", "http://localhost");

  if(grp){
     my_group = grp.substring(1);
     $("#header").show();
     $("#roster_wrapper").show();
     $(".about").hide();
     create_buttons();
   }else{
     $.ajax({
        url: "get_group.php",
        async: false,
        success: function (response) {
            console.log("Th group is = " + response);
            my_group = response;
        }
      });create_buttons();
   }
      //getting a possible initialization set of videos

   $(document).ready(function() { 
     $.ajax({
          url: 'get_tedtalks.php',
          dataType: "json",
          async: false,
          success: function(data) {
          starting_data = changeData(data);
          //console.log(JSON.stringify(starting_data));
          $('#json_object').val(JSON.stringify(starting_data));
          }
      });
  })
   history.pushState(state, "N-Screen", "http://localhost");
   clean_loc = String(window.location);
   window.location.hash=my_group;
   $("#group_name").html(my_group);
   $("#grp_link").html(clean_loc+"#"+my_group);
   $("#grp_link").attr("href",clean_loc+"#"+my_group);
   var state = {"canBeAnything": true};
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

// utility function create a temporary group name if none is set
function tmp_group(){
  var rand = Math.floor(Math.random()*9999);
  //var rand = 1;
  return String(rand);
}

//creates and initialises the buttons object                              

function create_buttons(){
   $("#inner").addClass("inner_noscroll");
   $(".slidey").addClass("slidey_noscroll");
   $(".about").hide();
   $("#header").show();
   $("#roster_wrapper").show();
     
   //ask the user for their name to continue
   show_ask_for_name();
    
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

//ask the user for their name

function show_ask_for_name(){

   $("#devices_menu").hide();
  
   $('#ask_name').show();
   show_grey_bg();
   $("#login").focus();
  
}

//spinner stuff

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


//hides the large notifications bar
function close_notifications(){
  $("#notify_large").hide();
}

//shows the overlay background      
function show_grey_bg(){
 $("#bg").show();       
}

//hides the overlay background
function hide_overlay(){
 $("#bg").hide();
 $(".new_overlay").hide();
 $("#new_overlay").html("");

}

//creates a new id from a programme and a person name string
function generate_new_id(j,n){
  var i = j["pid"]+"_"+n; //not really unique enough
  return i;
}

<!--REGISTRATION-FORM-->
  function swap(one, two) {
      document.getElementById(one).style.display = 'block';
      document.getElementById(two).style.display = 'none';
      //document.getElementById('ask_name').style.left = "35%";
}


</script>

</head>

<body onload="init()">

<!--FACEBOOK SDK for JavaScript-->
<script>

function userLogin(){
        FB.login(function(response){
           if (response.authResponse){
             console.log('Welcome!  Fetching your information.... ');
              FB.api('/me', function(response) {
              console.log('Successful login for: ' + response.name);
              document.forms["myname"].login.value = response.name;
              var id = response.id;
              var name = response.name;
              console.log('Your id is  ' + id);
              register(id,name);
              });
            } 
           else{
             console.log('User cancelled login or did not fully authorize.');
           }
         });
  };

function register(id, name) {
    $.ajax({
        url: "facebook-register.php",
        type: "POST",
        data: {facebook_id: id, firstname: name, recommendations : JSON.stringify(starting_data)},
        // data: "facebook_id="+id+"&firstname="+name+"&recommendations="+JSON.stringify(starting_data),
        dataType: "json",
        success: function (response) {
            create_buttons();
            //add_name();
            //SHOULD REDIRECT TO US ONLY AREA---HAVE TO WORK ON IT
            window.location.href= "member-index.php";
        }
      });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '710256039061787',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  // FB.getLoginStatus(function(response) {
  //   statusChangeCallback(response);
  // });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Logout Function

  // Logout Function
  function Logout() {
    $.ajax({
       url: 'logout.php',
       async : false,
         success: function(){
           window.location.href= "http://localhost/N-Screen/";
         }
      });
    //console.log("RESPUESTA  "+ lalala):
    //FB.logout(function () { document.location.reload(); });
  }
</script>



<div id="everything" ontouchmove="touchMove(event);">

<!-- workaround for audio problems on ipad - http://stackoverflow.com/questions/2894230/fake-user-initiated-audio-tag-on-ipad -->

<div id="junk" style="display:none">
  <audio src="sounds/x4.wav" id="a1" preload="auto"  autobuffer="autobuffer" controls="" ></audio>
  <audio src="sounds/x1.wav" id="a2" preload="auto"  autobuffer="autobuffer" controls="" ></audio>
</div>


<div class="about" style="display:none;">

This is <a 
href="http://notube.tv/2011/10/10/n-screen-a-second-screen-application-for-small-group-exploration-of-on-demand-content/">N-Screen</a>, 
an application that helps you choose what to watch on TV. It was created by <a 
href="http://twitter.com/libbymiller">Libby Miller</a>, <a 
href="http://twitter.com/vickybuser">Vicky Buser</a> and <a 
href="http://twitter.com/danbri">Dan Brickley</a> within the <a 
href="http://notube.tv">NoTube Project</a>. Many thanks to <a 
href="http://dannyayers.com/">Danny Ayers</a> for the sounds, <a 
href="http://www.sparkslabs.com/michael/">Michael Sparks</a> for the channel icons, and <a 
href="http://www.fabrique.nl/">Fabrique</a> for the designs. <br /> <br /> Thanks also to <a 
href="http://www.cs.vu.nl/~laroyo/">Lora Aroyo</a> and <a 
href="http://www.cs.vu.nl/~guus/">Guus Schreiber</a> and the rest of the NoTube Project, to 
Andrew McParland at the BBC and to <a href="http://twitter.com/nevali">Mo McRoberts</a> from 
the internet. <br /> <br />


<a onclick="create_buttons()">Try it</a>
</div>



  <div id="header" style="display:none;">
   <span id='title'></span>
<!--     <span class="form" >
      <form onsubmit='javascript:do_search(this.search_text.value);return false;'>

        <input type="text" id="search_text" name="search_text" value="search programmes" onclick="javascript:remove_search_text();return false;"/>

      </form>
     </span> -->
  </div>

<br clear="both"/>

        

  
<div id="container">
              

  <div id="inner">
 
        
    <div id="browser">  
      <div id="top_slider" class="slidey">

      <br clear="both"/>

        <div id="progs"></div>


      </div>
 
    </div>
  
    <div id="search_results">
        <span id="sub_title_sr"></span>
        <div id="progs2"></div>

    </div>

    <div id="devices_menu" class="device_menu alert" style="display:none;">
    </div>

              

    <br clear="both" />
 
  </div>
    
</div>
        
<div id="roster_wrapper" style="display:none;">
<div id="aboutlink">
<!-- <a target="_blank" href="about.html">About N-Screen</a> -->
<!-- <a id="logoutspan" href="#" onclick="Logout();">LOGOUT</a> -->
</div>

  <div class="notifications_red" id="notify"></div>
  <div class="notifications_red_large" id="notify_large" onclick="javascript:close_notifications();"></div>

  <div id="roster_inner">
    <div id="tv"></div>
      <br clear="both"/>
    <div id="roster"></div>
  </div>
</div>

        
          
        
<p style="display: none;"><small>Status:
<span id="demo">
<span id="out"></span>
</span></small></p>
      
<!-- overlays -->
  
<div class='new_overlay' id='new_overlay' style='display:none;'><div class='close_button'><img src='images/close.png'/></div></div>
<div id='bg' style='display:none;' onclick='javascript:hide_overlay()'></div>
    

              
      <div id="ask_name" style="display:none;" class="alert">

          <div id="registration" class="registration">
                  <h2 class="inline1_sub">Sign up</h2>
                  <div id="err">Some field was not correct. Please try again</div>
                  <div class="formRegistration">

                    <form id="loginForm" name="loginForm" method="post" action="register-exec.php">
                      <input type="hidden" name="json_object" id="json_object"/>
                      <div class="input-field">
                       <input id="fname" name="fname" type="fname" class="textfield" value="" required="required" placeholder="First Name" />
                      </div>
                      <div class="input-field">
                       <input id="lname" name="lname" type="lname" class="textfield" value="" required="required" placeholder="Last Name" />
                      </div>
                      <div class="input-field">
                       <input id="login" name="login" type="login" class="textfield" value="" required="required" placeholder="Username"  />
                      </div>
                      <div class="input-field">
                       <input id="password" name="password" type="password" class="textfield" value="" required="required" placeholder="Password"  />
                      </div>
                      <div class="input-field">                
                       <input id="cpassword" name="cpassword" type="password" class="textfield" value="" required="required" placeholder="Repeat Password"  />
                      </div>
                       </p>
                       <input class='bluesubmit' type="submit" name="Submit" value="Sign up!" />
                    </form>
                  </div>
                  <div class="divider">
                      <span></span>
                      <p>

                          or

                      </p>
                      <span></span>

                  </div>

                  <div class="formFacebook">
                    <div id="fbimage">

                      <img id="fbicon"src="images/facebook.jpg" href="#" onclick="userLogin();"/>

                      <!-- <fb:login-button data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button> -->
                    </div>
                  </div>
                  <div id="gotologin"><span id="whatever">Already a user? <span style="font-weight: bold;" id="colorsignin" onclick="swap('formLogin','registration'); return false">Sign in</span></span></div>
            </div>

            <div id="formLogin" class="formLogin">
              
              <h2 class="inline1_sub">Login</h2>
              <div id="joining">You are joining group <span id="group_name"></span></div>
              <div class="login">
                <form id="myname" name="loginForm" method="post" action="login-exec.php">
                <div class="input-field">   
                 <input id="login" name="login" type="username" class="textfield" value="" required="required" placeholder="Username" />
                </div>
                <div class="input-field"> 
                 <input id="password" name="password" type="password" class="textfield" value="" required="required" placeholder="Password" />
                </div>
                 <button class='bluesubmit' type="submit" name="Submit" value="Login">Login!</button>
               </form>
              </div>
                 <div class="divider">
                      <span></span>
                      <p>

                          or

                      </p>
                      <span></span>

                  </div>
                  <div class="formFacebook">
                    <div id="fbimage">

                      <img id="fbicon"src="images/facebook_login.jpg" href="#" onclick="userLogin();"/>

                      <!-- <fb:login-button data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button> -->
                    </div>
                  </div>
                  <div id="gotologin"><span id="whatever">Not a member? <span style="font-weight: bold;" id="colorsignin" onclick="swap('registration','formLogin'); return false">Sign up</span></span></div>
            </div>
      </div>
  
    
            <div id="disconnected" style="overflow:auto;display: none;" class="alert">
              <h2>Sorry, you've been disconnected - please reload the page.</h2>
            </div>


        <div id="results" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/group_wide.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Shared</div>
               <p class="description">
                 Shared by friends and shared by you to friends. 
               </p>
               <p>To add people to the group send them this link: <a id="grp_link" href=""></a> by email, twitter or however you like.</p><p>Anyone who clicks on the link can join your group.</p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="shared"></div>
        </div>



        <div id="history" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/history_wide.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Recently Viewed</div>
               <p class="description">
                  Recently viewed programmes.
               </p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="history_items"></div>
        </div>

        <div id="recs" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/recs_wide.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Recommended</div>
               <p class="description">
                 Recommendations for you.
               </p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="recs_items"></div>
        </div>

        <div id="ssee" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/search_results.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Search results</div>
               <p class="description">
                 Last search.
               </p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="search_items"></div>
        </div>

        <!-- MY NEW CHANNELS -->

        <div id="watch_later" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/watch_later.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Watch Later</div>
               <p class="description">
                 y personal list of programmes that I would like to watch.
               </p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="list_later"></div>
        </div>

        <div id="like_dislike" style="display:none;">
        <div class="close_button"><img src="images/close.png" width="30px" onclick="javascript:hide_overlay();"></div>
        <div id="group_overlay" class="ui-widget-content large_prog ui-draggable">
           <div style="float:left;"> 
             <img class="img" src="images/likes_dislikes.png" width="150px;"/>
           </div>
           <div style="width:300px;float:left;">
               <div class="p_title_large">Likes & Dislikes</div>
               <p class="description">
                 My personal list of Likes & Dislikes.
               </p>
           </div>

           <br clear="both"></div>
           <div class='dotted_spacer2'></div>
           <div id="list_likes"></div>
           <div id="list_dislikes"></div>
        </div>



</div>

</body>

</html>

<?php

error_reporting(7);
header('Content-Type: text/html; charset=UTF-8');
session_start();
date_default_timezone_set('Asia/Jakarta');

$host = "localhost";
$username = "root";
$password = "";	
$dbname = "liker";

$ip = getenv("REMOTE_ADDR") ;
$time = time();
$waktu = date("G:i:s",time());
//database connect
mysql_connect($host,$username,$password) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
mysql_query("SET NAMES utf8");




 mysql_query("CREATE TABLE IF NOT EXISTS `cookies` (
`ip` varchar(32) NOT NULL DEFAULT '',
`time` varchar(32) DEFAULT NULL,
`waktu` varchar(255) DEFAULT NULL,
PRIMARY KEY (`ip`)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
) 
");

 
$ref = $_SERVER['HTTP_REFERER'];
$referer = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (strpos($ref,'http://tipsvstricks.com/url/$referer') !== false) {
 } else {
	if (strpos($ref,'http://tipsvstricks.com/url/$referer') !== true) {
	} else{
header("Location: http://tipsvstricks.com/url/$referer");
	
}
}
function get_html($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
	return $data;
    }
$token = $_SESSION['token'];

if($token){
	$graph_url ="https://graph.facebook.com/me?fields=id,name&access_token=" . $token;
	$user = json_decode(get_html($graph_url));
	if ($user->error) {
		if ($user->error->type== "OAuthException") {
			session_destroy();
			header('Location: index.php?i=Token hết hạn vui lòng làm mới Token..! !');
			}
		}
	}
	else{
	header('Location: index.php');
	}
	$result = mysql_query("
      SELECT * FROM cookie WHERE ip = '$ip'");
	if($result){
     while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$times = $row;
			}
	$timer = time(900)- $times['time'];
	$countdown = 900 - $timer;
	};	
if(isset($_POST['submit'])) {
        $token = $_SESSION['token'];
           if(!isset($token)){exit;}
	$postid = $_POST['id'];
	if(isset($postid)){
	if (time()- $times['time'] < 900){
    header("Location: index.php?i=Vui lòng chờ 15 phút..");
	}
	else{
	
	mysql_query("REPLACE INTO cookie (ip,time,waktu) VALUES ( '$ip','$time','$waktu')");
	$ch = curl_init('http://localhost/like/z_like.php'); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, "id=$postid");
	$hasil = curl_exec ($ch);
	curl_close ($ch);
    if (strpos($hasil,'GAGAL') !== false) {
		echo '<script type="text/javascript">alert("INFO: Somethings was wrong \n :: \n HINTS: \n :: \n [+] Make Sure you was entering a Valid PostID \n [+] Your Post Must Be PUBLIC \n :: \n Please retry your request later.");</script>';
			}else{
        //header("Location: liker.php?i=Liking In Process, We are Prosessing your request, Estimate finish is 5 Mins depend on our server traffic");
        header("Location: liker.php?i==Chúng tôi đang thực hiện yêu cầu của bạn, Thời gian kết thúc là 5 phút phụ thuộc vào lưu lượng truy cập vào máy chủ của chúng tôi");
	}
	}
	}else{
	header("Location: index.php?i=Post ID đang rỗng");
	};
}else{
	



if(isset($_GET['type'])){
if($_GET['type'] == "status"){
$beranda = json_decode(get_html("https://graph.facebook.com/$user->id/statuses?fields=id,message&limit=7&access_token=$token"))->data;
	foreach($beranda as $id){
	$status .= '
	<section class="status">
	<section class="image">
	<img src="https://graph.facebook.com/'.$user->id.'/picture">
	</section>
	<section class="name">'.$user->name.'</section>
	<section class="message">'.$id->message.'</section>
	<form action="" method="post">
	<input type="hidden" name="id" value="'.$id->id.'">
	<input type="submit" name="submit" value="Start" class="submit"></form>
	</section>';
	}
	}
if($_GET['type'] == "custom"){
	$status = '
	<section class="status">
	<form action="" method="post">
	Nhập ID:<input type="text" name="id" style=" width: 170px; height:30px;" class="form-control" value="'.$id->id.'" required>
	<input type="submit" name="submit" value="Start" class="submit"></form>
	<section class="image">
	<img src="https://graph.facebook.com/'.$user->id.'/picture"></section>
	<section class="name">'.$user->name.'</section>';

	}
if($_GET['type'] == "photo"){
if(!isset($_GET['album'])){
$beranda = json_decode(get_html("https://graph.facebook.com/$user->id/albums?fields=id,name,cover_photo&limit=7&access_token=$token"))->data;
	if(!empty($beranda)){
	foreach($beranda as $id){
	$status .= '
	<section class="picture" style="overflow: hidden">
	
	<a href="?type=photo&album='.$id->id.'" class="ajax" title="'.$id->name.'">
	<img src="https://graph.facebook.com/'.$user->id.'/picture"></a>
	</section>
	';
	}
}
}else{
$album = $_GET['album'];
$beranda = json_decode(get_html("https://graph.facebook.com/$album/photos?fields=id,picture&limit=10&access_token=$token"))->data;
	if(!empty($beranda)){
	foreach($beranda as $id){
	$status .= '
	<section class="picture">
	<img src="'.$id->picture.'"></a>
	<form action="" method="post">
	<input type="hidden" name="id" value="'.$id->id.'">
	<input type="submit" name="submit" value="Start" class="submit"></form>
	</section>
	
	';
	}
}
}
}
}else{
header('Location: ?type=status');
}
}
if($user->id =="10000801556827" 
|| $user->id =="4" 
){
echo "Have a Nice Day ^_^, You got Blocked...!!";
echo "<br>";
echo " Easyliker Team was Here";
exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Easyliker Autolike | Facebook Autoliker, Commenter, Follower, Posters.</title>
    <link rel="shortcut icon" href="img/favicon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Easyliker là ứng dụng AutoLike đáng tin cậy nhất trên thế giới. Nó được phát hành dưới chứng chỉ của facebook! EasyLiker là ứng dụng autolike tốt nhất.">
<meta name="keywords" content="Easyliker Facebook Autoliker, New Facebook Autolike, Facebook Autolike 2015, Mobile Autolike, Top 5 Facebook Autolikers">
<meta name="author" content="MinhHung-It.Net">
<meta property="og:image" content="./img/slider/robot3.png" />
<!-- Google Fonts -->
<link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<!--[if IE]>
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato:700" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
<![endif]-->

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/theme.css" rel="stylesheet">
<link rel="stylesheet" href="styleswitcher/css/styleswitcher.css">
<link id="colours" rel="stylesheet" href="css/colour.css" />
<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
<link href="css/zocial.css" rel="stylesheet" type="text/css"/>
<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />
<link rel="stylesheet" href="assets/stylesheets/arthref.css">
<link href="indexc0d0.php?format=feed&amp;type=rss" rel="alternate" type="application/rss+xml" title="RSS 2.0" />
  <link href="index7b17.php?format=feed&amp;type=atom" rel="alternate" type="application/atom+xml" title="Atom 1.0" />
  <link href="/ico.ico" rel="shortcut icon" type="/ico.ico" />
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" type="text/css" />
  <link rel="stylesheet" href="cache/gk/cbadf5da9e4574ebe31e13b4dbdce912.css.css" type="text/css" />
 <link rel="stylesheet" href="_.css" type="text/css" />
<link rel="stylesheet" href="css/main.css">
	
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style type="text/css">
.google.button {
  padding: 6px 10px;
  -webkit-border-radius: 2px 2px;
  border: solid 1px rgb(153, 153, 153);
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgb(255, 255, 255)), to(rgb(221, 221, 221)));
  color: #333;
  text-decoration: none;
  cursor: pointer;
  display: inline-block;
  text-align: center;
  text-shadow: 0px 1px 1px rgba(255,255,255,1);
  line-height: 1;
}
.google.button.scaled {
  -webkit-transform: scale(2); -webkit-transform-origin: bottom left;
}
 
.google.button.large {
  padding: 12px 20px; font-size: 21px; font-weight: bold;
}

#transparent {
    background-color:  rgba(0,0,0,0.3);
    position: absolute;
    z-index:-1;
    opacity: 0.2;
}
#container { /* These aren't needed,just here to demo */
    margin: 10px;
    padding: 20px;
    width: 400px;
    height: 100px;
    border: 1px solid black;
}


  .transparent.header {
    background: #333;
  }
  section[role="main"] {
    background: #333;
    color: #fff;
  }
  section[role="main"] h1 {
    color: #fff;
  }
  section[role="main"] h2 {
    color: #fff;
  }
  section[role="main"] h3 {
    color: #fff;
  }
  .button {
    margin-left: 0.5em;
    border-radius: 4px;
  }
  ol {
    margin-left: 2em;
  }
  div#sidebarAd.cleanslate {
    background: #444 !important;
    color: #fff !important;
  }
  div#sidebarAd.cleanslate .ad-sponsor {
    color: #ccc !important;
  }
  .zurb-footer-top {
    background: #222;
  }

  @-webkit-keyframes bigAssButtonPulse {
	  from { background-color: #749a02; -webkit-box-shadow: 0 0 25px #333; }
	  50% { background-color: #91bd09; -webkit-box-shadow: 0 0 50px #91bd09; }
	  to { background-color: #749a02; -webkit-box-shadow: 0 0 25px #333; }
	}

	@-webkit-keyframes greenPulse {
	  from { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #91bd09; -webkit-box-shadow: 0 0 18px #91bd09; }
	  to { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
	}

	@-webkit-keyframes bluePulse {
	  from { background-color: #007d9a; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #2daebf; -webkit-box-shadow: 0 0 18px #2daebf; }
	  to { background-color: #007d9a; -webkit-box-shadow: 0 0 9px #333; }
	}

	@-webkit-keyframes redPulse {
	  from { background-color: #bc330d; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #e33100; -webkit-box-shadow: 0 0 18px #e33100; }
	  to { background-color: #bc330d; -webkit-box-shadow: 0 0 9px #333; }
	}

	@-webkit-keyframes magentaPulse {
	  from { background-color: #630030; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #a9014b; -webkit-box-shadow: 0 0 18px #a9014b; }
	  to { background-color: #630030; -webkit-box-shadow: 0 0 9px #333; }
	}

	@-webkit-keyframes orangePulse {
	  from { background-color: #d45500; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #ff5c00; -webkit-box-shadow: 0 0 18px #ff5c00; }
	  to { background-color: #d45500; -webkit-box-shadow: 0 0 9px #333; }
	}

	@-webkit-keyframes orangellowPulse {
	  from { background-color: #fc9200; -webkit-box-shadow: 0 0 9px #333; }
	  50% { background-color: #ffb515; -webkit-box-shadow: 0 0 18px #ffb515; }
	  to { background-color: #fc9200; -webkit-box-shadow: 0 0 9px #333; }
	}

	a.button {
		-webkit-animation-duration: 2s;
		-webkit-animation-iteration-count: infinite; 
	}
	
	.green.button { -webkit-animation-name: greenPulse; -webkit-animation-duration: 3s; }
	.blue.button { -webkit-animation-name: bluePulse; -webkit-animation-duration: 4s; }
	.red.button { -webkit-animation-name: redPulse; -webkit-animation-duration: 1s; }
	.magenta.button { -webkit-animation-name: magentaPulse; -webkit-animation-duration: 2s; }
	.orange.button { -webkit-animation-name: orangePulse; -webkit-animation-duration: 3s; }
	.orangellow.button { -webkit-animation-name: orangellowPulse; -webkit-animation-duration: 5s; }
	
	.wall-of-buttons { text-align: center; margin-top: 2em; margin-bottom: 2em; }
  <style type="text/css">
.childcontent .gkcol { width: 220px; }

body,
html, 
body button, 
body input, 
body select, 
body textarea { font-family: 'Open Sans', Arial, sans-serif; }

.blank { font-family: Arial, Helvetica, sans-serif; }

.blank { font-family: Arial, Helvetica, sans-serif; }

.blank { font-family: Arial, Helvetica, sans-serif; }

@media screen and (max-width: 772.5px) {
    	#k2Container .itemsContainer { width: 100%!important; } 
    	.cols-2 .column-1,
    	.cols-2 .column-2,
    	.cols-3 .column-1,
    	.cols-3 .column-2,
    	.cols-3 .column-3,
    	.demo-typo-col2,
    	.demo-typo-col3,
    	.demo-typo-col4 {width: 100%; }
    	}
#gkContentWrap { width: 100%; }

.gkPage { max-width: 1150px; }

#menu102 > div,
#menu102 > div > .childcontent-inner { width: 220px; }

#menu414 > div,
#menu414 > div > .childcontent-inner { width: 220px; }

#menu415 > div,
#menu415 > div > .childcontent-inner { width: 220px; }

#menu426 > div,
#menu426 > div > .childcontent-inner { width: 220px; }

#menu431 > div,
#menu431 > div > .childcontent-inner { width: 220px; }

#menu436 > div,
#menu436 > div > .childcontent-inner { width: 220px; }

#menu439 > div,
#menu439 > div > .childcontent-inner { width: 220px; }

#menu443 > div,
#menu443 > div > .childcontent-inner { width: 220px; }

#menu103 > div,
#menu103 > div > .childcontent-inner { width: 220px; }

#menu663 > div,
#menu663 > div > .childcontent-inner { width: 220px; }

#menu668 > div,
#menu668 > div > .childcontent-inner { width: 220px; }

#menu263 > div,
#menu263 > div > .childcontent-inner { width: 220px; }

					#gk-cookie-law { background: #E55E48; bottom: 0; color: #fff; font: 400 16px/52px Arial, sans-serif; height: 52px; left: 0; �margin: 0!important; position: fixed; text-align: center; width: 100%; z-index: 10001; }
					#gk-cookie-law span { display: inline-block; max-width: 90%; }
					#gk-cookie-law a { color: #fff; font-weight: 600; text-decoration: underline}
					#gk-cookie-law a:hover { color: #222}
					#gk-cookie-law a.gk-cookie-law-close { background: #c33c26; color: #fff; display: block; float: right; font-size: 28px; font-weight: bold; height: 52px; line-height: 52px; width: 52px;text-decoration: none}
					#gk-cookie-law a.gk-cookie-law-close:active,
					#gk-cookie-law a.gk-cookie-law-close:focus,
					#gk-cookie-law a.gk-cookie-law-close:hover { background: #282828; }
					@media (max-width: 1280px) { #gk-cookie-law { font-size: 13px!important; } }
					@media (max-width: 1050px) { #gk-cookie-law { font-size: 12px!important; line-height: 26px!important; } }
					@media (max-width: 620px) { #gk-cookie-law { font-size: 11px!important; line-height: 18px!important; } #gk-cookie-law span { max-width: 80%; } }
					@media (max-width: 400px) { #gk-cookie-law { font-size: 10px!important; line-height: 13px!important; } }
				
  </style>
  <script src="cache/gk/c30cfb09d56b5e84fb787da85c6d9c62.js.php" type="text/javascript"></script>
 </head>
<body>	


<script type="text/javascript" >
$(function() {
$(".submit").click(function() {
$("#controller").hide();
$( "#finish" ).show();
});
});
</script><center>
<div id="finish"style="display:none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;backzground: #f4f4f4;z-index: 99;">
<div class="text" style="position: absolute;top: 45%;left: 0;height: 100%;width: 100%;font-size: 18px;text-align: center;">
<center><img src="ajax.gif"></center>
Giving Likes To Your Post ID! <Br>Mean while Please <b style="color: red;">BE ONLINE on Easyliker</b>
</div>
</div></center>
<!--header-->
	<div class="header">
<!--menu-->
    <nav id="main_menu" class="navbar" role="navigation">
      <div class="container">
            <div class="navbar-header">
        <!--toggle-->
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
				<i class="fa fa-bars"></i>
			</button>
		<!--logo-->
			<div class="logo">
				<a href="./index.php"><img src="img/logo.png" alt="" class="animated bounceInDown" /></a> 
			</div>
		</div>
           
            <div class="collapse navbar-collapse" id="menu">
                <ul class="nav navbar-nav pull-right">
                   			<li class="dropdown "><a href="./index.php">Trang Chủ</a></li>
							<li class="dropdown"><a href="javascript:{}">Liên Kết</a>
						<ul class="dropdown-menu">
								<li><a href="http://www.minhhung-it.net" target="_blank">MinhHung Blog</a></li>
								<li><a href="http://www.facebook.com/hung.nguyenhoangminh1412" target="_blank">Facebook</a></li>
						</ul>
							</li>
							<li><a href="about.php" target="_blank">Thông Tin</a></li>
							<li><a href="contact.php" target="_blank">Liên Hệ</a></li>
							<li><a href="https://www.youtube.com/watch?v=9dkLdqvW8qY" target="_blank">Hướng Dẫn</a></li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
	<!--//header-->

<br>
  <div style="vertical-align:middle; display:table-cell;">


    </div>
<br>
				<center>
			
</center>
<center>

               <a href="?type=status" class="btn btn-info">Status</a>
             <a href="?type=custom"class="btn btn-warning" >Custom Post ID</a>
			 <a href="?type=photo" class="btn btn-success"> Photo</a>
             <a href="index.php"class="btn btn-danger" >Menu <i class="fa fa-sign-out  fa-lg"></i></a>
                           
<br>
</center>
<center>
<span id="countdown" class="timer"></span>
<script>
var seconds = <?php echo $countdown ?>;
function secondPassed() {
    var minutes = Math.round((seconds - 30)/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds;  
    }
    document.getElementById('countdown').innerHTML = "-->Next Submit: Wait  " + minutes + ":" + remainingSeconds + "  Seconds<--" ;
    if (seconds <= 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "-->Next Submit: READY....!<--";
    } else {
        seconds--;
    }
}
 
var countdownTimer = setInterval('secondPassed()', 1000);
</script>
</center>       
    
<div>
   <script type="text/javascript" src="
http://code.jquery.com/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="
http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js
"></script><?php if($_GET['type'] == "status"){
echo '<section class="feed">';
echo $status; 
echo '</section>';
}
if($_GET['type'] == "custom"){
echo '<section class="feed">';
echo $status; 
echo '</section>';
}
if($_GET['type'] == "photo"){
echo '<section class="albums">';
echo $status; 
echo '</section>';
}
?>
		</div>
			</div>
<center>
<br><br><br>
</center>
	<!-- footer 2 -->
	<div id="footer2">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<div class="copyright">
							EASYLIKER
							&copy;
							<script type="text/javascript">
							//<![CDATA[
								var d = new Date()
								document.write(d.getFullYear())
								//]]>
								</script>
							 - All Rights Reserved :
							Designed by <a href="http://www.minhhung-it.net">MinhHung Blog</a>
						</div>
						</div>
					</div>
				</div>
					</div>
				
<!-- SCRIPTS -->
<script src="js/jquery.js"></script>			
<script src="js/bootstrap.min.js"></script>	
<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>   
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<!-- slider settings -->
<script type="text/javascript">
	//<![CDATA[
			jQuery(document).ready(function() {
					jQuery('.tp-banner').show().revolution(
				{
					delay:9000,
					startwidth:1170,
					startheight:600,
					navigationType:"bullet",
					navigationStyle:"square",
					hideBulletsOnMobile:"on",
					hideArrowsOnMobile: "on",
					shadow:0,
					fullWidth:"on",
				});
			});	
		//]]>
	</script>
<script type="text/javascript">

//<![CDATA[
   	window.fbAsyncInit = function() {
		FB.init({ appId: '171342606239806', 
			status: true, 
			cookie: true,
			xfbml: true,
			oauth: true
		});
   		    
	  		  	function updateButton(response) {
	    	var button = document.getElementById('fb-auth');
		
			if(button) {	
	    		if (response.authResponse) {
	      		// user is already logged in and connected
				button.onclick = function() {
					if($('login-form')){
						$('modlgn-username').set('value','Facebook');
						$('modlgn-passwd').set('value','Facebook');
						$('login-form').submit();
					} else if($('com-login-form')) {
					   $('username').set('value','Facebook');
					   $('password').set('value','Facebook');
					   $('com-login-form').submit();
					}
				}
			} else {
	      		//user is not connected to your app or logged out
	      		button.onclick = function() {
					FB.login(function(response) {
					   if (response.authResponse) {
					      if($('login-form')){
					      	$('modlgn-username').set('value','Facebook');
					      	$('modlgn-passwd').set('value','Facebook');
					      	$('login-form').submit();
					      } else if($('com-login-form')) {
					         $('username').set('value','Facebook');
					         $('password').set('value','Facebook');
					         $('com-login-form').submit();
					      }
					  } else {
					    //user cancelled login or did not grant authorization
					  }
					}, {scope:'email'});  	
	      		}
	    	}
	    }
	  }
	  // run once with current status and whenever the status changes
	  FB.getLoginStatus(updateButton);
	  FB.Event.subscribe('auth.statusChange', updateButton);	
	  	};
    //      
   window.addEvent('load', function(){
        (function(){
                if(!document.getElementById('fb-root')) {
                     var root = document.createElement('div');
                     root.id = 'fb-root';
                     document.getElementById('gkfb-root').appendChild(root);
                     var e = document.createElement('script');
                 e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                     e.async = true;
                 document.getElementById('fb-root').appendChild(e);   
                }
        }());
    }); 
    //]]>
</script>
<script src="js/jquery.touchSwipe.min.js"></script>
<script src="js/jquery.mousewheel.min.js"></script>				
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script src="js/retina.js"></script>
<!-- carousel -->
<script type="text/javascript" src="js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
	$("#slider_home").carouFredSel({ 
		width : "100%", 
		height : "auto",
		responsive : true,
		auto : false,
		items : { width : 280, visible: { min: 1, max: 3 }
		},
		swipe : { onTouch : true, onMouse : true },
		scroll: { items: 1, },
		prev : { button : "#sl-prev", key : "left"},
		next : { button : "#sl-next", key : "right" }
		});
	});
//]]>
</script>
<script src="styleswitcher/js/styleswitcher.js"></script>
<script src="assets/javascripts/socialShare.min.js"></script>
<script src="assets/javascripts/socialProfiles.js"></script>

</body>
</html>

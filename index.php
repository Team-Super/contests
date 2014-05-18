<?php
require 'includes/lightopenid/openid.php';
session_start();
$_STEAMAPI = "ENTER OWN HERE";

	$openid = new LightOpenID('http://contests.team-super.com');
    if(!$openid->mode) 
    {
        if(isset($_GET['login'])) 
        {
            $openid->identity = 'http://steamcommunity.com/openid/?l=english';    // This is forcing english because it has a weird habit of selecting a random language otherwise
            header("Location: {$openid->authUrl()}");
        }
		if(!isset($_SESSION['USRSteamAuth'])){
			$login = "<div id=\"login\"> Welcome Guest. Please <a href=\"?login\"><img src=\"http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png\"/></a></div>";
		}
		
	} elseif($openid->mode == "cancel"){
		echo "Authentication cancelled";
	} else{
		if(!isset($_SESSION['USRSteamAuth'])){
			$_SESSION['USRSteamAuth'] = $openid->validate() ? $openid->identity : null;
			$_SESSION['USRSteamID64'] = str_replace("http://steamcommunity.com/openid/id/","", $_SESSION['USRSteamAuth']);
			
			if($_SESSION['USRSteamAuth']!= null){
				$Steam64 =str_replace("http://steamcommunity.com/openid/id/","", $_SESSION['USRSteamAuth']);
				$profile = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$_STEAMAPI}&steamids={$Steam64}");
				$buffer = fopen("cache/{$Steam64}.json","w+");
				fwrite($buffer,$profile);
				fclose($buffer);
			}
			header("Location : index.php");
		}
	}
	if(isset($_SESSION['USRSteamAuth'])){
		$steam = json_decode(file_get_contents("cache/{$_SESSION['USRSteamID64']}.json"));
		$usrName = $steam->response->players[0]->personaname;
		$usrAvtr =  "<img src=\"{$steam->response->players[0]->avatarmedium}\"/>";
		$login = "<p>Welcome {$usrName}<br/>{$usrAvtr}</p><div id=\"login\"><a href=\"?logout\">Click Here To Logout</a></div>";
		
		$xmlUrl = "http://steamcommunity.com/gid/103582791432836246/memberslistxml/?xml=1";
		$get = file_get_contents($xmlUrl);
		$xml = simplexml_load_string($get);
		
		$ismember = false;
		$ids = $xml->members->steamID64;
		foreach($ids as $groupMemberSteamID){
			if($groupMemberSteamID == $_SESSION['USRSteamID64']){
				$ismember = true;
				break;
			}
		}
	}
	if(isset($_GET['logout'])){
		unset($_SESSION['USRSteamAuth']);
		unset($_SESSION['USRSteamID64']);
		header("Location: index.php");
	}
	
	
?>
<!DOCTYPE HTML>
<html>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-34694398-2', 'team-super.com');
  ga('send', 'pageview');

</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Team-Super Multi-Gaming Community</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/style.css" type="text/css" rel="stylesheet" media="all">
<link href="css/flexslider.css" type="text/css" rel="stylesheet" media="screen">
<link href="css/tipsy.css" type="text/css" rel="stylesheet" media="screen">
<link href="css/prettyPhoto.css" type="text/css" rel="stylesheet" media="screen">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Arvo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/css3-mediaqueries.js"></script>
<script type="text/javascript" src="js/jquery.visualNav.min.js"></script>
<script type="text/javascript" src="js/jquery.isotope.min.js"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="js/jquery.jigowatt.js"></script>
<!--[if lt IE 9]>
<script src="js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript" src="js/custom.js"></script>
</head>
<body>
<div id="page">
  <aside id="header">
    <h1 id="logo"><a href="http://contests.team-super.com">Team-Super</a></h1>
    <nav id="main-nav">
      <ul>
        <li><a class="menu" href="#home">Home</a></li>
        <li><a class="menu" href="#giveaways">Giveaways</a></li>
        <li><a class="menu" href="#tournaments">Tournaments</a></li>
        <li class="external"><a href="http://team-Super.com">Main Site</a></li>
      </ul>
    </nav>
    <div class="widget">
      <ul class="social">
        <li class="youtube"><a href="http://www.youtube.com/user/OfficialTeamSuper" target="_newtab" class="tip" title="Youtube"><img src="images/icon_youtube.png" alt="Youtube"></a></li>
        <li class="facebook"><a href="https://www.facebook.com/OfficialTeamSuper" target="_newtab"class="tip" title="Facebook"><img src="images/icon_facebook.png" alt="Facebook"></a></li>
        <li class="steam"><a href="http://steamcommunity.com/groups/officialTeam-Super/" target="_newtab" class="tip" title="Steam"><img src="images/icon_steam.png" alt="Steam"></a></li>
        <li class="twitch"><a href="http://www.twitch.tv/teamsupercommunity" target="_newtab"class="tip" title="Twitch"><img src="images/icon_twitch.png" alt="Twitch"></a></li>
		<li class="spreadshirt"><a href="http://team-super.spreadshirt.com/" target="_newtab"class="tip" title="SpreadShirt"><img src="images/icon_spreadshirt.png" alt="SpreadShirt"></a></li>
      </ul>
    </div>
	<div class="widget">
	<?php echo $login;?>
</div>
<div class="widget">
	<p>Current Mumble IP is<br/><b>75.127.5.226:1337</b></p>
	</div>
	<div class="widget">
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Team-Super New -->
<ins class="adsbygoogle"
     style="display:inline-block;width:234px;height:60px"
     data-ad-client="ca-pub-9930854932871337"
     data-ad-slot="3496800394"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<h6>This ad directly supports Team-Super</h6>
</div>
</aside>
<section id="main">
    <div id="home" class="content">
       <div class="page_title">
        <h2>home</h2>
		</div>
			<?php 
				if (isset($_SESSION['USRSteamAuth'])) {
				echo"<p>im Logged in</p>";
					if($ismember == true){
						echo"</br>You are a member of Team-Super Community steam group";
					}
					else{
						echo"</br>You are not a member of our steam group. In order to win prizes/givaways and take part in tournaments you must be a member of our steam group";
					}
				// user logged in
				} else {
				echo"<p>not Logged in</p>";
				// user not logged in
				}
			?>
			<!-- enter content here -->
    </div>
	 <!-- END: .content -->
	    <div id="giveaways" class="content">
      <div class="page_title">
        <h2>Giveaways</h2>
		</div>
		<?php 
				if (isset($_SESSION['USRSteamAuth'])) {
				echo"<p>im Logged in</p>";
				// user logged in
				} else {
				echo"<p>not Logged in</p>";
				// user not logged in
				}
			?>
			<!-- enter content here -->
    </div>
<!-- END: .content -->
	    <div id="tournaments" class="content">
       <div class="page_title">
        <h2>Tournaments</h2>
		</div>
		<?php 
				if (isset($_SESSION['USRSteamAuth'])) {
				echo"<p>im Logged in</p>";
				// user logged in
				} else {
				echo"<p>not Logged in</p>";
				// user not logged in
				}
			?>
			<!-- enter content here -->
    </div>
	<!-- END: .content -->
</section>
</div>
</body>
</html>

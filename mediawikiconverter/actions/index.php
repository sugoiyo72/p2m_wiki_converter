<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>mediawikiconverter</title>
<style>
	body {font-family: verdana;}
</style>
</head>
<body>
<?php
	
	class index 
	{
		function execute() {
			$path = preg_replace("!^(.*/)(.*)$!", "$1", $_SERVER["REQUEST_URI"]);
			$script = $path. CONTROLLER;
			print <<<HTML
<div>
<h2>convert pukiwiki data for mediawiki format</h2>
You wanna get your pukiwiki data list ?
<form action="$script" method="post">
	<input type="hidden" name="action" value="getPageList">
	<input type="submit" value="get pukiwiki data list">
</form>
</div>
<hr>
HTML;


			$s = new searchDir();
			$s->execute(DATA_DIR. "/wiki");
			$wikifiles = $s->getFiles();
			if(!count($wikifiles)) {
				exit;
			}
			$username = MW_USERNAME;
			$loginurl = MW_SPECIALLOGIN_URL;
			$decodeloginurl = urldecode(MW_SPECIALLOGIN_URL);
			$password = MW_PASSWORD;
			print <<<HTML2
<div>
<h2>upload data to your mediawiki</h2>
You wanna upload these ?<br>
Please execute the above-mentioned list acquisition again if you want to do over again.
<form action="$script" method="post">
	<input type="hidden" name="action" value="uploadToMediaWiki">
	<h3>infomation : login logic</h3>
	<h4>Page URI : "Special:UserLogin"</h4>
	Page URI : <a href="$loginurl">$decodeloginurl</a>
	<h4>Post Parameters for login form</h4>
	form.action : This value is automatically acquired from above-mentioned Page URI by this program.<br>	
	form.method : post<br>	
	input.name : <input type="text" name="wpName" value="$username"><br>
	input.password : <input type="text" name="wpPassword" value="$password"><br>
	input.wpLoginattempt : This value is automatically acquired from above-mentioned Page URI by this program.<br>
	input.wpLoginToken : This value is automatically acquired from above-mentioned Page URI by this program.<br>
	<br>
				<br>
HTML2;
			sort($wikifiles);
			print "<h3>infomation : upload pages</h3>";
			foreach($wikifiles as $wiki) {
				print decode(preg_replace("!^.+/(.+?)\.txt$!", "$1", $wiki))."<br>";
			}
			$s = new searchDir();
			$s->execute(DATA_DIR. "/attach");
			$attachfiles = $s->getFiles();
			sort($attachfiles);
			print "<h3>infomation : upload attach files</h3>";
			foreach($attachfiles as $attach) {
				print decode(preg_replace("!^.+/(.+)$!", "$1", $attach))."<br>";
			}
			print <<<HTML3
	<input type="submit" value="upload to mediawiki">
</form>
</div>
HTML3;

		}
	}
?>
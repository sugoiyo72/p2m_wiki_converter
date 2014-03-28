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
<div>
	<form action="" method="post">
		<input type="hidden" name="action" value="convert">
	check it, if you wana convert.
<?php
	class getPageList {
		function execute() {
			$host = $_SERVER["HTTP_HOST"];
			$path = preg_replace("!^(.*/)(.*)$!", "$1", $_SERVER["REQUEST_URI"]);
			$uri = "http://";
			$s = new searchDir();
			$s->execute("wiki");
			$files = $s->getFiles();
			sort($files);
			$content = "";
			foreach($files as $wikifile) {
				$page = decode(preg_replace("!^.*/(.*).txt$!", "$1", $wikifile));
				if (substr($page, 0, 1) == ":") {
					continue;
				}
				$content .= "<li> <input type='checkbox' value='$wikifile' name='wikifile[]'><a href='./?$page'>$page</a></li>";
			}
			//var_dump($files);
			print "<ul>$content</ul><input type='submit' value='convert'></form></div></html>";
		}
	}
?>

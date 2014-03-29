<?php
	class uploadToMediaWiki {
		function execute() {
			//ob_start();
print <<<HTMLHEADER
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
	<ol>
HTMLHEADER;
			$login = $this->login();
			if ($login) {
				print "<li>login : success.</li>";
ob_flush();
flush();
			} else {
				print "login : failed.<br>";exit;
			}
			$this->uploadWikiData();
			$this->uploadAttachFiles();
				print "finished : I hope.<script>alert('finished, I hope.')</script>";exit;
		}
		function login() {
			//unlink(DATA_DIR. "/cookie.txt");
			//print "<li>initialize : php curl cookie file delete.</li>";
			// @ob_flush();
			
			$url = MW_SPECIALLOGIN_URL;
			$c = new mycurl;

			$ret = $c->execute(
				$url ,
				null ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				0,
				''
			);
			$wpLoginattempt = $this->getTagValue($ret, "input", "wpLoginAttempt");

			$wpLoginToken = $this->getTagValue($ret, "input", "wpLoginToken");

			$postfields = $_POST;
			$postfields["wpLoginattempt"] = $wpLoginattempt;
			$postfields["wpLoginToken"] = $wpLoginToken;

			$formaction = $this->getTagValue($ret, "form", "userlogin", "action");
			$url = MW_ROOTURL. 	preg_replace("!^/!", "", $formaction);
			print "<li>form action : $url</li>";
ob_flush();
flush();

			$ret = $c->execute(
				$url ,
				$postfields ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				1,
				''
			);

			$setcookieheader = "Set\-Cookie: .+?wikiUserName=";

			if (preg_match("!$setcookieheader!", $ret)) {
				return true;
			}
			return false;
		}
		function uploadWikiData () {
				$s = new searchDir();
				$s->execute(DATA_DIR. "/wiki");
				$wikifiles = $s->getFileNames();
				if(!count($wikifiles)) {
					exit;
				}
				foreach($wikifiles as $wiki) {
					$this->uploadWikiPage($wiki);
					print "<li>upload page : ". decode(preg_replace("!^(.+?)\.txt$!", "$1", $wiki)). "</li>";
					@ob_flush();
				}
		}
		function uploadWikiPage ($wiki) {
			$params = $this->getParamsForPageUpload($wiki);
			//var_dump($params);
			$upload = $this->sendWikiPageData($params, $wiki, "txt");
		}
		function getParamsForPageUpload ($wiki) {
			$title = urlencode(decode(preg_replace("!^(.+?)\.txt$!", "$1", $wiki)));
			$url = MW_ROOT_SCRIPT. "?title=$title&action=edit";

			$c = new mycurl;
			$html = $c->execute(
				$url ,
				null ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				0,
				''
			);
			return $this->parseUploadParams($html, $wiki);
		}

		function parseUploadParams($html, $wiki) {
			//see http://www.mediawiki.org/wiki/Manual:Parameters_to_index.php/ja
			$params = array(
				"wpSection",
				"wpStarttime",
				"wpEdittime",
				"wpScrolltop",
				"wpAutoSummary",
				"wpSummary",
				"wpSave",
				"wpEditToken"
			);
			$html = $this->htmlNormalize($html);
			$html = preg_replace("!>!", ">\n", $html);
			$ret = array();
			foreach ($params as $param) {
				$ret[$param] = $this->getTagValue($html, "input", $param);
			}
			$ret["wpTextbox1"] = file_get_contents(DATA_DIR. "/wiki/". $wiki);
			return array(
				"action" => $this->getTagValue($html, "form", "editform", "action"),
				"postfields" => $ret
			);
		}

		function htmlNormalize($html) {
			$html = preg_replace("!(\t|\r|\n)!", " ", $html);
			$html = preg_replace("!>\s+!", "> ", $html);
			$html = preg_replace("!\s+<!", " <", $html);
			return $html;
		}

		function sendWikiPageData ($params, $wiki) {
			$url = MW_ROOTURL. preg_replace("!^/!", "", htmlspecialchars_decode($params["action"]));
			$c = new mycurl();
			$html = $c->execute(
				$url ,
				$params["postfields"] ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				1,
				''
			);
		}

		function uploadAttachFiles () {
			$s = new searchDir();
			$s->execute(DATA_DIR. "/attach");
			$attachfiles = $s->getFiles();
			if(!count($attachfiles)) {
				exit;
			}
			foreach($attachfiles as $attache) {
				#print dirname($attache);
				//print dirname(dirname(__FILE__));
				$attachefullpath = dirname(dirname(dirname(__FILE__)))."/$attache";
				//print $fullpath;
				//var_dump(file_exists($fullpath));
				//print dirname("./$attache");
				$name = decode(preg_replace("!^.+/(.+?)$!", "$1", $attache)) ;
				if (EUC_FLAG) {
					$name = mb_convert_encoding($name, 'UTF-8', 'EUC-JP');
				}
				$this->uploadAttachFile($attachefullpath, $name);
				print "<li>upload file : ". $name. "</li>";
ob_flush();
flush();
			}
			//$params = $this->getParamsForFileUpload($wiki);
		}

		function uploadAttachFile ($attache, $name) {
			$params = $this->getParamsForFileUpload($attache, $name);
			$this->sendAttachFile($params);
		}

		function getParamsForFileUpload ($attache, $name) {
			$url = MW_SPECIALUPLOAD_URL;

			$c = new mycurl;
			$html = $c->execute(
				$url ,
				null ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				0,
				''
			);
			return $this->parseAttachUploadParams($html, $attache, $name);
		}

		function parseAttachUploadParams ($html, $attache, $name) {
			
			//see http://www.mediawiki.org/wiki/Manual:Parameters_to_index.php/ja
			$params = array(
				"wpSourceType",
				"wpDestFile",
				"wpUploadDescription",
				"wpWatchthis",
				"wpLicense",
				"wpIgnoreWarning",
				"wpUpload",
				"wpDestFileWarningAck",
				"wpForReUpload",
				"wpEditToken",
				"title",
			);
			$html = $this->htmlNormalize($html);
			$html = preg_replace("!>!", ">\n", $html);
			$ret = array();
			foreach ($params as $param) {
				$ret[$param] = $this->getTagValue($html, "input", $param);
			}
			$ret["wpUploadFile"] = '@'.$attache;
			$ret["wpDestFile"] = $name;
			$ret["wpDestFileWarningAck"] = "1";
			return $ret;
			
		}

		function sendAttachFile ($params) {
			$url = MW_SPECIALUPLOAD_URL;
			$c = new mycurl();
			$html = $c->execute(
				$url ,
				$params ,
				null ,
				DATA_DIR. "/cookie.txt",
				null,
				'php_curl',
				1,
				''
			);
		}

		function getTagValue ($str, $elementName, $keyword, $attrName = "value") {
			//if ($attrName == "action") {
			//	preg_match("!<$elementName.+?('|\")$keyword('|\").*? >!", $str, $match);
			//} else {
				preg_match("!<$elementName.+?=('|\")$keyword('|\").*?>!i", $str, $match);
			//}
			preg_match("!$attrName=('|\")(.*?)('|\")!i", $match[0], $match);
			print $keyword.' : '.$match[2].'<br>';
			return $match[2];

		}
	}
?>

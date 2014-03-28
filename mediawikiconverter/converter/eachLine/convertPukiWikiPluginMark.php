<?php
	class convertPukiWikiPluginMark {
		function execute($line, $wikifile) {
			if (preg_match("!^#ls2!", $line)) {
				return $this->ls2($wikifile);
			}
			return preg_replace("!^#\w+$!", "", $line);
		}
		function ls2($wikifile) {
			$wikiPageName = preg_replace("!^.+?/(.+?)\.txt$!", "$1", $wikifile);
			//print "!$wikiPageName.+!";exit;
			$s = new searchDir("^$wikiPageName\w+");
			$s->execute("wiki");
			$files = $s->getFileNames();
			sort($files);
			$tempret = array();
			foreach ($files as $file) {
				$tempret[] = "- [[".decode(preg_replace("!^(.+?)\.txt$!", "$1", $file)). "]]";
			}
			return join("\n", $tempret);
		}
	}

?>
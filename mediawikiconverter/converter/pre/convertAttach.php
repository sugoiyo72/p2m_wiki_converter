<?php
	class convertAttach {
		function execute($wiki, $wikiFile) {
			preg_match_all("!&ref\((.+?)\);!", $wiki, $matches);
			if (!count($matches[1])) {
				return $wiki;
			}
			$attaches = array_unique($matches[1]);

			$wikiPageName = decode(preg_replace("!^.+/(.+)\.txt!", "$1", $wikiFile));

			$this->copyAttach($wikiPageName, $attaches);

			$newFileName = preg_replace("!/!", " ", $wikiPageName);
			//png、gif、jpg、jpeg、ppt、pdf、doc、psd、mp3、xls、zip、swf、doc、odt、odc、odp、odg、mpp
			$wiki = preg_replace("!&ref\((.+?\.(gif|jpg|jpeg|png))\);!i", "&ref(ファイル:$newFileName $1);", $wiki);
			$wiki = preg_replace("!&ref\((.+?\.(ppt|doc|psd|mp3|xls|zip|swf|odt|odc|odp|odg|mpp))\);!i", "&ref(メディア:$newFileName $1);", $wiki);
			return $wiki;
		}
		function copyAttach($wikiPageName, $attaches) {
			$wikiNewPageName = preg_replace("!/!", " ", $wikiPageName);
			foreach ($attaches as $attach) {
				$orgFile = "attach/". encode($wikiPageName). "_". encode($attach);
				$copyFile = DATA_DIR. "/attach/". encode(preg_replace("!/!", " ",$wikiPageName). " $attach");
				if (!file_exists($orgFile)) {
					continue;
				}
				copy($orgFile, $copyFile);
			}
		}
	}

?>
<?php
	class convertHeaderAndListMark {
		function execute($line) {
			if (preg_match("!^\*{1,3}!", $line, $match)) {
				$mark = $match[0];
				$convertmark = "=".preg_replace("!\*!", "=", $mark);
				$templine = preg_replace("!^(\*{1,3})(.*)$!", "$convertmark $2 $convertmark", $line);
				return preg_replace("! \[#\w{8}\]!", "", $templine);
			} elseif (preg_match("!^\-{1,3}!", $line, $match)) {
				$mark = $match[0];
				$convertmark = preg_replace("!\-!", "*", $mark);
				//print preg_replace("!^(\-{1,3})(.*)$!", "$convertmark$2", $line);
				//exit;
				return preg_replace("!^(\-{1,3})(.*)$!", "$convertmark$2", $line);
			} elseif (preg_match("!^\+{1,3}!", $line, $match)) {
				$mark = $match[0];
				$convertmark = preg_replace("!\+!", "#", $mark);
				return preg_replace("!^(\+{1,3})(.*)$!", "$convertmark$2", $line);
			} else {
				return $line;
			}
		}
	}

?>
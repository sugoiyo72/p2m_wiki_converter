<?php
	class convertQuote {
		function execute($line) {
			return $line;
			//$line = preg_match_all("!'''.+?'''!", $line, $matches)
			//return preg_replace("!^#\w+*$!", "", $line);
		}
	}

?>
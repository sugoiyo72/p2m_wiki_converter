<?php
	class convertPageLink {
		function execute($line) {

			preg_match_all("!\[\[(.+?)\]\]!", $line, $matches);
			$links = $matches[1];
			if (!count($links)) {
				return $line;
			}
			foreach ($links as $link) {
				if (!preg_match("!.>.!", $link)) {
					$tempstr = preg_quote($link);
					$line = preg_replace("!\[\[$tempstr\]\]!", "[[$link]]", $line);
				} elseif (preg_match("!^(.+)>(https?://.+)$!", $link, $match)) {
					$tempstr = preg_quote($link);
					$line = preg_replace("!\[\[$tempstr\]\]!", "[${match[2]} ${match[1]}]", $line);
				} else {
					preg_match("!^(.+)>(.+)!", $link, $match);
					$tempstr = preg_quote($link);
					$line = preg_replace("!\[\[$tempstr\]\]!", "[[${match[2]}|${match[1]}]]", $line);
				}
			}
			return $line;
		}
	}

?>
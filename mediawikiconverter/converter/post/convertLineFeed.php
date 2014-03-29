<?php
	class convertLineFeed {
		function execute($wiki) {
			return preg_replace("!~\n!", "<br />", $wiki);
		}
	}

?>
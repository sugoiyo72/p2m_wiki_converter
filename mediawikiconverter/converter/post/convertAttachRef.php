<?php
	class convertAttachRef {
		function execute($wiki) {
			return preg_replace("!&ref\((.+?)\);!i", "[[$1]]", $wiki);
		}
	}

?>
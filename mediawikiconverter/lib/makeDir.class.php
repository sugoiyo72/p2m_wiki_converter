<?php


	class MakeDir {

		private $root = '/var/www/html';
		public $error = null;


		function execute ($path) {
			$path = preg_replace('!^'. $this->root.'!', '', $path);
			$path = preg_replace('!^/!', '', $path);
			$path = preg_replace('!/$!', '', $path);
			if ($this->checkAndMkdir('/'. $path)) {
				return true;
			}

			$paths = explode('/', $path);
			if(!$paths[0]) {
				$this->error = $path;
				return false;
			}
			# foreachでチェックしながらmake
			$current = null;
			foreach ($paths as $val) {
				$current .= '/'. $val;
				$this->checkAndMkdir($current);
			}
			if ($this->checkAndMkdir('/'. $path)) {
				return true;
			}
			$this->error = "error : $path";
			return false;
		}


		function checkAndMkdir ($path) {
			$path = $this->root. $path;
			if (is_dir($path)) {
				return true;
			}
			#var_dump(is_dir($path. '/'));
			if (! @mkdir($path, 0777)){
				return false;
			} else {
				return true;
			}
		}


	}

?>
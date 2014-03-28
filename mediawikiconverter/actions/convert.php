<?php
	class convert {
		private $preConverters = array("Attach");
		private $eachLineConverters = array("PukiWikiPluginMark", "Quote", "HeaderAndListMark", "PageLink");
		private $postConverters = array("LineFeed", "AttachRef");
		private $wikiFiles = array();

		function execute() {
			$this->cleanFile();
			$this->setWikiFiles();
			$this->requireConverter();
			$this->convert();
			print "<a href=''>done</a>";
		}

		function cleanFile() {
			$s = new searchDir();
			$s->execute(DATA_DIR. "/wiki");
			$s->execute(DATA_DIR. "/attach");
			$files = $s->getFiles();
			foreach($files as $file) {
				unlink($file);
			}
		}

		function setWikiFiles() {
			if (!isset($_POST["wikifile"]) || !is_array($_POST["wikifile"])  ) {
				print 'Wikifile List to convert was not sended.';
				exit;
			}
			$this->wikiFiles = $_POST["wikifile"];
		}

		function convert() {
			foreach ($this->wikiFiles as $wikiFile) {
				if (!file_exists($wikiFile)) {
					print "Skip $wikiFile.<br>";
				}
				$this->convertWikiFile($wikiFile);
			}
		}

		function convertWikiFile($wikiFile) {
			$wiki = file_get_contents($wikiFile);
			//$this->attachConvert($wiki);
			$wiki = $this->preConvert($wiki, $wikiFile);
			$wiki = $this->eachLineConvert($wiki, $wikiFile);
			$wiki = $this->postConvert($wiki, $wikiFile);
			print "<pre>$wiki</pre>";//exit;
			$this->saveConvertedWiki($wikiFile, $wiki);
		}


		function preConvert($wiki, $wikiFile) {
			if (!count($this->preConverters)) {
				return $wiki;
			}
			foreach ($this->preConverters as $converter) {
				$className = "convert$converter";
				$conObj = new $className;
				$wiki = $conObj->execute($wiki, $wikiFile);
			}
			return $wiki;
		}

		function eachLineConvert($wiki, $wikiFile) {
			if (!count($this->eachLineConverters)) {
				return $wiki;
			}
			$wikiline = explode("\n", $wiki);
			$tmpline = array();
			foreach ($this->eachLineConverters as $converter) {
				$className = "convert$converter";
				$conObj = new $className;
				$tmpline = $wikiline;
				foreach ($tmpline as $num => $line) {
					$wikiline[$num] = $conObj->execute($line, $wikiFile);
				}
				$wikiline = explode("\n", implode("\n", $wikiline));
			}
			return implode("\n", $wikiline);
		}

		function postConvert($wiki, $wikiFile) {
			if (!count($this->postConverters)) {
				return $wiki;
			}
			foreach ($this->postConverters as $converter) {
				$className = "convert$converter";
				$conObj = new $className;
				$wiki = $conObj->execute($wiki, $wikiFile);
			}
			return $wiki;
		}

		function requireConverter() {
			$allConverter = array(
				"pre" => $this->preConverters,
				"eachLine" => $this->eachLineConverters,
				"post" =>  $this->postConverters
			);
			foreach ($allConverter as $class => $converters ) {
				if ( ! count($converters) ) {
					continue;
				}
				foreach ($converters as $converter) {
					$fileName = CONVERTER_DIR. "/$class/convert$converter.php";
					if (file_exists($fileName)) {
						require_once $fileName;
					 	if (!class_exists("convert$converter")) {
							print "The Class 'convert$converter' does not exist.";
							exit;
						}
					} else {
						print "The Converter '$fileName' does not exist.";
						exit;
					}
				}
			}
		}
		function saveConvertedWiki($wikiFile, $wiki) {
			file_put_contents(DATA_DIR. "/$wikiFile", $wiki);
		}


	}
?>

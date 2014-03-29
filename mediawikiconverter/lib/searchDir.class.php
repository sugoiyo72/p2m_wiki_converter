<?php

	class searchDir {
		
		private $files = array();
		# カレントと親ディレクトリを無視する
		private $matchFileNamesRgexes = null;
		private $unmatchFileNamesRgexes = null;
		private $matchDirectoryNamesRegexs = null;
		private $unmatchDirectoryNamesRegexs = null;
		private $scanFileRegex = null;
		private $scanCharset = null;
		private $showMatchFlag = null;

		# コンストラクタ マッチ、アンマッチの定義を設定可能
		function __construct (
				$matchFileNamesRgexes = null,
				$unmatchFileNamesRgexes = null,
				$matchDirectoryNamesRegexs = null,
				$unmatchDirectoryNamesRegexs = null
			) {
			$this->matchFileNamesRgexes = $matchFileNamesRgexes;
			$this->unmatchFileNamesRgexes = $unmatchFileNamesRgexes;
			$this->matchDirectoryNamesRegexs = $matchDirectoryNamesRegexs;
			$this->unmatchDirectoryNamesRegexs = $unmatchDirectoryNamesRegexs;
		}

		# エイリアス名
		function execute ($dir) {
			$this->searchReflexive ($dir);
		}

		# 再帰的にファイルを取得
		function searchReflexive ($dir) {
			if (!isset($dir) || !is_dir($dir)) {
				return false;
			}
			$directories = array();

			$directories = $this->searchCurrent($dir);
			if ($directories == null) {
				return false;
			}
			foreach ($directories as $value) {
				$this->searchReflexive($value);
			}
		}

		# カレントのファイル名を取得し、下位ディレクトリを返す
		function searchCurrent ($dir) {
			if (!isset($dir) || !is_dir($dir)) {
				return false;
			}

			$dirs = array();

			# ディレクトリが開くなら
			if ($dh = opendir ($dir)) {

				# ディレクトリが帰ってくる間はループ
				while (($value = readdir($dh)) !== false) {

					# カレントか親ディレクトリならスキップ
					if ($value == '.' || $value == '..') {
						continue;
					}

					# ファイルの場合の処理
					if (is_file("$dir/$value")) {
						if (!$this->checkFilesByRegex ($this->matchDirectoryNamesRegexs, null, "$dir/$value")) {
							continue;
						}
						# ファイル名が指定に合っているかどうかを判別 不適なら処理を飛ばす
						if (!$this->checkFilesByRegex ($this->matchFileNamesRgexes, $this->unmatchFileNamesRgexes, $value)) {
							continue;
						} else {
							# ファイルの中身をscanする必要がある場合は scanFileByRegex メソッドを実行
							if (!$this->scanFileRegex) {
								$this->files[] = "$dir/$value";
							} else {
								$this->scanFileByRegex("$dir/$value");
							} 
						}
					# ディレクトリの場合の処理
					} elseif(is_dir("$dir/$value")) {
						# ディレクトリ名が指定の名前で無い場合は処理をスキップ
						if (!$this->checkFilesByRegex (null, $this->unmatchDirectoryNamesRegexs, $value)) {
							continue;
						} else {
							array_push($dirs, "$dir/$value");
						}
					}
				}
				closedir ($dh);
			}
			return $dirs;
		}

		# ファイル名やディレクトリ名のマッチ、アンマッチを判定
		function checkFilesByRegex ($matchString, $unmatchString, $value){
			if (
					(
						isset($matchString) && $matchString != '' && !preg_match("!{$matchString}!", $value)
					)
						||
					(
						isset($unmatchString) && $unmatchString != '' && preg_match("!{$unmatchString}!", $value)
					)
				) {
				return false;
			}
			return true;
		}
		
		# 取得したファイル名を返す
		function getFiles (){
			return $this->files;
		}

		# 取得したファイルネームを返す
		function getFileNames (){
			$ret = array();
			foreach($this->files as $file) {
				$ret[] = preg_replace('!^.+/(.+)$!', '\1', $file);
			}
			return $ret;
		}


		# ファイルをスキャンする場合のメソッド
		function setScanFileRegex ($reg, $charset, $flag = null){
			$this->scanFileRegex = $reg;
			$this->scanCharset = $charset;
			$this->showMatchFlag = $flag;
		}

		# スキャンファイル実行
		private function scanFileByRegex ($file){
			$reg = $this->scanFileRegex;
			$charset = $this->scanCharset;


			#拡張
			$string = file_get_contents($file);
			$string = @mb_convert_encoding ($string, "UTF-8", $charset);
			if (preg_match_all("!.+$reg.+!", $string, $matches)) {
				$m = null;
				array_push($this->files, $file);

				if ($this->showMatchFlag && isset($matches)) {
					foreach($matches as $num => $value) {
						$m .= ($num + 1 ). ' : '. preg_replace('!<.+?>!', '', $value[0]);
					}
				}
				print "$file <span style=\"color:red;\">matched !</span><br/>$m<br/>\n";
				flush();
			} else {
				#print "$file <strong>unmatched</strong><br/>\n";
				print "\n";
				flush();
			}
		}
		function getRegexes () {
			print "scanFileRegex\t". $this->scanFileRegex. "\n";
			print "matchFileNamesRgexes\t". $this->matchFileNamesRgexes. "\n";
			print "unmatchFileNamesRgexes\t". $this->unmatchFileNamesRgexes. "\n";
			print "matchDirectoryNamesRegexs\t". $this->matchDirectoryNamesRegexs. "\n";
			print "unmatchDirectoryNamesRegexs\t". $this->unmatchDirectoryNamesRegexs. "\n";
		}

	}

?>
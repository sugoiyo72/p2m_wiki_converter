<?php

	class MyPipe {

		function execute($cmd, $stdin = null){
			return $this->get($cmd, $stdin);
		}

		function get($cmd, $stdin = null){

			$descriptorspec = array(
				0 => array('pipe',	'r'),
				1 => array('pipe',	'w'),
				2 => array('file',	'error-pipe.txt',	'w')
			);
			$process = proc_open($cmd, $descriptorspec, $pipes);
			if	(is_resource($process)) {

				fwrite($pipes[0], $stdin);
				fclose($pipes[0]);

				$return_value = '';
				while(!feof($pipes[1])){
					$return_value .= fgets($pipes[1], 1024);
				}
				fclose($pipes[1]);
				proc_close($process);
				if($return_value !== ''){
					return $return_value;
				}else{
					return $stdin;
				}
			}
		}
	}

?>
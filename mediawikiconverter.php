<?php
ob_end_flush();
ob_start();

	require "mediawikiconverter/config.php";

	$action = null;
	extract($_POST);
	$action = $action ? $action : 'index';
	if (file_exists(ACTIONS_DIR. "/$action.php")) {
		require ACTIONS_DIR. "/$action.php";
		if (class_exists($action)) {
			$a = new $action;
			$a->execute();
			exit;
		}
	}
	print "The Action '$action' does not exist.";
?>
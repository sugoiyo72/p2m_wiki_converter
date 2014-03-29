<?php

	// UTF8基本
	mb_internal_encoding("UTF-8");

	define ("EUC_FLAG", true);
	define ("CONTROLLER", "mediawikiconverter.php");
	define ("ACTIONS_DIR", "mediawikiconverter/actions");
	define ("LIB_DIR", "mediawikiconverter/lib");
	define ("CONVERTER_DIR", "mediawikiconverter/converter");
	define ("DATA_DIR", "mediawikiconverter/data");

	define ("MW_USERNAME", "admin");
	define ("MW_PASSWORD", "test");
	// e.g.
	define ("MW_ROOTURL", "http://10.211.55.4/");
	define ("MW_ROOT_SCRIPT", "http://10.211.55.4/mediawiki/index.php");
	define ("MW_SPECIALLOGIN_URL", "http://10.211.55.4/mediawiki/index.php?title=%E7%89%B9%E5%88%A5:%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3&returnto=%E3%83%A1%E3%82%A4%E3%83%B3%E3%83%9A%E3%83%BC%E3%82%B8");
	#define ("MW_SPECIALUPLOAD_URL", "http://10.211.55.4/mediawiki/index.php?title=%E7%89%B9%E5%88%A5:%E3%82%A2%E3%83%83%E3%83%97%E3%83%AD%E3%83%BC%E3%83%89");

	define ("MW_SPECIALUPLOAD_URL", "http://10.211.55.4/mediawiki/index.php/%E7%89%B9%E5%88%A5:%E3%82%A2%E3%83%83%E3%83%97%E3%83%AD%E3%83%BC%E3%83%89");
	// require pukiwiki functions
	//require "lib/func.php";
	// classes that I wrote.
	require LIB_DIR. "/func.php";
	require LIB_DIR. "/searchDir.class.php";
	require LIB_DIR. "/mycurl.class.php";
?>
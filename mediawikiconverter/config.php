<?php

	define ("CONTROLLER", "mediawikiconverter.php");
	define ("ACTIONS_DIR", "mediawikiconverter/actions");
	define ("LIB_DIR", "mediawikiconverter/lib");
	define ("CONVERTER_DIR", "mediawikiconverter/converter");
	define ("DATA_DIR", "mediawikiconverter/data");

	define ("MW_USERNAME", "");
	define ("MW_PASSWORD", "");
	// e.g.
	// http://www.sample.com/wiki/index.php/
	// http://www.sample.com/wiki/
	// http://www.sample.com/
	define ("MW_ROOTURL", "http://wiki.sugoiyo.com:8080/");
	// http://wiki.sugoiyo.com:8080/?title=特別:ログイン&action=submitlogin&type=login&returnto=メインページ
	define ("MW_SPECIALLOGIN_URL", "http://wiki.sugoiyo.com:8080/?title=%E7%89%B9%E5%88%A5:%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3&returnto=%E3%83%A1%E3%82%A4%E3%83%B3%E3%83%9A%E3%83%BC%E3%82%B8");
	// http://wiki.sugoiyo.com:8080/?title=特別:アップロード
	define ("MW_SPECIALUPLOAD_URL", "http://wiki.sugoiyo.com:8080/%E7%89%B9%E5%88%A5:%E3%82%A2%E3%83%83%E3%83%97%E3%83%AD%E3%83%BC%E3%83%89");


	// require pukiwiki functions
	require "lib/func.php";
	// classes that I wrote.
	require LIB_DIR. "/searchDir.class.php";
	require LIB_DIR. "/mycurl.class.php";
?>
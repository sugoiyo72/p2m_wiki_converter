<?php
	class mycurl {
	function execute (
      $url,
      $postfields = null,
      $getfields = null,
      $cookie_file = null,
      $dump_file = null,
      $user_agent = null,
      $header_flag = null,
      $encoding = null
		){
  $ch = curl_init ($url);
  if (isset ($postfields)) {
   curl_setopt ($ch, CURLOPT_POST, 1);
   curl_setopt ($ch, CURLOPT_POSTFIELDS, $postfields);

  } elseif (isset ($getfields)) {
   curl_setopt ($ch, CURLOPT_POSTFIELDS, $getfields);
  }
  curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie_file);
  curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie_file);
  if (isset ($dump_file)) {
   $fp = fopen ($dump_file, "w");
   curl_setopt ($ch, CURLOPT_FILE, $fp);
  }
  curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt ($ch, CURLOPT_HEADER, $header_flag);
  curl_setopt ($ch, CURLOPT_ENCODING, $encoding);

  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);


  $ret = curl_exec ($ch);
  curl_close ($ch);

  if (isset ($dump_file)) {fclose ($fp);}

	return $ret;
 }
}
?> 
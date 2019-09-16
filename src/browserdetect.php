<?php

$agent = $_SERVER['HTTP_USER_AGENT'];
$browser = "Other";
      
if (eregi("opera",$agent)) $browser = 'Opera';  // test for Opera 
elseif(eregi("msie",$agent))  // test for MS Internet Explorer
{
	$val = explode(" ",stristr($agent,"msie"));
	$version = $val[1];
	$version = ereg_replace("[^0-9,.,a-z,A-Z]", "", $version);
	if ($version >= 7.0 && $version <= 8.0) $browser = "IE 7";
	elseif ($version < 7.0) $browser = "IE 1-6";
}
elseif(eregi("Firefox", $agent)) $browser = 'Firefox';  // test for Firefox
?>
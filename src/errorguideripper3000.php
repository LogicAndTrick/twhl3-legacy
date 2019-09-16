<?php
include 'middle.php';

function htmlfilter2($s,$replace = false) {
	// no more goatse in the shoutbox
	$nstr = trim($s);
	$nstr = htmlspecialchars($nstr);
	if ($replace) $nstr = preg_replace('/(\\r\\n){2,}/sim', "\n\n", $nstr);;
	//$nstr = mysql_real_escape_string($nstr);
	return $nstr;
}

	$fp = @fopen ("errorguide.txt", "r");
	if (!empty($fp)) {
		$content = fread ($fp, filesize ("errorguide.txt"));
	}
	fclose ($fp);
	
	$content = str_ireplace("<h2>","<h2>",$content);
	$content = str_ireplace("</h2>","</h2>",$content);
	$content = preg_replace('%<a.+?>(.*?)</a>%si', '\1', $content);
	$content = preg_replace('%<p>%si', '', $content);
	$content = preg_replace('%<hr>%si', '', $content);
	$content = str_ireplace("<br>","\n",$content);
	$content = str_ireplace("<br/>","\n",$content);
	$content = str_ireplace("<br />","\n",$content);
	$content = str_ireplace("<b>","[b]",$content);
	$content = str_ireplace("</b>","[/b]",$content);
	$content = str_ireplace("<i>","[i]",$content);
	$content = str_ireplace("</i>","[/i]",$content);
	$content = str_ireplace("<u>","[u]",$content);
	$content = str_ireplace("</u>","[/u]",$content);
	$content = str_ireplace("<li>","[li]",$content);
	$content = str_ireplace("</li>","[/li]",$content);
	$content = str_ireplace("<ul>","[list]",$content);
	$content = str_ireplace("</ul>","[/list]",$content);
	$content = str_ireplace("<ol>","[olist]",$content);
	$content = str_ireplace("</ol>","[/olist]",$content);
	$content = str_ireplace("<blockquote>","[quote]",$content);
	$content = str_ireplace("</blockquote>","[/quote]",$content);
	$content = str_ireplace("&nbsp","",$content);
	
	$errors = explode("<h2>",$content);
	
	foreach ($errors as $err)
	{
		$split = explode("</h2>",$err);
		echo htmlfilter2($split[0],true)."\n"."_!__t:__"."\n".htmlfilter2($split[1],true)."\n"."_e!!~nd~``___"."\n";
	}
?>
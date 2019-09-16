<?
/*
TWHL3
GENERAL FUNCTIONS
To be included in all pages
Functions used by all pages, or universal functions, or functions used in logins.php (hence all pages)
*/

function htmlfilter($s,$replace = false) {
	// no more goatse in the shoutbox
	$nstr = trim($s);
	//$nstr = stripslashes($nstr);
	$nstr = htmlspecialchars($nstr, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
	if ($replace) $nstr = preg_replace('/(\\r\\n){2,}/sim', "\n\n", $nstr);;
	$nstr = mysql_real_escape_string($nstr);
	return $nstr;
}

function reversefilter($s) {
  $nstr = trim($s);
  $nstr = str_replace('<br />','',$nstr);
  return $nstr;
}

function newlines($s) {
  $nstr = trim($s);
  $nstr = str_replace("\n",'<br />',$nstr);
  return $nstr;
}

function unfilter($string)
{
	$string = str_replace ('&amp;','&', $string);
	$string = str_replace ('&#039;','\'', $string);
	$string = str_replace ('&quot;','"', $string);
	$string = str_replace ('&lt;','<', $string);
	$string = str_replace ('&gt;','>', $string);
    $string = str_ireplace("<P>","\n",$string);
    $string = str_ireplace("<BR>","\n",$string);
	return $string;
}

function htmltobbcode($str)
{
	$str = preg_replace('%<a[^>]+?href="(.+?)"[^>]*?>(.+?)</a>%si', '[url=\1]\2[/url]', $str);
	$str = preg_replace('/<img[^>]+?src="(.+?)"[^>]*?>/si', '[img]\1[/img]', $str);
	$str = preg_replace('%<b>(.+?)</b>%si', '[b]\1[/b]', $str);
	$str = preg_replace('%<u>(.+?)</u>%si', '[u]\1[/u]', $str);
	$str = preg_replace('%<i>(.+?)</i>%si', '[i]\1[/i]', $str);
	$str = preg_replace('/<p>/si', "\n\n", $str);
	$str = preg_replace('%</p>%si', "", $str);
	$str = preg_replace('%<br ??/??>%si', "\n", $str);
	$str = preg_replace('/(\\r\\n){2,}/sim', "\n\n", $str);
	return $str;
}

function getavatar($usrid,$avtype,$small = false) {
	$ext = ".lol";
	$name = $usrid;
	$path = "avatars/";
	if ($avtype == 0) $avtype = 1;
	if ($small) $name .= "_small";
	if ($avtype >= 1) {
		$ext = ".gif";
		$name = str_pad($avtype,3,"0",STR_PAD_LEFT);
		$path = "gfx/avatars/";
	}
	elseif ($avtype == -1) $ext = ".jpg";
	elseif ($avtype == -2) $ext = ".png";

	return $path . $name . $ext;
}

function getresizedavatar($usrid,$avtype,$max = 100) {
	if ($max == 100) return getavatar($usrid,$avtype);
	$ext = ".lol";
	$name = $usrid;
	$path = "beta/resize/" . $max . "/";
	if ($avtype >= 1) {
		$ext = ".gif";
		$name = str_pad($avtype,3,"0",STR_PAD_LEFT);
		$path = "gfx/avatars/";
	}
	elseif ($avtype == -1) $ext = ".jpg";
	elseif ($avtype == -2) $ext = ".png";

	return $path . $name . $ext;
}

function makeindex($page,$jump,$upperlimit,$url)
{
	//make list of links to jump pages.
	$str = "";
	
	$lower = $page - $jump;
	$upper = $page + $jump;
	$lowjump = true;
	$upjump = true;
	
	if ($lower < 1)
	{
		$upper += (0 - $lower);
		$lower = 1;
	}
	if ($lower == 1) $lowjump = false;
	if ($upper >= $upperlimit)
	{
		$upper = $upperlimit;
		$upjump = false;
	}
	
	if ($lowjump) $str .= '<a href="' . $url . '1">&lt;&lt;</a>' . "\n";
	$current = $lower;
	while ($current <= $upper)
	{
		$pageno = $current;
		if ($current == $page) $pageno = "[$current]";
		if (($current == $lower) && ($lowjump)) $str .= "<span>...</span> ";
		$str .= '<a href="' . $url . $current . '">' . $pageno . '</a>';
		if (($current == $upper) && ($upjump)) $str .= " <span>...</span>";
		$str .= "\n";
		$current += 1;
	}
	if ($upjump) $str .= '<a href="' . $url . 'last">&gt;&gt;</a>' . "\n";
	
	return $str;
}

//postname is the name of the _post variable
function uploadcheck($postname,$filetype,$multiplier=1)
{
	$upload = true;
	
	$ferror = $_FILES[$postname]['error'];
	$fname = $_FILES[$postname]['name'];
	$ftype = $_FILES[$postname]['type'];
	$fsize = $_FILES[$postname]['size'];
	$ftmp = $_FILES[$postname]['tmp_name'];
	if (!$fname || !isset($fname) || $fname == "") $upload = false;
	if ($ferror > 0) $upload = false;
	if (!is_uploaded_file($ftmp)) $upload = false;
	if ($filetype == "archive")
	{
		if (!eregi('.rar',substr($fname,-4)) && !eregi('.zip',substr($fname,-4))) $upload = false;
	}
	elseif ($filetype == "image")
	{
		if (!eregi('.jpg',substr($fname,-4)) && !eregi('.png',substr($fname,-4))) $upload = false;
	}
	if ($fsize > (1048576*$multiplier)) $upload = false;
	
	return $upload;
}

function fail($error="unspecified",$link="index.php",$inctop=false)
{
	if ($inctop)
	{
		include 'header.php';
		include 'sidebar.php';
	}
	$problem = $error;
	$back = $link;
	include 'failure.php';
	include 'bottom.php';
	exit;
}

//replace with access level SQL table.
function axslvl($n) {
$n=trim($n);
if ($n<0)
$l="Banned";
if ($n=="0")
$l="User";
elseif ($n=="1")
$l="Member";
elseif ($n=="2")
$l="Advanced Member";
elseif ($n=="3")
$l="Content Contributor";
elseif ($n=="4")
$l="Moderator";
elseif ($n=="5")
$l="Admin";
else
$l="Godlike Admin";
return $l;
}

function banned($userID) {
	$bansrch=mysql_real_escape_string(trim($userID));
	$banq = mysql_query("SELECT * FROM bans WHERE userID = '$bansrch' ORDER BY banID DESC LIMIT 1");
	$banned = false;
	if (mysql_num_rows($banq) > 0)
	{
		$banr = mysql_fetch_array($banq);
		$time = $banr['time'];
		if ($time < 0) $banned = true;
		elseif ($time > gmt("U")) $banned = true;
	}
	return $banned;
}

function ipbanned($ip) {
	$bansrch=mysql_real_escape_string($ip);
	$banq = mysql_query("SELECT * FROM bans WHERE IP = '$bansrch' ORDER BY banID DESC LIMIT 1");
	$banned = false;
	if (mysql_num_rows($banq) > 0)
	{
		$banr = mysql_fetch_array($banq);
		$time = $banr['time'];
		if ($time < 0) $banned = true;
		elseif ($time > gmt("U")) $banned = true;
	}
	return $banned;
}

function strclean($s) {
  // cleans strings up - removes excess spaces and newlines.
  $s = trim($s);
  if (get_magic_quotes_gpc()) $s = stripslashes($s);
   
  $s = str_replace("\t",' ',$s);
  $s = str_replace("\n",' ',$s);
  $s = str_replace("\r",' ',$s);
  
  $slen = strlen($s);
  $nstr = '';
  for ($i=0;$i<$slen;$i++) {
    $c = substr($s,$i,1);
    if ($c == ' ') $spc++; else $spc = 0;
    if ($spc <= 1) $nstr .= $c;
  }
  return $nstr;
}

function numex($c) {
$a=trim($c);
$a=$a%10;
if ($c=="11" or $c=="12" or $c=="13")
$b="th";
elseif ($a=="1")
$b="st";
elseif ($a=="2")
$b="nd";
elseif ($a=="3")
$b="rd";
else
$b="th";
return $b;
}

function gmt($str = "U") {
	$thenow = getztime(0);
	return date($str,$thenow);
}

function timezone($stamp,$zone = 0, $str = "U") {
	$thenow = $stamp + ($zone * 3600);
	return date($str,$thenow);
}

function linesplitter($str, $num) {
	//LINE SPLITTER
	$num-=1;
	$strlng = strlen($str);
	$nospc = 0;
	$ishtml = false;
	$istag = false;
	for($j=0; $j<$strlng; $j++) {
		if (substr($str,$j,1) == '<') {
			$ishtml = true;
			if (substr($str,$j,6) == '<br />') $nospc = 0;
		}
		if (substr($str,$j,1) == '&') $istag = true;
		if (!$ishtml && !$istag) {
			if ($nospc > $num) {
				$str = substr($str,0,$j).'<br />'.substr($str,$j);
				$strlng += 4;
				$j += 4;
				$nospc = 0;
			}
			if (substr($str,$j,1) != ' ' && substr($str,$j,1) != "\n") $nospc++;
			else $nospc = 0;
		}
		//if (substr($str,$j,1) == '<') $ishtml = true;
		//elseif (substr($str,$j,1) == '>') $ishtml = false;
		if (substr($str,$j,1) == '>') $ishtml = false;
		if (substr($str,$j,1) == ';' && $istag == true )
		{
			$istag = false;
			$nospc++;
			if ($nospc > $num) {
				$j += 1;
				$str = substr($str,0,$j).'<br />'.substr($str,$j);
				$strlng += 4;
				$j += 4;
				$nospc = 0;
			}
		}
	}

	return $str;
	
	// END LINE SPLITTER
}

function comment_format($str) {

	// Superior arrays! Don't know why they're superior, they just are.
	$bbcode = array(
		'#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si',
		'#\[url=([^[" ]+)\](.+?)\[/url\]#si',
		"#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",
		"#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",
		"#\[email=([^[]+)\](.+?)\[/email\]#si",
		"#\[email\]([^]\"\n ]+?)\[/email\]#si",
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[s\](.+?)\[/s\]#si'	
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="http://\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<a href="mailto:\\1">\\2</a>',
		'<a href="mailto:\\1">\\1</a>',
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>'
	);
	
	$str = preg_replace($bbcode, $html, $str);
	
	$str = linesplitter($str, 49);
	$str = str_replace("\n",'<br />'."\n",$str);
	
	return $str;
}

function news_format($str) {

	// Superior arrays! Don't know why they're superior, they just are.
	$bbcode = array(
		'#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si',
		'#\[url=([^[" ]+)\](.+?)\[/url\]#si',
		"#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",
		"#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",
		"#\[email=([^[]+)\](.+?)\[/email\]#si",
		"#\[email\]([^]\"\n ]+?)\[/email\]#si",
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[s\](.+?)\[/s\]#si',
		'#\[img\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/img\\]#si'
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="http://\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<a href="mailto:\\1">\\2</a>',
		'<a href="mailto:\\1">\\1</a>',
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>',
		"<img src=\"\\1\" alt=\"News Image\" />"
	);
	
	$str = preg_replace($bbcode, $html, $str);
	
	$str = linesplitter($str, 49);
	$str = str_replace("\n",'<br />'."\n",$str);
	
	return $str;
}

?>
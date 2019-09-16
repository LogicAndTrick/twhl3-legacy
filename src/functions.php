<?php

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

function resizexy($width, $height, $maxwidth, $maxheight)
{
	$max_width = $maxwidth;
	$max_height = $maxheight;
	
	$x_ratio = $max_width / $width;
	$y_ratio = $max_height / $height;
	
	$tn_width = $width;
	$tn_height = $height;

	if( ($width <= $max_width) && ($height <= $max_height) )
	{
		$tn_width = $width;
		$tn_height = $height;
	}
	elseif (($width > $max_width) && ($height <= $max_height))
	{
		$tn_height = ceil(($x_ratio) * $height);
		$tn_width = $max_width;
	}
	elseif (($width <= $max_width) && ($height > $max_height))
	{
		$tn_height = $max_height;
		$tn_width = ceil(($y_ratio) * $width);
	}
	else
	{
		if ($y_ratio>$x_ratio)
		{
			$tn_height = ceil(($x_ratio) * $height);
			$tn_width = $max_width;
		}
		else
		{
			$tn_height = $max_height;
			$tn_width = ceil(($y_ratio) * $width);
		}
	}
	return array($tn_width,$tn_height);
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

// requires connection to mysql
// pagevar is the GET variable with the page in it
// entcount is the number of entries per page
// countq is the query of all entries
// jump and url: see above makeindex
function generateindex($pagevar,$entcount,$countq,$jump,$url)
{
	$startat = 0;
	$page = 1;
	
	$numents = mysql_num_rows(mysql_query($countq));
	$lastpage = ceil($numents/$entcount);
	
	$getpage = $_GET[$pagevar];
	
	if ($getpage == "last") $page = $lastpage;
	elseif (!isset($getpage) || $getpage < 1 || $getpage == "" || !is_numeric($getpage)) $page = 1;
	elseif (($getpage-1)*$entcount > $numents) $page = 1;
	else $page = $getpage;
	
	$startat = ($page-1)*$entcount;
	
	return array(makeindex($page,$jump,$lastpage,$url),$startat,$page);
}

//QUERY MUST BE SORTED BY ALPHA
//url is the link without the id on the end
// abc is the name of the column that is sorted by alpha
// idc is the id column.
function tri_column($qry,$url,$abc,$idc)
{
	$numrows = mysql_num_rows($qry);
	if ($numrows == 0) return false;
	
	$numcols = 3;
	if ($numrows < 12) $numcols = 2;
	elseif ($numrows < 6) $numcols = 1;
	
	$column12 = ceil($numrows/$numcols);
	$count = 0;
	$new = true;
	$currentletter = "";
	
	$tct = "";
	$tct .= '<table class="entityguide-index">'."\n";
	$tct .= '<tr valign="top">'."\n";
	
	while ($tcr = mysql_fetch_array($qry))
	{
		$name = $tcr[$abc];
		$letter = strtoupper(substr($name,0,1));
		$id = $tcr[$idc];
		
		if ($new)
		{
			$tct .= "<td>"."\n";
			if ($currentletter != $letter || $currentletter == "") $tct .= "<h3>$letter</h3>"."\n";
			else $tct .= "<h3>$letter (cont.)</h3>"."\n";
			$tct .= "<ul>"."\n";
		}
		else
		{
			if ($currentletter != $letter || $currentletter == "")
			{
				$tct .= "</ul>"."\n"."<h3>$letter</h3>"."\n"."<ul>"."\n";
			}
		}
		
		if ($currentletter != $letter || $currentletter == "") $currentletter = $letter;
		
		$tct .= '<li><a href="'.$url.$id.'">'.$name.'</a></li>'."\n";
		
		$count++;
		$new = false;
		
		if ($count >= $column12)
		{
			$new = true;
			$tct .= "</ul>"."\n";
			$tct .= "</td>"."\n";
			$count = 0;
		}
	}
	if ($count < $column12)
	{
		$new = true;
		$tct .= "</ul>"."\n";
		$tct .= "</td>"."\n";
		$count = 0;
	}
	
	$tct .= "</tr>"."\n";
	$tct .= "</table>";
	
	return $tct;
}

// arr must be a 3-element array with values 0-255
function arraytohexcolour($arr)
{
	$red = str_pad(base_convert(round($arr[0]),10,16), 2, "0", STR_PAD_LEFT);
	$blue = str_pad(base_convert(round($arr[1]),10,16), 2, "0", STR_PAD_LEFT);
	$green = str_pad(base_convert(round($arr[2]),10,16), 2, "0", STR_PAD_LEFT);
	return "#".$red.$blue.$green;
}

// val is a number between 1 and 100
// c1-3 are arrays featuring the RGB values of three colours. default is red-orange-green
// c1: val = 0, c2: val = 50, c3: val = 100
function tri_colour($val,$c1 = Array(255,0,0),$c2 = Array(255,138,0),$c3 = Array(0,150,0))
{
	if ($val == 0) return arraytohexcolour($c1);
	if ($val == 50) return arraytohexcolour($c2);
	if ($val == 100) return arraytohexcolour($c3);
	
	$resr = 0;
	$resg = 0;
	$resb = 0;
	
	if ($val < 50)
	{
		$first = $c2;
		$second = $c1;
		$val1 = $val;
	}
	else
	{
		$first = $c3;
		$second = $c2;
		$val1 = $val - 50;
	}
	
	$resr = (($first[0]*$val1)+($second[0]*(50-$val1)))/50;
	$resg = (($first[1]*$val1)+($second[1]*(50-$val1)))/50;
	$resb = (($first[2]*$val1)+($second[2]*(50-$val1)))/50;
	
	$res = Array($resr,$resg,$resb);
	return arraytohexcolour($res);
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

// returns the WHERE part of an SQL query to search for str.
// uses google-like formatting: "exact phrase" +must_include -must_not_include and the rest is a substring search.
// assumes str is NOT escaped
// must be connected to the database
function search_format($str,$field)
{
	$qwhere = "";
	$pwhere = "";
	$mwhere = "";
	$rwhere = "";
	$str = trim($str);
	
	// first get quotes
	preg_match_all('/"(.+?)"/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/"(.+?)"/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$qwhere .= "$field LIKE '%$matchstr%' OR ";
	}
	$qwhere = substr($qwhere, 0, -4);
	$str = str_ireplace('"','',$str);
	
	//next get +
	preg_match_all('/\+([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/\+([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$pwhere .= "$field LIKE '% $matchstr %' AND ";
	}
	$pwhere = substr($pwhere, 0, -5);
	$str = str_ireplace('+','',$str);
	
	//then get -
	preg_match_all('/-([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/-([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$mwhere .= "$field NOT LIKE '% $matchstr %' AND ";
	}
	$mwhere = substr($mwhere, 0, -5);
	$str = str_ireplace('-','',$str);
	
	//then the rest of it
	$strs = explode(" ",$str);
	foreach ($strs as $tok)
	{
		$tok = htmlfilter($tok);
		if ($tok != "") $rwhere .= "$field LIKE '%$tok%' OR ";
	}
	$rwhere = substr($rwhere, 0, -4);
	
	$compsto = "$field != ".'""';
	if ($qwhere != "" && $rwhere != "")
	{
		$compsto = "($qwhere OR $rwhere)";
	}
	elseif ($qwhere != "")
	{
		$compsto = "($qwhere)";
	}
	elseif ($rwhere != "")
	{
		$compsto = "($rwhere)";
	}
	
	$compsta = "$field != ".'""';
	if ($pwhere != "" && $mwhere != "")
	{
		$compsta = "($pwhere AND $mwhere)";
	}
	elseif ($pwhere != "")
	{
		$compsta = "($pwhere)";
	}
	elseif ($mwhere != "")
	{
		$compsta = "($mwhere)";
	}
	
	$compst = $compsto . " AND " . $compsta;
	
	//return $qwhere . " AND " . $pwhere . " AND " . $mwhere . " AND " . $rwhere;
	return $compst;
}

// arr is an array of all the fields to search in
// str, same as above.
// returns the WHERE part of an sql query to search for str in all fields
function search_all($str,$arr)
{
	$where = "";
	foreach ($arr as $field)
	{
		if ($field != "") $where .= "(" . search_format($str,$field) . ") OR ";
	}
	$where = substr($where, 0, -4);
	return $where;
}

function search_advanced($str,$arr)
{
	// first get quotes
	preg_match_all('/"(.+?)"/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/"(.+?)"/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$qwhere .= "$field LIKE '%$matchstr%' OR ";
	}
	$qwhere = substr($qwhere, 0, -4);
	$str = str_ireplace('"','',$str);
	
	//next get +
	preg_match_all('/\+([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/\+([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$pwhere .= "$field LIKE '% $matchstr %' AND ";
	}
	$pwhere = substr($pwhere, 0, -5);
	$str = str_ireplace('+','',$str);
	
	//then get -
	preg_match_all('/-([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/-([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$mwhere .= "$field NOT LIKE '% $matchstr %' AND ";
	}
	$mwhere = substr($mwhere, 0, -5);
	$str = str_ireplace('-','',$str);
	
	//then the rest of it
	$strs = explode(" ",$str);
	foreach ($strs as $tok)
	{
		$tok = htmlfilter($tok);
		if ($tok != "") $rwhere .= "$field LIKE '%$tok%' OR ";
	}
	$rwhere = substr($rwhere, 0, -4);
	
	$compsto = "$field != ".'""';
	if ($qwhere != "" && $rwhere != "")
	{
		$compsto = "($qwhere OR $rwhere)";
	}
	elseif ($qwhere != "")
	{
		$compsto = "($qwhere)";
	}
	elseif ($rwhere != "")
	{
		$compsto = "($rwhere)";
	}
	
	$compsta = "$field != ".'""';
	if ($pwhere != "" && $mwhere != "")
	{
		$compsta = "($pwhere AND $mwhere)";
	}
	elseif ($pwhere != "")
	{
		$compsta = "($pwhere)";
	}
	elseif ($mwhere != "")
	{
		$compsta = "($mwhere)";
	}
	
	$compst = $compsto . " AND " . $compsta;
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

function naxslvl($nm) {

$nm =mysql_real_escape_string($nm);
$query = "SELECT * FROM users WHERE uid = '$nm'";
$result = mysql_query($query) or die("Unable to verify user because : " . mysql_error());

$lv="News poster";

if(mysql_num_rows($result) == 1)

if(mysql_num_rows($result)==1)
  {
  $row = mysql_fetch_array($result);
  $lv = axslvl($row['lvl']);
  }

return $lv;
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

function forumprocess($txt,$charnum,$max=500,$images=1) {

$ntext=$txt;
$link="";
$numrez=0;
if ($images==1)
{
preg_match_all('%\\[img\\](.+?)?\\[/img\\]%si', $txt, $result, PREG_PATTERN_ORDER);
ini_set('allow_url_fopen', 'on');
for ($i = 0; $i < count($result[0]); $i++) {
	$match=$result[0][$i];
	$match=str_replace(' ','%20',$match);
	//$match=str_replace('[img]', '',str_replace('[/img]', '',$match));
	$replace='<img src="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '" alt="user posted image, aparrently nonexistant" />';
	
	if ($blah = @getimagesize(str_replace('[img]', '',str_replace('[/img]', '',$match))))
	{
	$type = $blah['mime'];
	$width = $blah[0];
	$height = $blah[1];

	$file = str_replace('[img]', '',str_replace('[/img]', '',$match));
	$ext = @substr($file, (@strrpos($file, ".") ? @strrpos($file, ".") + 1 : @strlen($file)), @strlen($file));
	$fname = basename($file,$ext);
		
	$replace='<img src="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '" alt="user posted image" />';
		
		if ($width>$max or $height>$max)
		{
			//$numrez+=1;		
			//$link.='<br />Image Resized: <a href="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '">' . $fname . $ext . '</a>
			//		';
			$replace='<img src="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '" ' . sizelimit2($width,$height,$max) . ' "alt="user posted image" /><br />
						<a href="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '">See full image</a><br /><br />';
		}
		/*elseif (($width>500 or $height>500) and ($type=='image/gif'))
		{
			$numrez+=1;
			$replace='<b>SNIP</b>';
			$link.='<br />Image Snipped (GIF resizing not supported): <a href="' . str_replace('[img]', '',str_replace('[/img]', '',$match)) . '">' . $fname . $ext . '</a>
					';
		}*/
	}
	
	
	$ntext=str_replace($match, $replace ,$ntext);
}
}
else
{
preg_match_all('%\\[img\\](.+?)?\\[/img\\]%si', $txt, $result, PREG_PATTERN_ORDER);
ini_set('allow_url_fopen', 'on');
	for ($i = 0; $i < count($result[0]); $i++) {
		$match=$result[0][$i];
		$match=str_replace(' ','%20',$match);
		//$match=str_replace('[img]', '',str_replace('[/img]', '',$match));
		$replace="Image: " . str_replace('[img]', '',str_replace('[/img]', '',$match)) . "<br />";
		$ntext=str_replace($match, $replace ,$ntext);
	}
}


$bbcode = array(
//'%\\[img\\](.+?)?\\[/img\\]%si',
'%\\[url]http://(.+?)\\[\\/url\\]%i',
'%\\[url=http://([^][]+)?\\]([^][]+)?\\[/url\\]%i',
'%\\[b\\](.+?)\\[/b\\]%im',
'%\\[u\\](.+?)\\[/u\\]%im',
'%\\[i\\](.+?)\\[/i\\]%im',
'%\\[pre\\](.+?)\\[/pre\\]%im',
'#\\[email\\]\\b([A-Z0-9._%-]+@[A-Z0-9.-]+\\.[A-Z]{1,10})\\b\\[/email\\]#im',
'#\\[email=([A-Z0-9._%-]+@[A-Z0-9.-]+\\.[A-Z]{1,10})\\](.+?)\\[/email\\]#im'
);

$html = array(
//'<img src="$1" alt="user posted image" />',
'<a href="http://$1">http://$1</a> ',
'<a href="http://$1">$2</a> ',
'<b>$1</b>',
'<u>$1</u>',
'<i>$1</i>',
'<pre>$1</pre>',
'<a href="mailto:$1">$1</a>',
'<a href="mailto:$1">$2</a>'
);

$ntext=preg_replace($bbcode, $html, $ntext);



$lol=0;
while ($lol<20)
{	
	if (stristr($ntext,"[quote]")==false or stristr($ntext,"[/quote]")==false) break;
	$ntext = preg_replace('%\\[quote\\]([^[]+?)\\[/quote\\]%', '<b>Quote:</b><blockquote>$1</blockquote>', $ntext);
	$lol+=1;
}
$ntext = preg_replace('%(<b>Quote:</b>(.+)</blockquote>)%si', '<div id="forumquote">$1<br /></div>', $ntext);
//$ntext = preg_replace('%(</?[a-z][a-z0-9]*[^<>]*>)|([^<\\s>]{' . $charnum . '})%i', '$1$2 ', $ntext);
/*$ntext = preg_replace('/(?:<.+?>)|([^<>\\s]{' . $charnum . ',}?)/i', '\\0<br />', $ntext);*/

/*$lol=0;
while ($lol<20) {
	if (preg_match('/(<.+?>+)|(?P<long>[^<>\\s]{40})/i', $ntext, $regs) and isset($regs['long']) and $regs['long']!="") {
	$ntext=str_replace(substr($regs['long'],0,39),substr($regs['long'],0,39) . "<br />",$ntext); }
	$lol+=1;
}*/

$start=0;
$long=0;
$go=1;
$quot=0;
$len=$charnum;

while ($start<strlen($ntext)) 
{
	$char1=substr($ntext,$start,1);
	if ($char1=="<")
	{
		if (substr($ntext,$start,12)=="<blockquote>") $len=$charnum-10;
		if (substr($ntext,$start,13)=="</blockquote>") $len=$charnum;
	}
	if ($char1=="<")
	{
		$long=0;
		$go=0;
	}
	elseif ($char1==">")
	{
		$long=0;
		$go=1;
	}
	elseif ($go==1)
	{
		if (preg_match('/[^<>\\s]/i', $char1))
		{
			$long+=1;
		}
		else
		{
			$long=0;
		}
	}
	if ($long==$len+1)
	{
		$long=0;
		$ntext=str_replace(substr($ntext,0,$start),substr($ntext,0,$start) . "<br />",$ntext);
		$start+=5;
	}
$start+=1;
}

return $ntext;
}

function latestforumprocess($txt,$charnum) {

$bbcode = array(
'%\\[url]http://(.+?)\\[\\/url\\]%i',
'%\\[url=http://([^][]+)?\\]([^][]+)?\\[/url\\]%i',
'%\\[b\\](.+?)\\[/b\\]%im',
'%\\[u\\](.+?)\\[/u\\]%im',
'%\\[i\\](.+?)\\[/i\\]%im',
'%\\[pre\\](.+?)\\[/pre\\]%im',
'#\\[email\\]\\b([A-Z0-9._%-]+@[A-Z0-9.-]+\\.[A-Z]{1,10})\\b\\[/email\\]#im',
'#\\[email=([A-Z0-9._%-]+@[A-Z0-9.-]+\\.[A-Z]{1,10})\\](.+?)\\[/email\\]#im'
);

$html = array(
'<a href="http://$1">http://$1</a> ',
'<a href="http://$1">$2</a> ',
'<b>$1</b>',
'<u>$1</u>',
'<i>$1</i>',
'<pre>$1</pre>',
'<a href="mailto:$1">$1</a>',
'<a href="mailto:$1">$2</a>'
);

$ntext=preg_replace($bbcode, $html, $txt);


$start=0;
$long=0;
$go=1;
$quot=0;
$len=$charnum;

while ($start<strlen($ntext)) 
{
	$char1=substr($ntext,$start,1);
	if ($char1=="<")
	{
		if (substr($ntext,$start,12)=="<blockquote>") $len=$charnum-10;
		if (substr($ntext,$start,13)=="</blockquote>") $len=$charnum;
	}
	if ($char1=="<")
	{
		$long=0;
		$go=0;
	}
	elseif ($char1==">")
	{
		$long=0;
		$go=1;
	}
	elseif ($go==1)
	{
		if (preg_match('/[^<>\\s]/i', $char1))
		{
			$long+=1;
		}
		else
		{
			$long=0;
		}
	}
	if ($long==$len+1)
	{
		$long=0;
		$ntext=str_replace(substr($ntext,0,$start),substr($ntext,0,$start) . "<br />",$ntext);
		$start+=5;
	}
$start+=1;
}


//substr($ptext,0,200)

return $ntext;
}

function revforumprocess($txt) {

$ntext=$txt;

$ntext = preg_replace('%<span class="resize">.+?</span>%si', '', $ntext);

$ntext = $nstr=str_replace('<b>Quote:</b><blockquote>', "[quote]",$ntext);
$ntext = $nstr=str_replace('</blockquote>', "[/quote]",$ntext);
$ntext = $nstr=str_replace('<div id="forumquote">', "",$ntext);
$ntext = $nstr=str_replace('<br /></div>', "",$ntext);
$ntext = $nstr=str_replace('</div>', "",$ntext);

$html = array(
'%<a href="mailto:(.+?)?">\\1</a>%i',
'%<a href="mailto:(.+?)?">(.+?)?</a>%i',
'%<img src="(.+?)?" (.+?)? />%si',
'%<a href="(.+?)?">\\1</a>%i',
'%<a href="(.+?)?">(.+?)?</a>%i',
'%<b>(.+?)?</b>%i',
'%<u>(.+?)?</u>%i',
'%<i>(.+?)?</i>%i',
'%<pre>(.+?)?</pre>%i'
);

$bbcode = array(
'[email]$1[/email]',
'[email=$1]$2[/email]',
'[img]$1[/img]',
'[url]$1[/url]',
'[url=$1]$2[/url]',
'[b]$1[/b]',
'[u]$1[/u]',
'[i]$1[/i]',
'[pre]$1[/pre]'
);

$ntext=preg_replace($html, $bbcode, $ntext);

return $ntext;
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

function shoutprocess($txt,$usrlvl = 0) {


	$str=$txt;
	//STOLEN FROM TWHL
	// Look for: http://x.x or www.x.x
	$str = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', '<a href="\0"><b>link</b></a>',$str);
	//$str = preg_replace('/\bwww\.[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', '[url]\0[/url]',$str);
	// Email replacer. More advanced than above thing... but then, e-mail addresses are simpler.
	$str = eregi_replace('([[:space:]]|^)(([a-z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-z0-9\-]+\.)+))([a-z]{2,4}|[0-9]{1,3})(\]?))','\\1[email]\\2[/email]',$str);
	
	//$str = preg_replace('%\[url\](.+?)\[/url\]%i', '<a href="\1"><b>link</b></a>',$str);
	$str = eregi_replace('\[email\]([^] ]+)\[/email\]','<a href="mailto:\\1">\\1</a>',$str);
	//END STOLEN FROM TWHL
	
	// formatting bbcode
	$bbcode = array(
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[s\](.+?)\[/s\]#si',
		'%\[font=(.+?)\](.+?)\[/font\]%si',
		'%\[size=(\d*)\](.*?)\[/size\]%si'
	);
	
	$html = array(
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>',
		'<span style="color: \1">\2</span>',
		'<span style="font-size: \1px;">\2</span>'
	);
	
	if ($usrlvl > 35) $str=preg_replace($bbcode, $html, $str);

	$ntext = linesplitter($str,13);

	return $ntext;
}

// TEH funxion
function post_format($str, $simple = false, $level = 0) {

//$str=str_replace("&","&amp;",$str);
//$str=htmlfilter($str);

  // Create arrays of smilies and their corresponding images
	static $smilies, $smilies_a;
	if (!isset($smilies)) {
		$srs = mysql_query('SELECT emot_file, emot_code, emot_alt FROM twhl_emoticons',$GLOBALS['dbh']) or die('Database error');
		if (mysql_num_rows($srs) > 0) {
			while ($srow2 = mysql_fetch_array($srs)) {
				foreach (explode(',,',$srow2['emot_code']) as $val) {
					$smilies_a[] = "#( |^|\n)".preg_quote($val,'#').'#';
					$smilies[] = '\\1<img src="newforum/smilies/'.$srow2['emot_file'].'" alt="'.$srow2['emot_alt'].' - '.$val.'" />';
				}
			}
		}
		else {
			$smilies[] = ''; // dummy value so it's set and doesn't try again
		}
	}

  // autolinking (from c/box)
  //$str = eregi_replace('([[:space:]]|^)(http://|ftp://)([a-z0-9.-]+\.[a-z0-9-]+[][a-z0-9.?&%!@#$^*()+=/\,_-]*[^.,?! ])','\\1[url]\\2\\3[/url]',$str);
  //$str = eregi_replace('([[:space:]]|^)(www\.[a-z0-9.-]+\.[a-z0-9-]+[][a-z0-9.?&%!@#$^*()+=/\,_-]*[^.,?! ])','\\1[url]\\2[/url]',$str);
  //$str = eregi_replace('([[:space:]]|^)(([a-z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-z0-9\-]+\.)+))([a-z]{2,4}|[0-9]{1,3})(\]?))','\\1[email]\\2[/email]',$str);

  //if (!isset($_SESSION['forumimages']) || $_SESSION['forumimages']) { 
  //Depreciated
	if (!$simple) {
		$str = preg_replace('#\[url=([a-z]{3,6}://[^[" ]+)\\]\\[img\\]([a-z]{3,6}://[^\\"\\n\\r]+?)\\[/img\\]\\[/url\\]#si',"<div class=\"image\"><a href=\"\\1\"><img src=\"\\2\" alt=\"User Posted Image\" /></a><br /><a href=\"\\2\" rel=\"external\">[Open in new window]</a></div>",$str);   
		$str = preg_replace('#\[img\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/img\\]#si',"<div class=\"image\"><img src=\"\\1\" alt=\"User Posted Image\" /><br /><a href=\"\\1\" rel=\"external\">[Open in new window]</a></div>",$str);         
		$str = preg_replace('#\[simg\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/simg\\]#si',"<span class=\"simage\"><img src=\"\\1\" alt=\"User Posted Image\" /></span>",$str);  
	}
  //}

	// bbcode
	/* DEPRECIATED LAWL!
	$str = preg_replace('#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si','<a href="\\1\\2" target="_blank">\\3</a>',$str);    
	$str = preg_replace('#\[url=([^[" ]+)\](.+?)\[/url\]#si','<a href="http://\\1" target="_blank">\\2</a>',$str);
	$str = preg_replace("#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",'<a href="\\1\\2" target="_blank">\\1\\2</a>',$str);      
	$str = preg_replace("#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",'<a href="http://\\1" target="_blank">\\1</a>',$str);      
	$str = preg_replace("#\[email=([^[]+)\](.+?)\[/email\]#si",'<a href="mailto:\\1">\\2</a>',$str);
	$str = preg_replace("#\[email\]([^]\"\n ]+?)\[/email\]#si",'<a href="mailto:\\1">\\1</a>',$str);   
	*/

	// Superior arrays! Don't know why they're superior, they just are.
	// linking bbcode
	$bbcode = array(
		'#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si',
		'#\[url=([^[" ]+)\](.+?)\[/url\]#si',
		"#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",
		"#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",
		"#\[email=([^[]+)\](.+?)\[/email\]#si",
		"#\[email\]([^]\"\n ]+?)\[/email\]#si"
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="http://\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<a href="mailto:\\1">\\2</a>',
		'<a href="mailto:\\1">\\1</a>'
	);
	
	$str=preg_replace($bbcode, $html, $str);

	$str = " " . $str; // Hax to get whitespace before the autolink if it's the first thing in the post.
  
	// autolinking
	/* EVEN MORE DEPRECIATED LAWL!
	$str = preg_replace("#([]\n ])([a-z]{3,6})://([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^][\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"\\2://\\3.\\4\\5\" target=\"_blank\">\\2://\\3.\\4\\5</a>", $str);
	$str = preg_replace("#([]\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str);
	$str = preg_replace("#([]\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+[^][\".,?!;:\n\t ])#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $str);
	*/
  
	// autolinking
	$bbcode = array(
		"#([]\n ])([a-z]{3,6})://([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^][\t \n]*)?[^][\".,?!;:\n\t ])#i",
		"#([]\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t \n]*)?[^][\".,?!;:\n\t ])#i",
		"#([]\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+[^][\".,?!;:\n\t ])#i"
	);
	
	$html = array(
		"\\1<a href=\"\\2://\\3.\\4\\5\">\\2://\\3.\\4\\5</a>",
		"\\1<a href=\"http://www.\\2.\\3\\4\">www.\\2.\\3\\4</a>",
		"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"
	);
	
	$str=preg_replace($bbcode, $html, $str);

	$str = substr($str, 1); // Fix the whitespace autolinking hax.
	
	/* TOTALLY MEGA DEPRECIATED LAWL!
	$str = preg_replace('#\[b\](.+?)\[/b\]#si','<b>\\1</b>',$str);
	$str = preg_replace('#\[i\](.+?)\[/i\]#si','<i>\\1</i>',$str);
	$str = preg_replace('#\[u\](.+?)\[/u\]#si','<u>\\1</u>',$str);
	$str = preg_replace('#\[s\](.+?)\[/s\]#si','<span style="text-decoration: line-through">\\1</span>',$str);
	$str = preg_replace('#\[pre\](.+?)\[/pre\]#si','<pre>\\1</pre>',$str);
	*/
  
	// formatting bbcode
	$bbcode = array(
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[s\](.+?)\[/s\]#si',
		'#\[pre\](.+?)\[/pre\]#si',
		'#\[green\](.+?)\[/green\]#si',
		'#\[blue\](.+?)\[/blue\]#si',
		'#\[purple\](.+?)\[/purple\]#si'
	);
	
	$html = array(
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>',
		'<span style="font-family: monospace">\\1</span>',
		'<span style="color: #009900">\\1</span>',
		'<span style="color: #0000ff">\\1</span>',
		'<span style="color: #990099">\\1</span>'
	);
	
	$str=preg_replace($bbcode, $html, $str);
  
  if ($level>4) {
	$str = preg_replace('#\[l\](.+?)\[/l\]#si','<span style="text-decoration: blink">\\1</span>',$str);
	$str = preg_replace('#\[m\](.+?)\[/m\]#si','<span style="color: #dd3333">\\1</span>',$str);
	if (!$simple) $str = preg_replace('/\\[fail\\]/','<img src="gfx/failtruck.jpg" alt="Epic Fail" />',$str);
	else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
  }
  else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
  
  //$str = eregi_replace('\[quote\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>Quote: </B><br />\\1</TD></TR></TABLE>',$str);
  //$str = eregi_replace('\[quote=([^][]+)\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>\\1 said: </B><br />\\2</TD></TR></TABLE>',$str);

  
  if (!$simple) {
  // BEGIN QUOTE PARSER (note: NOT case-insensitive)
  
  
  // NEW QUOTES
  /*
  
<div class="quote-container">
	<div class="quote">
		<strong>Quote</strong> <br>
		lol test
		<div class="quote">
			<strong>Quote</strong> <br>
			lol test again?
		</div>
	</div>
</div>
  
  */
 
  if (strpos($str,'[quote]') !== false) {

  $quote_open = array();
  $quote_open_level = array();
  $quote_ok = array();
  $quote_close = array();
  $quote_close_level = array();
  $j = -1;
  $k = -1;
  $len = strlen($str);
  $q1 = '<div class="quote"><strong>Quote:</strong><br />';
  $q2 = '</div>';
  // find opening and closing quote tags
  for ($i=0;$i<$len;$i++) {
    if (substr($str, $i, 7) == '[quote]') {
      $j++;
      $k++;
      $quote_open[$j] = $i;
      $quote_open_level[$j] = $k;
    }
    if (substr($str, $i, 8) == '[/quote]') {
      $j++;
      $quote_close[$j] = $i;
      $quote_close_level[$j] = $k;
      $k--;
    }
  }
  // loop through all opening tags, finding matching closing ones, creating pairs
  foreach ($quote_open as $i => $openpos) {
    foreach ($quote_close as $j => $closepos) {
      if ($j > $i) {		// skip forward to after the opening tag. bit cheap.
        if ($quote_close_level[$j] == $quote_open_level[$i]) {
          $quote_ok[$i] = $j;
          break;
        }
      }
    }
  }
   
  // go though all pairs, adding the HTML
  foreach ($quote_ok as $opentag => $closetag) {
    $str = substr($str, 0, $quote_open[$opentag]).$q1.substr($str, $quote_open[$opentag]);
    // work out new offsets. this is a little inefficient
    for ($i=$opentag;$i<(count($quote_open)+count($quote_close));$i++) {
      if ($i > $closetag) $ct = strlen($q2); else $ct = 0;
      if (isset($quote_open[$i])) $quote_open[$i] += strlen($q1) + $ct;
      if (isset($quote_close[$i])) $quote_close[$i] += strlen($q1) + $ct;
    }
    $str = substr($str, 0, $quote_close[$closetag]+$offset).$q2.substr($str, $quote_close[$closetag]+$offset);
  }

  $str = str_replace('[quote]','',$str);
  $str = str_replace('[/quote]','',$str);

  }
  
  //$str = preg_replace('%<div class="quote">(.*)</div>%s', '<div class="quote-container"><div class="quote">\\1</div></div>', $str);
  // END QUOTE PARSER
  }
  else
  {
	$str = str_replace('[quote]','',$str);
	$str = str_replace('[/quote]','',$str);
  }

  $str = linesplitter($str, 43);

  // unfortunately we have to check for both.
  if (strstr($str,':') !== false || strstr($str,';') !== false) {
    $str = preg_replace($smilies_a,$smilies,' '.$str.' ');
    $str = trim($str);
  }
  
  if ($simple) {
    $str = str_replace("\n", ' ', $str);
  }
  else {
    //$str = preg_replace('/(\\r\\n){2,}/sim', '<br /><br />', $str);
    //$str = str_replace("\n\n",'<br /><br />',$str);
    $str = str_replace("\n",'<br />',$str);
  }


  return $str;
}

function tutorial_format($str) {

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
		'#\[ins\](.+?)\[/ins\]#si',
		'#\[val\](.+?)\[/val\]#si',
		'#\[ent\](.+?)\[/ent\]#si',
		'#\[prop\](.+?)\[/prop\]#si',
		'#\[green\](.+?)\[/green\]#si',
		'#\[yellow\](.+?)\[/yellow\]#si',
		'#\[red\](.+?)\[/red\]#si',
		'#\[blue\](.+?)\[/blue\]#si',
		'#\[pre\](.+?)\[/pre\]#si',
		'#\[h\](.+?)\[/h\]#si',
		'#\[li\](.+?)\[/li\]#si',
		'#\[list\](.+?)\[/list\]#si',
		'#\[olist\](.+?)\[/olist\]#si'	
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<a href="mailto:\\1">\\2</a>',
		'<a href="mailto:\\1">\\1</a>',
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>',
		'<span style="color: #e97000">\\1</span>',
		'<span style="color: blue">\\1</span>',
		'<span style="color: purple">\\1</span>',
		'<span style="color: red">\\1</span>',
		'<span style="color: #009900">\\1</span>',
		'<span style="color: orange">\\1</span>',
		'<span style="color: red">\\1</span>',
		'<span style="color: blue">\\1</span>',
		'<span style="font-family: monospace;">\\1</span>',
		'</p><h3>\\1</h3>'."\n".'<p class="single-center-content">',
		'<li>\\1</li>',
		'</p><ul>\\1</ul><p class="single-center-content">',
		'</p><ol>\\1</ol><p class="single-center-content">'
	);
	
	$str = preg_replace($bbcode, $html, $str);
	
	$str = '<p class="single-center-content">' . "\n" . $str . '</p>';
	$str = str_ireplace("\n"."\n",'<br />'.'</p>'."\n".'<p class="single-center-content">',$str);
	$str = str_ireplace('<br>','<br />',$str);
	$str = str_ireplace("\n",'<br />'."\n",$str);
	//$str = str_replace("\r\n",'<br />'."\n",$str);
	$str = str_ireplace("<br /></p>","\n".'</p>',$str);
	$str = str_ireplace('<p class="single-center-content"><br />','<p class="single-center-content">',$str);
	$str = preg_replace('%<p class="single-center-content">[\\s]*?<br />%', '<p class="single-center-content">', $str);
	$str = preg_replace('%<ul>[\\s]*?<br />%si', '<ul>', $str);
	$str = preg_replace('%<ol>[\\s]*?<br />%si', '<ol>', $str);
	$str = preg_replace('%</li>[\\s]*?<br />%si', '</li>', $str);
	$str = str_ireplace('</p><br />','</p>',$str);
	$str = str_ireplace('</h3><br />','</h3>',$str);
	$str = str_ireplace("<br />"."\n"."</p>","\n".'</p>'."\n",$str);
	$str = str_ireplace("</p><h3>","</p>"."\n".'<h3>',$str);
	$str = str_ireplace('<p class="single-center-content"></p>','',$str);
	$str = str_ireplace('<p class="single-center-content"> </p>','',$str);
	$str = str_ireplace('<p class="single-center-content">'."\n".'</p>'."\n",'',$str);
	$str = str_ireplace('<p class="single-center-content"> '."\n".'</p>'."\n",'',$str);
	
	
	$str = preg_replace('#\[img=(.+?)\\](.+?)\\[/img\\]#si', "</p><p class=\"single-center-content-image\">"."\n"."<a href=\"javascript:showfull('http://twhl.info/tutpics/\\1')\"><img src=\"http://twhl.info/tutpics/\\1\" alt=\"Tutorial Image\" /></a>"."\n"."\\2"."\n"."</p>"."\n"."<p class=\"single-center-content\">", $str);
	
	$str = linesplitter($str, 54);
	
	return $str;
}

function tutorial_revision_split($str) {
	
	$split1[0] = 0;
	$split2[0] = strlen($str);
	$startpos = stripos($str,'<span class="green">',0);
	$endpos = stripos($str,'</span>',0);
	if ($startpos)
	{
		$counter = 1;
		$doing = true;
		while ($doing)
		{
			$startpos -= 100;
			$endpos +=100;
			if ($startpos < $split1[$counter-1]) $startpos = $split1[$counter-1];
			if ($endpos > $split2[$counter-1]) $endpos = $split2[$counter-1];
		}
	}
	
	return $str;
}

function tutorial_revision_format($str) {

	$str = str_replace("\n\n\n",'<br /><br />'."\n",$str);
	$str = str_replace("\n\n",'<br /><br />'."\n",$str);
	$str = str_replace("\n",' ',$str);
	
	tutorial_revision_split($str);
	
	return $str;
}

function bio_format($str) {

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
		'#\[ins\](.+?)\[/ins\]#si',
		'#\[val\](.+?)\[/val\]#si',
		'#\[ent\](.+?)\[/ent\]#si',
		'#\[prop\](.+?)\[/prop\]#si',
		'#\[green\](.+?)\[/green\]#si',
		'#\[yellow\](.+?)\[/yellow\]#si',
		'#\[red\](.+?)\[/red\]#si',
		'#\[pre\](.+?)\[/pre\]#si',
		'#\[h\](.+?)\[/h\]#si',
		'#\[li\](.+?)\[/li\]#si',
		'#\[list\](.+?)\[/list\]#si',
		'#\[olist\](.+?)\[/olist\]#si'	
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
		'<span style="color: orange">\\1</span>',
		'<span style="color: blue">\\1</span>',
		'<span style="color: purple">\\1</span>',
		'<span style="color: red">\\1</span>',
		'<span style="color: #009900">\\1</span>',
		'<span style="color: orange">\\1</span>',
		'<span style="color: red">\\1</span>',
		'<span style="font-family: monospace">\\1</span>',
		'</p><h3>\\1</h3>'."\n".'<p class="single-center-content">',
		'<li>\\1</li>',
		'</p><ul>\\1</ul><p class="single-center-content">',
		'</p><ol>\\1</ol><p class="single-center-content">'
	);
	
	$str = preg_replace('#\[url=([a-z]{3,6}://[^[" ]+)\\]\\[img\\]([a-z]{3,6}://[^\\"\\n\\r]+?)\\[/img\\]\\[/url\\]#si',"</p><p class=\"single-center-content-image\"><a href=\"\\1\"><img src=\"\\2\" alt=\"User Posted Image\" /></a><br /><a href=\"\\2\" rel=\"external\">[Open in new window]</a></p><p class=\"single-center-content\">",$str);   
	$str = preg_replace('#\[img\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/img\\]#si',"</p><p class=\"single-center-content-image\"><img src=\"\\1\" alt=\"User Posted Image\" /><br /><a href=\"\\1\" rel=\"external\">[Open in new window]</a></p><p class=\"single-center-content\">",$str);         
	$str = preg_replace('#\[simg\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/simg\\]#si',"<span class=\"simage\"><img src=\"\\1\" alt=\"User Posted Image\" /></span>",$str);  
	
	$str = preg_replace($bbcode, $html, $str);
	
	$str = '<p class="single-center-content">' . "\n" . $str . '</p>';
	$str = linesplitter($str, 54);
	$str = str_replace("\n"."\n",'<br />'.'</p>'."\n".'<p class="single-center-content">',$str);
	$str = str_replace("\n",'<br />'."\n",$str);
	//$str = str_replace("\r\n",'<br />'."\n",$str);
	$str = str_replace("<br /></p>","\n".'</p>',$str);
	$str = str_replace('<p class="single-center-content"><br />','<p class="single-center-content">',$str);
	$str = preg_replace('%<p class="single-center-content">[\\s]*?<br />%', '<p class="single-center-content">', $str);
	$str = preg_replace('%<ul>[\\s]*?<br />%si', '<ul>', $str);
	$str = preg_replace('%</li>[\\s]*?<br />%si', '</li>', $str);
	$str = str_replace('</p><br />','</p>',$str);
	$str = str_replace('</h3><br />','</h3>',$str);
	$str = str_replace("<br />"."\n"."</p>","\n".'</p>'."\n",$str);
	$str = str_replace("</p><h3>","</p>"."\n".'<h3>',$str);
	$str = str_replace('<p class="single-center-content"></p>','',$str);
	$str = str_replace('<p class="single-center-content"> </p>','',$str);
	$str = str_replace('<p class="single-center-content">'."\n".'</p>'."\n",'',$str);
	$str = str_replace('<p class="single-center-content"> '."\n".'</p>'."\n",'',$str);
	
	
	$str = preg_replace('#\[img=(.+?)\\](.+?)\\[/img\\]#si', "</p><p class=\"single-center-content-image\">"."\n"."<a href=\"javascript:showfull('http://twhl.info/tutpics/\\1')\"><img src=\"http://twhl.info/tutpics/\\1\" alt=\"Tutorial Image\" /></a>"."\n"."\\2"."\n"."</p>"."\n"."<p class=\"single-center-content\">", $str);
	
	return $str;
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
		"<img src=\"\\1\" alt=\"User Posted Image\" />"
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

function motm_format($str)
{
	$bbcode = array(
		'#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si',
		'#\[url=([^[" ]+)\](.+?)\[/url\]#si',
		"#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",
		"#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[sub\](.+?)\[/sub\]#si',
        '#\[youtube\](.+?)\[/youtube\]#si',
        '#\[yt\](.+?)\[/yt\]#si'
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="http://\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'</p><h3>\\1</h3><p class="single-center-content">',
		'</p><p class="single-center-content-center"><object type="application/x-shockwave-flash" data="\\1" width="425" height="355"><param name="movie" value="\\1" /></object></p><p class="single-center-content">',
		'</p><p class="single-center-content-center"><object style="width: 650px; height: 370px;" type="application/x-shockwave-flash" data="http://www.youtube.com/v/\1"><param name="movie" value="http://www.youtube.com/v/\1" /></object></p><p class="single-center-content">'
	);
	
	//'</p><p class="single-center-content-center"><object width="425" height="355"><param name="movie" value="\\1"></param><param name="wmode" value="transparent"></param><embed src="\\1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object></p><p class="single-center-content">'
	
	$str=preg_replace($bbcode, $html, $str);
	
$str = preg_replace('%\[img=(.+?)\](.+?)\[/img\]%', "</p>\r\n<p class=\"single-center-content-image\">\r\n<img src=\"\1\" alt=\"MOTM Image\" />\r\n\2\r\n</p>\r\n<p class=\"single-center-content\">", $str);

	return $str;
}

function text_bbcode_only($str)
{
	$bbcode = array(
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si'
	);
	
	$html = array(
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>'
	);
	
	$str=preg_replace($bbcode, $html, $str);
	
	return $str;
}

?>
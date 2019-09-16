<?
/*
TWHL3
WIKI FUNCTIONS
To be included in wiki (entity guide,error guide,glossary) pages
Functions used by wiki
*/
/*
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
}*/

/*

PROPOSED ALLOWED WIKI BBCODE

[list][li][list][li]Nested list item: maximum ONE NESTING ONLY.[/li][/list][/li][li]regular list item[/li][/list]

[col=rrggbb]font colour in hex or a web-accepted colour (e.g. blue, red, etc)[/col]

[red],[green],[yellow],etc for ease of use

[table=TABLE_TYPE][tr][td]table cell[/td][/tr][/table]
Table type is one of several:
full: width of page
half: half page width, text wraps (hopefully, if css/IE allows it)
half-right: same as above except floats to the right
more if needed
No nesting tables permitted.

[img=image_url]centered resized image,with caption. clicking the image brings up the full size image. resizes are done on site, NOT by using max-width in browsers.[/img]

<[word]> links to the wiki entry called "word", with text "word"

[size=num]font of a different size. num will be in pt, not pixels.[/size]




*/

function wiki_list($str)
{
	function list_single($txt,$inner)
	{
		$txt = preg_replace('#\[li\](.+?)\[/li\]#si', '<li>\\1</li>', $txt);
		$outlistu = '</p><ul>\\1</ul><p class="single-center-content">';
		if ($inner) $outlistu = '<ul>\\1</ul>';
		$outlisto = '</p><ol>\\1</ol><p class="single-center-content">';
		if ($inner) $outlisto = '<ol>\\1</ol>';
		$txt = preg_replace('#\[list\](.+?)\[/list\]#si', $outlistu, $txt);
		$txt = preg_replace('#\[olist\](.+?)\[/olist\]#si', $outlisto, $txt);
		return $txt;
	}
	
	preg_match_all('%\[li\]((?:(?!\[/li\]|\[li\]).)*+(?:\[li\](?:(?!\[/li\]|\[li\]).)*+\[/li\](?:(?!\[/li\]|\[li\]).)*+)*+.*?)\[/li\]%si', $str, $result, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($result[0]); $i++) {
		# Matched text = $result[0][$i];
		$str = str_ireplace($result[1][$i],list_single($result[1][$i],true),$str);
	}
	return list_single($str,false);
}

function wiki_format($str) {

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
		'#\[green\](.+?)\[/green\]#si',
		'#\[blue\](.+?)\[/blue\]#si',
		'#\[purple\](.+?)\[/purple\]#si',
		'#\[yellow\](.+?)\[/yellow\]#si',
		'#\[red\](.+?)\[/red\]#si',
		'#\[pre\](.+?)\[/pre\]#si',
		'#\[h\](.+?)\[/h\]#si'
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
		'<span style="color: #009900">\\1</span>',
		'<span style="color: #0000ff">\\1</span>',
		'<span style="color: #990099">\\1</span>',
		'<span style="color: orange">\\1</span>',
		'<span style="color: red">\\1</span>',
		'<span style="font-family: monospace;">\\1</span>',
		'</p><h3>\\1</h3>'."\n".'<p class="single-center-content">'
	);
	
	$str = preg_replace($bbcode, $html, $str);
	
	$str = wiki_list($str);
	
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


?>
<?
/*
TWHL3
JOURNAL AND PROFILE FUNCTIONS
To be included in user and journal pages
Functions used by profiles and journals
*/

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
	
	$str = preg_replace('#\[url=([a-z]{3,6}://[^[" ]+)\\]\\[img\\]([a-z]{3,6}://[^\\"\\n\\r]+?)\\[/img\\]\\[/url\\]#si',"</p><p class=\"single-center-content-image\"><a href=\"\\1\"><img src=\"\\2\" alt=\"User Posted Image\" /></a><br /><a href=\"javascript:showfull('\\2')\">[Open in new window]</a></p><p class=\"single-center-content\">",$str);   
		$str = preg_replace('#\[img\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/img\\]#si',"</p><p class=\"single-center-content-image\"><img src=\"\\1\" alt=\"User Posted Image\" /><br /><a href=\"javascript:showfull('\\1')\">[Open in new window]</a></p><p class=\"single-center-content\">",$str);         
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

?>
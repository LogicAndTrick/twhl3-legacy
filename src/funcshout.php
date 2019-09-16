<?
/*
TWHL3
FORUM FUNCTIONS
To be included in shoutbox pages
Functions used by shoutbox exclusively
*/

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

?>
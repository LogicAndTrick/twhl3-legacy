<html>
<head>
<title>TextFlipper3000</title>
</head>
<body>
<?php
$str="your converted text will appear here";
if (isset($_POST['context']) && $_POST['context']!="")
{
$str=stripslashes($_POST['context']);
$str = str_ireplace('<OL>', '[olist]', $str);
$str = str_ireplace('</OL>', '[/olist]', $str);
$str = str_ireplace('<B>', '[b]', $str);
$str = str_ireplace('</B>', '[/b]', $str);
$str = str_ireplace('<I>', '[i]', $str);
$str = str_ireplace('</I>', '[/i]', $str);
$str = str_ireplace('<U>', '[u]', $str);
$str = str_ireplace('</U>', '[/u]', $str);
$str = str_ireplace('<UL>', '[list]', $str);
$str = str_ireplace('</p>', '', $str);
$str = str_ireplace('</UL>', '[/list]', $str);
$str = str_ireplace('<LI>', "\n[li]", $str);
$str = str_ireplace('</LI>', '[/li]', $str);
$str = str_ireplace('<pre>', '[pre]', $str);
$str = str_ireplace('</pre>', '[/pre]', $str);
$str = str_ireplace('<strong>', '[b]', $str);
$str = str_ireplace('</strong>', '[/b]', $str);
$str = str_ireplace('<em>', '[i]', $str);
$str = str_ireplace('</em>', '[/i]', $str);
$str = preg_replace('/<P>/i', "<br /><br />\n\n", $str);
$str = preg_replace('%<(.*?)>(.*?)</SPAN>%i', '[\1]\2[/\1]', $str);
$str = str_ireplace('[CON]', '[ins]', $str);
$str = str_ireplace('[/CON]', '[/ins]', $str);
$str = preg_replace('/ENT\]/', 'ent]', $str);
$str = preg_replace('/PROP\]/', 'prop]', $str);
$str = preg_replace('/VAL\]/', 'val]', $str);
$str = preg_replace('/\r\n+/i', ' ', $str);
$str = preg_replace('/<br>/i', "<br />\n", $str);
$str = preg_replace('%<H>(.*?)</H>%i', "\n\n".'[h]\1[/h]'."\n\n", $str);
$str = preg_replace('%<IMG SRC="tutpics/(.*?)".*?ALT="(.*?)".*?>%si', "\n\n".'[img=\1]\2[/img]'."\n\n", $str);
$str = preg_replace('%<IMG SRC="tutpics/(.*?)".*?>%i', "\n\n".'[img=\1]Caption[/img]'."\n\n", $str);
$str = preg_replace('%<IMG SRC="/tutpics/(.*?)".*?ALT="(.*?)".*?>%si', "\n\n".'[img=\1]\2[/img]'."\n\n", $str);
$str = preg_replace('%<IMG SRC="/tutpics/(.*?)".*?>%i', "\n\n".'[img=\1]Caption[/img]'."\n\n", $str);
$str = preg_replace('%<CENTER>(.*?)</CENTER>%si', '\1', $str);
$str = preg_replace('%<A HREF="(.*?)">(.*?)</A>%i', '[url=\1]\2[/url]', $str);
//$str = preg_replace('/[\s]{2,}/i', ' ', $str);
$str = preg_replace('%\[/h\][\s]+\[img%si', "[/h]\r\n\r\n[img", $str);
$str = preg_replace('%<br /><br />[\s]+\[img%si', "<br /><br />\r\n\r\n[img", $str);
$str = preg_replace('%\[/img\][\s]+<br /><br />%si', '[/img]', $str);
$str = preg_replace('/ +/si', ' ', $str);
$str = preg_replace('/'."\n".' /', "\n", $str);
$str = preg_replace('/\r\n /si', "\r\n", $str);
$str = preg_replace('%<br ??/??>%si', "\n", $str);
$str = preg_replace('/(\\r\\n){2,}/sim', "\n\n", $str);
}

?>
	<h1>TEXTFLIPPER3000</h1>
	<h2>converted text</h2>
	<textarea id="newposttext" name="tuttext" rows="10" cols="76"><?=$str?></textarea><br><br>
	<h2>old text</h2>
	<form action="textflipper3000.php" method="post">
		<textarea name="context" rows="10" cols="76"><?=stripslashes($_POST['context'])?></textarea><br>
		<input value="Go" type="submit">
	</form>
</body>
</html>
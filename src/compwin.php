<?php
function format_compo_results($str)
{
    // pasted from tutorial_format, edited image junk
    // Reusability? maintainability? fuck you! I'm rewriting this shit, I don't care if it sucks!

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

    $str = preg_replace('%\[img\](.+?)\[/img\]%', '</p><p class="single-center-content-image" style="float: right; margin: 0px 10px 10px 15px;"><img src="\1" alt="Compo Image" /></p><p class="single-center-content">', $str);

    $str = linesplitter($str, 54);

    return $str;
}
	$getcomp = mysql_real_escape_string($_GET['results']);
	
	$allowed = false;
	
	// check compo exists
	$compq = mysql_query("SELECT * FROM compos WHERE compID = '$getcomp' AND comptype > 0");
	if (mysql_num_rows($compq) > 0)
	{
		$comprow = mysql_fetch_array($compq);
		// check compo has closed
		if ($comprow['compclose'] < gmt("U")) $allowed = true;
		// get info
		$cname=$comprow['compname'];
		$cdesc=$comprow['compclosedesc'];
		$crest=$comprow['comprest'];
	}
	
	//check compo has been judged
	if ($allowed)
	{
		$winq = mysql_query("SELECT compwins.*, uid FROM compwins LEFT JOIN users ON winuser = userID WHERE wincomp = '$getcomp' ORDER BY winplace ASC");
		if (mysql_num_rows($winq) == 0) $allowed = false;
	}
	
	if (!$allowed) fail("This competition hasn't been judged. It is likely there were no entries in the competition, or that the judging is still in progress.","competitions.php");
?>
<div class="single-center">
	<h1>Competition <?=$getcomp?> - <?=$cname?></h1>
	<h2><a href="competitions.php">Competitions</a> &gt; <a href="competitions.php?comp=<?=$getcomp?>">View Original Brief</a></h2>
	<?=tutorial_format($cdesc)?>		
</div>	
<div class="single-center" id="gap-fix">
	<h1>The Results</h1>
<?
		while ($winr = mysql_fetch_array($winq))
		{
	
		$external = false;
		if (substr($winr['winfile'],0,7) == "http://" || substr($winr['winfile'],0,6) == "ftp://")
			$external = true;
		
		$name = $winr['winname'];
		if (!isset($name) || $name == "") $name = "Unnamed";
?>
	<div class="compo-results-container">
		<p class="single-center-content">
			<span class="trophy-image">
				<img src="gfx/cupbig_<?=$winr['winplace']?>.jpg" alt="trophy" />
			</span>	
			<span class="map-image">
				<img src="compopics/<?=$winr['winpic']?>" alt="map pic" />
			</span>	
			<strong><?=$name?>, by <a href="user.php?id=<?=$winr['winuser']?>"><?=$winr['uid']?></a></strong>
		</p>
 <? if (trim($winr['winfile']) != '') { ?>
		<p class="download-image">
			<a href="<?=($external)?$winr['winfile']:'compodl/'.$winr['winfile']?>"><img src="images/download.png" alt="download" /></a>
		</p>
 <? } ?>
		<p class="single-center-content">
			<?=post_format($winr['wintext'])?>
		</p>
		<div style="clear: both"></div>
	</div>
<?
		}
?>
</div>
<?
		if (trim($crest) != "")
		{
?>
<div class="single-center">
<h1>And the Rest...</h1>
	<?=format_compo_results($crest)?>
</div>
<?
		}
?>
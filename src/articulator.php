<?php
include 'top.php';
?>
<div class="single-center">
<?
	
	function e($s) {
		return mysql_real_escape_string($s);
	}
	
	if (isset($_GET['art']))
	{
	$qry = "SELECT * FROM articulator WHERE articleID = '".e($_GET['art'])."' AND revisionactive = '1'";
	if (isset($_GET['form']) && $_GET['form']=="old") $qry = "SELECT * FROM articulator WHERE articleID = '".e($_GET['art'])."' AND revisionnumber = '0'";
	$r = mysql_fetch_array(mysql_query($qry)) or die();
?>
<h1><?=$r['title']?></h1>
<h2>By <?=$r['originalauthor']?></h2>
<?
	if ((isset($_GET['form']) && $_GET['form']=="old") || $r['revisionnumber']==0) {
?>
<table>
<?=$r['content']?>
</table>
<?
	}
	else
	{
		echo tutorial_format($row['content']);
	}
}
else
{
?>
<h1>VERC Tutorial Archive</h1>
<p class="single-center-content">
We at TWHL believe that information should never be lost, which is why this page exists. Sometime in 2008, the Valve Collective website, also known as VERC, disappeared off the face of the internet. Using the Internet Archive's <a href="http://www.archive.org/web/web.php">Wayback Machine</a>, and some clever web crawling, the articles that were on VERC are now right here.
<br /><br />
There are some things you should know about this:
<ul>
	<li>The articles are in bad shape - they're not formatted correctly for the site, and they're missing images</li>
	<li>Only a very few number of the original authors have authorised the use of this content. If you're an author of any of these tutorials and feel that they should not be here, please use the <a href="contact.php">Contact Us</a> page to notify us, and we will remove your article(s).</li>
	<li>Eventually, this will be a wiki-type system, with users editing the text with better formatting and new images. This is not in place as of now.</li>
</ul>
</p>
</div>
<div class="single-center" id="gap-fix-bottom">				
<h1>VERC Revival</h1>
<table class="no-width">
	<tr>
		<th>Article</th>
		<th>Author</th>
		<th>Ver.</th>
		<th>Last Edited</th>
		<th>Done</th>
	</tr>
<?
	$q = mysql_query("SELECT articulator.*,uid FROM articulator LEFT JOIN users ON revisionuser = userID ORDER BY articleID ASC");
	while ($r = mysql_fetch_array($q))
	{
		echo "<tr>
		<td class=\"left\"><a href=\"articulator.php?art=".$r['articleID']."\">".$r['title']."</a>
			<br><a href=\"".$r['waybacklink'].$r['weblink']."\">VERC</a> &bull; <a href=\"articulator.php?art=".$r['articleID']."&form=old\">Old</a> &bull; <a href=\"articulator.php?art=".$r['articleID']."&form=new\">TWHL</a> &bull; <a href=\"#\">History</a> &bull; <a href=\"#\">Edit</a></td>
		<td>".$r['originalauthor']."</td>
		<td>".$r['revisionnumber']."</td>
		<td><a href=\"user.php?id=".$r['revisionuser']."\">".$r['uid']."</a><br>".timezone($r['revisiondate'],$_SESSION['tmz'],"d M y H:i")."</td>
		<td><img src=\"images/tut".(($r['final']==1)?"yes":"no").".png\" alt=\"".(($r['final']==1)?"yes":"no")."\" /></td>
		</tr>\n";
	}
?>
</table>
<?
}
?>
</div>
<?
include "bottom.php";
?>
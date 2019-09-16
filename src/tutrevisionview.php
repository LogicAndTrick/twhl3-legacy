
<?
	include 'compare.php';
	
	$gettut = mysql_real_escape_string($_GET['revision']);
	
	$newq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND isactive = '-1' LIMIT 1");
	
	if (mysql_num_rows($newq) == 0) fail("Revision not found.","tutorial.php");
	
		$newr = mysql_fetch_array($newq);
		$newpage = $newr['page'];
		$curq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$newpage' AND isactive = '1' LIMIT 1");

	if (mysql_num_rows($curq) == 0) fail("Page not found.","tutorial.php");
			$curr = mysql_fetch_array($curq);
			
			$oldtext = str_replace("\n","\n\n",$curr['content']);
			$newtext = str_replace("\n","\n\n",$newr['content']);
			
			$diff = compare($oldtext, $newtext);
			$diff2 = compare($oldtext, $newtext, true);
			echo '<style>
					span.green {background:#cfc;}
					span.red {background:#fcc;}
					span.hidden {display:none;}
				</style>';
			//echo '<div style="width:327px; float: right; border-left: 1px solid; padding: 5px">'.tutorial_revision_format($diff2).'</div>';
			//echo '<div style="width:327px; padding: 5px">'.tutorial_revision_format($diff).'</div>';
			
			$diff = str_ireplace("\n"," ",$diff);
			$diff = str_ireplace("  ","<br />\n",$diff);
			
			$diff2 = str_ireplace("\n"," ",$diff2);
			$diff2 = str_ireplace("  ","<br />\n",$diff2);
			
?>
<div class="single-center">
	<h1>Tutorial Revision</h1>
	<span class="page-index">Old Text</span>
	<h2>New Text</h2>
	<div style="width:327px; float: right; border-left: 1px solid; padding: 5px">
		<?=linesplitter($diff,27)?>
	</div>
	<div style="width:327px; padding: 5px">
		<?=linesplitter($diff2,27)?>
	</div>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>Accept Revision</h1>
	<p class="single-center-content-center">
		This revision has been submitted by the author as a perminent edit to the tutorial.<br />
		Should the new version be made public?<br />
		(Note that you can revert to any previous version at any time)
	</p>
	<form action="tutrevisionyes.php?id=<?=$gettut?>" method="post">
		<p class="single-center-content-center">
			<input name="revisionyes" value="Approve" type="submit" />
		</p>
	</form>
	<form action="tutrevisionno.php?id=<?=$gettut?>" method="post">
		<p class="single-center-content-center">
			<input name="noreason" value="Enter Reason for Denial Here" type="text" />
			<input name="revisionno" value="Deny" type="submit" />
		</p>
	</form>
</div>
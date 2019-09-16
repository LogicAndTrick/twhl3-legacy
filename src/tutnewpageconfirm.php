<?php	

	$pageid=mysql_real_escape_string($_GET['newpage']);
	$pagq = mysql_query("SELECT * FROM tutorialpages LEFT JOIN tutorials ON tutorialpages.tutorialid = tutorials.tutorialID WHERE pageID = '$pageid' AND isactive = '-1'");
	if (mysql_num_rows($pagq) > 0)
	{
		$pagr = mysql_fetch_array($pagq);
		if (isset($_SESSION['usr']) && isset($_SESSION['lvl']) && $_SESSION['lvl'] >= 25)
		{
?>
<div class="single-center">
	<h1><?=$pagr['name']?> - Page <?=$pagr['page']?></h1>
	<?=tutorial_format($pagr['content'])?>
	<p class="single-center-content-center" style="border-top: 1px dotted #daa134">
		This page has been requested to be added to the tutorial. Add?
	</p>
	<form action="tutnewpageyes.php?id=<?=$pageid?>" method="post">
		<p class="single-center-content-center">
			<input value="Approve" type="submit" />
		</p>
	</form>
	<form action="tutnewpageno.php?id=<?=$pageid?>" method="post">
		<p class="single-center-content-center">
		<input value="Deny" type="submit" />
		</p>
	</form>
</div>
<?
		}
		else fail("You aren't logged in, or you don't have permission to do this.","tutorial.php");
	}
	else fail("Page not found","tutorial.php");
?>
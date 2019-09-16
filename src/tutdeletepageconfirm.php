<?php	

	$pageid=mysql_real_escape_string($_GET['pagedelete']);
	$pagq = mysql_query("SELECT * FROM tutorialpages LEFT JOIN tutorials ON tutorialpages.tutorialid = tutorials.tutorialID WHERE pageID = '$pageid' AND deletecandidate = 1");
	
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (mysql_num_rows($pagq) == 0) fail("Page not found.","tutorial.php",true);
	
		$pagr = mysql_fetch_array($pagq);
?>
<div class="single-center">
	<h1><?=$pagr['name']?> - Page <?=$pagr['page']?></h1>
	<?=tutorial_format($pagr['content'])?>
	<p class="single-center-content-center" style="border-top: 1px dotted #daa134">
		This page has been requested to be deleted. Delete?
	</p>
	<form action="tutpagedeleteyes.php?id=<?=$pageid?>" method="post">
		<p class="single-center-content-center">
			<input value="Delete" type="submit" />
		</p>
	</form>
	<form action="tutpagedeleteno.php?id=<?=$pageid?>" method="post">
		<p class="single-center-content-center">
		<input value="Retain" type="submit" />
		</p>
	</form>
</div>
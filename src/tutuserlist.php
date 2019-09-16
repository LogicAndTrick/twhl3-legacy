<div class="single-center">
<?
	$hastuts = false;
	if (isset($usr) && $usr != "")
	{
		$mytutq = mysql_query("SELECT * FROM tutorials WHERE authorid = '$usr' ORDER BY waiting DESC, date DESC");
		if (mysql_num_rows($mytutq) > 0) $hastuts = true;
	}
	
	if ($hastuts)
	{
?>
	<h1>The TWHL Tutorial Creator</h1>
	<p class="single-center-content">
		Welcome to the TWHL Tutorial Creator! From here, you have complete control over the creation and editing of your new tutorial. You have full use of TWHL's bbcode system, as well as some tutorial specific stuff that is as follows:
	</p>

	<ul>
		<li>[ent] - Entity. This tag is used for entities. For example, 'func_wall'.</li>
		<li>[ins] - Instructions. This tag is used when giving specific instructions to the user. Useful for keyboard shortcuts and the like.</li>
		<li>[val] - Value. This tag is used for data values. Things like important numbers and similar.</li>
		<li>[prop] - Property. This tag is used for properties. Can be used for entity information.</li>
	</ul>	
	<p class="single-center-content">

		If you need help, or need to clarify something, you can leave a note for the tutorial moderators when you save your tutorial. While your tutorial is in draft stage, a temporary comments section is setup for communication between you and the tutorial mods. Once the tutorial goes live, it will disappear.	
	</p>	
	<p class="single-center-content">
		Once your tutorial goes live, you'll be able to edit it. However, before the edits go live, a tutorial moderator will need to review it. The team will be automatically alerted to everything and will respond accordingly.
	</p>
	<p class="single-center-content">
		And now some tips. Write in clear, concise English: we don't want to be spending ages going through it to fix the grammar and spelling. If English isn't your native language, please tell us! Make sure your topic is well researched (the tutorial mods will conduct their own research into your topic when reviewing your work, so stay sharp). Images and diagrams really do help, so if you can, suppliment your tutorial with images. Finally, if applicable, an example map is always handy.
	</p>	
</div>	
<div class="single-center" id="gap-fix">
	<h1 class="no-bottom-border">Your Tutorials</h1>
<?
		while ($mtr = mysql_fetch_array($mytutq))
		{
			$difficulty = $mtr['difficulty'];
			$diff="Advanced";
			if ($difficulty == 0)
				$diff = "Beginner";
			elseif ($difficulty == 1)
				$diff = "Intermediate";
				
			$waiting = $mtr['waiting'];
				
			$topics=$mtr['topics'];
			
			$tutorialid = $mtr['tutorialID'];
			
			if ($waiting == 1)
				$pagiq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutorialid' ORDER BY page ASC");
			else
				$pagiq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutorialid' AND isactive > 0 ORDER BY page ASC");
?>
	<span class="page-index">
		Edit Page:
<?
			$counter = 0;
			while($pagir = mysql_fetch_array($pagiq))
			{
?>
				<a href="tutorial.php?edit=<?=$tutorialid?>&amp;page=<?=$pagir['page']?>"><?=$pagir['page']?></a>
<?
				$counter++;
			}
			if ($counter < 5)
			{
?>
				<a href="tutcreatepage.php?id=<?=$tutorialid?>">[Add Page]</a>
<?
			}
?>
	</span>
	<h2 class="top-border"><a href="tutorial.php?id=<?=$mtr['tutorialID']?>"><?=$mtr['name']?></a></h2>
	<p class="right-info">
		<strong><?=timezone($mtr['date'],$_SESSION['tmz'],"jS F Y")?></strong><br />
		<?=$diff?><br />
		<?=$topics?><br />
		<strong><?=($mtr['waiting'] == 0)?"Active":"Draft"?></strong><br />
		<a href="tutorial.php?edit=<?=$mtr['tutorialID']?>">Edit this information</a><br />
	</p>
<?
		}
	}
	else
	{
?>
	<h1>Your Tutorials</h1>
	<p class="single-center-content">
		You do not have any tutorials!
	</p>
<?
	}
?>
</div>
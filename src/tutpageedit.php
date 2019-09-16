<?
	$gettut=mysql_real_escape_string($_GET['edit']);
	$getpage=mysql_real_escape_string($_GET['page']);
	
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$gettut'");
	$row = mysql_fetch_array($result);
	$authorid = $row['authorid'];
	
	if (!((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
	$tutq = mysql_query("SELECT tutorials.*,uid,avtype FROM tutorials LEFT JOIN users ON userID = authorid WHERE tutorialID = '$gettut'");
	
	if (mysql_num_rows($tutq) == 0) fail("Tutorial not found.","tutorial.php");

	$tutr = mysql_fetch_array($tutq);
	
	$waiting = $tutr['waiting'];
	
	if ($waiting == 1)
		$pagq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$getpage'");
	else
		$pagq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$getpage' AND isactive > 0 ORDER BY isactive DESC");
		
	$watq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND isactive < 0");
	$delq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND deletecandidate = 1");
	
	if (mysql_num_rows($pagq) == 0) fail("Page not found.","tutorial.php");
	if (!((mysql_num_rows($watq) == 0 && mysql_num_rows($delq) == 0) || ($_SESSION['lvl'] >= 25))) fail("You cannot modify your tutorial content again until your recent changes have been authorised.","tutorial.php");
	
	$numrevs = mysql_num_rows($pagq);
	$pagr = mysql_fetch_array($pagq);
	
	$pageid = $pagr['pageID'];
	
	$difficulty=$tutr['difficulty'];
	$diff="Advanced";
	if ($difficulty == 0)
		$diff = "Beginner";
	elseif ($difficulty == 1)
		$diff = "Intermediate";
		
	$topics=$tutr['topics'];
?>
<div class="single-center">
	<h1><?=$tutr['name']?></h1>
<?
	if ($waiting == 1)
		$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' ORDER BY page ASC");
	else
		$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND isactive > 0 ORDER BY page ASC");
		
	$counter = 0;
?>
	<span class="page-index">
		Edit Page:
<?
			while($row1 = mysql_fetch_array($result1))
			{
?>
				<a href="tutorial.php?edit=<?=$gettut?>&amp;page=<?=$row1['page']?>"><?=(($row1['page']==$getpage)?'[':'').$row1['page'].(($row1['page']==$getpage)?']':'')?></a>
<?
				$counter++;
			}
			if ($counter < 5)
			{
?>
				<a href="tutcreatepage.php?id=<?=$gettut?>">[Add Page]</a>
<?
			}
?>
	</span>
	<h2><a href="tutorial.php?id=<?=$tutr['tutorialID']?>">View Tutorial</a> | <a href="tutdeletepage.php?id=<?=$pageid?>">Delete Page</a></h2>
	<span class="right-avatar">
		<img src="<?=getresizedavatar($tutr['authorid'],$tutr['avtype'],100)?>" alt="avatar" />	
	</span>
	<p class="right-info">
		By <a href="user.php?id=<?=$tutr['authorid']?>"><?=$tutr['uid']?></a><br />
		<strong><?=timezone($tutr['date'],$_SESSION['tmz'],"d M y, H:i")?></strong><br />
		<?=$diff?><br />	
		<?=$topics?><br />
		<a href="tutorial.php?edit=<?=$gettut?>">Edit Info/Upload Example</a>
	</p>
</div>
<div class="single-center" id="gap-fix">
	<h1><?=$tutr['name']?> - Page <?=$page?></h1>
	<form action="tutchangepage.php?id=<?=$gettut?>&amp;page=<?=$getpage?>" method="post">
		<fieldset class="new-thread">
			<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
			<div class="smilies" id="bb-content">
				<? include 'tutbbcode.php'; ?>
			</div>
			<fieldset style="text-align: center;">
				<textarea id="newposttext" rows="30" cols="76" name="pagecontent"><?=$pagr['content']?></textarea>
			</fieldset>
<?
			if (isset($lvl) && ($lvl >= 25))
			{
?>
			<p class="single-center-content-center">
				<input name="save" value="Save" type="submit" />
<?
				if ($waiting == 1)
				{
?>
				<input name="adminsaveandquit" value="Publish" type="submit" />
<?
				}
?>
			</p>
<?
			}
			elseif ($waiting == 1)
			{
?>
			<p class="single-center-content-center">
				<input name="save" value="Save" type="submit" /><br /><br />
				<input name="saveandquit" value="Save and Submit for Review" type="submit" />
			</p>
<?
			}
			elseif ($pagr['isactive']==2 && $numrevs==1)
			{
?>
			<p class="single-center-content">
				Because this is a new page, you can choose to save it and come back to it later, or you can submit it to be checked if it can go live.
			</p>
			<p class="single-center-content-center">
				<input name="save" value="Save" type="submit" />
				<input name="newpagequit" value="Save and Submit" type="submit" />
			</p>
<?
			}
			else
			{
?>
			<p class="single-center-content-center">
				<input name="revise" value="Submit Revision" type="submit" />
			</p>
<?
			}
?>
		</fieldset>
	</form>
</div>
<div class="single-center">
	<h1>Add Images</h1>
<?
			$result3 = mysql_query("SELECT * FROM tutorialpics WHERE tutID = $gettut ORDER BY picID ASC");
			if (mysql_num_rows($result3) > 0)
			{
?>
	<p class="single-center-content-center">
		Click an image to insert it into the page.
	</p>
	<table class="tutorial-image-edit">
<?
				$counter = 1;
				while ($row3 = mysql_fetch_array($result3))
				{
					if ($counter%5==1) echo '<tr>';
?>
		<td><div><a href="javascript:smilie('[img=<?=$row3['piclink']?>]caption[/img]')" title="<?=$row3['piclink']?>"><img src="tutpics/<?=$row3['piclink']?>" alt="tutorial pic" /></a><br /><a href="tutdeletepic.php?id=<?=$row3['picID']?>&amp;page=<?=$getpage?>">[Delete]</a></div></td>
<?
					if ($counter%5==0) echo '</tr>';
					$counter++;
				}
				if ($counter%5!=1) echo '</tr>';
?>
	</table>
<?
			}
?>
	<form action="tutuploadpics.php?id=<?=$gettut?>&amp;page=<?=$getpage?>" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				Upload your images. Make sure they're JPG or PNG.<br />
				<strong>Uploading images does not save your work.<br />
				Save the content in the page before uploading images.</strong><br />
				<input type="file" name="image1" /><br />
				<input type="file" name="image2" /><br />
				<input type="file" name="image3" />
			</p>	
			<p class="single-center-content-center">
				<input value="Upload" type="submit" />
			</p>
		</fieldset>
	</form>
</div>
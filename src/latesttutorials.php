<h1>Recent Tutorials <a href="javascript:toggleLayer('tutorial-div');"><img alt="show/hide" src="images/arrow_up.gif" id="tutorial-div-hide"/></a></h1>
<div id="tutorial-div">
<?
	$newtutq = mysql_query('SELECT * FROM tutorials LEFT JOIN users ON authorid = userID WHERE waiting = 0 ORDER BY date Desc LIMIT 5');
	$alt = true;
	while ($recr = mysql_fetch_array($newtutq))
	{
		$alt = !$alt;
?>
	<div class="tutorial<?=($alt)?'-alt':''?>"><a href="tutorial.php?id=<?=$recr['tutorialID']?>"><?=$recr['name']?></a></div>
<?
	}
?>
</div>
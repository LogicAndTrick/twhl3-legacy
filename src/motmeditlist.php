<?
	include 'top.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
?>
<div class="single-center">
<?
		if (isset($_GET['id']) && $_GET['id']!="")
		{
			$motmid=mysql_real_escape_string($_GET['id']);
			$result=mysql_query("SELECT * FROM motmreviews WHERE motm = $motmid");
			$cnt=1;
?>
	<h1>MOTM #<?=$motmid?></h1>
	<h2><a href="motmedit.php">MOTM Management</a> > <a href="motmeditlist.php">MOTM List</a> > MOTM #<?=$motmid?></h2>
	<p class="single-center-content">
<?
			echo '<a href="motmeditmotm.php?id=' . $motmid . '">Modify MOTM</a><br/><br/>' . "\n";
			while ($row=mysql_fetch_array($result))
			{
				echo '<a href="motmreview.php?id=' . $row['reviewID'] . '">Review ' . $cnt . '</a><br/>' . "\n";
				$cnt++;
			}
			echo '<br /><a href="motmpublish.php?id=' . $motmid . '">Publish MOTM</a>';
		}
		else
		{
			$result=mysql_query("SELECT * FROM motm LIMIT 50");
?>
	<h1>MOTM List</h1>
	<h2><a href="motmedit.php">MOTM Management</a> > MOTM List</h2>
	<p class="single-center-content">
<?
			while ($row=mysql_fetch_array($result))
			{
				echo '<a href="motmeditlist.php?id=' . $row['motmID'] . '">' . $row['date'] . '</a><br/>' . "\n";
			}
		}
?>
	</p>
</div>
<?
	}
	else fail("You are not logged in, or you do not have permission to do this.","index.php");
	include 'bottom.php';
?>
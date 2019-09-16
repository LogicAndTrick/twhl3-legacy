<?
	include 'top.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
?>
<div class="single-center">
	<h1>MOTM Management</h1>
	<h2>MOTM Management</h2>
	<p class="single-center-content">
		<a href="motmaddmotm.php">Add new MOTM</a><br/>
		<a href="motmeditlist.php">View MOTMs for editing</a><br/>
		<a href="motm.php">Go to MOTM page</a>
	</p>
</div>
<?
	}
	else fail("You are not logged in, or you do not have permission to do this.","index.php");
	include 'bottom.php';
?>
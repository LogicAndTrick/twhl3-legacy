<?
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
	$alertq = mysql_query("SELECT * FROM alertadmin LEFT JOIN alerttypes ON alerttype = alerttypeID LEFT JOIN users ON alertuser = userID ORDER BY alertdate DESC");
	
	$tabcont = 'alerts';
	if (isset($_GET['alerts'])) $tabcont = 'alerts';
	if (isset($_GET['user'])) $tabcont = 'user';
?>
<div class="single-center" style="margin-bottom: 0;">
	<h1>Welcome to the Admin Panel</h1>
	<h2><a href="#">View Your Profile</a> | <a href="#">Forums</a> | <a href="#">Map Vault</a> | <a href="#">Other Important Link</a></h2> 	
	<span class="left-control-image">
		<img src="images/shield_large.png" alt="large shield" />
	</span>
	<p class="single-center-content">
		Welcome to the Admin Panel. This is the central control hub for everything TWHL related, including Moderation, Tutorials, Map Vault and other aspects of the site. Your panel has been tailored automatically depending on your mod level.
	</p>	
</div>
<div class="single-center" style="display: <?=($tabcont=='alerts')?'block':'none'?>;" id="alert-tab">
	<h1>Admin Alerts</h1>
	<h2>Admin Alerts | <a href="javascript:tabswitcher(new Array('user-tab','alert-tab'))">User Management</a></h2>
<?
	if (mysql_num_rows($alertq) > 0)
	{
?>
	<table class="user-alerts">
		<tr>
			<th>User</th>
			<th>Date</th>
			<th>Subject</th>
			<th>Message</th>
			<th>Delete</th>
		</tr>
<?
		while ($alertr = mysql_fetch_array($alertq))
		{
?>
		<tr>
			<td><a href="user.php?id=<?=$alertr['alerter']?>"><?=$alertr['uid']?></a></td>
			<td><?=timezone($alertr['alertdate'],$_SESSION['tmz'],"jS F Y")?></td>
			<td><a href="<?=$alertr['typelink']?><?=$alertr['alertlink']?>"><?=$alertr['typetext']?></a></td>
			<td><?=$alertr['alertcontent']?></td>
			<td><a href="adminalertdelete.php?id=<?=$alertr['alertID']?>">[D]</a></td>
		</tr>
<?
		}
?>
	</table>
<?
	}
	else
	{
?>
	<p class="single-center-content">
		There are no active admin alerts right now.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='user')?'block':'none'?>;" id="user-tab">
	<h1>User Management</h1>
	<h2><a href="javascript:tabswitcher(new Array('alert-tab','user-tab'))">Admin Alerts</a> | User Management</h2>
	<p class="single-center-content">
		Enter a user ID to go to that user's management page. Alternatively, if you know the exact username, you can enter that, too.
	</p>
	<form action="user.php" method="get">
		<fieldset class="new-thread">
			<p style="margin: 12px 12px 0 12px; text-align: center;">
				Enter Username<br />
				<input size="20" id="username" type="text" onclick="javascript:nametable('username','userlist')" name="manage" />
			</p>
			<div id="userlist">
			</div>
			<p class="single-center-content-center">
			</p>	
		</fieldset>	
	</form>
</div>
<h1>ShoutBOX</h1>
<?php

	$shoutq = mysql_query("SELECT shoutID,sq.uid,users.lvl,shout,time,users.uid AS uname FROM (SELECT * FROM shouts ORDER BY shoutID DESC LIMIT 7) as sq LEFT JOIN users ON sq.uid = users.userID ORDER BY shoutID ASC");

	if (mysql_num_rows($shoutq) > 0)
	{
?>
	<div class="shoutbox" id="sidebar_shoutbox">
<?
	include 'shoutboxcontent.php';
?>
	</div>
<?
	}
	else
	{
?>
<ul>
	<li>No Shouts Yet</li>
</ul>
<?
	}
	
	if (isset($_SESSION['uid']) && $_SESSION['uid'] != "")
	{
?>
<div id="shoutedit" style="display: none;"></div>
<form action="javascript:ajax_shout_post()" id="shout_form">
	<fieldset>
		<input class="shout-text" id="shout_box" type="text" size="12" maxlength="200" onfocus="JavaScript:(this.value=='Type here')?this.value='':this.select();" value="Type here" />
		<input class="shout-button" type="submit" value="Go" />
	</fieldset>
</form>
<?
	}
	else
	{
?>
	<input class="shout-text" type="text" size="14" disabled="disabled" value="Login to Shout" />
	<input class="shout-button" type="button" value="Go" />
<?
	}
?>
<ul>
	<li><a href="JavaScript:popShoutbox()">ShoutBOX Live</a></li>
</ul>
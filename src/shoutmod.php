<?php

	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	if (isset($lvl) && ($lvl >= 35) && isset($_GET['edit']) && ($_GET['edit'] != ""))
	{
		$result = mysql_query("SELECT shout FROM shouts WHERE shoutID='".mysql_real_escape_string($_GET['edit'])."'");
		if(mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);
			$shout_text=$row['shout'];
?>
<form action="javascript:ajax_shout_postedit('<?=mysql_real_escape_string($_GET['edit'])?>')" name="edit_shout" id="edit_shout">
	<fieldset style="margin-top: 4px;">
		<input class="shout-text" id="edit_shout_box" type="text" size="14" name="shout" maxlength="200" value="<?=$shout_text?>" />
		<input class="shout-button" type="submit" value="Go" />
	</fieldset>
</form>
<?
		}
		else echo 'no row';
	}
	elseif (isset($lvl) && ($lvl >= 35) && isset($_GET['delete']) && ($_GET['delete'] != ""))
	{
		$result = mysql_query("SELECT shout FROM shouts WHERE shoutID='".mysql_real_escape_string($_GET['delete'])."'");
		if(mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);
			$shout_text=$row['shout'];
?>
<div style="text-align: center;">
	Are you sure?<br />
	<a href="javascript:ajax_shout_postdelete('<?=mysql_real_escape_string($_GET['delete'])?>')">[Yes]</a>
</div>
<?
		}
		else echo 'no row';
	}
	else echo 'no lvl/get';
?>
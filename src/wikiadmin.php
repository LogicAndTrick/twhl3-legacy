<?
	if (!(isset($lvl) && ($usr >= 20))) fail("You are not allowed view this page.","wiki.php");
	
	// current = current tab
	// tabs = all tabs including current, in an array:
	// tab id => tab name
	function tabswitchlinkmaker($current,$alltabs)
	{
		$str = '';
		foreach($alltabs as $key => $value)
		{
			$allkeys = "'$key'";
			foreach($alltabs as $key2 => $value2) if ($key2 != $key) $allkeys .= ",'$key2'";
			if ($key != $current) $str .= ' | <a href="javascript:tabswitcher(new Array('.$allkeys.'))">'.$value.'</a>';
			else $str .= ' | '.$value;
		}
		$str=substr($str,3);
		return $str;
	}
	
	$tabs = array('validate-tab' => 'Validate Entries','user-tab' => 'Problematic Users');
	
	$tabcont = 'validate';
	if (isset($_GET['validate'])) $tabcont = 'validate';
	elseif (isset($_GET['user'])) $tabcont = 'user';
?>

<div class="single-center" style="margin-bottom: 0;">
	<h1>Wiki Management</h1>
	<h2><a href="wiki.php">Wiki Main Page</a> | <a href="wiki.php?log=1">Wiki Changelog</a></h2> 	
	<span class="left-control-image">
		<img src="images/shield_large.png" alt="large shield" />
	</span>
	<p class="single-center-content">
		Welcome to the Wiki Admin Panel. There's not much to do here because of the free nature of a Wiki. You can use the validator from here (also available from the changelog page). You may also choose to remove Wiki priveliges from repeat offenders.
	</p>	
</div>
<div class="single-center" style="display: <?=($tabcont=='validate')?'block':'none'?>;" id="validate-tab">
	<h1>Validate Entries</h1>
	<h2><?=tabswitchlinkmaker('validate-tab',$tabs)?></h2>
	<p class="single-center-content">
		This tool will cycle through unvalidated edits, entries, and comments. You can choose to keep or remove the entry. Remember that comments can reference other comments, but must have meaning by themselves. Choosing to edit a comment, or to revert to a different version of the entry (other than the last revision) will exit the validator.
	</p>
	<p class="single-center-content">
		Currently there are:<br />
		<?=''?> Unvalidated Entries<br />
		<?=''?> Unvalidated Comments
	</p>
	<p class="single-center-content-center">
		<a href="wikivalcycle.php">Go to the Validator</a>
	</p>
</div>
<div class="single-center" style="display: <?=($tabcont=='user')?'block':'none'?>;" id="user-tab">
	<h1>Problematic Users</h1>
	<h2><?=tabswitchlinkmaker('user-tab',$tabs)?></h2>
	<p class="single-center-content">
		Here you can modify the permissions of users which have made bad edits several times. Note that this only applies to the Wiki, and not anywhere else on the site.
	</p>
</div>
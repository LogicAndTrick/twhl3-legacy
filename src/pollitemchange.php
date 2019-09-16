<?
	include 'middle.php';
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
	$item = htmlfilter(trim($_POST['itemname']));
	$getitem = mysql_real_escape_string($_GET['id']);
	
	if (isset($item) && $item != "" && is_numeric($getitem))
	{
		mysql_query("UPDATE pollitems SET item = '$item' WHERE itemID = '$getitem'");
	}
	
	header("Location: polledit.php");
?>
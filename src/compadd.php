<?
	include 'middle.php';
	
	$thenow = gmt("U");
	$activeq = mysql_query("SELECT * FROM compos LEFT JOIN comptypes ON comptype = comptypeID WHERE compclose > '$thenow' AND compopen > 0 ORDER BY compclose DESC");
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","competitions.php",true);
	if (mysql_num_rows($activeq) > 0) fail("There is already a competition active, wait for it to finish before creating a new tutorial.","competitions.php",true);
	
	$name = htmlfilter($_POST['compname']);
	$start = htmlfilter($_POST['compstart']);
	$end = htmlfilter($_POST['compend']);
	$desc = mysql_real_escape_string(preg_replace('/(\\r\\n){2,}/sim', "\n\n", stripslashes(trim($_POST['compdesc']))));
	$rest = mysql_real_escape_string(preg_replace('/(\\r\\n){2,}/sim', "\n\n", stripslashes(trim($_POST['comprest']))));
	$game = mysql_real_escape_string((int)$_POST['compgame']);
	$type = mysql_real_escape_string((int)$_POST['comptype']);
	
	$gamet = 1;
	$judge = 1;
	
	if ($game == 2)
	{
		$gamet = 2;
	}
	elseif ($game == 3)
	{
		$gamet = 3;
		$judge = 3;
	}
	elseif ($game == 4)
	{
		$gamet = 3;
		$judge = 2;
	}
	
	$starta = explode(" ",$start);
	$startdate = mktime(0,0,0,$starta[1],$starta[0],$starta[2]);
	
	$enda = explode(" ",$end);
	$enddate = mktime(0,0,0,$enda[1],$enda[0],$enda[2]);
	
	mysql_query("INSERT INTO compos (compname,compopen,compclose,comptype,compgame,compjudgetype,compdesc,compclosedesc,comprest,comprestrictions,comppic,compexample) VALUES ('$name','$startdate','$enddate','$type','$gamet','$judge','$desc','','','$rest','','')");
	
	$cor = mysql_fetch_array(mysql_query("SELECT * FROM compos ORDER BY compID DESC LIMIT 1"));
	$compid = mysql_real_escape_string($cor['compID']);
	
	if (uploadcheck("compfile","archive"))
	{
		$ext = ".".strtolower(end(explode(".", $_FILES["compfile"]['name'])));
		$filename = "compo_".str_pad($compid,3,"0",STR_PAD_LEFT)."_base".$ext;
		move_uploaded_file($ftmp, "compodl/".$filename) or die ('Sorry, server is being stupid. Please retry your upload.');
		mysql_query("UPDATE compos SET compexample = '$filename' WHERE compID = '$compid'");
	}
	
	if (uploadcheck("comppic","image"))
	{
		$ext = ".".strtolower(end(explode(".", $_FILES["comppic"]['name'])));
		$filename = "compo_".str_pad($compid,3,"0",STR_PAD_LEFT)."_image".$ext;
		move_uploaded_file($ftmp, "compopics/".$filename) or die ('Sorry, server is being stupid. Please retry your upload.');
		mysql_query("UPDATE compos SET comppic = '$filename' WHERE compID = '$compid'");
	}
	header("Location: competitions.php?hub=$compid&judges");
?>
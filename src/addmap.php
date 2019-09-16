<?php

include 'functions.php';
include 'logins.php';
$redir="vault.php";
if (isset($_SESSION['uid']))
{
	if ($_POST['name']!="")
	{

		require_once("db.inc.php");
		
		$ownerID=$_SESSION['usr'];
		$name=$_POST['name'];
		$cat=$_POST['cat'];
		$game=$_POST['mod'];
		
		$included='0';
		if ($_POST['inrmf']==1) $included+=1;
		if ($_POST['inmap']==1) $included+=2;
		if ($_POST['inbsp']==1) $included+=4;
		
		$info=$_POST['info'];
		$date=gmt(U);
		$updated=gmt(U);
		$filesize='olol';
		
		$pmcomment='0';
		if ($_POST['pmoncomment']==1) $pmcomment='1'; 
		
		$allowrating=$_POST['rating'];
		
		$allowupload=$_POST['uploads'];
		
		
		$sql="INSERT INTO maps (ownerID,name,cat,game,included,info,date,updated,filesize,pmcomment,views,downloads,comments,allowrating,allowupload,ratings,rating) VALUES ('$ownerID','$name','$cat','$game','$included','$info','$date','$updated','$filesize','$pmcomment','0','0','0','$allowrating','$allowupload','0','0')";
		
		

		if (!mysql_query($sql,$dbh))
		  {
		  die('Error: ' . mysql_error());
		  }
		  
		mysql_close($dbh);

	}
}

header("Location: $redir");

?>
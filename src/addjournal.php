<?php

include 'functions.php';
include 'logins.php';
$redir="user.php?control=1";
if (isset($_SESSION['uid']) and isset($_POST['journtext']))
{
if ($_POST['journtext']!="")
{

require_once("db.inc.php");

$jtext=forumprocess(htmlfilter($_POST['journtext'],true),35);
$owner=$_SESSION['usr'];
$jdate=gmt(U); //date(d) . " " . date(M) . " " . date(y) . " " . date(H) . ":" . date(i);
$sql="INSERT INTO journals (ownerID,journaldate,journaltext) VALUES ('$owner','$jdate','$jtext')";

if (!mysql_query($sql,$dbh))
  {
  die('Error: ' . mysql_error());
  }
  
mysql_close($dbh);

}
}

header("Location: $redir");

?>
<?php

include 'functions.php';

if (isset($_POST['addforum']))
{
if ($_POST['addforum']!="")
{

require_once("db.inc.php");

$postnm=mysql_real_escape_string($_POST['addforum']);
$sql="INSERT INTO forumcats (name) VALUES ('$postnm')";

if (!mysql_query($sql,$dbh))
  {
  die('Error: ' . mysql_error());
  }

mysql_close($dbh);

}
}
header("Location: forums.php");

?>
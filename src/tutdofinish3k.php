<?

die();

include 'middle.php';

$getid=mysql_real_escape_string($_GET['id']);

mysql_query("UPDATE progress SET done = '1' WHERE tut = '$getid' LIMIT 1");

mysql_close($dbh);
header("Location: tutorialconverter3000.php");

?>
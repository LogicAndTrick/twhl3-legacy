<?

die();

include 'middle.php';

$getid=mysql_real_escape_string($_GET['id']);
$picname=htmlfilter($_POST['picname'],true);

mysql_query("INSERT INTO tutorialpics (tutID, piclink) VALUES ('$getid', '$picname')");

mysql_close($dbh);
header("Location: tutorialconverter3000.php?tut=$getid");

?>
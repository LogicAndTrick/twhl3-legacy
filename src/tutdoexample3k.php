<?

die();

include 'middle.php';

$getid=mysql_real_escape_string($_GET['id']);
$size=htmlfilter($_POST['filesize'],true);
$cont=htmlfilter($_POST['contents'],true);
$note=htmlfilter($_POST['notes'],true);

mysql_query("UPDATE tutorials SET examplesize = '$size', examplecont = '$cont', examplenotes = '$note' WHERE tutorialID = '$getid' LIMIT 1");

mysql_close($dbh);
header("Location: tutorialconverter3000.php?tut=$getid");

?>
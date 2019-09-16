<?

die();

include 'middle.php';

$getid=mysql_real_escape_string($_GET['id']);
$newtext=htmlfilter($_POST['tuttext'],true);

mysql_query("UPDATE tutorialpages SET content = '$newtext' WHERE tutorialid = '$getid' AND page = '1' LIMIT 1");

mysql_close($dbh);
header("Location: tutorialconverter3000.php?tut=$getid");

?>
<?

include 'top.php';

if (isset($_SESSION['lvl']) && $_SESSION['lvl'] > 4)
{
	if (isset($_GET['convert']))
		include 'tutconv3k.php';
	elseif (isset($_GET['tut']))
		include 'tutedit3k.php';
	else
		include 'tutlist3k.php';
}
else include 'denied.php';

include 'bottom.php';

?>
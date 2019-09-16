<?
	if (isset($_SESSION['noscript']) && $_SESSION['noscript'] == '1')
		include 'profileshow_ns.php';
	else
		include 'profileshow.php';
?>
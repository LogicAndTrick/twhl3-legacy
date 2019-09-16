<?

	$sloc = $_POST['sloc'];
	$sstr = urlencode(stripslashes($_POST['sstr']));
	header("Location: search.php?searchstring=$sstr&searchlocation=$sloc");

?>
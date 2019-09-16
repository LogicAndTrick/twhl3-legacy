<?php

include 'middle.php';

print_r($_POST);
echo '<br>';
echo '<br>';
if (isset($_POST['text'])) {
    echo $_POST['text'] . '<br>';
    echo htmlfilter($_POST['text'], true) . '<br>';
    
    $nstr = trim($_POST['text']);
    echo '1:' . $nstr . '<br>';
	$nstr = stripslashes($nstr);
    echo '2:' . $nstr . '<br>';
	$nstr = htmlspecialchars($nstr, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
    echo '3:' . $nstr . '<br>';
	if ($replace) $nstr = preg_replace('/(\\r\\n){2,}/sim', "\n\n", $nstr);;
    echo '4:' . $nstr . '<br>';
	$nstr = mysql_real_escape_string($nstr);
    echo '5:' . $nstr . '<br>';
    
    $t = htmlfilter($_POST['text'], true);
    
    mysql_query("INSERT INTO _temp (Text)
	VALUES
	('$t')");
}
echo '<br>';
echo '<br>';

?>
<html>
<head>
<title>postback test</title>
</head>
<body>
<form method="post">
<textarea name="text"></textarea>
<input type="submit" />
</form>
</body>
</html>
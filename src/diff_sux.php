<?php

function strclean($s) {
  // cleans strings up - removes excess spaces and newlines.
  $s = trim($s);
  if (get_magic_quotes_gpc()) $s = stripslashes($s);
   
  $s = str_replace("\t",' ',$s);
  $s = str_replace("\n",' ',$s);
  $s = str_replace("\r",' ',$s);
  
  $slen = strlen($s);
  $nstr = '';
  for ($i=0;$i<$slen;$i++) {
    $c = substr($s,$i,1);
    if ($c == ' ') $spc++; else $spc = 0;
    if ($spc <= 1) $nstr .= $c;
  }
  return $nstr;
}

$str=
"This is where tutorial text goes. it goes here. yep, thats right! it all goes here.
This is where tutorial text goes, even more. it goes here too.";
$str=str_replace("\n","<br>",trim($str));
$array=explode(".",$str);
//$print_r(array_values($array));

//print_r (explode(".",$str));
foreach ($array as $line) {
	if ($line != "") {
	echo $line;
	echo ". ";
	}
}


if (isset($_POST['text']) and $_POST['text']!="") {
$str2=$_POST['text'];
$str2=str_replace("\n","<br>",trim($str2));
$array2=explode(".",$str2);
/*
	foreach ($array2 as $line2) {
		if ($line2 != "") {
		echo $line2;
		echo ". ";
		}
	}
	*/
	
echo "<BR><BR>";

foreach ($array2 as $line2) {
		if ($line2 != "") {
		if (stristr(strclean($str),strclean($line2)) != false)
		echo $line2;
		else {
		echo "<B>$line2</B>";
		}
		echo ". ";
		}
	}
}

echo "<BR><BR>";


?>

<form action="diff.php" method="post">
		<textarea rows="10" cols="50" name="text"><? echo str_replace("<br>","\n",$str); ?></textarea><br />
		<input type="submit" value="Post!" size="7" />
	</form>
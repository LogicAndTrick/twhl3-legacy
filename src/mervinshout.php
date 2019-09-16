<?php

function unhtmlspecialchars( $string )
{
  $string = str_replace ( '&amp;', '&', $string );
  $string = str_replace ( '&#039;', '\'', $string );
  $string = str_replace ( '&quot;', '"', $string );
  $string = str_replace ( '&lt;', '<', $string );
  $string = str_replace ( '&gt;', '>', $string );
  return $string;
}

include "middle.php";
if ($_GET['latestonly'] > 0) {
$q = mysql_query("SELECT * FROM shouts ORDER BY shoutID DESC  LIMIT 1");
while ($shtr = mysql_fetch_array($q)) {
echo $shtr['shoutID'];
}
}
elseif ($_GET['last'] > 0) {
$q = mysql_query(mysql_real_escape_string("SELECT * FROM shouts LEFT JOIN users ON shouts.uid=userID WHERE shoutID > ".mysql_real_escape_string($_GET['last'])." ORDER BY shoutID ASC"));
$num = mysql_num_rows($q);
$counter = 0;
while ($shtr = mysql_fetch_array($q)) {
$counter++;
echo $shtr['shoutID']." ".$shtr['uid']."\n".unhtmlspecialchars($shtr['shout']);
if ($counter < $num) echo "\n";
}
}
else {
$q = mysql_query("SELECT * FROM (SELECT * FROM shouts ORDER BY shoutID DESC  LIMIT 20) as aq LEFT JOIN users ON aq.uid=userID ORDER BY shoutID ASC");
$counter = 0;
while ($shtr = mysql_fetch_array($q)) {
$counter++;
echo $shtr['shoutID']." ".$shtr['uid']."\n".unhtmlspecialchars($shtr['shout']);
if ($counter < 20) echo "\n";
}
}
?>
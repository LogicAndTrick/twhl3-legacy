<?php
include 'browserdetect.php';

$is_ie6 = false;
if ($browser == "IE 1-6") $is_ie6 = true;

$browser = mysql_real_escape_string($browser);
$usr = mysql_real_escape_string($usr);

if (isset($_SESSION['usr']) && $_SESSION['usr'] != "") mysql_query("UPDATE users SET lastbrowser = '$browser' WHERE userID = '$usr'");
?>
<?php
include 'functions.php';
include 'logins.php';

if (isset($usr) && $usr != "") {
    $thenow = gmt("U");
    mysql_query("INSERT INTO logouts (UserID, LogoutTime) VALUES ('$usr', '$thenow')");
}

setcookie ("twhl_username","",time()-30000000);
setcookie ("twhl_log","",time()-30000000);

$_SESSION = array();
if (session_id() != "") session_destroy();

header("Location: index.php"); 

?>
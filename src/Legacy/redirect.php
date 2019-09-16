<?
session_start();
$_SESSION['FROM_COZA'] = true;
header('Location: http://twhl.info/' . substr($_SERVER['REQUEST_URI'], 8));

?>
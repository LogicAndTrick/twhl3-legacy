<?
include 'funcgeneral.php';
include 'logins.php';
$lol = mysql_real_escape_string($_GET['part'])."%";
$uq = mysql_query("SELECT * FROM users WHERE uid LIKE '$lol' ORDER BY uid ASC LIMIT 5");
if (mysql_num_rows($uq) > 0 && $lol != '%')
{
?>
<table style="margin: 0 0 0 267px; background-color: white; width: 145px;" class="no-width">
<?
while ($ur = mysql_fetch_array($uq))
{
?>
<tr><td><a href="user.php?manage=<?=$ur['userID']?>"><?=$ur['uid']?></a></td></tr>
<?
}
?>
</table>
<?
}
elseif ($lol == '%')
{
?>
<p class="single-center-content-center">No text entered</p>
<?
}
else
{
?>
<p class="single-center-content-center">User Doesn't Exist</p>
<?
}
?>
function user()
{<?php

die();

	$con = mysql_connect("-----","-----","-----");

mysql_select_db("-----", $con);

$result = mysql_query("SELECT * FROM users");

include 'functions.php';

while($row = mysql_fetch_array($result))
{
	echo "if (document.getElementById('id').value==" . chr(34) . $row['userID'] . chr(34) . ")
	document.getElementById('txt').innerHTML=" . chr(34) . "User: " . $row['uid']. "<br>Current level: " . axslvl($row['lvl']) . chr(34) . "
	else ";
}

  
mysql_close($con);

?>document.getElementById('txt').innerHTML="User Doesn't Exist"

t=setTimeout("user()",10)

}

function level()
{
if (document.getElementById('lv').value=="0")
document.getElementById('lvl').innerHTML="New level: Newb"
else if (document.getElementById('lv').value=="1")
document.getElementById('lvl').innerHTML="New level: Member"
else if (document.getElementById('lv').value=="2")
document.getElementById('lvl').innerHTML="New level: Advanced Member"
else if (document.getElementById('lv').value=="3")
document.getElementById('lvl').innerHTML="New level: Content Contributer"
else if (document.getElementById('lv').value=="4")
document.getElementById('lvl').innerHTML="New level: Moderator"
else if (document.getElementById('lv').value=="5")
document.getElementById('lvl').innerHTML="New level: Admin"
else if (document.getElementById('lv').value=="-1")
document.getElementById('lvl').innerHTML="New level: Banned" 
else if (document.getElementById('lv').value==0)
document.getElementById('lvl').innerHTML="New level: Doesn't Exist"
else if (document.getElementById('lv').value>=6)
document.getElementById('lvl').innerHTML="New level: Godlike Admin"  
else
document.getElementById('lvl').innerHTML="New level: Doesn't Exist"

t=setTimeout("level()",10)

}
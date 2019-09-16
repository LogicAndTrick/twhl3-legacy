<?php

//------------------------------------------//
$timeparts = explode(" ",microtime());
$astarttime = $timeparts[1].substr($timeparts[0],1);
//------------------------------------------//

//------------------------------------------//
$timeparts = explode(" ",microtime());
$starttime = $timeparts[1].substr($timeparts[0],1);
//------------------------------------------//

	//include 'functions.php';
	//i'm sure this is a teeny tiny bit more efficient
	//i'd use some awesome proof using n/log(n) etc, but i wouldn't have any idea what the hell i was talking about.
	include 'funcgeneral.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_func_gen'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'funcshout.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_func_shout'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'funcforums.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_func_forum'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'logins.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_log'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	//content
	include 'header.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_head'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'sidebar.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_side'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'benchcent.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$astarttime = $timeparts[1].substr($timeparts[0],1);
//------------------------------------------//
	include 'rightbar.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_right'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
	include 'footer.php';
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_foot'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//

//------------------------------------------//
$timeparts = explode(" ",microtime());
$aendtime = $timeparts[1].substr($timeparts[0],1);
echo "Full execution time: " . bcsub($aendtime,$astarttime,6) . " seconds.";
//------------------------------------------//

arsort($bench);
echo '<br /><br /><table>';
foreach($bench as $key => $value)
{
	echo "<tr><td>$key:</td><td>$value</td></tr>\n";
}
echo '</table>';
?>
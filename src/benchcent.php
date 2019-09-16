<div class="center-bar">
	<?php
	
//------------------------------------------//
$timeparts = explode(" ",microtime());
$starttime = $timeparts[1].substr($timeparts[0],1);
//------------------------------------------//
	
		include 'latestnews.php';
		
//------------------------------------------//
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$bench['inc_cent_news'] = bcsub($endtime,$starttime,6);
$starttime = $endtime;
//------------------------------------------//
		
		include 'benchlatestforums.php';
	?>
</div>
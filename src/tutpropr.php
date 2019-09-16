<?
	include 'middle.php';
	
	$getprop = mysql_real_escape_string($_GET['id']);
	$proq = mysql_query("SELECT * FROM tutorialproposals WHERE propID = '$getprop'");

	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	if (mysql_num_rows($proq) == 0) fail("Proposal not found.","tutorial.php",true);
	
		// finalise proposal
		$reas = htmlfilter($_POST['reason']);
		mysql_query("UPDATE tutorialproposals SET accepted = '1',decisionby = '$usr',rejectreason = '$reas' WHERE propID = '$getprop'");
		$pror = mysql_fetch_array($proq);
		$propuser = $pror['propuser'];
		$propname = $pror['propname'];
		$thenow = gmt("U");
		// alert user
		mysql_query("INSERT INTO alertuser (alertuser,alerter,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$propuser','$usr','3','$thenow','0','Your tutorial proposal, $propname, has not been accepted, because: $reas','1')");
		
		header("Location: tutorial.php?viewprops=1");
?>
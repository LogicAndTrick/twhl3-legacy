<?
	include 'middle.php';
	
	$getprop = mysql_real_escape_string($_GET['id']);
	$proq = mysql_query("SELECT * FROM tutorialproposals WHERE propID = '$getprop'");
	
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	if (mysql_num_rows($proq) == 0) fail("Proposal not found.","tutorial.php",true);
	
		// finalise proposal
		mysql_query("UPDATE tutorialproposals SET accepted = '2',decisionby = '$usr' WHERE propID = '$getprop'");
		$pror = mysql_fetch_array($proq);
		$propuser = mysql_real_escape_string($pror['propuser']);
		$propgame = mysql_real_escape_string($pror['propgame']);
		$propname = mysql_real_escape_string($pror['propname']);
		$propdiff = mysql_real_escape_string($pror['propdifficulty']);
		$propkeys = mysql_real_escape_string($pror['propkeywords']);
		$thenow = gmt("U");
		// create tutorial
		mysql_query("INSERT INTO tutorials (catID, difficulty, authorid, pages, name, description, topics, example, date, editdate, hits, hitdate, waiting) VALUES ('$propgame', '$propdiff', '$propuser', '1', '$propname', '', '$propkeys', '', '$thenow', '0', '0', '$thenow', '1')");
		//get tutorial info
		$tutq = mysql_query("SELECT * FROM tutorials ORDER BY tutorialID DESC LIMIT 1");
		$tutr = mysql_fetch_array($tutq);
		$tutid = $tutr['tutorialID'];
		// create page
		mysql_query("INSERT INTO tutorialpages (tutorialid, page, subtitle, content) VALUES ('$tutid', '1', '', '')");
		// alert user
		mysql_query("INSERT INTO alertuser (alertuser,alerter,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$propuser','$usr','2','$thenow','$tutid','Your tutorial proposal, $propname, has been accepted!','1')");
		
		header("Location: tutorial.php?viewprops=1");
?>
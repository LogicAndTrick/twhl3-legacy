<?
	include 'middle.php';
	
	$getcomp = mysql_real_escape_string($_GET['id']);
	$thenow = gmt("U");
	$compq = mysql_query("SELECT * FROM compos WHERE compID = '$getcomp' AND compclose > '$thenow' AND compopen > 0");
	$countq = mysql_query("SELECT * FROM compentries WHERE entryuser = '$usr' && entrycomp = '$getcomp'");
	$count = mysql_num_rows($countq);
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to enter a competition.","competitions.php?comp=$getcomp",true);
	if (mysql_num_rows($compq) == 0) fail("The competition you are trying to enter is closed for entries, or doesn't exist.","competitions.php?comp=$getcomp",true);
	if ($count >= 20) fail("There is a limit of 20 entries for any one competition.","competitions.php?comp=$getcomp",true);
	
	$entname = htmlfilter($_POST['name']);
	$entcomm = htmlfilter($_POST['comments'],true);
	$entlinkn = "";
	$entfilen = "";
	
	$entip = $_SERVER['REMOTE_ADDR'];
	
	$file = false;
	$entlink = htmlfilter($_POST['link']);
	if ($entlink != "" && $_POST['choose']=='link')
	{
		$file = true;
		$entlinkn = $entlink;
	}
	elseif ($_POST['choose']=='upload' && uploadcheck("upload","archive",2))
	{
		$file = true;
		$filenum = $count + 1;
		$ext = ".".strtolower(end(explode(".", $_FILES["upload"]['name'])));
		$filename = "compo".str_pad($getcomp,3,"0",STR_PAD_LEFT)."_".preg_replace('/[^ 0-9a-z._-]/si', '', str_replace(" ","_",$uid))."_".$filenum.$ext;
		$ftmp = $_FILES['upload']['tmp_name'];
		move_uploaded_file($ftmp, "uploads/".$filename) or die ('Sorry, server is being stupid. Please retry your upload.');
		$entfilen = $filename;
	}
	
	if ($file)
	{
		mysql_query("INSERT INTO compentries (entrycomp,entryuser,entryname,entrydate,entrytext,entryfile,entrylink,entryip) VALUES ('$getcomp','$usr','$entname','$thenow','$entcomm','$entfilen','$entlinkn','$entip')");
		header("Location: competitions.php?thanks=$getcomp");		
	}
	else fail("You need to supply a link or a file with your entry.","competitions.php?comp=$getcomp");
?>
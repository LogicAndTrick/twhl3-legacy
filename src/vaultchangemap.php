<?php	

include 'middle.php';

$valid = true;
$changelink = true;
$changefile = true;
$changeimage = true;

$mapid = mysql_real_escape_string($_GET['id']);
if (!is_numeric($mapid)) $valid = false;

$game = mysql_real_escape_string($_POST['game']);
if (!is_numeric($game)) $valid = false;

$cat = mysql_real_escape_string($_POST['category']);
if (!is_numeric($cat)) $valid = false;

$name = htmlfilter($_POST['name']);
if (!isset($name) || $name == "") $valid = false;

$rmf = $_POST['RMF'];
$map = $_POST['MAP'];
$bsp = $_POST['BSP'];

$incl = 0;
$incl = ($rmf)?1:0;
$incl += ($map)?2:0;
$incl += ($bsp)?4:0;

$rating = $_POST['ratings'];
$upload = $_POST['uploads'];
$pmcomm = $_POST['pmcomment'];

$allowrating = ($rating)?1:0;
$allowupload = ($upload)?1:0;
$pmcomment = ($pmcomm)?1:0;

$uplink = mysql_real_escape_string($_POST['link']);
$fileup = false;
if ($uplink == "file") $fileup = true;

if ($fileup) $changelink = false;
else $changefile = false;

$link = htmlfilter($_POST['uplink']);
if (!eregi('^http://.+$',$link) && !eregi('^ftp://.+$',$link)) $link = '';
if ($fileup) $link = '';
if (!$fileup && (!$link || !isset($link) || $link == "")) $changelink = false;

$ferror = $_FILES['upload']['error'];
$fname = $_FILES['upload']['name'];
$ftype = $_FILES['upload']['type'];
$fsize = $_FILES['upload']['size'];
$ftmp = $_FILES['upload']['tmp_name'];
if ($fileup && (!$fname || !isset($fname) || $fname == "")) $changefile = false;
if ($ferror > 0) $changefile = false;
if (!is_uploaded_file($ftmp)) $changefile = false;
if (!eregi('.zip',substr($fname,-4)) && !eregi('.rar',substr($fname,-4))) $changefile = false;
if ($fsize > 2097152) $changefile = false;

if (!$fileup) $fsize = '0';

$text = htmlfilter($_POST['details']);
if (!isset($text) || $text == "") $valid = false;

$serror = $_FILES['image']['error'];
$sname = $_FILES['image']['name'];
$stype = $_FILES['image']['type'];
$ssize = $_FILES['image']['size'];
$stmp = $_FILES['image']['tmp_name'];
if (!$sname || !isset($sname) || $sname == "") $changeimage = false;
if ($serror > 0) $changeimage = false;
if (!is_uploaded_file($stmp)) $changeimage = false;
if (!eregi('.jpg',substr($sname,-4)) && !eregi('.png',substr($sname,-4))) $changeimage = false;
if ($ssize > 1048576) $changeimage = false;

$screen = 1;
if (eregi('.png',substr($sname,-4))) $screen = 2;

$rar = 0;
if (eregi('.rar',substr($fname,-4))) $rar = 1;

if (!isset($usr) || $usr == "") $valid = false;

$thenow = gmt("U");

if ($valid)
{
	//add map entry
	mysql_query("UPDATE maps SET name = '$name',cat = '$cat',game = '$game',included = '$incl',info = '$text',editdate = '$thenow',pmcomment = '$pmcomment',allowrating = '$allowrating',allowupload = '$allowupload' WHERE mapID = '$mapid'");
	//save files
	if ($changelink)
	{
		mysql_query("UPDATE maps SET filesize = '0', file = '$link' WHERE mapID = '$mapid'");
	}
	elseif ($fileup && $changefile)
	{
		move_uploaded_file($ftmp, "mapvault/".$mapid.substr($fname,-4)) or die ('Sorry, server is being stupid. Please retry your upload.');
		mysql_query("UPDATE maps SET filesize = '$fsize', file = '$link', israr = '$rar' WHERE mapID = '$mapid'");
	}
	if ($changeimage)
	{
		//resize image
		$tmpname = "mapvault/tmp_".$mapid.substr($sname,-4);
		copy($stmp, $tmpname);
		$osize = getimagesize($tmpname);
		if (eregi('.jpg',substr($sname,-4)))
			$oimg = @imagecreatefromjpeg($tmpname);
		else
			$oimg = @imagecreatefrompng($tmpname);
		
		unlink($tmpname);
		if ($osize[0] && $osize[1] && $oimg != '')
		{
			$width = $osize[0];
			$height = $osize[1];
			$type = $osize['mime'];
			
			$bigimg = resizexy($width, $height, 320, 240);
			$b_width = $bigimg[0];
			$b_height = $bigimg[1];
			
			$smlimg = resizexy($width, $height, 160, 120);
			$s_width = $smlimg[0];
			$s_height = $smlimg[1];
			
			$bimg = ImageCreateTrueColor($b_width, $b_height);
			$simg = ImageCreateTrueColor($s_width, $s_height);
			imageAntiAlias($bimg,true);
			imageAntiAlias($simg,true);
			ImageCopyResampled($bimg, $oimg, 0, 0, 0, 0, $b_width, $b_height, $width, $height);
			ImageCopyResampled($simg, $oimg, 0, 0, 0, 0, $s_width, $s_height, $width, $height);
			
			if ($type=='image/jpeg')
			{
				ImageJpeg($bimg,"mapvault/".$mapid.".jpg");
				ImageJpeg($simg,"mapvault/".$mapid."_small.jpg");
			}
			elseif ($type=='image/png')
			{
				
				$background = imagecolorallocate($bimg, 0, 0, 0);
				ImageColorTransparent($bimg, $background);
				imagealphablending($bimg, false);
				imagesavealpha ($bimg, true);
				
				$sbackground = imagecolorallocate($simg, 0, 0, 0);
				ImageColorTransparent($simg, $sbackground);
				imagealphablending($simg, false);
				imagesavealpha ($simg, true);
				
				ImagePng($bimg,"mapvault/".$mapid.".png");
				ImagePng($simg,"mapvault/".$mapid."_small.png");
			}
			
			imagedestroy($oimg);
			imagedestroy($bimg);
			imagedestroy($simg);
			
			mysql_query("UPDATE maps SET screen = '$screen' WHERE mapID = '$mapid'");
		}
	}
	//redirect to map page
	header("Location: vault.php?map=".$mapid);
}
else
{
	include 'header.php';
	include 'sidebar.php';
	$back = "vault.php?submit=1";
	if (!is_numeric($game))
		$problem = "game";
	if (!is_numeric($cat))
		$problem = "cat";
	if (!isset($name) || $name == "")
		$problem = "name";
	if (!isset($text) || $text == "")
		$problem = "text";
	if (!isset($usr) || $usr == "")
		$problem = "not logged in";
	include 'failure.php';
	include 'bottom.php';
}
?>
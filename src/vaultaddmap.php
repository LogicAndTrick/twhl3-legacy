<?php	

include 'middle.php';

$valid = true;

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

$link = htmlfilter($_POST['uplink']);
if (!eregi('^http://.+$',$link) && !eregi('^https://.+$',$link) && !eregi('^ftp://.+$',$link)) $link = '';
if ($fileup) $link = '';
if (!$fileup && (!$link || !isset($link) || $link == "")) $valid = false;

$ferror = $_FILES['upload']['error'];
$fname = $_FILES['upload']['name'];
$ftype = $_FILES['upload']['type'];
$fsize = $_FILES['upload']['size'];
$ftmp = $_FILES['upload']['tmp_name'];

if ($fileup && (!uploadcheck("upload","archive",2))) $valid = false;

if (!$fileup) $fsize = '0';

$text = htmlfilter($_POST['details']);
if (!isset($text) || $text == "") $valid = false;

$serror = $_FILES['image']['error'];
$sname = $_FILES['image']['name'];
$stype = $_FILES['image']['type'];
$ssize = $_FILES['image']['size'];
$stmp = $_FILES['image']['tmp_name'];
if (!$sname || !isset($sname) || $sname == "") $valid = false;
if ($serror > 0) $valid = false;
if (!is_uploaded_file($stmp)) $valid = false;
if (!eregi('.jpg',substr($sname,-4)) && !eregi('.png',substr($sname,-4))) $valid = false;
if ($ssize > 1048576) $valid = false;

$screen = 1;
if (eregi('.png',substr($sname,-4))) $screen = 2;

$rar = 0;
if (eregi('.rar',substr($fname,-4))) $rar = 1;

if (!isset($usr) || $usr == "") $valid = false;

$thenow = gmt("U");

if ($valid)
{
	//add map entry
	mysql_query("INSERT INTO maps (owner,name,cat,game,included,info,postdate,editdate,filesize,file,screen,pmcomment,views,downloads,allowrating,allowupload,ratings,rating,israr) VALUES ('$usr','$name','$cat','$game','$incl','$text','$thenow','0','$fsize','$link','$screen','$pmcomment','0','0','$allowrating','$allowupload','0','0','$rar')");
	//save files
	$newid = mysql_insert_id($dbh);
	if ($fileup)
	{
		move_uploaded_file($ftmp, "mapvault/".$newid.substr($fname,-4)) or die ('Sorry, server is being stupid. Please retry your upload.');
	}
	//resize image
	$tmpname = "mapvault/tmp_".$newid.substr($sname,-4);
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
			ImageJpeg($bimg,"mapvault/".$newid.".jpg");
			ImageJpeg($simg,"mapvault/".$newid."_small.jpg");
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
			
			ImagePng($bimg,"mapvault/".$newid.".png");
			ImagePng($simg,"mapvault/".$newid."_small.png");
		}
		
		imagedestroy($oimg);
		imagedestroy($bimg);
		imagedestroy($simg);
	}
	//user maps +1
	mysql_query("UPDATE users SET stat_maps = stat_maps+1 WHERE userID = '$usr'");
	//redirect to map page
	header("Location: vault.php?map=".$newid);
}
else
{
	include 'header.php';
	include 'sidebar.php';
	$back = "vault.php?submit=1";
	if (!is_numeric($game))
		$problem = "game";
	elseif (!is_numeric($cat))
		$problem = "cat";
	elseif (!isset($name) || $name == "")
		$problem = "name";
	elseif (!$fileup && (!$link || !isset($link) || $link == ""))
		$problem = "link";
	elseif ($fileup && (!$fname || !isset($fname) || $fname == ""))
		$problem = "file";
	elseif ($ferror > 0)
		$problem = "file error";
	elseif (!is_uploaded_file($ftmp))
		$problem = "file not uploaded";
	elseif (!eregi('.zip',substr($fname,-4)) && !eregi('.rar',substr($fname,-4)))
		$problem = "file type";
	elseif ($fsize > 2097152)
		$problem = "file size";
	elseif (!isset($text) || $text == "")
		$problem = "text";
	elseif (!$sname || !isset($sname) || $sname == "")
		$problem = "image";
	elseif ($serror > 0)
		$problem = "image error";
	elseif (!is_uploaded_file($stmp))
		$problem = "image not uploaded";
	elseif (!eregi('.jpg',substr($sname,-4)) && !eregi('.png',substr($sname,-4)))
		$problem = "image type";
	elseif ($ssize > 1048576)
		$problem = "image size";
	elseif (!isset($usr) || $usr == "")
		$problem = "not logged in";
    $problem .= '. Make sure you have filled in the required information, including the screenshot.';
	include 'failure.php';
	include 'bottom.php';
}
?>
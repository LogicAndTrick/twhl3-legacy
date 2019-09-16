<?php

include 'middle.php';

	function findfilenumber($prefix) // tutpics/filename
	{
		$not_finished = true;
		$counter = 1;
		while ($not_finished)
		{
			if (is_file($prefix.$counter.".jpg")) $counter += 1;
			elseif (is_file($prefix.$counter.".png")) $counter += 1;
			else $not_finished = false;
		}
		return $counter;
	}
	
	function uploadcheckandconfirm($postname,$tutorialid,$tutorialname)
	{
		$upload = true;
		
		$ferror = $_FILES[$postname]['error'];
		$fname = $_FILES[$postname]['name'];
		$ftype = $_FILES[$postname]['type'];
		$fsize = $_FILES[$postname]['size'];
		$ftmp = $_FILES[$postname]['tmp_name'];
		if (!$fname || !isset($fname) || $fname == "") $upload = false;
		if ($ferror > 0) $upload = false;
		if (!is_uploaded_file($ftmp)) $upload = false;
		if (!eregi('.jpg',substr($fname,-4)) && !eregi('.png',substr($fname,-4))) $upload = false;
		if ($fsize > 1048576) $upload = false;
		
		$ext = ".".strtolower(end(explode(".", $fname)));
		$basefilename = strtolower(preg_replace('/[^ 0-9a-z._-]/si', '', str_replace(" ","_",$tutorialname)))."_";
		$filename = $basefilename.findfilenumber("tutpics/".$basefilename).$ext;
		
		if ($upload)
		{
			$osize = getimagesize($ftmp);
			if ($ext == ".jpg")
				$oimg = @imagecreatefromjpeg($ftmp);
			else
				$oimg = @imagecreatefrompng($ftmp);
			
			if ($osize[0] && $osize[1] && $oimg != '')
			{
				
				$width = $osize[0];
				$height = $osize[1];
				$type = $osize['mime'];
				
				$sizes = resizexy($width, $height, 600, 600);
				$nwidth = $sizes[0];
				$nheight = $sizes[1];
				
				$tutpic = ImageCreateTrueColor($nwidth, $nheight);
				if ($type=='image/png')
				{
					$background = imagecolorallocate($tutpic, 0, 0, 0);
					ImageColorTransparent($tutpic, $background);
					imagealphablending($tutpic, false);
					imagesavealpha ($tutpic, true);
				}
				imageAntiAlias($tutpic,true);
				ImageCopyResampled($tutpic, $oimg, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
				
				if ($type=='image/jpeg')
				{
					ImageJpeg($tutpic,"tutpics/".$filename);
				}
				elseif ($type=='image/png')
				{
					ImagePng($tutpic,"tutpics/".$filename);
				}
				
				imagedestroy($oimg);
				imagedestroy($tutpic);
			}

            $filename = mysql_real_escape_string($filename);
			mysql_query("INSERT INTO tutorialpics (tutID, piclink) VALUES ('$tutorialid', '$filename')");
		}
	}

	$editid=mysql_real_escape_string($_GET['id']);
	$pageid=mysql_real_escape_string($_GET['page']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$editid'");
	$valid = false;
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$authorid = $row['authorid'];
		$tutname = $row['name'];
		$valid = true;
	}
	if ($valid && ((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25))))
	{
		uploadcheckandconfirm('image1',$editid,$tutname);
		uploadcheckandconfirm('image2',$editid,$tutname);
		uploadcheckandconfirm('image3',$editid,$tutname);
		header("Location: tutorial.php?edit=$editid&page=$pageid");
	}
	else fail("Tutorial not found, or you don't have permission to do this.","tutorial.php?edit=$editid&page=$pageid",true);


?>
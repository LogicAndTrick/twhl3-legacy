<?
	include 'middle.php';	
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	
	$type = $_POST['avselect'];
	
	if (!is_numeric($type) || $type < -1 || $type > 30) fail("That selection is not valid. Your avatar was not changed.","user.php?control&amp;avatar",true);
	
	$newavatar = 1;
	
	if ($type > 0) $newavatar = (int)$type;
	
	if ($type == "-1" && uploadcheck("avup","image"))
	{
		$ext = ".".strtolower(end(explode(".", $_FILES['avup']['name'])));
		$filename = $usr.$ext;
		
		$tmpname = "avatars/tmp_".$usr.$ext;
		copy($_FILES['avup']['tmp_name'], $tmpname);
		
		$osize = getimagesize($tmpname);
		if ($ext == ".jpg")
			$oimg = @imagecreatefromjpeg($tmpname);
		else
			$oimg = @imagecreatefrompng($tmpname);
		
		unlink($tmpname);
		
		if ($osize[0] && $osize[1] && $oimg != '')
		{
			
			$width = $osize[0];
			$height = $osize[1];
			$type = $osize['mime'];
			
			$sizes = resizexy($width, $height, 100, 100);
			$nwidth = $sizes[0];
			$nheight = $sizes[1];
			
			$smallsizes = resizexy($width, $height, 45, 45);
			$nwidth_small = $smallsizes[0];
			$nheight_small = $smallsizes[1];
			
			$avatar = ImageCreateTrueColor($nwidth, $nheight);
			if ($type=='image/png')
			{
				$background = imagecolorallocate($avatar, 0, 0, 0);
				ImageColorTransparent($avatar, $background);
				imagealphablending($avatar, false);
				imagesavealpha ($avatar, true);
			}
			imageAntiAlias($avatar,true);
			ImageCopyResampled($avatar, $oimg, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
			
			$avatar_small = ImageCreateTrueColor(45,45);
			if ($type=='image/png')
			{
				$sbackground = imagecolorallocate($avatar_small, 0, 0, 0);
				ImageColorTransparent($avatar_small, $sbackground);
				imagealphablending($avatar_small, false);
				imagesavealpha ($avatar_small, true);
			}
			else
			{
				imagefill($avatar_small,0,0,imagecolorallocate($avatar_small,255,255,255));
			}
			imageAntiAlias($avatar_small,true);
			$wleft = (45-$nwidth_small)/2;
			$htop = (45-$nheight_small)/2;
			ImageCopyResampled($avatar_small, $oimg, $wleft, $htop, 0, 0, $nwidth_small, $nheight_small, $width, $height);
			
			@unlink("avatars/$usr".".jpg");
			@unlink("avatars/$usr"."_small.jpg");
			@unlink("avatars/$usr".".png");
			@unlink("avatars/$usr"."_small.png");
			
			if ($type=='image/jpeg')
			{
				ImageJpeg($avatar,"avatars/$usr".".jpg");
				ImageJpeg($avatar_small,"avatars/$usr"."_small.jpg");
				//echo '<img src="test/'.$first.'.jpg" /><img src="test/'.$first.'_small.jpg" /><br/>';
			}
			elseif ($type=='image/png')
			{
				ImagePng($avatar,"avatars/$usr".".png");
				ImagePng($avatar_small,"avatars/$usr"."_small.png");
			}
			
			imagedestroy($oimg);
			imagedestroy($avatar);
			imagedestroy($avatar_small);
			
			$newavatar = -1;
			if ($ext == '.png') $newavatar = -2;
		}
	}
	
	mysql_query("UPDATE users SET avtype = '$newavatar' WHERE userID = '$usr' LIMIT 1");
	
	header("Location: user.php?id=$usr");
?>
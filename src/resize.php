<?php
ini_set('allow_url_fopen', 'on');
if (isset($_GET['url']))
{
// Max height and width
$max_width = 500;
$max_height = 500;
if (isset($_GET['max']) and $_GET['max']!="")
{
	$max_width = $_GET['max'];
	$max_height = $_GET['max'];
}

	// Path to your jpeg
	$upfile="avatars/".$_GET['url'];
	$upfile=str_replace(" ","%20",$upfile);
    
    if ($size = GetImageSize($upfile)) // Read the size
	{
			$width = $size[0];
			$height = $size[1];
			$type = $size['mime'];
			
			$tn_width = $width;
            $tn_height = $height;
			
          // Proportionally resize the image to the
          // max sizes specified above
         
          $x_ratio = $max_width / $width;
          $y_ratio = $max_height / $height;

        if( ($width <= $max_width) && ($height <= $max_height) )
		{
			$tn_width = $width;
			$tn_height = $height;
		}
		elseif (($width > $max_width) and ($height <= $max_height))
		{
			$tn_height = ceil(($x_ratio) * $height);
			$tn_width = $max_width;
		}
		elseif (($width <= $max_width) and ($height > $max_height))
		{
			$tn_height = $max_height;
			$tn_width = ceil(($y_ratio) * $width);
		}
		else
		{
			if ($width>$height)
			{
				$tn_height = ceil(($x_ratio) * $height);
				$tn_width = $max_width;
			}
			else
			{
				$tn_height = $max_height;
				$tn_width = ceil(($y_ratio) * $width);
			}
		}
	
     // Increase memory limit to support larger files
    
     ini_set('memory_limit', '32M');
	 
	 if ($type=='image/jpeg')
	 {
	     Header("Content-type: image/jpeg");
	     // Create the new image!
	     $src = ImageCreateFromJpeg($upfile);
	     $dst = ImageCreateTrueColor($tn_width, $tn_height);
	     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	     ImageJpeg($dst);;
		 // Destroy the images
		 ImageDestroy($src);
		 ImageDestroy($dst);
	}
	elseif ($type=='image/png')
	{
	
	
		$dst = imagecreatetruecolor($tn_width, $tn_height);
 
		/* making the new image transparent */
		$background = imagecolorallocate($dst, 0, 0, 0);
		ImageColorTransparent($dst, $background); // make the new temp image all transparent
		imagealphablending($dst, false); // turn off the alpha blending to keep the alpha channel
		imagesavealpha ($dst, true); // Save full alpha
		imageAntiAlias($dst,true);
 
		/* Resize the PNG file */
		/* use imagecopyresized to gain some performance but loose some quality */
		//imagecopyresized($temp, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
	
	
	     Header("Content-type: image/png");
	     // Create the new image!
	     $src = ImageCreateFromPng($upfile);
	     //$dst = ImageCreateTrueColor($tn_width, $tn_height);
	     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	     ImagePng($dst);
		 // Destroy the images
		 ImageDestroy($src);
		 ImageDestroy($dst);
	}
	}

}
?>
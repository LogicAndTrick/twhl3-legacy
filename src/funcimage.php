<?
/*
TWHL3
IMAGE FUNCTIONS
To be included in pages that resize images
Functions used by image resize code
*/

function resizexy($width, $height, $maxwidth, $maxheight)
{
	$max_width = $maxwidth;
	$max_height = $maxheight;
	
	$x_ratio = $max_width / $width;
	$y_ratio = $max_height / $height;
	
	$tn_width = $width;
	$tn_height = $height;

	if( ($width <= $max_width) && ($height <= $max_height) )
	{
		$tn_width = $width;
		$tn_height = $height;
	}
	elseif (($width > $max_width) && ($height <= $max_height))
	{
		$tn_height = ceil(($x_ratio) * $height);
		$tn_width = $max_width;
	}
	elseif (($width <= $max_width) && ($height > $max_height))
	{
		$tn_height = $max_height;
		$tn_width = ceil(($y_ratio) * $width);
	}
	else
	{
		if ($y_ratio>$x_ratio)
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
	return array($tn_width,$tn_height);
}

?>
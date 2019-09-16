<?php
session_start();

/*
* File: CaptchaSecurityImages.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 03/08/06
* Updated: 07/02/07
* Requirements: PHP 4/5 with GD and FreeType libraries
* Link: http://www.white-hat-web-design.co.uk/articles/php-captcha.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/

class CaptchaSecurityImages {

	var $font = 'GOTHIC.TTF';

	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '2345679BCDFGHJKMNPQRSTVWXYZ';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function CaptchaSecurityImages($width='200',$height='40',$characters='6') {
		$code = $this->generateCode($characters);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, mt_rand(15, 100), mt_rand(15, 100), mt_rand(15, 100));
		$text_color = imagecolorallocate($image, mt_rand(160, 240), mt_rand(160, 240), mt_rand(160, 240));
		$noise_color = imagecolorallocate($image, mt_rand(80, 160), mt_rand(80, 160), mt_rand(80, 160));
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		
		/* big fat line */
		function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
		{
		    /* this way it works well only for orthogonal lines
		    imagesetthickness($image, $thick);
		    return imageline($image, $x1, $y1, $x2, $y2, $color);
		    */
		    if ($thick == 1) {
		        return imageline($image, $x1, $y1, $x2, $y2, $color);
		    }
		    $t = $thick / 2 - 0.5;
		    if ($x1 == $x2 || $y1 == $y2) {
		        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
		    }
		    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
		    $a = $t / sqrt(1 + pow($k, 2));
		    $points = array(
		        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
		        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
		        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
		        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
		    );
		    imagefilledpolygon($image, $points, 4, $color);
		    return imagepolygon($image, $points, 4, $color);
		}
		
		$first=mt_rand(0,$height);
		$second=$height-$first;
		imagelinethick($image, 0, $first, $width, $second, $text_color, 3);
		
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		
		
		function drawboldtext($image, $size, $angle, $x_cord, $y_cord, $color, $fontfile, $text)
		{
			//$color = ImageColorAllocate($image, $r, $g, $b);
			$_x = array(1, 0, 1, 0, -1, -1, 1, 0, -1);
			$_y = array(0, -1, -1, 0, 0, -1, 1, 1, 1);
			for($n=0;$n<=8;$n++)
			{
				ImageTTFText($image, $size, $angle, $x_cord+$_x[$n], $y_cord+$_y[$n], $color, $fontfile, $text);
			}
		} 
		
		drawboldtext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code);// or die('Error in imagettftext function');
		
		
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code'] = $code;
	}

}

$width = isset($_GET['width']) ? $_GET['width'] : '200';
$height = isset($_GET['height']) ? $_GET['height'] : '40';
$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';

$captcha = new CaptchaSecurityImages($width,$height,$characters);

?>
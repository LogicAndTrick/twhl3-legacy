<?
/*
TWHL3
MOTM FUNCTIONS
To be included in motm pages
Functions used by motm exclusively
*/


// arr must be a 3-element array with values 0-255
function arraytohexcolour($arr)
{
	$red = str_pad(base_convert(round($arr[0]),10,16), 2, "0", STR_PAD_LEFT);
	$blue = str_pad(base_convert(round($arr[1]),10,16), 2, "0", STR_PAD_LEFT);
	$green = str_pad(base_convert(round($arr[2]),10,16), 2, "0", STR_PAD_LEFT);
	return "#".$red.$blue.$green;
}

// val is a number between 1 and 100
// c1-3 are arrays featuring the RGB values of three colours. default is red-orange-green
// c1: val = 0, c2: val = 50, c3: val = 100
function tri_colour($val,$c1 = Array(255,0,0),$c2 = Array(255,138,0),$c3 = Array(0,150,0))
{
	if ($val == 0) return arraytohexcolour($c1);
	if ($val == 50) return arraytohexcolour($c2);
	if ($val == 100) return arraytohexcolour($c3);
	
	$resr = 0;
	$resg = 0;
	$resb = 0;
	
	if ($val < 50)
	{
		$first = $c2;
		$second = $c1;
		$val1 = $val;
	}
	else
	{
		$first = $c3;
		$second = $c2;
		$val1 = $val - 50;
	}
	
	$resr = (($first[0]*$val1)+($second[0]*(50-$val1)))/50;
	$resg = (($first[1]*$val1)+($second[1]*(50-$val1)))/50;
	$resb = (($first[2]*$val1)+($second[2]*(50-$val1)))/50;
	
	$res = Array($resr,$resg,$resb);
	return arraytohexcolour($res);
}

function motm_format($str)
{
	$bbcode = array(
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[sub\](.+?)\[/sub\]#si',
		'#\[youtube\](.+?)\[/youtube\]#si'
	);
	
	$html = array(
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'</p><h3>\\1</h3><p class="single-center-content">',
		'</p><p class="single-center-content-center"><object type="application/x-shockwave-flash" data="\\1" width="425" height="355"><param name="movie" value="\\1" /></object></p><p class="single-center-content">'
	);
	
	//'</p><p class="single-center-content-center"><object width="425" height="355"><param name="movie" value="\\1"></param><param name="wmode" value="transparent"></param><embed src="\\1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object></p><p class="single-center-content">'
	
	$str=preg_replace($bbcode, $html, $str);
	
	return $str;
}

?>
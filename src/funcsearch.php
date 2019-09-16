<?
/*
TWHL3
SEARCH FUNCTIONS
To be included in search pages
Functions used by search exclusively
*/

// returns the WHERE part of an SQL query to search for str.
// uses google-like formatting: "exact phrase" +must_include -must_not_include and the rest is a substring search.
// assumes str is NOT escaped
// must be connected to the database
function search_format($str,$field)
{
	$qwhere = "";
	$pwhere = "";
	$mwhere = "";
	$rwhere = "";
	$str = trim($str);
	
	// first get quotes
	preg_match_all('/"(.+?)"/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/"(.+?)"/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$qwhere .= "$field LIKE '%$matchstr%' OR ";
	}
	$qwhere = substr($qwhere, 0, -4);
	$str = str_ireplace('"','',$str);
	
	//next get +
	preg_match_all('/\+([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/\+([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$pwhere .= "$field LIKE '% $matchstr %' AND ";
	}
	$pwhere = substr($pwhere, 0, -5);
	$str = str_ireplace('+','',$str);
	
	//then get -
	preg_match_all('/-([^ ]+)/si', $str, $res, PREG_PATTERN_ORDER);
	for ($i = 0; $i < count($res[0]); $i++) {
		# Matched text = $res[0][$i];
		$match = $res[0][$i];
		$matchstr = "";
		if (preg_match('/-([^ ]+)/si', $match, $regs)) {
			$matchstr = mysql_real_escape_string($regs[1]);
		}
		$str = str_ireplace($match,'',$str);
		$mwhere .= "$field NOT LIKE '% $matchstr %' AND ";
	}
	$mwhere = substr($mwhere, 0, -5);
	$str = str_ireplace('-','',$str);
	
	//then the rest of it
	$strs = explode(" ",$str);
	foreach ($strs as $tok)
	{
		$tok = htmlfilter($tok);
		if ($tok != "") $rwhere .= "$field LIKE '%$tok%' OR ";
	}
	$rwhere = substr($rwhere, 0, -4);
	
	$compsto = "$field != ".'""';
	if ($qwhere != "" && $rwhere != "")
	{
		$compsto = "($qwhere OR $rwhere)";
	}
	elseif ($qwhere != "")
	{
		$compsto = "($qwhere)";
	}
	elseif ($rwhere != "")
	{
		$compsto = "($rwhere)";
	}
	
	$compsta = "$field != ".'""';
	if ($pwhere != "" && $mwhere != "")
	{
		$compsta = "($pwhere AND $mwhere)";
	}
	elseif ($pwhere != "")
	{
		$compsta = "($pwhere)";
	}
	elseif ($mwhere != "")
	{
		$compsta = "($mwhere)";
	}
	
	$compst = $compsto . " AND " . $compsta;
	
	//return $qwhere . " AND " . $pwhere . " AND " . $mwhere . " AND " . $rwhere;
	return $compst;
}

// arr is an array of all the fields to search in
// str, same as above.
// returns the WHERE part of an sql query to search for str in all fields
function search_all($str,$arr)
{
	$where = "";
	foreach ($arr as $field)
	{
		if ($field != "") $where .= "(" . search_format($str,$field) . ") OR ";
	}
	$where = substr($where, 0, -4);
	return $where;
}

?>
<?
include 'middle.php';


$games=$_POST['game'];
$gamelist="0";
$iszero = false;
if ($games){
	$gamelist = "";
	foreach ($games as $game)
	{
		$gamelist .= ',' . $game;
		if ($game == '0') $iszero = true;
	}
	$gamelist = substr($gamelist,1);
	if ($iszero) $gamelist = '0';
}
$gamelist = mysql_real_escape_string($gamelist);

$cats=$_POST['cat'];
$catlist="0";
$iszero = false;
if ($cats){
	$catlist = "";
	foreach ($cats as $cat)
	{
		$catlist .= ',' . $cat;
		if ($cat == '0') $iszero = true;
	}
	$catlist = substr($catlist,1);
	if ($iszero) $catlist = '0';
}

$incbsp = false;
$incrmf = false;
$incmap = false;
if ($_POST['RMF']) $incrmf = true;
if ($_POST['MAP']) $incmap = true;
if ($_POST['BSP']) $incbsp = true;

$inclist = ((!$incrmf && !$incmap && !$incbsp)?',0':'').
(($incrmf && !$incmap && !$incbsp)?',1':'').
((!$incrmf && $incmap && !$incbsp)?',2':'').
((($incrmf || $incmap) && !$incbsp)?',3':'').
((!$incrmf && !$incmap && $incbsp)?',4':'').
((($incrmf || $incbsp) && !$incmap)?',5':'').
((($incmap || $incbsp) && !$incrmf)?',6':'').
(($incrmf || $incmap || $incbsp)?',7':'');

$inclist = substr($inclist,1);
$inclist = mysql_real_escape_string($inclist);

$minrating = mysql_real_escape_string($_POST['minrating']);
if (!is_numeric($minrating) || $minrating > 5 || $minrating < 0) $minrating = 0;

$sortorder = mysql_real_escape_string($_POST['sort']);
if ($sortorder != 'postdate' && $sortorder != 'avgrating' && $sortorder != 'ratings' && $sortorder != 'views' && $sortorder != 'downloads') $sortorder = 'postdate';

$ascdesc = 'DESC';
$ascdesc = mysql_real_escape_string($_POST['order']);
if ($ascdesc != 'ASC' && $ascdesc != 'DESC') $ascdesc = 'DESC';
$ascdesc=strtolower($ascdesc);

//echo "gamelist = $gamelist <br /><br />catlist = $catlist <br /><br />inclist = $inclist <br /><br />minrating = $minrating <br /><br />sortorder = $sortorder <br /><br />ascdesc = $ascdesc";

header("Location: vault.php?advfilter=1&games=$gamelist&cats=$catlist&inc=$inclist&min=$minrating&sort=$sortorder&arr=$ascdesc&page=1");
?>
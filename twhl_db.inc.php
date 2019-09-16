<?

$dbh = @mysql_connect('-----','-----','-----') or die("TWHL will be back shortly.");
mysql_select_db("-----",$dbh);


function getztime() {
  // Note: this function does not report errors!
  // NB: This version of the function does not work correctly - it does not return GMT time by
  // default.
  // Returns time adjusted to supplied timezone, or to GMT if no timezone is passed. 
  // If no time is provided, the current time is used. 
  // So: No arguments: current GMT time
  //     Zone only: returns the current time at zone.
  //     Both args: returns time for zone.
  // Args: Timezone (hours), Time (seconds)
  $tme = time()-date('Z');$tz = 0; // Set defaults
  if (func_num_args() > 1) $tme = func_get_arg(1);
  if (func_num_args() > 0) $tz = func_get_arg(0);
  
  return $tme+($tz*3600);
}

function sqlquote($str) {
  // smart search string escaping. escapes % and _, and converts * and ?, but keeps them if 
  // they are escaped.
  $str = str_replace('%','\%',$str);
  $str = str_replace('_','\_',$str); 
  $str = str_replace('\*','##*##',$str);
  $str = str_replace('\?','##?##',$str);
  $str = str_replace('*','%',$str);
  $str = str_replace('?','_',$str);
  $str = str_replace('##*##','*',$str);
  $str = str_replace('##?##','?',$str);
  return $str;
}

function qstr($q,$k,$s) {
  // Returns the querystring with a selected var altered. VERY USEFUL and PERFECT!
  // This function can be used to modify a return from this function, as a way 
  // $q:	querystring var to work on
  // $k:	value to give $q
  // $s:	the subject string (use '' or 0 to default to current page qstring);
  if (empty($s)) $s = $_SERVER['QUERY_STRING'];
  if (!empty($s)) {
    if (eregi($q.'=.*&',$s)) $r = eregi_replace($q.'=[^&]*&',$q.'='.$k.'&',$s);
    elseif (eregi($q.'=.*$',$s)) $r = eregi_replace($q.'=.*$',$q.'='.$k,$s);
    else $r = $s.'&'.$q.'='.$k;
  }
  else $r = $q.'='.$k;
  return $r;
}

?>
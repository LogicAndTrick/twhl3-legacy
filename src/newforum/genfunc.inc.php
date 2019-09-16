<?
function preparebody($body) {
  $body = trim($body);
  $body = str_replace("\r\n","\n",$body);
  $body = str_replace("\r","\n",$body); // strips out return characters but converts them for mac
  $body = str_replace('<','&lt;',$body);
  $body = str_replace('>','&gt;',$body);
  if (strlen($body) > 60000) {
    $body = substr($body,0,60000).'\n[message length limit exceeded]';
  }
  return $body;
}

// TEH funxion
function post_format($str, $simple = false, $moderatorPost = FALSE) {

  // Create arrays of smilies and their corresponding images
  static $smilies, $smilies_a;
  if (!isset($smilies)) {
    $srs = mysql_query('SELECT emot_file, emot_code, emot_alt FROM twhl_emoticons',$GLOBALS['dbh']) or die('Database error');
    if (mysql_num_rows($srs) > 0) {
      while ($srow2 = mysql_fetch_array($srs)) {
        foreach (explode(',,',$srow2['emot_code']) as $val) {
          $smilies_a[] = "#( |^|\n)".preg_quote($val,'#').'#';
          $smilies[] = '\\1<IMG SRC="newforum/smilies/'.$srow2['emot_file'].'" BORDER="0" WIDTH="15" HEIGHT="15" ALT="'.$srow2['emot_alt'].' - '.$val.'">';
        }
      }
    }
    else {
      $smilies[] = ''; // dummy value so it's set and doesn't try again
    }
  }

  // autolinking (from c/box)
  //$str = eregi_replace('([[:space:]]|^)(http://|ftp://)([a-z0-9.-]+\.[a-z0-9-]+[][a-z0-9.?&%!@#$^*()+=/\,_-]*[^.,?! ])','\\1[url]\\2\\3[/url]',$str);
  //$str = eregi_replace('([[:space:]]|^)(www\.[a-z0-9.-]+\.[a-z0-9-]+[][a-z0-9.?&%!@#$^*()+=/\,_-]*[^.,?! ])','\\1[url]\\2[/url]',$str);
  //$str = eregi_replace('([[:space:]]|^)(([a-z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-z0-9\-]+\.)+))([a-z]{2,4}|[0-9]{1,3})(\]?))','\\1[email]\\2[/email]',$str);

  if (!isset($_SESSION['forumimages']) || $_SESSION['forumimages']) {
    if (!$simple) $str = preg_replace("#\[img\](([a-z]{3,6})://([^ \"\n\r]+?))\[/img\]#si",'<IMG SRC="\\1" border="0">',$str);      
  }

  // bbcode
  $str = preg_replace('#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si','<A HREF="\\1\\2" TARGET="_blank">\\3</A>',$str);    
  $str = preg_replace('#\[url=([^[" ]+)\](.+?)\[/url\]#si','<A HREF="http://\\1" TARGET="_blank">\\2</A>',$str);
  $str = preg_replace("#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",'<A HREF="\\1\\2" TARGET="_blank">\\1\\2</A>',$str);      
  $str = preg_replace("#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",'<A HREF="http://\\1" TARGET="_blank">\\1</A>',$str);      
  $str = preg_replace("#\[email=([^[]+)\](.+?)\[/email\]#si",'<A HREF="mailto:\\1">\\2</A>',$str);
  $str = preg_replace("#\[email\]([^]\"\n ]+?)\[/email\]#si",'<A HREF="mailto:\\1">\\1</A>',$str);      
  
  // autolinking
  
  $str = " " . $str;
  
  $str = preg_replace("#([]\n ])([a-z]{3,6})://([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^][\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"\\2://\\3.\\4\\5\" target=\"_blank\">\\2://\\3.\\4\\5</a>", $str);
  $str = preg_replace("#([]\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str);
  $str = preg_replace("#([]\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+[^][\".,?!;:\n\t ])#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $str);

  $str = substr($str, 1);
  
  $str = preg_replace('#\[b\](.+?)\[/b\]#si','<B>\\1</B>',$str);
  $str = preg_replace('#\[i\](.+?)\[/i\]#si','<I>\\1</I>',$str);
  $str = preg_replace('#\[u\](.+?)\[/u\]#si','<U>\\1</U>',$str);
  $str = preg_replace('#\[s\](.+?)\[/s\]#si','<span style="text-decoration: line-through">\\1</span>',$str);
  $str = preg_replace('#\[pre\](.+?)\[/pre\]#si','<pre>\\1</pre>',$str);
  $str = preg_replace('#\[green\](.+?)\[/green\]#si','<span style="color: #009900">\\1</span>',$str);
  $str = preg_replace('#\[blue\](.+?)\[/blue\]#si','<span style="color: #0000ff">\\1</span>',$str);
  $str = preg_replace('#\[purple\](.+?)\[/purple\]#si','<span style="color: #990099">\\1</span>',$str);
  
  if ($moderatorPost) {
	$str = preg_replace('#\[l\](.+?)\[/l\]#si','<span style="text-decoration: blink">\\1</span>',$str);
	$str = preg_replace('#\[m\](.+?)\[/m\]#si','<span style="color: #dd3333">\\1</span>',$str);
	$str = preg_replace('#\[font=(.+?)\\](.+?)\\[/font\\]#si', '<span style="font-size: \\1;">\\2</span>',$str);
	if (!isset($_SESSION['forumimages']) || $_SESSION['forumimages']) {
		if (!$simple) $str = preg_replace('/\\[fail\\]/','<IMG SRC="gfx/failtruck.jpg" border="0">',$str);
		else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
	}
	else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
  }
  else 
  {
	  $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
	  $str = preg_replace('#\[font=(.+?)\\](.+?)\\[/font\\]#si', '\\2',$str);
  }
  
  //$str = eregi_replace('\[quote\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>Quote: </B><BR>\\1</TD></TR></TABLE>',$str);
  //$str = eregi_replace('\[quote=([^][]+)\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>\\1 said: </B><BR>\\2</TD></TR></TABLE>',$str);

  // BEGIN QUOTE PARSER (note: NOT case-insensitive)
 
  if (strpos($str,'[quote]') !== false) {

  $quote_open = array();
  $quote_open_level = array();
  $quote_ok = array();
  $quote_close = array();
  $quote_close_level = array();
  $j = -1;
  $k = -1;
  $len = strlen($str);
  $q1 = '<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>Quote: </B><BR>';
  $q2 = '</TD></TR></TABLE>';
  // find opening and closing quote tags
  for ($i=0;$i<$len;$i++) {
    if (substr($str, $i, 7) == '[quote]') {
      $j++;
      $k++;
      $quote_open[$j] = $i;
      $quote_open_level[$j] = $k;
    }
    if (substr($str, $i, 8) == '[/quote]') {
      $j++;
      $quote_close[$j] = $i;
      $quote_close_level[$j] = $k;
      $k--;
    }
  }
  // loop through all opening tags, finding matching closing ones, creating pairs
  foreach ($quote_open as $i => $openpos) {
    foreach ($quote_close as $j => $closepos) {
      if ($j > $i) {		// skip forward to after the opening tag. bit cheap.
        if ($quote_close_level[$j] == $quote_open_level[$i]) {
          $quote_ok[$i] = $j;
          break;
        }
      }
    }
  }
   
  // go though all pairs, adding the HTML
  foreach ($quote_ok as $opentag => $closetag) {
    $str = substr($str, 0, $quote_open[$opentag]).$q1.substr($str, $quote_open[$opentag]);
    // work out new offsets. this is a little inefficient
    for ($i=$opentag;$i<(count($quote_open)+count($quote_close));$i++) {
      if ($i > $closetag) $ct = strlen($q2); else $ct = 0;
      if (isset($quote_open[$i])) $quote_open[$i] += strlen($q1) + $ct;
      if (isset($quote_close[$i])) $quote_close[$i] += strlen($q1) + $ct;
    }
    $str = substr($str, 0, $quote_close[$closetag]+$offset).$q2.substr($str, $quote_close[$closetag]+$offset);
  }

  $str = str_replace('[quote]','',$str);
  $str = str_replace('[/quote]','',$str);

  }

  // END QUOTE PARSER

  
  //LINE SPLITTER
  
  $strlng = strlen($str);
  $nospc = 0;$ishtml = false;
  for($j=0;$j<$strlng;$j++) {
    if (substr($str,$j,1) == '<') $ishtml = true;
    if (!$ishtml) {
      if ($nospc > 40) {
        $str = substr($str,0,$j).'<BR>'.substr($str,$j);
        $strlng += 4;
        $j += 4;
        $nospc = 0;
      }
      if (substr($str,$j,1) != ' ' && substr($str,$j,1) != "\n") $nospc++;
      else $nospc = 0;
    }
    //if (substr($str,$j,1) == '<') $ishtml = true;
    //elseif (substr($str,$j,1) == '>') $ishtml = false;
    if (substr($str,$j,1) == '>') $ishtml = false;
  }
  
  // END LINE SPLITTER

  // unfortunately we have to check for both.
  if (strstr($str,':') !== false || strstr($str,';') !== false) {
    $str = preg_replace($smilies_a,$smilies,' '.$str.' ');
    $str = trim($str);
  }
  
  if ($simple) {
    $str = str_replace("\n", ' ', $str);
  }
  else {
    $str = str_replace("\n\n",'<P>',$str);
    $str = str_replace("\n",'<BR>',$str);
  }


  return $str;
}
?>
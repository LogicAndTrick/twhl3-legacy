<?
/*
TWHL3
FORUM FUNCTIONS
To be included in forum pages
Functions used by forums exclusively
*/

// TEH funxion
function post_format($str, $simple = false, $level = 0) {

//$str=str_replace("&","&amp;",$str);
//$str=htmlfilter($str);

  // Create arrays of smilies and their corresponding images
	static $smilies, $smilies_a;
	if (!isset($smilies)) {
		$srs = mysql_query('SELECT emot_file, emot_code, emot_alt FROM twhl_emoticons',$GLOBALS['dbh']) or die('Database error');
		if (mysql_num_rows($srs) > 0) {
			while ($srow2 = mysql_fetch_array($srs)) {
				foreach (explode(',,',$srow2['emot_code']) as $val) {
					$smilies_a[] = "#( |^|\n)".preg_quote($val,'#').'#';
					$smilies[] = '\\1<img src="newforum/smilies/'.$srow2['emot_file'].'" alt="'.$srow2['emot_alt'].' - '.$val.'" />';
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

  //if (!isset($_SESSION['forumimages']) || $_SESSION['forumimages']) { 
  //Depreciated
	if (!$simple) {
		$str = preg_replace('#\[url=([a-z]{3,6}://[^[" ]+)\\]\\[img\\]([a-z]{3,6}://[^\\"\\n\\r]+?)\\[/img\\]\\[/url\\]#si',"<div class=\"image\"><a href=\"\\1\"><img src=\"\\2\" alt=\"User Posted Image\" /></a><br /><a href=\"\\2\" rel=\"external\">[Open in new window]</a></div>",$str);   
		$str = preg_replace('#\[img\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/img\\]#si',"<div class=\"image\"><img src=\"\\1\" alt=\"User Posted Image\" /><br /><a href=\"\\1\" rel=\"external\">[Open in new window]</a></div>",$str);         
		$str = preg_replace('#\[simg\\](([a-z]{3,6})://([^ \\"\\n\\r]+?))\\[/simg\\]#si',"<span class=\"simage\"><img src=\"\\1\" alt=\"User Posted Image\" /></span>",$str);  
	}
  //}

	// bbcode
	/* DEPRECIATED LAWL!
	$str = preg_replace('#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si','<a href="\\1\\2" target="_blank">\\3</a>',$str);    
	$str = preg_replace('#\[url=([^[" ]+)\](.+?)\[/url\]#si','<a href="http://\\1" target="_blank">\\2</a>',$str);
	$str = preg_replace("#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",'<a href="\\1\\2" target="_blank">\\1\\2</a>',$str);      
	$str = preg_replace("#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",'<a href="http://\\1" target="_blank">\\1</a>',$str);      
	$str = preg_replace("#\[email=([^[]+)\](.+?)\[/email\]#si",'<a href="mailto:\\1">\\2</a>',$str);
	$str = preg_replace("#\[email\]([^]\"\n ]+?)\[/email\]#si",'<a href="mailto:\\1">\\1</a>',$str);   
	*/

	// Superior arrays! Don't know why they're superior, they just are.
	// linking bbcode
	$bbcode = array(
		'#\[url=([a-z]{3,6}://)([^[" ]+)\](.+?)\[/url\]#si',
		'#\[url=([^[" ]+)\](.+?)\[/url\]#si',
		"#\[url\]([a-z]{3,6}://)([^]\"\n ]+?)\[/url\]#si",
		"#\[url\]([^/]+\.[^]\"\n ]+?)\[/url\]#si",
		"#\[email=([^[]+)\](.+?)\[/email\]#si",
		"#\[email\]([^]\"\n ]+?)\[/email\]#si"
	);
	
	$html = array(
		'<a href="\\1\\2">\\3</a>',
		'<a href="http://\\1">\\2</a>',
		'<a href="\\1\\2">\\1\\2</a>',
		'<a href="http://\\1">\\1</a>',
		'<a href="mailto:\\1">\\2</a>',
		'<a href="mailto:\\1">\\1</a>'
	);
	
	$str=preg_replace($bbcode, $html, $str);

	$str = " " . $str; // Hax to get whitespace before the autolink if it's the first thing in the post.
  
	// autolinking
	/* EVEN MORE DEPRECIATED LAWL!
	$str = preg_replace("#([]\n ])([a-z]{3,6})://([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^][\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"\\2://\\3.\\4\\5\" target=\"_blank\">\\2://\\3.\\4\\5</a>", $str);
	$str = preg_replace("#([]\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t \n]*)?[^][\".,?!;:\n\t ])#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str);
	$str = preg_replace("#([]\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+[^][\".,?!;:\n\t ])#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $str);
	*/
  
	// autolinking
	$bbcode = array(
		"#([]\n ])([a-z]{3,6})://([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^][\t \n]*)?[^][\".,?!;:\n\t ])#i",
		"#([]\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t \n]*)?[^][\".,?!;:\n\t ])#i",
		"#([]\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+[^][\".,?!;:\n\t ])#i"
	);
	
	$html = array(
		"\\1<a href=\"\\2://\\3.\\4\\5\">\\2://\\3.\\4\\5</a>",
		"\\1<a href=\"http://www.\\2.\\3\\4\">www.\\2.\\3\\4</a>",
		"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"
	);
	
	$str=preg_replace($bbcode, $html, $str);

	$str = substr($str, 1); // Fix the whitespace autolinking hax.
	
	/* TOTALLY MEGA DEPRECIATED LAWL!
	$str = preg_replace('#\[b\](.+?)\[/b\]#si','<b>\\1</b>',$str);
	$str = preg_replace('#\[i\](.+?)\[/i\]#si','<i>\\1</i>',$str);
	$str = preg_replace('#\[u\](.+?)\[/u\]#si','<u>\\1</u>',$str);
	$str = preg_replace('#\[s\](.+?)\[/s\]#si','<span style="text-decoration: line-through">\\1</span>',$str);
	$str = preg_replace('#\[pre\](.+?)\[/pre\]#si','<pre>\\1</pre>',$str);
	*/
  
	// formatting bbcode
	$bbcode = array(
		'#\[b\](.+?)\[/b\]#si',
		'#\[i\](.+?)\[/i\]#si',
		'#\[u\](.+?)\[/u\]#si',
		'#\[s\](.+?)\[/s\]#si',
		'#\[pre\](.+?)\[/pre\]#si',
		'#\[green\](.+?)\[/green\]#si',
		'#\[blue\](.+?)\[/blue\]#si',
		'#\[purple\](.+?)\[/purple\]#si'
	);
	
	$html = array(
		'<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<span style="text-decoration: line-through">\\1</span>',
		'<span style="font-family: monospace">\\1</span>',
		'<span style="color: #009900">\\1</span>',
		'<span style="color: #0000ff">\\1</span>',
		'<span style="color: #990099">\\1</span>'
	);
	
	$str=preg_replace($bbcode, $html, $str);
  
  if ($level>4) {
	$str = preg_replace('#\[l\](.+?)\[/l\]#si','<span style="text-decoration: blink">\\1</span>',$str);
	$str = preg_replace('#\[m\](.+?)\[/m\]#si','<span style="color: #dd3333">\\1</span>',$str);
	if (!$simple) $str = preg_replace('/\\[fail\\]/','<img src="gfx/failtruck.jpg" alt="Epic Fail" />',$str);
	else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
  }
  else $str = preg_replace('/\\[fail\\]/','EPIC FAIL',$str);
  
  //$str = eregi_replace('\[quote\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>Quote: </B><br />\\1</TD></TR></TABLE>',$str);
  //$str = eregi_replace('\[quote=([^][]+)\](.+)\[/quote\]','<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" BGCOLOR="#cbc9b2" ALIGN="center"><TR><TD BGCOLOR="#edebd4" STYLE="font-size: 8pt;"><B>\\1 said: </B><br />\\2</TD></TR></TABLE>',$str);

  
  if (!$simple) {
  // BEGIN QUOTE PARSER (note: NOT case-insensitive)
  
  
  // NEW QUOTES
  /*
  
<div class="quote-container">
	<div class="quote">
		<strong>Quote</strong> <br>
		lol test
		<div class="quote">
			<strong>Quote</strong> <br>
			lol test again?
		</div>
	</div>
</div>
  
  */
 
  if (strpos($str,'[quote]') !== false) {

  $quote_open = array();
  $quote_open_level = array();
  $quote_ok = array();
  $quote_close = array();
  $quote_close_level = array();
  $j = -1;
  $k = -1;
  $len = strlen($str);
  $q1 = '<div class="quote"><strong>Quote:</strong><br />';
  $q2 = '</div>';
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
  
  //$str = preg_replace('%<div class="quote">(.*)</div>%s', '<div class="quote-container"><div class="quote">\\1</div></div>', $str);
  // END QUOTE PARSER
  }
  else
  {
	$str = str_replace('[quote]','',$str);
	$str = str_replace('[/quote]','',$str);
  }

  $str = linesplitter($str, 43);

  // unfortunately we have to check for both.
  if (strstr($str,':') !== false || strstr($str,';') !== false) {
    $str = preg_replace($smilies_a,$smilies,' '.$str.' ');
    $str = trim($str);
  }
  
  if ($simple) {
    $str = str_replace("\n", ' ', $str);
  }
  else {
    //$str = preg_replace('/(\\r\\n){2,}/sim', '<br /><br />', $str);
    //$str = str_replace("\n\n",'<br /><br />',$str);
    $str = str_replace("\n",'<br />',$str);
  }


  return $str;
}

function delete_post($postid)
{
	// PURPOSE: deletes one post.
	// Must be connected to mysql! 
	// Get post (to retrieve user id)
	$del_res = mysql_query("SELECT * FROM posts WHERE postID='$postid'");
	// Return if post doesn't exist or something really weird is going on (a.k.a The Apocalypse)
	if (mysql_num_rows($del_res) != 1) return;
	// Else get info
	$del_row = mysql_fetch_array($del_res);
	$poor_user = $del_row['posterid'];
	// Reduce user's post count by 1
	mysql_query("UPDATE users SET stat_posts = stat_posts-1 WHERE userID = '$poor_user'");
	// Then delete the post. updating last thread/post etc in the forum is handled by the caller, not in here.
	$result = mysql_query("DELETE FROM posts WHERE postID='$postid'");
}

function delete_thread_posts($threadid)
{
	// PURPOSE: deletes all the posts in a thread (NOT the thread itself)
	// Get all posts in the thread
	$thd_del_res = mysql_query("SELECT * FROM posts WHERE threadid='$threadid'");
	// Check that the thread exists
	if (mysql_num_rows($thd_del_res) <= 0) return;
	// Cycle through all posts, deleting each one
	while ($thd_del_row = mysql_fetch_array($thd_del_res))
	{
		delete_post($thd_del_row['postID']);
	}
}

?>
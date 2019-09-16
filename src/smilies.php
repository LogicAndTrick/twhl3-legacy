<?

$srs = mysql_query('SELECT emot_file, emot_code, emot_alt FROM twhl_emoticons ORDER BY emot_category ASC');
$counter = 0;
while ($srow2 = mysql_fetch_array($srs)) {
	$all = explode(',,',$srow2['emot_code']);
	$code = str_replace("\\", "\\"."\\", $all[0]);
	if ($srow2['emot_file']!="thebox.gif") {
		echo '<a href="javascript:smilie(' . "'$code'" . ')">
			<img src="newforum/smilies/' . $srow2['emot_file'] . '" alt="' . $srow2['emot_alt'] . '" />
			</a>';
		$counter++;
		if ($counter >= 10) {
			echo '<br />';
			$counter = 0;
		}
	}
}
	
?>
<?
	include 'middle.php';
	$result = mysql_query("SELECT * FROM entities ORDER BY entID");
	while ($row = mysql_fetch_array($result))
	{
	
		$props = $row['entprops'];
		$flags = $row['entflags'];
		
		$proplist = explode(",,",$props);
		foreach ($proplist as $prp)
		{
			$prp = trim($prp);
			if (preg_match('%&lt;X&gt;(.+?)&lt;/X&gt;&lt;Y&gt;.+?&lt;/Y&gt;%si', $prp, $regs)) {
				$res = $regs[1];
			} else {
				$res = "";
			}
			if (preg_match('%&lt;X&gt;.+?&lt;/X&gt;&lt;Y&gt;(.+?)&lt;/Y&gt;%si', $prp, $regs)) {
				$res2 = $regs[1];
			} else {
				$res2 = "";
			}
			
			if ($res != "" && $res2 != "")
				$noida[$res] += 1;
		}
		
		$flaglist = explode(",,",$flags);
		foreach ($flaglist as $flg)
		{
			$flg = trim($flg);
			if (preg_match('%&lt;X&gt;(.+?)&lt;/X&gt;&lt;Y&gt;.+?&lt;/Y&gt;%si', $flg, $regf)) {
				$resf = $regf[1];
			} else {
				$resf = "";
			}
			if (preg_match('%&lt;X&gt;.+?&lt;/X&gt;&lt;Y&gt;(.+?)&lt;/Y&gt;%si', $flg, $regf)) {
				$res2f = $regf[1];
			} else {
				$res2f = "";
			}
			
			if ($resf != "" && res2f != "")
				$noidaf[$resf] += 1;
		}
	}
		
	arsort($noida);
	foreach ($noida as $key => $value) {
		if ($value > 1) echo "#attribute_no_entry#: $value -  $key<br />\n";
	}
	
	arsort($noidaf);
	foreach ($noidaf as $key => $value) {
		if ($value > 1) echo "#flag_no_entry#: $value -  $key<br />\n";
	}
?>
<?php
	include 'middle.php';
	
	$fp = @fopen ("entripz.txt", "r");
	if (!empty($fp)) {
		$content = fread ($fp, filesize ("entripz.txt"));
	}
	fclose ($fp);
	
	$entities = explode("<--splitter-->",$content);
	$counter = 0;
	$counter2 = 0;
	//$infoes = array();
	//$titles = array();
	
	$hitstart = gmt("U");
	$cat = 30;
	$pic = "";
	$notes = "";
	$example = "";
	$relents = "";
	$reltuts = "";
	$ccent = "";
	$hits = 0;
	
	$notadded = "";
	mysql_query("DELETE FROM entities WHERE entcat = '30'");
	foreach ($entities as $ent)
	{
		$counter++;
		//rawr
		$game = 6;
		$name = "";
		$gametext = "";
		$text = "";
		$props = "";
		$flags = "";
		$ins = "";
		$outs = "";
		$point = 1;
		if (preg_match('%\[bigtitle\](.+?)\[/bigtitle\]%si', $ent, $regs)) {
			$name = strclean($regs[1]);
			//$titles[$name] = $counter;
		}
		if (preg_match('%\[biginfo\](.+?)\[/biginfo\]%si', $ent, $regs)) {
			$gametext = strclean($regs[1]);
			//$infoes[$gametext] += 1;
		}
		//echo "$name - - $gametext<br>";
		preg_match_all('%\[smalltitle\](.+?)\[/smallinfo\]%si', $ent, $smalls, PREG_PATTERN_ORDER);
		for ($i = 0; $i < count($smalls[0]); $i++) {
			# Matched text = $result[0][$i];
			$title = '';
			$info = '';
			if (preg_match('%\[smalltitle\](.+?)\[/smalltitle\]%si', $smalls[0][$i], $regs)) {
				$title = strclean($regs[1]);
			} 
			if (preg_match('%\[smallinfo\](.+?)\[/smallinfo\]%si', $smalls[0][$i], $regs)) {
				$info = strclean($regs[1]);
			}
			$info = htmlfilter($info);
			$title = htmlfilter($title);
			$name = htmlfilter($name);
			if (preg_match('/desc/si', $title)) {
				$text = $info;
			}
			elseif (preg_match('/input/si', $title)) {
				$ins = $info;
			}
			elseif (preg_match('/output/si', $title)) {
				$outs = $info;
			}
			elseif (preg_match('/prop/si', $title)) {
				$props = $info;
			}
			elseif (preg_match('/flag/si', $title)) {
				$flags = $info;
			}
			
			if (preg_match('/half-life/i', $gametext)) {
				$game = 6;
			}
			elseif (preg_match('/counter-strike/i', $gametext)) {
				$game = 8;
			}
			elseif (preg_match('/defeat/i', $gametext)) {
				$game = 9;
			}
			elseif (preg_match('/source/i', $gametext)) {
				$game = 6;
			}
			elseif (preg_match('/deathmatch/i', $gametext)) {
				$game = 7;
			}
			
			if (preg_match('/point/i', $gametext)) {
				$point = 1;
			}
			elseif (preg_match('/brush/i', $gametext)) {
				$point = 2;
			}
			elseif (preg_match('/npc/i', $gametext)) {
				$point = 3;
			}
			//echo "$title: $info<br>";
		}
		if ($name != "")
		{
			mysql_query("INSERT INTO entities (entcat,entgame,entname,enttext,entprops,entflags,entin,entout,entpic,entnotes,entpoint,entexample,entents,enttuts,entcc,active,enthits,enthitstart) VALUES ('$cat','$game','$name','$text','$props','$flags','$ins','$outs','$pic','$notes','$point','$example','$relents','$reltuts','$ccent','$active','$hits','$hitstart')") or die(mysql_error());
			$counter2++;
		}
		else
		{
			$notadded .= "<br/> $name $gametext $text $props $flags $ins $outs <br/>";
		}
		//echo "<br><br><br>";
		
		//hl2 = 6
		//hdm = 7
		//css = 8
		//dod = 9
		
		//point = 1
		//brush = 2
		//NPC = 3
		
		
	}
	echo "$counter2 of $counter entities added, not added: <br/> $notadded";
	/*
	arsort($infoes);
	
	echo "hl2:<br>";
	foreach ($infoes as $key => $value) {
		if (preg_match('/half-life/i', $key)) {
			# Successful match
			if ($value != 0) echo "$value -  $key<br />\n";
			$infoes[$key] = 0;
		}
	}
	echo "<br><br>css:<br>";
	foreach ($infoes as $key => $value) {
		if (preg_match('/counter-strike/i', $key)) {
			# Successful match
			if ($value != 0) echo "$value -  $key<br />\n";
			$infoes[$key] = 0;
		}
	}
	echo "<br><br>dods:<br>";
	foreach ($infoes as $key => $value) {
		if (preg_match('/defeat/i', $key)) {
			# Successful match
			if ($value != 0) echo "$value -  $key<br />\n";
			$infoes[$key] = 0;
		}
	}
	echo "<br><br>general source:<br>";
	foreach ($infoes as $key => $value) {
		if (preg_match('/source/i', $key)) {
			# Successful match
			if ($value != 0) echo "$value -  $key<br />\n";
			$infoes[$key] = 0;
		}
	}
	echo "<br><br>other:<br>";
	foreach ($infoes as $key => $value) {
		if ($value != 0) echo "$value -  $key<br />\n";
	}
	
	echo "<br /><br />---<br /><br />";
	
	foreach ($titles as $key => $value) {
		echo "$value :  $key<br />\n";
	}*/
?>
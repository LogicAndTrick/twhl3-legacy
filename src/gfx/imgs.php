<?

	$dir = "../gfx/";
	if ($open = @opendir($dir))
	{
		for($i=0; ($file = readdir($open)) !== false; $i++)
		{
			echo '<img src="'.$file.'">';
		}
		closedir($open);
	}

?>
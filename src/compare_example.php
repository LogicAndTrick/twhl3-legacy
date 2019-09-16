<?php
	include 'top.php';
	$text1="
One of the most awesome improvements in the Sauce engine from the original Half-Lofe was the addition of dasplasmints.
Without displacements, HL2 wouldn't look half as good as it duz. This five-part series of tutorials will explain how to use them, as well a lulz hints on using them.

to do: moar content.";

	$text2="
One of the best improvements in the Source engine from the original Half-Life was the addition of displacements.
Without displacements, HL2 wouldn't look half as good as it does. This twelve-part series of tutorials will explain how to use them, as well as tips and tricks on using them.

[h]What is a Displacement?[/h]

[list]
[li]A displacement is a four-sided plane which has been 'split' into a grid of triangles.[/li]
[li]The triangle grid is shown in your hammer 3D view with lines going horizontally, vertically, and diagonally across the plane.
The points made by 2 or 3 crossing lines can be moved up and down - freely to the other points. [/li]
[li]Displacements are not entities. They are solid, as in you can walk on them.[/li]
[li]They don't seal your map (i.e. you still need to close your map to the void with a brush to avoid leaks),
although if a displacement happens to be in the void, it wont cause a leak. [/li]
[li]They can only be seen from one side.[/li]
[li]You can only edit displacements in the 3D view (except for normal 2D size-adjustment)[/li]
[/list]

[h]What do you use displacements for?[/h]

Displacements are generally used for realistic looking terrain like the ground and cliffs, But they have other uses.
Look at some of the coast levels in hl2 to see liberal use of displacements.";
	
	if (isset($_POST['text1']) && $_POST['text1'] !="")
		$text1 = $_POST['text1'];
	if (isset($_POST['text2']) && $_POST['text2'] !="")
		$text2 = $_POST['text2'];

	//$text1 = str_replace("\n",' <br /> ',$text1);
	//$text2 = str_replace("\n",' <br /> ',$text2);

	include 'sde/diff.php';
	
	function wikidiff($old_t,$new_t)
	{
		$thediff = new DifferenceEngine($old_t,$new_t);
		return $thediff->showDiffPage();
	}
	
	echo wikidiff($text1,$text2);
	
	?>
	<form action="compare_example.php" method="post">
				<fieldset>	
					<textarea rows="10" cols="76" name="text1"><?=$text1?></textarea><br />
					<textarea rows="10" cols="76" name="text2"><?=$text2?></textarea><br />
						<input value="compare" type="submit"></input>
				</fieldset>
				</form>
	<?
	
	include 'bottom.php';
?>
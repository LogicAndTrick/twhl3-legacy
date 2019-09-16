<?php

/*
	file: inline_function.php

	This file defines a function which hacks two strings so they can be
	used by the Text_Diff parser, then recomposes a single string out of
	the two original ones, with inline diffs applied.

	The inline_diff code was written by Ciprian Popovici in 2004,
	as a hack building upon the Text_Diff PEAR package.
	It is released under the GPL.

	There are 3 files in this package: inline_example.php, inline_function.php, inline_renderer.php
*/

// for the following two you need Text_Diff from PEAR installed
include_once 'diff.php';
include_once 'compare_function.php';

function compare($text1, $text2, $rev = false) {

	$htext1 = chunk_split($text1, 1, "\n");
	$htext2 = chunk_split($text2, 1, "\n");

	$hlines1 = str_split(htext1, 2);
	$hlines2 = str_split(htext2, 2);
	
	$text1 = str_replace("\n"," \n",$text1);
	$text2 = str_replace("\n"," \n",$text2);

	$hlines1 = explode(" ", $text1);
	$hlines2 = explode(" ", $text2);

	// create the diff object
	$temp = &new Text_Diff($hlines1, $hlines2);
	if ($rev) $diff = $temp->reverse();
	else $diff = $temp;

	// get the diff in unified format
	// you can add 4 other parameters, which will be the ins/del prefix/suffix tags
	if ($rev) $renderer = &new Text_Diff_Renderer(array(),true);
	else $renderer = &new Text_Diff_Renderer();
	return $renderer->render($diff);

}

?>
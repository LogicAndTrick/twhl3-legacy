<?
	//if (!isset($lvl) || $lvl < 20) fail("You do not have permission to preview the Wiki system. Get out.","index.php");

	if (isset($_GET['cat'])) include 'wikicat.php';
	elseif (isset($_GET['sub'])) include 'wikisubcat.php';
	elseif (isset($_GET['id'])) include 'wikientry.php';
	elseif (isset($_GET['comment'])) include 'wikimodcomment.php';
	elseif (isset($_GET['edit'])) include 'wikiedit.php';
	elseif (isset($_GET['history'])) include 'wikihistory.php';
	elseif (isset($_GET['revert'])) include 'wikioldentry.php';
	//elseif (isset($_GET['log'])) include 'wikilog.php';
	elseif (isset($_GET['ctl'])) include 'wikicomplast.php';
	elseif (isset($_GET['admin'])) include 'wikiadmin.php';
	elseif (isset($_GET['valc'])) include 'wikivalcom.php';
	elseif (isset($_GET['vale'])) include 'wikivalent.php';
	elseif (isset($_GET['new'])) include 'wikinew.php';
	elseif (isset($_GET['request'])) include 'wikirequest.php';
	elseif (isset($_GET['reqdone'])) include 'wikirequestdone.php';
	else include 'wikimain.php';
?>
<?
if (isset($_GET['map'])) include 'vaultmapview.php';
elseif (isset($_GET['id'])) include 'vaultmaplist.php';
elseif (isset($_GET['screens'])) include 'vaultmapscreens.php';
elseif (isset($_GET['advfilter'])) include 'vaultfilterview.php';
elseif (isset($_GET['submit'])) include 'vaultsubmit.php';
elseif (isset($_GET['download'])) include 'vaultdownload.php';
elseif (isset($_GET['editcomment']) || isset($_GET['deletecomment'])) include 'vaulteditcomment.php';
elseif (isset($_GET['edit']) || isset($_GET['delete'])) include 'vaulteditmap.php';
elseif (isset($_GET['motm'])) include 'vaultmotmlist.php';
else include 'vaultindex.php';
?>
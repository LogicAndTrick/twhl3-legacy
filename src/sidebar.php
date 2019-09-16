<div class="left-bar">
	<h1>Site Stuff</h1>
	<ul>
		<li><a href="/index.php">Front Page</a></li>
		<li><a href="/where.php">Where to start?</a></li>
		<li><a href="/about.php">About</a></li>
		<li><a href="/links.php">Links</a></li>
		<li><a href="/stats.php">Stats</a></li>
		<li><a href="/contact.php">Contact Us</a></li>
	</ul>
	<h1>Reference</h1>
	<ul>
		<li><a href="/tutorial.php">Tutorials</a></li>
		<li><a href="/wiki.php">Wiki</a></li>
		<li><a href="/wiki.php?cat=1">Entity Guides</a></li>
		<li><a href="/wiki.php?cat=2">Glossary</a></li>
		<li><a href="/wiki.php?cat=3">Error Guides</a></li>
		<li><a href="/articulator.php">VERC Archive</a></li>
	</ul>
	<h1>Maps</h1>
	<ul>
		<li><a href="/vault.php">Map Vault</a></li>
		<li><a href="/competitions.php">Competitions</a></li>
		<li><a href="/motm.php">Map of the Month</a></li>
	</ul>
	<h1>Community</h1>
	<ul>
		<?=(isset($usr) && $usr!="")?'<li><a href="/user.php?control">My Control Panel</a></li>':''?>
		<li><a href="/forums.php">Forums</a></li>
		<li><a href="irc://irc.gamesurge.net/twhl">IRC</a>&nbsp;&nbsp;<a href="irc.php">(?)</a></li>
		<li><a href="/user.php">Members</a></li>
		<li><a href="/awards.php">Awards</a></li>
		<li><a href="/journals.php">Journals</a></li>
		<li><a href="/servers.php">Game Servers</a></li>
	</ul>
<?
	include 'shoutbox.php';
	include 'poll.php';
	include 'onliners.php';
?>
	<h1>Affiliates</h1>
	<p class="affiliates">
		<a href="http://invert-x.com/"><img src="http://twhl.info/gfx/invertxlogo.png" alt="A gaming and technology blog by TWHL admins Penguinboy and Ant." /></a>
		<a href="http://either-or.net/"><img src="http://twhl.info/gfx/eitherorlogosmall2lx.gif" alt="A music blog by TWHL users Ant and Hugh." /></a>
	</p>
</div> 
<div class="single-center">
	<h1>TWHL Tutorials</h1>
	<h2>Tutorials</h2>
	<p class="single-center-content">
		This is the main tutorial page, which provides a gateway to all the tutorials listed on TWHL. If you are logged in, you can also manage your tutorials, and propose a tutorial to be added to the list of Half-Life 1 and Source tutorials at TWHL.
	</p>	
	<table class="tutorial-hub">
		<tr valign="top">
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?cat=1"><img src="images/tut_hl.png" alt="Half-Life tutorial image" /><br />
					Half-Life Tutorials</a>
				</p>
				Although a little long in the tooth nowadays, we still have a large collection of fantastic tutorials for Half-Life (and a couple of Counter-Strike ones too)
			</td>
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?cat=2"><img src="images/tut_src.png" alt="Source tutorial image" /><br />
					Source Tutorials</a>
				</p>
				Gordon's back and so are his...mapping problems. Check out our growing collection of Source tutorials, including Half-Life 2 and more.
			</td>
		</tr>
		<tr valign="top">
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?cat=4">
					<img src="images/tut_gen.png" alt="General tutorial image" /><br />
					General Tutorials</a>
				</p>
				Anything that doesn't fit into the other categories goes here. This includes tutorials about the site and level design in general.
			</td>
			<td>
				<p class="tutorial-hub-title">
					Recent Tutorials
				</p>
				<div style="text-align: center;">
					<table class="no-width">
						<tr>
							<th>Tutorial</th>
							<th>Author</th>
						</tr>
<?
	$newtutq = mysql_query('SELECT * FROM tutorials LEFT JOIN users ON authorid = userID WHERE waiting = 0 ORDER BY date Desc LIMIT 5');
	while ($recr = mysql_fetch_array($newtutq))
	{
?>
						<tr>
							<td><a href="tutorial.php?id=<?=$recr['tutorialID']?>"><?=$recr['name']?></a></td>
							<td><a href="user.php?id=<?=$recr['userID']?>"><?=$recr['uid']?></a></td>
						</tr>
<?
	}
?>
					</table>
				</div>
			</td>
		</tr>
<?
	if (isset($_SESSION['usr']) && $_SESSION['usr'] != "")
	{
?>
		<tr valign="top">
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?mytuts"><img src="images/tut_my.png" alt="My tutorials image" /><br />
					My Tutorials</a>
				</p>
				Your published tutorials, and your draft tutorials can be found here.
			</td>
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?propose"><img src="images/tut_prop.png" alt="Propose image" /><br />
					Propose Tutorial</a>
				</p>
				Want to write a tutorial for TWHL? Submit a proposal for it here.
			</td>
		</tr>
<?
	}
	if (isset($_SESSION['lvl']) && $_SESSION['lvl'] >= 25)
	{
?>
		<tr valign="top">
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?viewprops=1"><img src="images/tut_view.png" alt="View proposal image" /><br />
					View Proposals</a>
				</p>
				Tutorial mods, if this section builds up, you're in trouble!
			</td>
			<td>
				<p class="tutorial-hub-title">
					<a href="tutorial.php?drafts"><img src="images/tut_draft.png" alt="View draft image" /><br />
					View Drafts</a>
				</p>
				Here moderators and admins can see all upcoming tutorials that are still in their draft stage.
			</td>
		</tr>
<?
	}
?>
	</table>
</div>
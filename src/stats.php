<?php	
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';
	$rnd = rand(0,1000);
?>

<div class="single-center">
	<h1>Site Statistics</h1>
	<h3>Member Registrations</h3>
	<p class="single-center-content-center">
		<img src="statgraph.php?rnd=<?=$rnd?>" alt="member registrations" />
	</p>
	<h3>Page Hits (per day<?
	$earliest = mysql_query("SELECT * FROM pagehits ORDER BY hitstart ASC LIMIT 1");
	if (mysql_num_rows($earliest) > 0) {
		$newr = mysql_fetch_array($earliest);
		echo ", starting from " . timezone($newr['hitstart'],$_SESSION['tmz'],"F jS, Y");
	}
	?>)</h3>
	<p class="single-center-content-center">
		<img src="statgraph.php?graph=hits&rnd=<?=$rnd?>" alt="page hits" />
	</p>
	<h3>Userprofile Hits (per day)</h3>
	<p class="single-center-content">
		Note that this graph is somewhat misleading. It needs to have a start date that is equal for all users.
	</p>
	<p class="single-center-content-center">
		<img src="statgraph.php?graph=uphits&rnd=<?=$rnd?>" alt="userprofile hits" />
	</p>
	<h3>User Logins (per day)</h3>
	<p class="single-center-content-center">
		<img src="statgraph.php?graph=logins&rnd=<?=$rnd?>" alt="user logins" />
	</p>

	<h3>User Browsers (in percent)</h3>
	<p class="single-center-content">
		This data is from members only. Current sample size is <?=mysql_num_rows(mysql_query("SELECT * FROM users WHERE (lastbrowser='Firefox' OR lastbrowser='Opera' OR lastbrowser='IE 7' OR lastbrowser='IE 1-6')"))?>.
	</p>
	<p class="single-center-content-center">
		<img src="statgraph.php?graph=browsers&rnd=<?=$rnd?>" alt="browser stats" />
	</p>
</div>	

<?
include 'footer.php';
?>
<div class="single-center">
					<h1>The TWHL Forums - Welcome</h1>
					<p class="single-center-content">
						Welcome to the TWHL Forums! Below, you'll find a list of boards that you're free to post in. If you have a mapping question, please make sure it goes into the correct board to ensure that your query is answered quicker. Like all forums, we have a set of rules for using these forums.
					</p>

					<ul>
						<li>Use our Search function before posting in the forums: more often than not, your question would've already have been answered.</li>
						<li>Members who spam the forum or perform otherwise inappropriate activities will have their account suspended.</li>
						<li>Keep it clean in there: we're pretty lenient, but we do have our limits.</li>
						<li>Discussion of illegally pirated software and warez of any sort on the forum is terms for immediate suspension of your account.</li>
					</ul>	
				</div>		
				<div class="single-center" id="gap-fix">
					<h1>The Boards</h1>
					<?php

						$result = mysql_query("SELECT
						forumID, description, stat_topics, forumcats.stat_posts, accesslevel, orderindex, postID, posterid,
						postdate, userID, uid, threads.name AS thname, forumcats.name AS name, forumcats.stat_lastpostid AS stat_lastpostid, posts.threadid AS thid
						FROM forumcats
						LEFT  JOIN posts ON forumcats.stat_lastpostid = posts.postID
						LEFT JOIN threads ON threads.threadID = posts.threadid
						LEFT  JOIN users ON posts.posterid = users.userID
						WHERE orderindex !=  '-1' AND accesslevel =0
						ORDER  BY orderindex");

						$numbrds = mysql_num_rows($result);

						$nummods = mysql_num_rows(mysql_query("SELECT * FROM users WHERE lvl >= 35"));

						echo '<h2>' . $numbrds . ' boards | ' . $nummods . ' <a href="#">Moderators</a></h2>
							<div class="forum-index">';

						while($row = mysql_fetch_array($result))
						{
							$idz=$row['forumID'];
							$title=$row['name'];
							$thid=$row['thid'];
							$thname=$row['thname'];
							$desc=$row['description'];
							$numtops=$row['stat_topics'];
							$numposts=$row['stat_posts'];
							$lastid=$row['userID'];
							$last=$row['uid'];
							$lastdate=$row['postdate'];
							$level=$row['accesslevel'];
							$order=$row['orderindex'];
							
							$ind = "orange";
							
							if (isset($_SESSION['lst']) && ($lastdate > $_SESSION['lst'])) {
						      $ind = "green";
						    }
							
							echo '<div class="forum-index-container">
								<span class="forum-info">
									' . $numposts . ' posts in ' . $numtops . ' threads
								</span>
								<span class="forum-name">

									<img src="images/dot' . $ind . '.png" alt="indicator" /><a href="forums.php?id=' . $idz . '">' . $title . '</a>
								</span>
								<p class="forum-description">
									' . $desc . '
								</p>
								<p class="last-post">
									' . timezone($lastdate,$_SESSION['tmz'],"jS F, Y") . ' at ' . timezone($lastdate,$_SESSION['tmz'],"H:i") . ' in <a href="forums.php?thread=' . $thid . '&amp;page=last">' . $thname . '</a>, by <a href="user.php?id=' . $lastid . '">' . $last . '</a>
								</p>	
							</div>';
								
							//if (isset($_SESSION['uid']) and $_SESSION['lvl']>=$level)
						}

					?>					
					</div>	
				</div>
				<? if (isset($_SESSION['uid']) && $_SESSION['lvl'] >= 20) { ?>
				<div class="single-center">
					<h1>The Admin Boards</h1>
					<? 
						$result = mysql_query("SELECT
							forumID, description, stat_topics, forumcats.stat_posts, accesslevel, orderindex, postID, posterid,
							postdate, userID, uid, threads.name AS thname, forumcats.name AS name, forumcats.stat_lastpostid AS stat_lastpostid, posts.threadid AS thid
							FROM forumcats
							LEFT  JOIN posts ON forumcats.stat_lastpostid = posts.postID
							LEFT JOIN threads ON threads.threadID = posts.threadid
							LEFT  JOIN users ON posts.posterid = users.userID
							WHERE orderindex !=  '-1' AND accesslevel != 0 AND accesslevel <= " . mysql_real_escape_string($_SESSION['lvl']) . "
							ORDER  BY orderindex");

						$numbrds = mysql_num_rows($result);
					
						echo '<h2>' . $numbrds . ' boards | ' . $nummods . ' <a href="#">Moderators</a></h2>
								<div class="forum-index">';
								
						while($row = mysql_fetch_array($result))
						{
							$idz=$row['forumID'];
							$title=$row['name'];
							$thid=$row['thid'];
							$thname=$row['thname'];
							$desc=$row['description'];
							$numtops=$row['stat_topics'];
							$numposts=$row['stat_posts'];
							$lastid=$row['userID'];
							$last=$row['uid'];
							$lastdate=$row['postdate'];
							$level=$row['accesslevel'];
							$order=$row['orderindex'];
							
							$ind = "orange";
							
							if (isset($_SESSION['lst']) && ($lastdate > $_SESSION['lst'])) {
						      $ind = "green";
						    }
							
							echo '<div class="forum-index-container">
								<span class="forum-info">
									' . $numposts . ' posts in ' . $numtops . ' threads
								</span>
								<span class="forum-name">

									<img src="images/dot' . $ind . '.png" alt="indicator" /><a href="forums.php?id=' . $idz . '">' . $title . '</a>
								</span>
								<p class="forum-description">
									' . $desc . '
								</p>
								<p class="last-post">
									' . timezone($lastdate,$_SESSION['tmz'],"jS F, Y") . ' at ' . timezone($lastdate,$_SESSION['tmz'],"H:i") . ' in <a href="forums.php?thread=' . $thid . '&amp;page=last">' . $thname . '</a>, by <a href="user.php?id=' . $lastid . '">' . $last . '</a>
								</p>	
							</div>';
								
							//if (isset($_SESSION['uid']) and $_SESSION['lvl']>=$level)
						}
					?>
						
					</div>	
				</div>
				<?}?>
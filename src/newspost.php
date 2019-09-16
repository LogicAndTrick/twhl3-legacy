<?
	if (!(isset($lvl) && ($lvl >= 35))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
?>
<div class="single-center">
	<h1>Posting News</h1>
	<p class="single-center-content">
		From here, you can post new news articles. You can use bbcode [s], [i], [u], [b], [url=], and [url] tags. The news title and content must not be empty.
	</p>	
</div>	
<div class="single-center" id="gap-fix">
	<h1>Post News</h1>
	<form action="newsadd.php" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content"> 
				<input class="right" type="text" name="title" />Title:
			</p>
			<fieldset style="text-align: center;">
				<textarea rows="10" cols="76" name="newstext"></textarea>
			</fieldset>
			<input class="right" id="post-thread" value="Post" type="submit" />
		</fieldset>
	</form>
</div>
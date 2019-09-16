<div class="single-center">
	<h1>Edit Journal</h1>
	<h2><a href="journals.php?user=<?=$juser?>"><?=$jusername?>'s Journals</a> &gt; Edit Journal</h2>
	<p class="single-center-content">
		This is where you can edit the content of your journals. Modify the text as you want and the changes will be made in your profile.
	</p>
	<form action="journchange.php?id=<?=$getjourn?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<textarea rows="10" cols="76" name="journtext"><?=$jtext?></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Edit" />
			</p>
		</fieldset>
	</form>
</div>
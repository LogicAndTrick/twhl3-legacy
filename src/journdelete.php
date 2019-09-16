<div class="single-center">
	<h1>Delete Journal</h1>
	<h2><a href="journals.php?user=<?=$juser?>"><?=$jusername?>'s Journals</a> &gt; Delete Journal</h2>
	<p class="single-center-content">
		This is where you can delete a journal. Verify you want to delete this journal, and it will be permanently removed.
	</p>
	<div class="journals">
		<div class="journal-container" style="border-bottom: 1px solid #daa134;">
			<div class="profile-text">
				<p>
					<?=bio_format($jtext)?>
				</p>
			</div>
		</div>
	</div>
	<form action="journremove.php?id=<?=$getjourn?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				Delete this journal?<br />
				<input type="submit" value="Delete" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center">
	<h1>Delete Journal</h1>
	<h2><a href="user.php?id=<?=$usr?>&amp;journals">Profile</a> &gt; Delete Journal</h2>
	<p class="single-center-content">
		This is where you can delete a journal from your profile. Verify you want to delete this journal, and it will be permanently removed from your profile.
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
	<form action="userjournalremove.php?id=<?=$getjourn?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				Delete this journal?<br />
				<input type="submit" value="Delete" />
			</p>
		</fieldset>
	</form>
</div>
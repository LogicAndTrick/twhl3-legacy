<div class="single-center">
	<h1>Edit - Comment <?=$getcomm.(($lvl>=35)?' by '.$cusername:'')?></h1>
	<h2><a href="journals.php">Journals</a> &gt; <a href="journals.php?id=<?=$cjourn?>">Journal <?=$cjourn?></a> &gt; Edit Comment</h2>
	<div class="comments">
		<div class="comment-box" style="border-top: 0;">
			<form action="journchangecomment.php?id=<?=$getcomm?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"><?=$ctext?></textarea>
					<input type="submit" value="Edit" />
				</fieldset>
			</form>
		</div>		
	</div>
</div>
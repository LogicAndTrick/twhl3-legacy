<div class="single-center">
	<h1>Edit - Comment <?=$getcomm.(($lvl>=35)?' by '.$cusername:'')?></h1>
	<h2><a href="motm.php">MOTM</a> &gt; <a href="motm.php?id=<?=$cmotmid?>">MOTM <?=$cmotmid?></a> &gt; Edit Comment</h2>
	<div class="comments">
		<div class="comment-box" style="border-top: 0;">
			<form action="motmchangecomment.php?id=<?=$getcomm?>&amp;focus=<?=$focus?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"><?=$ctext?></textarea>
					<input type="submit" value="Edit" />
				</fieldset>
			</form>
		</div>		
	</div>
</div>
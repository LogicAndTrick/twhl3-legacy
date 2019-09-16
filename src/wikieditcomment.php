<div class="single-center">
	<h1>Edit - Comment <?=$getcomm.(($lvl>=20)?' by '.$cusername:'')?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$entr['titleID']?>"><?=$entr['titletitle']?></a> &gt; Edit Comment</h2>
	<div class="comments">
		<div class="comment-box" style="border-top: 0;">
			<form action="wikichangecomment.php?id=<?=$getcomm?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"><?=$ctext?></textarea>
					<input type="submit" value="Edit" />
				</fieldset>
			</form>
		</div>		
	</div>
</div>
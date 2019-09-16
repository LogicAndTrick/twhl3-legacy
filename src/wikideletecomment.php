<div class="single-center">
	<h1>Delete - Comment <?=$getcomm.(($lvl>=20)?' by '.$cusername:'')?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$entr['titleID']?>"><?=$entr['titletitle']?></a> &gt; Delete Comment</h2>
	<p class="single-center-content">
		<?=$ctext?>
	</p>
	<form action="wikiremovecomment.php?id=<?=$getcomm?>" method="post">
		<p class="single-center-content-center">
			<input type="submit" value="Delete" size="7" />
		</p>
	</form>
</div>
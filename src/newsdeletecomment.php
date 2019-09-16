<div class="single-center">
	<h1>Delete - Comment <?=$getcomm.(($lvl>=35)?' by '.$cusername:'')?></h1>
	<h2><a href="news.php">News</a> &gt; <a href="news.php?id=<?=$cnews?>">News <?=$cnews?></a> &gt; Delete Comment</h2>
	<p class="single-center-content">
		<?=$ctext?>
	</p>
	<form action="newsremovecomment.php?id=<?=$getcomm?>" method="post">
		<p class="single-center-content-center">
			<input id="post" type="submit" value="Delete" size="7" />
		</p>
	</form>
</div>
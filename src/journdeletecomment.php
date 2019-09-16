<div class="single-center">
	<h1>Delete - Comment <?=$getcomm.(($lvl>=35)?' by '.$cusername:'')?></h1>
	<h2><a href="journals.php">Journals</a> &gt; <a href="journals.php?id=<?=$cjourn?>">Journal <?=$cjourn?></a> &gt; Delete Comment</h2>
	<p class="single-center-content">
		<?=$ctext?>
	</p>
	<form action="journremovecomment.php?id=<?=$getcomm?>" method="post">
		<p class="single-center-content-center">
			<input id="post" type="submit" value="Delete" size="7" />
		</p>
	</form>
</div>
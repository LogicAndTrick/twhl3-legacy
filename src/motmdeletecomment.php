<div class="single-center">
	<h1>Delete - Comment <?=$getcomm.(($lvl>=35)?' by '.$cusername:'')?></h1>
	<h2><a href="motm.php">MOTM</a> &gt; <a href="motm.php?id=<?=$cmotmid?>">MOTM <?=$cmotmid?></a> &gt; Delete Comment</h2>
	<p class="single-center-content">
		<?=$ctext?>
	</p>
	<form action="motmremovecomment.php?id=<?=$getcomm?>&amp;focus=<?=$focus?>" method="post">
		<p class="single-center-content-center">
			<input id="post" type="submit" value="Delete" size="7" />
		</p>
	</form>
</div>
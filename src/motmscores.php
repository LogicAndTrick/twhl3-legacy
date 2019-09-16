<?php
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';
?>
<div class="single-center">
	<div style="padding: 5px; border: 2px dashed #A3A3A3; margin: 10px;">
		<table style="border: 0; margin: 12px auto;">
			<tr>
				<td style="text-align: center; width: 75px;">
					<span style="font-size: 30px; color: <?=tri_colour(80)?>">80%</span>
					<span style="font-size: 10px;">Architecture</span>
				</td>
				<td style="text-align: center; width: 75px;">
					<span style="font-size: 30px; color: <?=tri_colour(89)?>">89%</span>
					<span style="font-size: 10px;">Texturing</span>
				</td>
				<td style="text-align: center; width: 75px;">
					<span style="font-size: 30px; color: <?=tri_colour(67)?>">67%</span>
					<span style="font-size: 10px;">Ambience</span>
				</td>
				<td style="text-align: center; width: 75px;">
					<span style="font-size: 30px; color: <?=tri_colour(92)?>">92%</span>
					<span style="font-size: 10px;">Lighting</span>
				</td>
				<td style="text-align: center; width: 75px;">
					<span style="font-size: 30px; color: <?=tri_colour(50)?>">50%</span>
					<span style="font-size: 10px;">Gameplay</span>
				</td>
			</tr>
		</table>
		<div style="margin: 0px auto 6px auto; width: 400px; border: 1px solid black; text-align: left;">
			<div style="padding: 3px; text-align: center; margin: 0px; width: 320px; background-color: #e6a83d; border-right: 1px solid black; color: #FFFFFF;">
				Total: 80%
			</div>
		</div>
	</div>
</div>
<?	
	include 'footer.php';
?>
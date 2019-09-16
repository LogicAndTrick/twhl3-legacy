<div class="single-center">
	<h1>Oops!</h1>
	<p class="single-center-content">
		There seems to be a problem with what you are trying to do.
	</p>
	<p class="single-center-content">
		The specific problem is: <span style="color: red;  font-style:italic;"><?=(isset($problem))?$problem:"unspecified"?></span>
	</p>
	<p class="single-center-content">
		Do not click back on your browser. <a href="<?=(isset($back))?$back:"index.php"?>">Click here</a> instead.
	</p>
</div>
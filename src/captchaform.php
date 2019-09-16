<?php 
session_start();

if( isset($_POST['submit'])) {
	$code=strtoupper($_POST['security_code']);
   if( $_SESSION['security_code'] == $code && !empty($_SESSION['security_code'] ) ) {
		// Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 
		echo 'It was correct.';
		unset($_SESSION['security_code']);
   } else {
		// Insert your code for showing an error message here
		echo 'It was incorrect.';
   }
} else {
?>
<html>
<head>
<title>Captcha</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<img src="captchaimage.php?time=<?php echo gmt(U); ?>" /><br />
		<label for="security_code">Security Code: </label><input id="security_code" name="security_code" type="text" /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>
</body>
</html>

<?php
	}
?>
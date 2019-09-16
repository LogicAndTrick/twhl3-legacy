<?php session_start(); if( isset($_POST['submit'])) {
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
<script type="text/javascript">
function newcaptcha()
	{
			var xmlHttp;
			try
				{
					// Firefox, Opera 8.0+, Safari
					xmlHttp=new XMLHttpRequest();
				}
				catch (e)
				{
				// Internet Explorer
				try
					{
						xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e)
					{
					try
						{
							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e)
							{
								alert("Your browser does not support AJAX!");
								return false;
							}
					}
				}
				xmlHttp.onreadystatechange=function()
				{
					if(xmlHttp.readyState==4)
					{
						//document.myForm.time.value=xmlHttp.responseText;
						document.getElementById('cap').innerHTML=xmlHttp.responseText;
					}
				}
			xmlHttp.open("GET","getcaptcha.php",true);
			xmlHttp.send(null);
	}
</script>
</head>
<body>
	<form action="reg.php" method="post">
		<span id="cap"><?php include 'getcaptcha.php'; ?></span><br />
		<a href="javascript:newcaptcha()">New</a><br />
		<label for="security_code">Security Code: </label><input id="security_code" name="security_code" type="text" /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>
</body>
</html>

<?php
	}
?>
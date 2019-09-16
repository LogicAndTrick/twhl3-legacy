<?
//print_r($_REQUEST);
//print_r($_SERVER);
die();
	$link = '(link)';
	$username = '(name)';
	$email = '-----@gmail.com';

	// Verification email
	$to      = $email;
	$subject = 'TWHL User Account Verification';
	$message = "Hi $username,\r\n" .
				"Your TWHL account has been created. To enable your account you must click the following link.\r\n" .
				"If it is not a link, copy it and paste it into your browser's address bar.\r\n\r\n" .
				"$link\r\n\r\n" .
				"Enjoy your stay at TWHL!\r\n\r\n" .
				"(Please do not try to reply to this email, it will not be read.)";
	$headers = 
	"From: TWHL User Verification <noreply@twhl.info>\r\n" .
    "Reply-To: noreply@twhl.info\r\n" .
    "X-Mailer: PHP/" . phpversion() . "\r\n";

	$result = mail($to, $subject, $message, $headers, '-fnoreply@twhl.info');

	echo $result;
?>
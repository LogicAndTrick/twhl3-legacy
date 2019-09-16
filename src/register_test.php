<?php

	include 'top.php';
	
	if (isset($_GET['complete']))
	{
?>
<div class="single-center">
	<h1>Thanks!</h1>
	<p class="single-center-content">
		Thankyou for registering for TWHL! To finalise your account creation, you must verify it.
		An email from TWHL should arrive in your inbox shortly (be sure to check it's not in junk mail!),
		it will contain the details on how to verify your account. If the email does not arrive, use the <a href="contact.php">Contact Us</a> page to contact the site admin.
	</p>
</div>
<?
	}
	else
	{
	
	if (isset($usr) && $usr != "") fail("You've already registered!","index.php");

?>
    <script type="text/javascript" src="register.js"></script>
<div class="single-center">
	<h1>Sign Up for TWHL</h1>
	<p class="single-center-content">
		Welcome to the registration page. Becoming a member of this fantastic community gives you access to the forums, map vault and other resources. You can also contribute content to the site as well. Before signing up, make sure you're familiar with our Rules and Regulations. Most of all, enjoy!
	</p>
</div>
	
<div class="single-center" id="gap-fix-bottom">
	<h1>Register New User</h1>
    <div id="registration_form">
        <p class="single-center-content">
            Loading form...
        </p>
    </div>
        <?php /**/ ?>
<?php /*
        <p class="single-center-content">Unfortunately, due to immature users creating spam accounts, we have temporarily disabled account creation. If you wish to create an account (and are not a spammer), please use the <a href="contact.php">Contact Us</a> page (select Penguinboy as the user to contact) to request an account to be created for you (be sure to include a contact email address in your message, or we cannot reply with your account details). Hopefully we should be able to re-enable account creation soon. Sorry for the inconvenience.</p>
        <p class="single-center-content">This message was updated 29th August, 2010.</p>
*/ ?>
</div>

<?php
	}
	include 'footer.php';
?>
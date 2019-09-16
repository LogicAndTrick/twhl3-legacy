<?php
    die();
	include 'top.php';
	
	if (isset($_GET['complete']))
	{
?>
<div class="single-center">
	<h1>Thanks!</h1>
	<p class="single-center-content">
		Thankyou for registering for TWHL! Now you can post in forums, submit new maps, and even add new tutorials! Just use the forum below to log in to the site.
	</p>
	<form class="login" action="lognow.php" method="post">
		<p class="single-center-content-center">
		Username: <input type="text" name="name" size="16" value="<?=$_GET['user']?>" /><br />
		Password: <input type="password" name="pass" size="16" /><br />
		<input type="submit" value="Login" />
		</p>
	</form>
</div>
<?
	}
	else
	{
	
	if (isset($usr) && $usr != "") fail("You've already registered!","index.php");

?>
<div class="single-center">
	<h1>Sign Up for TWHL</h1>
	<p class="single-center-content">
		Welcome to the registration page. Becoming a member of this fantastic community gives you access to the forums, map vault and other resources. You can also contribute content to the site as well. Before signing up, make sure you're familiar with our Rules and Regulations. Most of all, enjoy!
	</p>
</div>
	
<div class="single-center" id="gap-fix-bottom">
	<h1>Register New User</h1>
        <?php /* */ ?>
	<form action="adduser.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="username" maxlength="15" />Username (15 characters maximum):<br />
				<input class="right" size="30" type="password" name="pass1" />Password:<br />
				<input class="right" size="30" type="password" name="pass2" />Verify Password:<br />
				<select class="right" name="timezone">
<?
		for ($i=-12;$i<13;$i++)
		{
?>
					<option value="<?=$i?>"<?=($pdr['timezone']==$i)?' selected="selected"':''?>>GMT<?=(($i>0)?"+$i":(($i==0)?"":$i))." - ".timezone(gmt("U"),$i,"H:i")?></option>
<?
		}
?>
				</select>Timezone:<br />
				<input class="right" size="30" type="text" name="email" maxlength="50" value="<?=$pdr['email']?>" />Email:<br />
				<input class="right" style="margin: 5px" type="checkbox" name="showemail"<?=($pdr['allowemail']==1)?' checked="checked"':''?> /><span style="float:right;">Show Email</span><br />
			</p>
			<div style="text-align: right; margin: 10px;" id="captcha">
				<?php include 'getcaptcha.php'; ?>
			</div>
			<p class="single-center-content">
				<input class="right" type="text" name="capt" />Enter the letters above into the box (<a href="javascript:ajax_reload_captcha()">Click here for a new image</a>):
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Register" />
			</p>
		</fieldset>
	</form>
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
<?
include 'functions.php';
include 'logins.php';
?>
<form action="adduser.php" method="post" enctype="multipart/form-data">
    <fieldset class="new-thread">
        <p class="single-center-content">
            <input class="right" size="30" type="text" name="username" maxlength="15" />Username (15 characters maximum):<br />
            <input class="right" size="30" type="password" name="pass1" />Password:<br />
            <input class="right" size="30" type="password" name="pass2" />Verify Password:<br />
            <select class="right" name="timezone">
<? for ($i=-12;$i<13;$i++) { ?>
                <option value="<?=$i?>"<?=($pdr['timezone']==$i)?' selected="selected"':''?>>GMT<?=(($i>0)?"+$i":(($i==0)?"":$i))." - ".timezone(gmt("U"),$i,"H:i")?></option>
<? } ?>
            </select>Timezone:<br />
            <input class="right" size="30" type="text" name="email" maxlength="50" value="<?=$pdr['email']?>" />Email (must be valid!):<br />
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
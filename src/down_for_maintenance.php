<?php
header('Location: index.php');
include 'functions.php';
include 'logins.php';

if (isset($_GET['logout'])) {
    setcookie ("twhl_username","",time()-30000000);
    setcookie ("twhl_log","",time()-30000000);
    $_SESSION = array();
    if (session_id() != "") session_destroy();
    header("Location: down_for_maintenance.php");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Security Issues</title>
        <style type="text/css">
            * { margin: 0; padding: 0; font-family: sans-serif; }
            body { background-color: #777; background-image: url('/wipbg.png'); background-repeat: repeat-x; }
            img { margin: 20px auto; display: block; }
            .container { margin: 20px auto; width: 500px; color: #ddd; }
            p { margin: 20px; }
            .info { font-size: 22px; text-align: center; color: #fefefe; }
            .info-small { font-size: 16px; text-align: center; color: #fefefe; border: 1px solid #dedede; padding: 5px; }
            fieldset { padding: 20px; margin-bottom: 20px; border: 1px solid #ccc; background-color: #444; }
            a { color: #bbb; }
            .shoutbox { border: 1px solid #ccc; padding: 10px; background-color: #444; }
            .shoutbox p { line-height: 26px; padding: 10px; margin: 5px 10px 0 10px; clear: both; border-bottom: 1px solid #888; }
            .shoutbox p.admin { border: 1px solid #aaa; background-color: #555; }
            .shoutbox span { display: block; }
            .time { float: right; line-height: 16px !important; margin: 0 !important; font-size: 14px; border: 0 !important; }
        </style>
    </head>
    <body>
        <img src="/wip.png" alt="Freeman with a spanner!" title="Freeman with a spanner!">
        <div class="container">
<?php
    $shouts = mysql_query('SELECT S.shout, S.time, U.uid, U.lvl FROM shouts S LEFT JOIN users U ON U.userID = S.uid ORDER BY S.time DESC LIMIT 20');
    if (isset($usr))
    {
?>
            <p class="info">
                We're having some security issues right now, please be patient while we attempt to get them fixed. In the meantime, here's a shoutbox!
                You might also enjoy our <a href="irc://irc.gamesurge.net/twhl">IRC channel</a>.
            </p>
            <p class="info-small">
                Hi all, long story short: we got hacked. Looks like the person who did it was pretty nice and decided not to destroy too much of the site before letting us know.
                We're looking into the issue and seeing if we can't get this patched up and return to normal operations soon. Sorry for the inconvenience.
                <br><br>
                While we don't think any data has actually been leaked, the hack certainly made it very possible.
                If you use the same password on any other account, we suggest you change it as soon as you can.
                <br><br>
                Update: I was up until 5am working on this, now I need sleep. Progress will continue once I wake up.
                It's a time-consuming task to fix every page and it will take a bit longer. Apologies for the downtime, hopefully we should be up and running within the next day.
            </p>
            <form name="shout_form" action="wip_shout.php" method="post">
                <fieldset>
                    <input type="hidden" name="return" value="/work_in_progress.php" />
                    Shout: <input type="text" size="40" name="shout" maxlength="200" />
                    <input type="submit" value="Go">
                    <a href="work_in_progress.php?logout=1">Log out</a>
                </fieldset>
            </form>
<?php
    }
    else
    {
    
?>
            <p class="info">
                We're having some security issues right now, please be patient while we attempt to get them fixed. Login for a free shoutbox!
                You might also enjoy our <a href="irc://irc.gamesurge.net/twhl">IRC channel</a>.
            </p>
            <p class="info-small">
                Hi all, long story short: we got hacked. Looks like the person who did it was pretty nice and decided not to destroy too much of the site before letting us know.
                We're looking into the issue and seeing if we can't get this patched up and return to normal operations soon. Sorry for the inconvenience.
                <br><br>
                While we don't think any data has actually been leaked, the hack certainly made it very possible.
                If you use the same password on any other account, we suggest you change it as soon as you can.
                <br><br>
                Update: I was up until 5am working on this, now I need sleep. Progress will continue once I wake up.
                It's a time-consuming task to fix every page and it will take a bit longer. Apologies for the downtime, hopefully we should be up and running within the next day.
            </p>
            <form action="lognow.php" method="post">
                <fieldset id="header-login-form">
                    <span>
                        <input type="hidden" name="return" value="index.php" />
                        User: <input type="text" name="name" size="16" />
                        Pass: <input type="password" name="pass" size="16" />
                        <input size="16" value="Login" type="submit" />
                    </span>
                </fieldset>
            </form>
<?php

    }

?>

            <div class="shoutbox">
            <p class="time">The time right now is: <?=timezone(gmt('U'),$_SESSION['tmz'],"H:i")?></p>
<?php
                    while ($row = mysql_fetch_array($shouts))
                    {
                        $shouttext=shoutprocess($row['shout'],$row['lvl']);
                        $shouttime=timezone($row['time'],$_SESSION['tmz'],"H:i");
                        $token = strtok($shouttext, " ");
                        
                        $tokenstring = "";
                            
                        if ($token=="/me") $tokenstring =  '<b class="purple">'.trim($row['uid']).' '.trim(substr($shouttext,3)) . '</b>'; 
                        elseif ($token==$shoutbox_secret) $tokenstring =  '<b class="purple">' . trim(substr($shouttext,$shoutbox_secret_trim)) . '</b>';
                        else $tokenstring =  $shouttext;
?>
                <p class="<?=($row['lvl'] >= 40 ? 'admin' : 'member')?>">
                    <span><strong><?=$row['uid']?></strong> at <?=timezone($row['time'],$_SESSION['tmz'],"H:i")?>:</span>
                    <?=$tokenstring?>
                </p>
<?php
                    }
?>
            </div>
        </div>
    </body>
</html>
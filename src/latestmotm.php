<?
    $motmq = mysql_query('select o.motmid, o.date, m.mapid, m.name, o.total, u.userid, u.uid, m.screen as mscreen
                          from motm o
                          left join maps m on o.map = m.mapid
                          left join users u on m.owner = u.userid
                          where m.gotmotm = 1
                          order by motmid desc limit 1');
    if (mysql_num_rows($motmq) > 0)
    {
        $recr = mysql_fetch_array($motmq);
        $screen = $recr['mscreen'];
        $shot = $screen > 0 ? ($recr['mapid']. '.' .($screen == 2 ? 'png' : 'jpg')) : 'noscreen_small.png';
?>
<h1>Map of the Month <a href="javascript:toggleLayer('motm-div');"><img alt="show/hide" src="images/arrow_up.gif" id="motm-div-hide"/></a></h1>
<div id="motm-div" style="padding: 5px; line-height: 22px;">
    <h3 style="text-align: center; padding-bottom: 5px;"><a href="http://twhl.info/motm.php?id=<?=$recr['motmid']?>"><?=$recr['date']?></a></h3>
    <a href="http://twhl.info/motm.php?id=<?=$recr['motmid']?>">
        <img src="http://www.twhl.info/mapvault/<?=$shot?>" alt="<?=$recr['name']?>" style="margin: 0pt auto; display: block; max-width: 100%; max-height: 120px;" />
    </a>
    <div style="text-align: center;">
        <a href="http://twhl.info/vault.php?map=<?=$recr['mapid']?>"><?=$recr['name']?></a>
        By <a href="http://twhl.info/user.php?id=<?=$recr['userid']?>"><?=$recr['uid']?></a>
    </div>
</div>
<?
    }
?>
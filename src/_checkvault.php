<?

include 'functions.php';
include 'logins.php';

$maps = mysql_query('SELECT * from maps');

while ($row = mysql_fetch_array($maps))
{
    $screen = $row['screen'];
    $id = $row['mapID'];
    if ($screen > 0) {
        $ext = $screen == 2 ? 'png' : 'jpg';
        $file = 'mapvault/' . $id . '_small.' . $ext;
        $file2 = 'mapvault/' . $id . '.' . $ext;
        if (!file_exists($file) || !file_exists($file2)) echo $id . '<br>';
    }
}

?>
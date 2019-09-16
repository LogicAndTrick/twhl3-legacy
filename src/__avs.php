<?php
//echo 'NO AVATARS FOR YOU';
//die();
foreach (glob("mapvault/*.jpg") as $filename) {
    echo "<img src=\"$filename\">\n";
}
foreach (glob("mapvault/*.png") as $filename) {
    echo "<img src=\"$filename\">\n";
}
foreach (glob("mapvault/*.gif") as $filename) {
    echo "<img src=\"$filename\">\n";
}
?>

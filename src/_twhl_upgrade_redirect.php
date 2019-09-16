<?php

if (!(isset($_SESSION['lvl']) && $_SESSION['lvl'] >= 40)
    && $_SERVER['SCRIPT_NAME'] != '/work_in_progress.php'
    && $_SERVER['SCRIPT_NAME'] != '/wip_shout.php')
{
    header('Location: work_in_progress.php');
}

?>
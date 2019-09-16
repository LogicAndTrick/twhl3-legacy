<?php

if (!(isset($_SESSION['lvl']) && $_SESSION['lvl'] >= 40)
    && $_SERVER['SCRIPT_NAME'] != '/down_for_maintenance.php'
    && $_SERVER['SCRIPT_NAME'] != '/wip_shout.php')
{
    header('Location: down_for_maintenance.php');
}

?>
<?php

class TWHLAuthorisation extends AuthorisationMethod
{
    function HasPermission($user, $controller, $action) {
        $level = $this->GetAuthValue($controller, $action, -1);
        if (!is_numeric($level)) {
            return true;
        }
        if ($level < 0) {
            return true;
        }
        if ($level == 0) {
            return isset($_SESSION['usr']);
        }
        if (isset($_SESSION['usr'])) {
            return $_SESSION['lvl'] >= $level;
        }
        return false;
    }

    function HasCredentials($user, $cred) {
        if (!is_numeric($cred)) return false;
        return isset($_SESSION['usr']) && $_SESSION['lvl'] >= $cred;
    }
}
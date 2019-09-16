<?php

include '/home/twhl/main/functions.php';
include '/home/twhl/main/logins.php';
include '/home/twhl/frameworks/Phoenix/Phoenix.php';
include '/home/twhl/main/mvc/App/TWHLAuth.php';

Router::$default_controller = 'Home';
Router::$default_action = 'Index';
Templating::$master_page = 'Shared/Master';
Phoenix::$app_dir = realpath(dirname(__FILE__)).'/App';
Phoenix::$base_url = '/mvc/';
Phoenix::$debug = false;
Phoenix::$debug_user = null;

Database::$type = 'mysql';
Database::$host = '------';
Database::$database = '------';
Database::$username = '------';
Database::$password = '------';
Database::Enable();

Authorisation::SetAuthorisationMethod(new TWHLAuthorisation());

Phoenix::Run();

?>

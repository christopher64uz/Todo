<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../util/web.php");

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

if(!isset($_SESSION[CURRENT_USER])){
	redirect(APPLICATION_ROOT . "/index.php");
}
?>
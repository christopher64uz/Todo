<?php
//data source
define("DATASOURCE_MEMORY","MEMORY");
define("DATASOURCE_JSON","JSON");
define("DATASOURCE_CSV","CSV");
define("DATASOURCE_MYSQL","MYSQL");
//domain
define("user_type_USER", "USER");
define("user_type_ADMIN", "ADMIN");
define("user_FIRST_NAME","firstName");
define("user_LAST_NAME","lastName");
define("user_EMAIL","email");
define("user_PASSWORD","password");
define("user_SALT","salt");
define("user_TYPE","type");
define("user_ENABLED","enabled");
define("todo_ID","id");
define("todo_DESCRIPTION","desc");
define("todo_DATE","date");
define("todo_STATUS","status");

define("todo_status_NOT_STARTED","Not Started");
define("todo_status_STARTED","Started");
define("todo_status_MIDWAY","Midway");
define("todo_status_COMPLETE","Complete");

define("todo_format_DATE","m-d-Y");
//Application paths


define("APPLICATION_NAME","todo");
define("APPLICATION_ROOT", "http://" . $_SERVER["SERVER_NAME"] . "/" . APPLICATION_NAME);
define("CSS", APPLICATION_ROOT . "/resources/css");
define("JS", APPLICATION_ROOT . "/resources/js");
define("CONTROLLER", APPLICATION_ROOT . "/controller");
define("VIEWS", APPLICATION_ROOT . "/views");

define("CURRENT_USER","CURRENT_USER");

define("mysql_HOSTNAME", "localhost");
define("mysql_PORT", "3306");
define("mysql_USERNAME", "root");
define("mysql_PASSWORD", "");
define("mysql_DATABASE","todo");

?>
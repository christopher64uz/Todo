<?php
require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/domain.php");

/**
Following are functions that are private (not to be used by API outside)
*/

function get_connection(){
	todolog("mysql_data_access.php | trying to retrieve mysql connection using: " . mysql_HOSTNAME . ", " . mysql_USERNAME . ", " . mysql_DATABASE . ", " . mysql_PORT);
	$connection = mysqli_connect(mysql_HOSTNAME,mysql_USERNAME,mysql_PASSWORD,mysql_DATABASE,mysql_PORT);
	if(!$connection){
		todolog("mysql_data_access.php | Could not retrieve mysql connection");
		//there was an error
		$errorDescription = mysqli_connect_error();
		trigger_error($errorDescription, E_USER_ERROR);
	}

	return $connection;
}

function convert_mysql_user_array_to_map($usr){
	return array(
		user_FIRST_NAME=> $usr["first_name"],
		user_LAST_NAME=> $usr["last_name"],
		user_EMAIL=> $usr["email"],
		user_PASSWORD=> $usr["password"],
		user_SALT=> $usr["salt"],
		user_TYPE=> $usr["type"],
		user_ENABLED=> $usr["enabled"]
	);
}

function convert_mysql_todo_array_to_map($tdo){
	return array(
		todo_ID=> $tdo["id"],
		todo_DESCRIPTION=> $tdo["description"],
		todo_DATE=> date("m-d-Y", strtotime($tdo["scheduled_date"])),
		todo_STATUS=> $tdo["status"]
	);
}

/**
Public functions (Maybe freely used by API outside)
*/

function save_user_object($user){

}

function get_user_array(){
	return array (
		//map,
		//map
	);
}

function get_user_object($userId){
    todolog("mysql_data_access.php | trying to retrieve user object: $userId");
    $query = "SELECT * FROM users WHERE email='" . $userId . "'";
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);
    todolog("mysql_data_access.php | resultset: " . print_r($resultSet, true));
    $record = mysqli_fetch_array($resultSet);
    todolog("mysql_data_access.php | record: " . print_r($record, true));
    $user = null;
    if ($record) {
    	todolog("mysql_data_access.php | trying to convert stdclass to map");
        $user = convert_mysql_user_array_to_map($record);
    }
    todolog("mysql_data_access.php | user: " . print_r($user, true));
    return $user;
}



function save_todo_object($todo){
	todolog("mysql_data_access.php | trying to save todo");
	
	$stmt = "INSERT INTO todo (description, scheduled_date, status, owner) VALUES(";
    $stmt = $stmt . "'" . $todo[todo_DESCRIPTION] . "',";
    $stmt = $stmt . "'" . $todo[todo_DATE] . "',";
    $stmt = $stmt . "'" . $todo[todo_STATUS] . "',";
    $stmt = $stmt . "'" . $todo[todo_OWNER] . "'";
    $stmt = $stmt . ")";

	todolog("mysql_data_access.php | insert stmt: $stmt");

    $connection = get_connection();
    mysqli_query($connection, $stmt);
    
    $previousId = mysqli_insert_id($connection);
    todolog("mysql_data_access.php | generated todo id: $previousId");
    $todo[todo_ID] = $previousId;

    return $todo;
}

function get_todo_object($id){
	todolog("mysql_data_access.php | trying to retrieve todo object: $id");
	$query = "SELECT * FROM todos WHERE id='$id'";
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);
    todolog("mysql_data_access.php | resultset: " . print_r($resultSet, true));
    $record = mysqli_fetch_array($resultSet);
    todolog("mysql_data_access.php | record: " . print_r($record, true));
    $todo = null;
    if ($record) {
    	todolog("mysql_data_access.php | trying to convert mysql obj to domain obj");
        $todo = convert_mysql_todo_array_to_map($record);
    }
    todolog("mysql_data_access.php | todo: " . print_r($todo, true));
    return $todo;
}

function get_todo_array($user){	
	todolog("mysql_data_access.php | trying to retrieve todos for user: $user");
	$query = "SELECT * FROM todo WHERE owner='$user'";
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);
    todolog("mysql_data_access.php | resultset: " . print_r($resultSet, true));
    $todos = [];
    while ($task = mysqli_fetch_array($resultSet)) {
        $todos[] = convert_mysql_todo_array_to_map($task);
    }
    
    todolog("mysql_data_access.php | resultset: " . print_r($todos, true));
    return $todos;
}

function delete_todo_object($id){
	todolog("mysql_data_access.php | trying to delete todo with id: $id");
	$stmt = "DELETE FROM todo WHERE id=$id";
	$connection = get_connection();
	mysqli_query($connection, $stmt);
}

function generate_todo_id(){
	return NULL;
}


?>
<?php
require_once("../config/constants.php");

function create_user_object($firstName,$lastName,$email,$password,$salt,$type,$enabled=1){
	$user = array (
		user_FIRST_NAME=>$firstName,
		user_LAST_NAME=>$lastName,
		user_EMAIL=>$email,
		user_PASSWORD=>$password,
		user_SALT=>$salt,
		user_TYPE=>$type,
		user_ENABLED=>$enabled
	);

	return $user;
}

function create_todo_object($id,$desc,$date,$status=todo_status_NOT_STARTED){
	$todo = array (
		todo_ID=>$id,
		todo_DESCRIPTION=>$desc,
		todo_DATE=>$date,
		todo_STATUS=>$status		
	);
	return $todo;
}

?>
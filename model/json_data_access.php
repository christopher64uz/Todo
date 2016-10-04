<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/domain.php");
error_reporting(E_ALL);

$users_db_file = __DIR__ . "/../data/users.json";

$usersDB = array();
$todosDB = array();

function get_current_user_id(){
	if(session_id() == '' || !isset($_SESSION)) {
	    // session isn't started
	    session_start();
	}

	if(isset($_SESSION[CURRENT_USER])){
		$cusr = $_SESSION[CURRENT_USER];
		$split = explode("@",$cusr);
		return $split[0];
	}
	return false;
}

function init_users_db(){
    //todolog("json_data_access.php | initializing usersDB");
    global $usersDB;
    global $users_db_file;
    if(!$usersDB){		
        $users_json_string = file_get_contents($users_db_file);
        $tmpDB = json_decode($users_json_string);
        $usersCount = count($tmpDB);
        if($usersCount > 0) {
            //todolog("json_data_access.php | found $usersCount users");
            $tmpUsers = array();
            for($index=0;$index<$usersCount;$index++){
                $user = $tmpDB[$index];
                $userObj = convert_usr_stdclass_to_map($user);
                array_push($tmpUsers, $userObj);
            }
            $usersDB = $tmpUsers;
        } else {
            $usersDB = array();
        }
    }
    //print_r($usersDB);
}

// Created by Chris
function save_user_object($user){
    global $usersDB;    
    init_users_db();
    array_push($usersDB,$user);
    print_r($usersDB);
    save_user_file($usersDB,$user);    
    //exit();    
}

// Created by Chris
function save_user_file($usersDB,$user) {
    // Saving in usrs.json
    global $users_db_file;
    $newencoded = json_encode($usersDB);
    file_put_contents($users_db_file, $newencoded);
    
    // Creating new $currentUserId.json 
    $currentUserId = explode("@",$user['email']);
    $currentUserId = $currentUserId[0];
    $todos_db_file = __DIR__ . "/../data/${currentUserId}.json";
    $todosDB = array('nextId'=> 1, 'todos'=>array());
    $newencoded = json_encode($todosDB);
    file_put_contents($todos_db_file, $newencoded);
    unset($newencoded);
}

function get_user_array(){
	return array (
		//map,
		//map
	);
}

function get_user_object($userId){
    global $usersDB;
    init_users_db();
    $userCount = count($usersDB);

    if($userCount > 0) {
        $user = false;
        for($index=0;$index<$userCount;$index++){
            $usr = $usersDB[$index];			
            if($usr[user_EMAIL] === $userId){
                //convert $usr to map
                $user = $usr;
                break;
            }
        }
        //print_r($user);
        //exit();
        return $user;
    }
    return false;
}

function convert_usr_stdclass_to_map($usr){
	return array(
		user_FIRST_NAME=> $usr->firstName,
		user_LAST_NAME=> $usr->lastName,
		user_EMAIL=> $usr->email,
		user_PASSWORD=> $usr->password,
		user_SALT=> $usr->salt,
		user_TYPE=> $usr->type,
		user_ENABLED=> $usr->enabled
	);
}

function convert_todo_stdclass_to_map($tdo){
	return array(
		todo_ID=> $tdo->id,
		todo_DESCRIPTION=> $tdo->desc,
		todo_DATE=> $tdo->date,
		todo_STATUS=> $tdo->status
	);
}

function init_todos_db(){        
    global $todosDB;
    if(!$todosDB){
        $currentUserId = get_current_user_id();
        if(!$currentUserId){
            trigger_error("Please login before trying to access your To Do list");
        }
        $todos_db_file = __DIR__ . "/../data/${currentUserId}.json";

        $todos_json_string = file_get_contents($todos_db_file);
        $tmpDB = json_decode($todos_json_string);

        $stdTodos = $tmpDB->todos;
        
        $todoCount = count($stdTodos);
        //print_r($todoCount);

        if($todoCount >= 0) {
            $todosDB = array(
                "nextId"=>$tmpDB->nextId				
            );

            $tmpTodos = array();
            for($index=0;$index<$todoCount;$index++){
                $tdo = $stdTodos[$index];
                $todoObj = convert_todo_stdclass_to_map($tdo);
                array_push($tmpTodos, $todoObj);
            }

            $todosDB["todos"] = $tmpTodos;
        }
    }
}


// Edited by Chris
function save_todo_object($todo){    
    global $todosDB;    
    init_todos_db();
    //print_r($todosDB);
    $todosDB["nextId"]++;
    if(count($todosDB['todos'])==0){
        $todosDB['todos'] = array(0=>$todo);
    }
    else {
        array_push($todosDB["todos"], $todo);
    }
    echo "<br> <br>";
    //print_r($todosDB);
    save_todo_file($todosDB);
}
// Created by Chris
function save_todo_file($todosDB) {
    $newencoded = json_encode($todosDB);
    $currentUserId = get_current_user_id();
    $todos_db_file = __DIR__ . "/../data/${currentUserId}.json";
    file_put_contents($todos_db_file, $newencoded);
    unset($newencoded);
}
// Edited by Chris
function get_todo_object($id){
    global $todosDB;
    init_todos_db();
    //print_r($todosDB);
    for ($i = 0; $i < count($todosDB["todos"]); $i++) {
        if ($todosDB["todos"][$i]["id"] == $id) {
            return $todosDB["todos"][$i];
        }
    }
}

function get_todo_array($user){	
    global $todosDB;
    init_todos_db();	
    return $todosDB["todos"] ? $todosDB["todos"] : array();
}

// Edited by Chris
function generate_todo_id(){
    global $todosDB;
    init_todos_db();
    //print_r($todosDB["nextId"]);
    //exit();
    return $todosDB["nextId"];    
}
// Created by Chris
function update_todo_object($taskId, $description, $status) {
    global $todosDB;
    init_todos_db();
    for ($i = 0; $i < count($todosDB["todos"]); $i++) {
        if ($todosDB["todos"][$i]["id"] == $taskId) {
            $todosDB["todos"][$i]["desc"] = $description;
            $todosDB["todos"][$i]["status"] = $status;
            break;
        }
    }
    //print_r($todosDB);
    save_todo_file($todosDB);
}

// Created by Chris
function delete_todo_object($taskId) {
    global $todosDB;
    init_todos_db();
    for ($i = 0; $i < count($todosDB["todos"]); $i++) {
        if ($todosDB["todos"][$i]["id"] == $taskId) {
            unset($todosDB["todos"][$i]);
            $todosDB["todos"] = array_values($todosDB["todos"]);
            break;
        }
    }
    print_r($todosDB);
    save_todo_file($todosDB);
}


get_todo_array(null);

?>
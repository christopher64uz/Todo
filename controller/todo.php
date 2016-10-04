<?php

require_once("../config/constants.php");
//require_once('../utils/utils.php');
//require_once('../utils/security_utils.php');
//require_once('../da/data_access.php');
require_once('../util/validators.php');
require_once('../service/data_service.php');

session_start();
//unset($_SESSION["error"]);
if (empty($_POST["action"])) {  // isset() function does not work since it prints even when NULL
    redirect(VIEWS . "/home.php");
}

$action = $_POST["action"];

// Edited by Chris
if ($action == "Add") {    
    if (isset($_POST["description"])) {
        //echo $_POST["description"];
        $description = trim($_POST["description"]);
        $scheduledDate = trim($_POST["scheduledDate"]);
        
        //validate task description        
        $valid = validateRequired($description, $scheduledDate); 
        if ($valid) {
            // Converts date yyyy-mm-dd to mm-dd-yyyy format
            if (preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $scheduledDate)) {
                $time = strtotime($_POST["scheduledDate"]);
                $scheduledDate = date(todo_format_DATE,$time);                
            }
            //$task = [];
            //$task["description"] = $description;
            //$task["scheduledDate"] = $scheduledDate;            
            $userId = $_SESSION[CURRENT_USER];
            new_todo($description, $scheduledDate);            
        } 
        else {
            $_SESSION["error"] = "Task description & Date(mm-dd-yyyy or mm\dd\yyyy) is required(<=7 days)";
        }
    } 
    redirect(VIEWS . "/home.php");
} else if ($action == "Edit") {
    if (isset($_POST["taskId"])) {
        $taskId = $_POST["taskId"];
        $_SESSION["taskId"] = $taskId;
        redirect(VIEWS . "/update_task.php");
    } else {
        $_SESSION["error"] = "Select a task";
        redirect(VIEWS . "/home.php");
    }
} else if ($action == "Delete") {
    if (isset($_POST["taskId"])) {
        $taskId = $_POST["taskId"];
        delete_todo($taskId);
    } else {
        $_SESSION["error"] = "Select a task";
    }
    redirect(VIEWS . "/home.php");
} else if ($action == "Update") {
    if (!empty($_POST["taskId"])) {
        echo $taskId = $_POST["taskId"];
        echo $description = $_POST["description"];
        echo $status = $_POST["status"];
        update_todo($taskId, $description, $status);
    } else {
        $_SESSION["error"] = "Select a task";
    }
    redirect(VIEWS . "/home.php");
}
?>
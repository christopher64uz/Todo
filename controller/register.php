<?php

require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../util/security.php");
require_once(__DIR__ . "/../service/registration_service.php");
require_once(__DIR__ . "/../service/data_service.php");

session_start();
$validationResult = validate_registration_form($_POST);
if (count($validationResult) > 0) {
    $_SESSION["errors"] = $validationResult;
    $url = VIEWS . "/registration_form.php";
    redirect($url);
    exit;
} else {

    $userName = $_POST["userName"];
    $userNameAvailable = verify_username_availability($userName);
    //exit();
    if($userNameAvailable){
        $_SESSION["errors"] = array("userName"=>"Username already exists");
        $url = VIEWS . "/registration_form.php";
        redirect($url);
        exit;
    }

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];    
    $password = $_POST["password"];

    $user = new_todo_user($firstName,$lastName,$userName,$password);    
    if($user){
        $_SESSION["success"] = "Registration successful. Please login.";
    }
    redirect(APPLICATION_ROOT . "/index.php");
    exit;
}

?>

<?php

require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../util/security.php");
require_once(__DIR__ . "/../service/auth_service.php");
require_once(__DIR__ . "/../service/data_service.php");

if(session_id() == '' || !isset($_SESSION)) {
     // session isn't started
    session_start();
}

if (isset($_POST["action"]) && $_POST["action"] === "Login") {
    //Retrieve username & password
    $validationResult = validate_credentials($_POST);

    if (count($validationResult) > 0) {        
        $_SESSION["errors"] = $validationResult;
        $url = APPLICATION_ROOT . "/index.php";
        redirect($url);
        exit;
    }

    $userName = $_POST["userName"];
    $password = $_POST["password"];

    $user = get_user($userName);
    
    if ($user) {
        $salt = $user[user_SALT];
        $enteredPassword = encrypt_password($password, $salt);
        $savedPassword = $user[user_PASSWORD];
        if ($savedPassword === $enteredPassword) {
            if(session_id() == '' || !isset($_SESSION)) {
                // session isn't started
                session_start();
            }
            session_regenerate_id(true);
            //valid user            
            $_SESSION[CURRENT_USER] = $user[user_EMAIL];
            //redirect to home page
            redirect(VIEWS . "/home.php");            
        } else {
            $errors = [];
            $errors["auth"] = "Authentication failed. Please check username and password";
            $_SESSION["errors"] = $errors;
            $url = APPLICATION_ROOT . "/index.php";
            redirect($url);
            exit;            
        }
    } else {
        $errors = [];
        $errors["auth"] = "Authentication failed. Please check username and password";
        $_SESSION["errors"] = $errors;
        $url = APPLICATION_ROOT . "/index.php";        
        redirect($url);        
    }

} else {    
    redirect(APPLICATION_ROOT . "/index.php");
    exit;
}

?>
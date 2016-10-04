<?php

//require_once '';

$errors = [];
function validate_registration_form($form) {
    global $errors;
    
    $firstName = $form["firstName"];
    $lastName = $form["lastName"];
    $userName = $form["userName"];
    $password = $form["password"];        
    
    // Validating First Name
    validatefirstlastnames($firstName, "firstName");
    // Validating Last Name
    validatefirstlastnames($lastName, "lastName");
    // Validating User Name(Email)
    if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
        $errors["userName"] = "User name is required & should be a valid email address";
    }
    // Validating Password
    validatePassword($password);
    
    
    /*
    $firstNameValid = validatefirstlastnames($name, "firstName"); //Validate
    if(!$firstNameValid) {
        $errors["firstName"] = "First name is required";
    }
    
    $lastNameValid = true; //Validate
    if(!$lastNameValid) {
        $errors["lastName"] = "Last name is required";
    }
    
    $userNameValid = filter_var($form["userName"], FILTER_VALIDATE_EMAIL);
    if(!$userNameValid) {
        $errors["userName"] = "User name is required and should be a valid email address";
    }
    
    $passwordValid = true; //Validate
    if(!$passwordValid) {
        $errors["password"] = "Password is required and should have at least 4 characters";
    }
    */    
    return $errors;
}

function validatefirstlastnames($name, $nametype) {
    global $errors;    
    if (!empty($name) && (strlen($name) < 30) && preg_match('/[A-z]/', $name)) {
        // Do nothing        
    }
    else {
        $errors[$nametype] = ucfirst($nametype).$name." must be only alphabets(30 max) & not empty";
    }
}

function validatePassword($password) {
    global $errors;
    $flag = 1;
    //Checking if less than 6 and greater than 15
    if (strlen($password) >= 6 && strlen($password) <= 15) {
        // checks for alpha, digit & special character
        if(preg_match('/[A-z]/', $password) && preg_match('/\d/', $password) && preg_match('/[$_]/', $password))  {  
            $flag = 0;
        }	
    }
    if ($flag) {
        $errors["password"] = "Password can have min 6 and max 15 characters. Should have at least 1 alphabet, 1 number and one special character($ or _)";
    }
}

?>

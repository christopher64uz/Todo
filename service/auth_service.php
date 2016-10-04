<?php

function validate_credentials($form){
	$errors = [];    

    $userNameValid = validate_username($form);
    if (!$userNameValid) {
        $errors["validation.userName"] = "User name is required and should be an email address";
    }

    $passwordValid = validate_password($form);
    if (!$passwordValid) {
        $errors["validation.password"] = "Password is required and should have at least 4 characters";
    }

    return $errors;
}

function validate_username($form){
	if(isset($form["userName"])){		
		return filter_var($form["userName"], FILTER_VALIDATE_EMAIL);
	}
	return false;
}

function validate_password($form){
	if(isset($form["password"])){
		//validate
		return true;
	}
	return false;
}

?>
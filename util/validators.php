<?php

function validateRequired($desc, $date) {
    //$desc = trim($desc);
    //$date = trim($date);
    if (!empty($desc) && !preg_match('/[A-z]/', $date) && (preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $date) || preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $date)) && strtotime($date)>=strtotime('now') && strtotime($date)<=strtotime('+7 day')) {        
        return true;
    }
    else {
        return false;
    }
}

function validate($value, $noChars) {
    $valid = isset($value);
    if ($valid) {
        $valid = hasRequiredLength($value, $noChars);
    }

    return $valid;
}

function hasRequiredLength($value, $noChars) {
    $valid = false;
    $trimmedValue = trim($value);
    if (strlen($trimmedValue) >= $noChars) {
        $valid = true;
    }
    return $valid;
}

?>
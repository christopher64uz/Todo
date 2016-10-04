<?php

function encrypt_password($pass,$salt){
	return md5($pass . $salt);
}

function generate_salt($length = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $length);
}

?>
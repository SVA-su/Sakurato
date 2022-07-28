<?php
function ShowError($error, $die = NULL){
    $path = "../templates/error/".$error.".php";
    if($die == NULL){
        die(include($path));
    } else {
	include($path);
    }	
}
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
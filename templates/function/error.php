<?php
function ShowError($error, $die = NULL){
    $path = "../templates/error/".$error.".php";
    if($die == NULL){
        die(include($path));
    } else {
	include($path);
    }	
}
<?php
function redirect($to, $host = null) {
    if($host == null)
        return die(header("Location: ".$to));
    else 
        return die(header("Location: ".$host.$to));
}   
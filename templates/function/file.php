<?php
function permsfile($file){
    if(!is_readable($file))
    return null;
    for($i = -1; $i > -3; $i--){
        $result = (int)substr(decoct(fileperms($file)), $i);
        if($result < 4){
            ShowError(403);
        }
    }
}
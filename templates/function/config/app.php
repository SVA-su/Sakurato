<?php
function CheckApp($info_db = array(), $root){
    require($root."/vendor/client/handler.php");
    require($root."/templates/function/error.php");
    $handler = new ClientCommandHandler($info_db['host'], $info_db['user'], $info_db['password'], $info_db['db']['client']);
    unset($info_db);
    if($handler->CheckApp() == 1) ShowError("503");
    elseif($handler->CheckApp() == 0) {}
    else ShowError("500");  
}
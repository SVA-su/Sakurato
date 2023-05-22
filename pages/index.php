<?php

if(!isset($_SERVER['REQUEST_URI'])) die("[Sakurato] Вы используете php-cli, чего данный фреймворк не поддерживает. Пожалуйста, зайдите с браузера или используйте client в корне проекта\n");

require "../config/main.php";
require "../templates/function/file.php";
require "../templates/function/redirect.php";

if (PHP_VERSION_ID < 80000) {
	printf("PHP Version is < 8.0. Please use a > 8.0");
	ShowError(500);
}

//проверка на наличие get запроса в $_SERVER['REQUEST_URI'] и запись в $path запрашиваемой страницы

    $pathServer = $_SERVER['REQUEST_URI'];
    if(str_contains($pathServer, '?')){
        $pathServer = substr($pathServer, 0, strpos($pathServer, '?'));
    }
    $path = $pathServer;

        if(str_contains($path, '.')){
            $temp = explode('.', $path);
            $exe = $temp[1];
            if(!file_exists('../templates/site/' . $_SERVER['HTTP_HOST'] . $temp[0] . "." . $exe)) ShowError(404);
            if($exe == 'php'){ 
                redirect($temp[0]); 
            } else if($exe == 'css')
                header('Content-type: text/css;');
            else if($exe == 'js')
                header('Content-type: application/javascript;');
            else if($exe == 'json')
                header('Content-type: application/json;');
            else {
                $info = getimagesize("../templates/site/".$_SERVER['HTTP_HOST'].$temp[0].".".$exe);
                header('Content-type: '.$info['mime'].';');
                unset($info);
            }
        } else header("Content-type: text/html");
        if(substr($pathServer, -5) == 'index'){
            redirect(substr($pathServer, 0, strpos($pathServer, 'index')));
        }
    header('charset: UTF-8;');
//проверка пути, который запрашивают
    if(substr($pathServer, -1) == '/' ) $pathServer = $pathServer."index";
    if(!isset($exe)) $pathServer = '../templates/site/' . $_SERVER['HTTP_HOST'] . $pathServer . ".php";
    else $pathServer = '../templates/site/' . $_SERVER['HTTP_HOST'] . $temp[0] . "." . $exe;

    if(file_exists($pathServer)){
        $favicon = '<link rel="shortcut icon" href="'.$infoSite['protocol'].$infoSite['domain']['main'].'/images/favicon.svg" type="image/x-icon">';
        $css = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>'.
               '<link rel="stylesheet" href="resource/main.css"/>'.
               '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">';
        permsfile($pathServer);
        include($pathServer);
        die();
    }
    else if(!file_exists($pathServer)) ShowError(404);
    else ShowError(500); 

?>

<?php

if(!isset($_SERVER['REQUEST_URI'])) die("[GitEngine] Вы используете php-cli, чего данный фреймворк не поддерживает. Пожалуйста, зайдите с браузера или используйте client в корне проекта\n");

require "../config/main.php";
require "../templates/function/file.php";

if (PHP_VERSION_ID < 80000) {
	printf("PHP Version is < 8.0. Please use a > 8.0");
	ShowError(500);
}
//if(strcmp($_SERVER['HTTP_HOST'], $infoSite['domain']['client'])) die("HTTP хост, запрашиваемый, не соответствует тому, что есть в конфиге. Проверьте это.(".$_SERVER['HTTP_HOST']." != ".$infoSite['client']."(\$infoSite['client']))");
//проверка на наличие get запроса в $_SERVER['REQUEST_URI'] и запись в $path запрашиваемой страницы
    $pathServer = $_SERVER['REQUEST_URI'];
    if(str_contains($pathServer, '?')){
        $pathServer = substr($pathServer, 0, strpos($pathServer, '?'));
    }
    $path = $pathServer;
    if(str_contains($path, '.')){
        $temp = explode('.', $path);
        $exe = $temp[1];
    }
    //var_dump($exe);
//проверка пути, который запрашивают
    if(substr($pathServer, -1) == '/' ) $pathServer = $pathServer."index";
    if(!isset($exe)) $pathServer = '../templates/site/' . $_SERVER['HTTP_HOST'] . $pathServer . ".php";
    else $pathServer = '../templates/site/' . $_SERVER['HTTP_HOST'] . $temp[0] . "." . $exe;
    //var_dump($pathServer);

    if(file_exists($pathServer)){
        permsfile($pathServer);
        include($pathServer);
    }
    else if(!file_exists($pathServer)) ShowError(404);
    else ShowError(500); 

?>

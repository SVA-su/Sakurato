<?php
if(!isset($_SERVER['REQUEST_URI'])) die("Вы используете php-cli, чего данный фреймворк не поддерживает. Пожалуйста, зайдите с браузера или используйте client в корне проекта\n");
require "../config/main.php";
if (PHP_VERSION_ID < 80000) {
	printf("PHP Version is < 8.0. Please use a > 8.0");
	ShowError(500);
}
if(strcmp($_SERVER['HTTP_HOST'], $infoSite['domain']['client'])) die("HTTP хост, запрашиваемый, не соответствует тому, что есть в конфиге. Проверьте это.(".$_SERVER['HTTP_HOST']." != ".$infoSite['client']."(\$infoSite['client']))");
//require $root."/templates/function/session.php"; 

    $path = $_SERVER['REQUEST_URI'];
    $pathServer = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//проверка на наличие get запроса в $_SERVER['REQUEST_URI'] и запись в $path запрашиваемой страницы
    if(str_contains($path, '?')){
        $path = substr($path, 0, strpos($path, '?'));
    }
//проверка пути, который запрашивают
    if(substr($path, -1) == '/'){
        $path = $path."index";
    } 

//Вы можете выполнять код, если пользователь на определённой странице, пример:
/*
    if($path == '/') { echo "Главная Страница!"; }
*/
    $path = '../templates/site' . $path . '.php'; 
    if(file_exists($path)){
        die(include($path));
    } else if(!file_exists($path)) ShowError(404); else ShowError(500); 
?>

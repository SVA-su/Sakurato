<?php
$root = include("home.php");
$root = $root['root'];

require($root."templates/function/config/app.php");
$timezone = require("time.php");
    date_default_timezone_set($timezone['timezone']);
$info_db = require("database.php");
    if(!isset($info_db)) die("Error: database is not found");
    elseif($info_db['driver'] != 'mysql') die("Error: driver ".$info_db['driver']." is not found");
$connect = mysqli_connect($info_db['host'], $info_db['user'], $info_db['password'], $info_db['db']['client']);
    if (mysqli_connect_errno()) die(require($root."/templates/error/503.php")); mysqli_close($connect);
CheckApp($info_db, $root);
    

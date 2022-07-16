<?php
ini_set( 'serialize_precision', -1 );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require "connector.php";
$mail = require 'mail.php';
require $root.'/vendor/autoload.php';
$info = require 'name.php';
$infoSite = require 'domain.php';

<?php

require_once 'storage/SQL.php';

$sql = new SQL();

$ip = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$server_ip = $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'];

$sql->query("INSERT INTO `visitor`(`ip`, `agent`, `server_ip`) VALUES ('$ip','$agent','$server_ip')");
<?php

session_start();

require_once '../../route.php';

is_login();

$user = new User();
$user->setId( $_SESSION[USER_ID] );

$chatter_id = $_POST['id'];

$user->message_read($chatter_id);

response_msg(true, '', '#', []);
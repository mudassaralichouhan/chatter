<?php

session_start();

require_once '../../route.php';

is_login();

$user = new User();
$user->setId( $_SESSION[USER_ID] );

$chatter_id = $_POST['id'];

$msg_id = isset($_POST['msg_id']) ? $_POST['msg_id'] : '';

if(isset($_POST['offset'])) {
    $msg = $user->get_chat_limit($chatter_id , $_POST['offset']);
    if(!empty($msg)) {
        $user->message_read($chatter_id);
        response_msg(true, '', '#', $msg);
    }
    response_msg(false, '', '#', []);
}

$msg = $user->get_chat($chatter_id , $msg_id);
if(!empty($msg)) {
    $user->message_read($chatter_id);
    response_msg(true, '', '#', $msg);
}
response_msg(false, 'Some thing was wrong', '#', []);
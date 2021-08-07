<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

// session_id is user_id
$user_id = $_SESSION[USER_ID];
$chat_id = $_POST['chat-id'];
$msg = $_POST['message'];

$user = new User();
$user->setId( $user_id );

if(strlen($msg) > 2) {
    if( $user->save_mssage($chat_id, $msg) ) {
        response_msg(true, 'send successfully', '#', []);
    }
}

response_msg(false, 'Some thing was wrong!', '#', []);

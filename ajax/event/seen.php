<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

// session_id is user_id
$chatter_id = $_POST['id'];

response_msg(true, '', '#', [
    'last_seen' => (new User())->get_last_seen($chatter_id)
]);

<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

// session_id is user_id
$chatter_id = $_POST['id'];

$user = new User();

if( $user->is_typed($chatter_id) ) {
    response_msg(true);
} else {
    response_msg(false, 'Some thing was wrong');
}

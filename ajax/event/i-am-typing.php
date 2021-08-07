<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

// session_id is user_id
$user_id = $_SESSION[USER_ID];

$user = new User();

if( $user->typing($user_id) ) {
    response_msg(true);
} else {
    response_msg(false, 'Some thing was wrong');
}

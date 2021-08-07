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
$user->setId($user_id);

if( $user->last_seen() ) {
    response_msg(true);
} else {
    response_msg(true, 'Some thing was wrong');
}

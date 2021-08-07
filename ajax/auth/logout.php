<?php
/*
 *
 */

session_start();

require_once '../../route.php';

$user_id = $_SESSION[USER_ID];

$user = new User();

if( $user->logout($user_id) ) {

	session_destroy();

    response_msg(true,'', base_url().'/login.php');

} else {

    response_msg(false,'Some thing was wrong', '#');

}
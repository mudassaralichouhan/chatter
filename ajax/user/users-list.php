<?php
/*
 *
 */

session_start();

require_once '../../route.php';

is_login();

$user = new User();
$user->setId( $_SESSION[USER_ID] );

response_msg( true, '', '#', $user->get_top_users_list() );
